<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewCountToCotizacionesTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            // Agrega la columna view_count después del campo total (ajusta 'after' si lo requieres)
            $table->unsignedInteger('view_count')->default(0)->after('total');
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
}
