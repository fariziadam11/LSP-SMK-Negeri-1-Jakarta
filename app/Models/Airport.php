<?php

namespace App\Models;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'city', 'country'];

    // Relasi ke Flights sebagai Departure Airport
    public function departureFlights()
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    // Relasi ke Flights sebagai Arrival Airport
    public function arrivalFlights()
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
