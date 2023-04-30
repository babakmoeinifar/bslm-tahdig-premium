<?php

namespace Bslm\Tahdig\Http\Models;

use App\Models\Comment;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TahdigReservation extends Model
{
    protected $guarded = ['_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(TahdigBooking::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function comments()
    {
        return $this->morphOne(Comment::class, 'commentable');
    }
}
