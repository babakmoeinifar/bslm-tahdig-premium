<?php

namespace Bslm\Tahdig\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\PowerJoins\PowerJoins;

class TahdigBooking extends Model
{
    use SoftDeletes ,PowerJoins;

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'tahdig_food_tahdig_booking', 'booking_id');
    }

    public function foodsForInter()
    {
        return $this->belongsToMany(Food::class, 'tahdig_food_tahdig_booking', 'booking_id')
            ->wherePivot('for_inter', true);
    }

    public function defaultFood()
    {
        return $this->belongsTo(Food::class, 'default_food_id');
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function reservations()
    {
        return $this->hasMany(TahdigReservation::class, 'booking_id');
    }

    public function reservationsForUser()
    {
        return $this->hasMany(TahdigReservation::class, 'booking_id')
            ->where('user_id', auth()->id());
    }
}
