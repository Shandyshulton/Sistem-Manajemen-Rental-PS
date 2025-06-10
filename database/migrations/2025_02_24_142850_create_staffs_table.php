<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('staffs', function (Blueprint $table) {
        $table->id('id_staff');
        $table->string('name');
        $table->string('email')->unique();
        $table->enum('role', ['admin', 'operator']);
        $table->string('password');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
