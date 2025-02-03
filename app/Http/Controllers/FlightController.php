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

        if ($request->has('departure') && $request->has('arrival')) {
            $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
                ->where('departure_airport_id', $request->departure)
                ->where('arrival_airport_id', $request->arrival)
                ->when($request->date, function ($query) use ($request) {
                    return $query->whereDate('departure_time', $request->date);
                })
                ->where('available_seats', '>=', (int) $request->passengers)
                ->where('status', 'scheduled')
                ->get();
        } else {
            $flights = null;
        }
        // dd($request->all());


        return view('flights.search', compact('airports', 'flights'));
    }


    public function show(Flight $flight)
    {
        $flight->load(['airline', 'departure_airport', 'arrival_airport']);
        return view('flights.show', compact('flight'));
    }
}
