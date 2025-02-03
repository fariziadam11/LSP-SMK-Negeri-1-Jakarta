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
                                <option value="{{ $airport->id }}">{{ $airport->city }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To -->
                    <div>
                        <label for="arrival" class="block text-sm font-medium text-gray-700">To</label>
                        <select name="arrival" id="arrival" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            @foreach($airports as $airport)
                                <option value="{{ $airport->id }}">{{ $airport->city }}</option>
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
                <div class="flex justify-end mt-4">
                    <button onclick="openBookingModal({{ $flight->id }})" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Book Now</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 hidden bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Book Flight</h2>
        <form id="bookingForm" action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="flight_id" id="modalFlightId">
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-700">Payment Method</label>
                <div class="mt-2 space-y-2">
                    <label class="block"><input type="radio" name="payment_method" value="credit_card" required> Credit Card</label>
                    <label class="block"><input type="radio" name="payment_method" value="bank_transfer" required> Bank Transfer</label>
                    <label class="block"><input type="radio" name="payment_method" value="e_wallet" required> E-Wallet</label>
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                <button type="button" onclick="confirmBooking()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Proceed
                </button>
            </div>
        </form>
    </div>
</div>

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                });
            });
        </script>
    @endif


<script>
function openBookingModal(flightId) {
    document.getElementById('modalFlightId').value = flightId;
    document.getElementById('bookingModal').classList.remove('hidden');
}
function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

function confirmBooking() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to book this flight. Proceed?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('bookingForm').submit();
        }
    });
}
</script>
@endsection
