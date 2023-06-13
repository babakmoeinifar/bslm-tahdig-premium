<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeToMealsTable extends Migration
{
    public function up()
    {
        Schema::table('tahdig_meals', function (Blueprint $table) {
            $table->time('serve_at');
            $table->tinyInteger('block_time');
        });
    }

    public function down()
    {
        Schema::table('tahdig_meals', function (Blueprint $table) {
            $table->dropColumn('serve_at');
            $table->dropColumn('block_time');
        });
    }
}
