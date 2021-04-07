<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();;
            $table->bigInteger('age')->nullable();;
            $table->double('length')->nullable();;
            $table->double('weight')->nullable();;
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('reservation_types')->onDelete('restrict');
            $table->bigInteger('goal_id')->unsigned()->nullable();
            $table->foreign('goal_id')->references('id')->on('reservation_goals')->onDelete('restrict');
            $table->string('other')->nullable();;
            $table->bigInteger('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('hole_bookings')->onDelete('restrict');
            $table->double('price');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->enum('deleted',['0','1'])->default('0');
            $table->enum('status',['start','ended'])->default('start');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
