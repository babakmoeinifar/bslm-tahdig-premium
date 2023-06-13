<?php

namespace Bslm\Tahdig\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use softDeletes;

    protected $table = 'tahdig_foods';

    public function bookings()
    {
        return $this->belongsToMany(TahdigBooking::class);
    }

    public function reservations()
    {
        return $this->hasMany(TahdigReservation::class);
    }

    public function Restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
