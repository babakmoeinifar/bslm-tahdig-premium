<?php

namespace Bslm\Tahdig\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $table = 'tahdig_meals';
    public $timestamps = false;

    public static function all($columns = ['*'])
    {
        return self::where('is_active', true)->get();
    }

    public function bookings()
    {
        return $this->hasMany(TahdigBooking::class);
    }
}
