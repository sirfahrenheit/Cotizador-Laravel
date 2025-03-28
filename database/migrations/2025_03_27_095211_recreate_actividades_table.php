<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateActividadesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        // Elimina la tabla actividades si existe
        Schema::dropIfExists('actividades');

        // Crea la tabla actividades con la definición correcta
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->string('tipo');
            $table->dateTime('fecha');
            $table->text('descripcion')->nullable();
            $table->timestamps();

            // Crea la clave foránea correcta que referencia la columna 'cliente_id' en la tabla 'clientes'
            $table->foreign('cliente_id')
                  ->references('cliente_id')
                  ->on('clientes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}
