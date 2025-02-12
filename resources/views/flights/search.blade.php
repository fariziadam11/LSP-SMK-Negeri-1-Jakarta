@extends('components.app')

@section('title', 'Search Flights')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Search Flights</h2>

            <form action="{{ route('flights.search') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- From -->
                    <div>
                        <label for="departure" class="block text-sm font-medium text-gray-700">From</label>
                        <select name="departure" id="departure" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="" selected disabled>Select Departure</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To -->
                    <div>
                        <label for="arrival" class="block text-sm font-medium text-gray-700">To</label>
                        <select name="arrival" id="arrival" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="" selected disabled>Select Arrival</option>
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Departure Date -->
                    <div>
                        <label for="departure_date" class="block text-sm font-medium text-gray-700">Departure Date</label>
                        <input type="date" name="departure_date" id="departure_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Passengers -->
                    <div>
                        <label for="passengers" class="block text-sm font-medium text-gray-700">Passengers</label>
                        <input type="number" name="passengers" id="passengers" min="1" value="{{ old('passengers', 1) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Search Flights
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($flights))
    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Available Flights</h3>
        <div class="space-y-4">
            @foreach($flights as $flight)
            <div class="bg-white shadow rounded-lg hover:shadow-md transition-shadow duration-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('storage/' . ($flight->airline->logo ?? 'images/default-airline.png')) }}" class="h-10 w-10 object-contain">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $flight->airline->name ?? 'Unknown Airline' }}</div>
                            <div class="text-sm text-gray-500">Flight {{ $flight->flight_number }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-medium text-gray-900">Rp {{ number_format($flight->price, 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">per person</div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $flight->departureAirport->city ?? 'Unknown City' }}</p>
                        <p class="text-sm text-gray-500">{{ $flight->departure_time->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $flight->arrivalAirport->city ?? 'Unknown City' }}</p>
                        <p class="text-sm text-gray-500">{{ $flight->arrival_time->format('H:i') }}</p>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button onclick="openBookingModal({{ $flight->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Book Now</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
