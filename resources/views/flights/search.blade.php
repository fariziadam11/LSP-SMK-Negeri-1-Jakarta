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
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To -->
                    <div>
                        <label for="arrival" class="block text-sm font-medium text-gray-700">To</label>
                        <select name="arrival" id="arrival" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Passengers -->
                    <div>
                        <label for="passengers" class="block text-sm font-medium text-gray-700">Passengers</label>
                        <input type="number" name="passengers" id="passengers" min="1" value="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
            <div class="bg-white shadow rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('storage/' . ($flight->airline->logo ?? 'images/default-airline.png')) }}"
                            alt="{{ $flight->airline->name ?? 'Airline' }}"
                            class="h-10 w-10 object-contain">
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
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $flight->departureAirport->city ?? 'Unknown City' }}</p>
                                    <p class="text-sm text-gray-500">{{ $flight->departure_time->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $flight->arrivalAirport->city ?? 'Unknown City' }}</p>
                                    <p class="text-sm text-gray-500">{{ $flight->arrival_time->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('bookings.create', $flight) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Select Flight
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
