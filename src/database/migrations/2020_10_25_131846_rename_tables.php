<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTables extends Migration
{
    public function up()
    {
        Schema::rename('bookings', 'tahdig_bookings');
        Schema::rename('reservations', 'tahdig_reservations');
    }

    public function down()
    {
        Schema::rename('tahdig_bookings', 'bookings');
        Schema::rename('tahdig_reservations', 'reservations');
    }
}
