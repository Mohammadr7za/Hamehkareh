<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLongAndLatToBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('bookings', 'latitude')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->double('latitude')->nullable();
            });
        }
        if (!Schema::hasColumn('bookings', 'longitude')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->double('longitude')->nullable();
            });
        }
        if (!Schema::hasColumn('bookings', 'coordinates')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('coordinates')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['latitude', 'longitude', 'coordinates'] as $column) {
            if (Schema::hasColumn('bookings', $column)) {
                Schema::table('bookings', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
