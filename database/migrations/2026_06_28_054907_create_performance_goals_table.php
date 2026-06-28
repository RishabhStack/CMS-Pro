<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('performance_reviews')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('kpi');
            $table->string('target_value')->nullable();
            $table->string('achieved_value')->nullable();
            $table->decimal('weight', 5, 2)->default(0);
            $table->unsignedTinyInteger('self_rating')->nullable();
            $table->unsignedTinyInteger('manager_rating')->nullable();
            $table->string('status')->default('not_started');
            $table->timestamps();

            $table->index('review_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_goals');
    }
};
