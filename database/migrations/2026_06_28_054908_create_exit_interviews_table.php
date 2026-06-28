<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exit_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resignation_id')->unique();
            $table->date('interview_date')->nullable();
            $table->unsignedBigInteger('interviewed_by')->nullable();
            $table->text('overall_experience')->nullable();
            $table->text('reason_for_leaving')->nullable();
            $table->text('feedback_on_company')->nullable();
            $table->boolean('would_recommend')->nullable();
            $table->text('suggestions')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('resignation_id')->references('id')->on('resignations')->onDelete('cascade');
            $table->foreign('interviewed_by')->references('id')->on('users')->onDelete('set null');

            $table->index('resignation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exit_interviews');
    }
};
