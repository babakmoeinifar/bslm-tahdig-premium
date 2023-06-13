<?php

namespace Bslm\Tahdig\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = 'tahdig_restaurants';

    public function Foods()
    {
        return $this->hasMany(Food::class);
    }
}
