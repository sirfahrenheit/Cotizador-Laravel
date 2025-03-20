<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelefonoToUsersTable extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Agrega el campo 'telefono' después de 'email'. Puedes ajustar la posición si lo deseas.
            $table->string('telefono')->nullable()->after('email');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('telefono');
        });
    }
}
