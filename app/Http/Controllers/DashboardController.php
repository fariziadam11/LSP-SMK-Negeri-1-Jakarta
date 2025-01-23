<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $upcomingFlights = Booking::where('user_id', $userId)
            ->whereHas('flight', function ($query) {
                $query->where('departure_time', '>', now());
            })
            ->count();

        $completedBookings = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->count();

        $totalSpent = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->sum('total_amount');

        $recentBookings = Booking::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'upcomingFlights',
            'completedBookings',
            'totalSpent',
            'recentBookings'
        ));
    }
}
