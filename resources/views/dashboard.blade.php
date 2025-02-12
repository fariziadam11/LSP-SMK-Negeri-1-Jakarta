@extends('components.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Quick Stats -->
    @foreach ([
        ['title' => 'Upcoming Flights', 'count' => $upcomingFlights, 'color' => 'from-blue-500 to-indigo-600', 'icon' => 'clock'],
        ['title' => 'Completed Bookings', 'count' => $completedBookings, 'color' => 'from-green-500 to-teal-600', 'icon' => 'check-circle'],
        ['title' => 'Total Spent', 'count' => 'Rp ' . number_format($totalSpent, 0, ',', '.'), 'color' => 'from-yellow-500 to-orange-600', 'icon' => 'currency-dollar']
    ] as $stat)
    <div class="bg-white/80 backdrop-blur-lg shadow-lg rounded-xl p-6 border border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r {{ $stat['color'] }} text-white p-4 rounded-lg shadow-md">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if ($stat['icon'] === 'clock')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @elseif ($stat['icon'] === 'check-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                    @endif
                </svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">{{ $stat['title'] }}</p>
                <p class="text-xl font-bold text-gray-900">{{ $stat['count'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Recent Bookings -->
<div class="mt-8 bg-white/90 backdrop-blur-lg shadow-lg rounded-xl p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-center border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-blue-200 to-blue-300 text-blue-700">
                    <th class="px-4 py-2">Booking Code</th>
                    <th class="px-4 py-2">Flight</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $booking)
                <tr class="border-b hover:bg-gray-100 transition">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $booking->booking_code }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $booking->flight->departureAirport->city }} → {{ $booking->flight->arrivalAirport->city }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $booking->flight->departure_time->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                               ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $booking->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
