<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlightController extends Controller
{
    public function search(Request $request)
    {
        $airports = Airport::all();

        $flights = collect([]);

        if ($request->filled(['departure', 'arrival', 'date'])) {
            $flights = Flight::with(['airline', 'departure_airport', 'arrival_airport'])
                ->where('departure_airport_id', $request->departure)
                ->where('arrival_airport_id', $request->arrival)
                ->whereDate('departure_time', Carbon::parse($request->date))
                ->where('available_seats', '>=', $request->passengers ?? 1)
                ->where('status', 'scheduled')
                ->orderBy('departure_time')
                ->get();
        }

        return view('flights.search', compact('airports', 'flights'));
    }

    public function show(Flight $flight)
    {
        $flight->load(['airline', 'departure_airport', 'arrival_airport']);
        return view('flights.show', compact('flight'));
    }
}
