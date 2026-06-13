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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');

            $table->index('client_id');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['Efectivo', 'Transferencia', 'Tarjeta Débito', 'Tarjeta Crédito', 'Otro']);
            $table->enum('concept', ['Membresía Mensual', 'Trimestral', 'Semestral', 'Anual', 'Clase Personal', 'Producto', 'Otro']);
            $table->enum('status', ['Pagado', 'Pendiente', 'Cancelado']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
