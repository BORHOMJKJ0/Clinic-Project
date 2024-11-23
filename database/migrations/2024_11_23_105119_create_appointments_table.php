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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('doctor_id')->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->time('appointment_time');
            $table->date('appointment_date');
            $table->enum('status', ['canceled', 'reserved', 'available'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
