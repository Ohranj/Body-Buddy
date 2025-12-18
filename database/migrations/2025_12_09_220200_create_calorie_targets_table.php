<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calorie_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedSmallInteger('target')->default(2500);
            $table->date('take_effect');
            $table->boolean('can_trash')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calorie_targets');
    }
};
