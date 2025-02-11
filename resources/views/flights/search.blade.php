@extends('components.app')

@section('title', 'Search Flights')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <!-- Search Form -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-900 mb-5">Find Your Flight</h2>

        <form action="{{ route('flights.search') }}" method="GET" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Departure -->
                <div>
                    <label for="departure" class="block text-sm font-medium text-gray-700">From</label>
                    <select name="departure" id="departure" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Arrival -->
                <div>
                    <label for="arrival" class="block text-sm font-medium text-gray-700">To</label>
                    <select name="arrival" id="arrival" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($airports as $airport)
                            <option value="{{ $airport->id }}">{{ $airport->city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Passengers -->
                <div>
                    <label for="passengers" class="block text-sm font-medium text-gray-700">Passengers</label>
                    <input type="number" name="passengers" id="passengers" min="1" value="{{ old('passengers', 1) }}" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                Search Flights
            </button>
        </form>
    </div>

    <!-- Flight Results -->
    @if(isset($flights))
    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Available Flights</h3>
        <div class="grid grid-cols-1 gap-6">
            @foreach($flights as $flight)
            <div class="bg-white shadow-lg rounded-xl p-6 flex justify-between items-center transition hover:shadow-xl">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/' . ($flight->airline->logo ?? 'images/default-airline.png')) }}" class="h-12 w-12 object-contain">
                    <div>
                        <div class="text-lg font-semibold text-gray-900">{{ $flight->airline->name ?? 'Unknown Airline' }}</div>
                        <div class="text-sm text-gray-500">Flight {{ $flight->flight_number }}</div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-gray-700">{{ $flight->departureAirport->city }} → {{ $flight->arrivalAirport->city }}</div>
                    <div class="text-sm text-gray-500">{{ $flight->departure_time->format('H:i') }} - {{ $flight->arrival_time->format('H:i') }}</div>
                </div>

                <div class="text-right">
                    <div class="text-xl font-bold text-gray-900">Rp {{ number_format($flight->price, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500">per person</div>
                </div>

                <button onclick="openBookingModal({{ $flight->id }})" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Book Now
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Book Flight</h2>
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

<!-- Alerts -->
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
