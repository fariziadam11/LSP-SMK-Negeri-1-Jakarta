<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Passenger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['flight.airline', 'flight.departure_airport', 'flight.arrival_airport'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Flight $flight)
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport']); // BENAR
        return view('bookings.create', compact('flight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.title' => 'required|in:Mr,Mrs,Ms',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.id_card_number' => 'required|string|max:50',
            'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
        ]);

        $flight = Flight::findOrFail($request->flight_id);
        $totalPassengers = count($request->passengers);

        if ($flight->available_seats < $totalPassengers) {
            return back()->withErrors(['message' => 'Not enough seats available.']);
        }

        try {
            DB::beginTransaction();

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'flight_id' => $flight->id,
                'booking_code' => 'BK' . Str::upper(Str::random(6)),
                'booking_date' => now(),
                'total_passengers' => $totalPassengers,
                'total_amount' => $flight->price * $totalPassengers,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Create passengers
            foreach ($request->passengers as $passengerData) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'title' => $passengerData['title'],
                    'name' => $passengerData['name'],
                    'id_card_number' => $passengerData['id_card_number'],
                ]);
            }

            // Create payment
            Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $request->payment_method,
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);

            // Update available seats
            $flight->decrement('available_seats', $totalPassengers);

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking created successfully. Please complete the payment.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['message' => 'An error occurred while processing your booking.']);
        }
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['flight.airline', 'flight.departure_airport', 'flight.arrival_airport', 'passengers', 'payment']);
        return view('bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'confirmed') {
            return back()->withErrors(['message' => 'Cannot update confirmed booking.']);
        }

        $request->validate([
            'action' => 'required|in:cancel'
        ]);

        if ($request->action === 'cancel') {
            DB::transaction(function () use ($booking) {
                // Update booking status
                $booking->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded'
                ]);

                // Update flight available seats
                $booking->flight->increment('available_seats', $booking->total_passengers);

                // Update payment status
                if ($booking->payment) {
                    $booking->payment->update(['status' => 'refunded']);
                }
            });

            return back()->with('success', 'Booking cancelled successfully.');
        }

        return back()->withErrors(['message' => 'Invalid action.']);
    }
}
