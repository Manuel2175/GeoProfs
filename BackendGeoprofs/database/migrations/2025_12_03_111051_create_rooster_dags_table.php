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
        Schema::create('rooster_dags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('ochtend')->default(true);
            $table->boolean('middag')->default(true);
            $table->foreignId('rooster_weeks_id')->constrained('rooster_weeks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooster_dags');
    }
};
