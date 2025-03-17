<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesDeTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ordenes_de_trabajo', function (Blueprint $table) {
            $table->id('orden_id');
            $table->date('fecha'); // La fecha de la orden de trabajo
            $table->text('tareas'); // Las tareas o instrucciones que el admin asigna
            $table->text('avances')->nullable(); // Los avances que el técnico ingresará
            $table->text('solicitudes')->nullable(); // Las solicitudes que el técnico pueda realizar
            $table->enum('estado', ['pendiente', 'finalizado'])->default('pendiente');
            $table->unsignedBigInteger('tecnico_id'); // Asigna la orden a un técnico (usuario)
            $table->timestamps();

            // Llave foránea: asume que en la tabla de usuarios la clave primaria es id
            $table->foreign('tecnico_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_de_trabajo');
    }
}
