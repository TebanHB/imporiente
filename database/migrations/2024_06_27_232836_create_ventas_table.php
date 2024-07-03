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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_venta')->nullable();
            $table->date('expiracion_oferta')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vendedor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
