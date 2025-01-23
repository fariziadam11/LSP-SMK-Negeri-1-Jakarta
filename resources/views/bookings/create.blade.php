@extends('components.app')

    @section('title', 'Book Flight')

    @section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Flight Details</h2>

            <div class="border-t border-gray-200 pt-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Airline</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->airline?->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Flight Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->flight_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">From</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->departureAirport?->city ?? 'N/A' }} ({{ $flight->departureAirport?->code ?? 'N/A' }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">To</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->arrivalAirport?->city ?? 'N/A' }} ({{ $flight->arrivalAirport?->code ?? 'N/A' }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Departure</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->departure_time ? $flight->departure_time->format('d M Y H:i') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Arrival</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $flight->arrival_time ? $flight->arrival_time->format('d M Y H:i') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price per Person</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($flight->price ?? 0, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

                <form action="{{ route('bookings.store') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="flight_id" value="{{ $flight->id }}">

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Passenger Details</h3>
                        <div class="mt-4 space-y-4" id="passengers-container">
                            <div class="border rounded-md p-4 passenger-form">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <select name="passengers[0][title]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="passengers[0][name]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">ID Card Number</label>
                                        <input type="text" name="passengers[0][id_card_number]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-passenger" class="mt-4 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Passenger
                        </button>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="credit_card">Credit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="e_wallet">E-Wallet</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let passengerCount = 1;

    document.getElementById('add-passenger').addEventListener('click', function() {
        const container = document.getElementById('passengers-container');
        const template = document.querySelector('.passenger-form').cloneNode(true);

        // Update input names
        template.querySelectorAll('input, select').forEach(input => {
            input.name = input.name.replace('[0]', `[${passengerCount}]`);
            input.value = '';
        });

        container.appendChild(template);
        passengerCount++;
    });
    </script>
    @endsection
