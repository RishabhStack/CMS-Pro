<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('salary_component_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('salary_component_id')->references('id')->on('salary_components')->onDelete('cascade');
            $table->index(['company_id', 'employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary');
    }
};
