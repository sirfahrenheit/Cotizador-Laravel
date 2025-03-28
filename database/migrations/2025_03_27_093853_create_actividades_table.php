<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('actividades')) {
            Schema::create('actividades', function (Blueprint $table) {
                $table->id();
                // Relación con la tabla clientes (la clave foránea apunta a 'cliente_id')
                $table->unsignedBigInteger('cliente_id');
                $table->string('tipo');
                $table->dateTime('fecha');
                $table->text('descripcion')->nullable();
                $table->timestamps();

                // Define la clave foránea referenciando 'cliente_id' en la tabla 'clientes'
                $table->foreign('cliente_id')->references('cliente_id')->on('clientes')->onDelete('cascade');
            });
        }
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('actividades')) {
            Schema::dropIfExists('actividades');
        }
    }
}
