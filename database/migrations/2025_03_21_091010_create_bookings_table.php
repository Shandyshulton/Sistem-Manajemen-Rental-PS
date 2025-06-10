<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('customer_name');
            $table->string('phone_number');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('estimated_hours');
            $table->unsignedBigInteger('console_id');
            $table->text('selected_games');
            $table->enum('payment_type', ['Cash', 'Transfer', 'QRIS']);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'success', 'canceled']);
            $table->enum('playing_status', ['Play', 'Not Play'])->default('Play');
            $table->timestamps();

            // Set the foreign key reference
            $table->foreign('console_id')->references('id_console')->on('consoles');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
