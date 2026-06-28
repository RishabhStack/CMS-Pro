<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_swap_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('from_employee_id');
            $table->unsignedBigInteger('to_employee_id');
            $table->unsignedBigInteger('shift_assignment_id');
            $table->date('date');
            $table->text('reason');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('from_employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('to_employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('shift_assignment_id')->references('id')->on('shift_assignments')->onDelete('cascade');
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_swap_requests');
    }
};
