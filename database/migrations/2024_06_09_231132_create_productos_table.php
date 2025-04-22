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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            // El "código" es tu SKU
            $table->string('sku')->unique();
            $table->text('nombre');
            // OEM1 obligatorio
            $table->string('oem1');

            // Relaciones y campos obligatorios
            $table->foreignId('categoria_id')->constrained();
            $table->string('tipo_de_vehiculo');
            $table->string('origen');

            // Costos por moneda
            $table->decimal('costo_yen', 16, 2);
            $table->decimal('costo_usd', 16, 2);
            $table->decimal('costo_clp', 16, 2);

            $table->decimal('precio', 16, 2);

            // A partir de aquí, todos nullables
            $table->string('imagen')->nullable();
            $table->string('oem2')->nullable();
            $table->string('oem3')->nullable();
            $table->string('oem4')->nullable();
            $table->decimal('alto', 8, 2)->nullable();
            $table->decimal('ancho', 8, 2)->nullable();
            $table->decimal('largo', 8, 2)->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->text('descripcion')->nullable();
            
            $table->string('ubicacion')->nullable();

            $table->integer('stock');
            $table->timestamps();
        });
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // 3) Pivote modelo_producto
        Schema::create('modelo_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->foreignId('modelo_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelo_producto');
        Schema::dropIfExists('modelos');
        Schema::dropIfExists('productos');
    }
};
