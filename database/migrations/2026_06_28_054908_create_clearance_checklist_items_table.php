<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clearance_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resignation_id');
            $table->string('department');
            $table->string('item');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->boolean('is_cleared')->default(false);
            $table->unsignedBigInteger('cleared_by')->nullable();
            $table->timestamp('cleared_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('resignation_id')->references('id')->on('resignations')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cleared_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['resignation_id', 'department']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clearance_checklist_items');
    }
};
