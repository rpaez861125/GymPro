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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('birth_date');
            $table->string('photo_url')->nullable();
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->onDelete('set null');
            $table->foreignId('routine_id')->nullable()->constrained('exercises_routines')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->enum('membership_type', ['Mensual', 'Trimestral', 'Semestral', 'Anual']);
            $table->enum('membership_status', ['Activa', 'Vencida', 'Suspendida']);
            $table->date('membership_expiry_date')->nullable();
            $table->date('join_date');
            $table->enum('status', ['Activo', 'Inactivo']);
            $table->string('emergency_contact')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
