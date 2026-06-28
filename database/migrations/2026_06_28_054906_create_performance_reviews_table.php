<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('review_period');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('due_date')->nullable();
            $table->decimal('overall_rating', 5, 2)->nullable();
            $table->string('status')->default('draft');
            $table->text('employee_notes')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('employee_id');
            $table->index('reviewer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
