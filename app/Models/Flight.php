<?php

namespace App\Models;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'airline_id',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time',
        'price',
        'capacity',
        'available_seats',
        'status',
        'flight_number',
    ];

    // Relasi ke Airlines (Many-to-One)
    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    // Relasi ke Airports
    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
