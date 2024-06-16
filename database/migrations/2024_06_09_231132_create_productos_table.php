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
            $table->string('sku')->unique();
            $table->string('nombre');
            $table->string('oem1')->nullable();
            $table->string('oem2')->nullable();
            $table->string('oem3')->nullable();
            $table->string('oem4')->nullable();
            $table->string('descripcion',500)->nullable();
            $table->string('imagen')->nullable();
            $table->double('costo', 8, 2);
            $table->double('precio', 8, 2);
            $table->double('alto')->nullable();
            $table->double('ancho')->nullable();
            $table->double('largo')->nullable();
            $table->double('peso')->nullable();
            $table->integer('stock');
            $table->foreignId('categoria_id')->constrained(); // Aquí está la clave foránea
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
