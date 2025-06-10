<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consoles', function (Blueprint $table) {
            $table->id('id_console');
            $table->enum('typeConsole', ['PS 5', 'PS 4', 'PS 3']);
            $table->enum('availability', ['Ready', 'Not Yet'])->default('Ready');
            $table->string('consoleRoom');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consoles');
    }
};
