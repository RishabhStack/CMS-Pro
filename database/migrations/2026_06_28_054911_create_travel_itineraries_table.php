<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_request_id')->constrained('travel_requests')->cascadeOnDelete();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('activity');
            $table->string('location')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->index('travel_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_itineraries');
    }
};
