<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time_1')->nullable();
            $table->time('end_time_1')->nullable();
            $table->time('start_time_2')->nullable();
            $table->time('end_time_2')->nullable();
            $table->boolean('bell_enabled')->default(false);
            $table->integer('allow_late_minutes')->default(0);
            $table->integer('allow_early_minutes')->default(0);
            $table->integer('rest_days')->default(2);
            $table->boolean('enable_ot')->default(true);
            $table->integer('ot_start_minutes')->default(60);
            $table->integer('ot_valid_minutes')->default(30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};




