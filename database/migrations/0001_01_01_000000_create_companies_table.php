<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i:s');
            $table->string('language')->default('en');
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('financial_year_start')->nullable();
            $table->string('week_start_day')->default('Monday');
            $table->integer('rows_per_page')->default(25);
            $table->string('theme_color')->default('#4f46e5');
            $table->boolean('dark_mode')->default(false);
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
