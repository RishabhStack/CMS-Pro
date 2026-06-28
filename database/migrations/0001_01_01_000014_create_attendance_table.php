<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->integer('total_break_minutes')->default(0);
            $table->string('total_hours')->nullable();
            $table->string('overtime_hours')->nullable();
            $table->string('status')->default('present');
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unique(['employee_id', 'date']);
            $table->index(['company_id', 'date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
