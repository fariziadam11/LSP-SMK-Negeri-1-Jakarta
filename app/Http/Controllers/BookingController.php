<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Passenger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'payment_method' => 'required|string',
        ]);

        $flight = Flight::findOrFail($request->flight_id);
        $user = auth()->user();

        // dd($flight, $user);

        if ($flight->available_seats < 1) {
            return back()->withErrors(['message' => 'Not enough seats available.']);
        }

        try {
            DB::beginTransaction();

            // Create booking directly confirmed without payment
            $booking = Booking::create([
                'user_id' => $user->id,
                'flight_id' => $flight->id,
                'booking_code' => 'BK' . Str::upper(Str::random(6)),
                'booking_date' => now(),
                'total_passengers' => 1,
                'total_amount' => $flight->price,
                'status' => 'pending', // Directly confirmed
                'payment_status' => 'paid', // Assume already paid
            ]);

            // Update available seats
            $flight->decrement('available_seats', 1);

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking confirmed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'An error occurred while processing your booking.']);
        }
    }


    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'payment']);

        // Ambil semua data airport
        $airports = Airport::all();

        return view('flights.search', compact('booking', 'airports'))
            ->with('success', 'Booking details loaded successfully!');
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
