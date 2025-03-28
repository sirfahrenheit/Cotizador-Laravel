<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCrmFieldsToClientesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Campo para indicar el origen del cliente (ejemplo: web, referido, etc.)
            $table->string('origen')->nullable()->after('correo');
            // Campo para almacenar el historial de interacciones (comentarios, llamadas, reuniones, etc.)
            $table->text('historial_interacciones')->nullable()->after('origen');
            // Campo para segmentar el cliente (por ejemplo: VIP, prospecto, etc.)
            $table->string('segmentacion')->nullable()->after('historial_interacciones');
        });
    }

    /**
     * Revierte las migraciones.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['origen', 'historial_interacciones', 'segmentacion']);
        });
    }
}
