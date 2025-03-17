<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id('cotizacion_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('cotizacion_numero')->unique();
            $table->string('cotizacion_token', 64)->unique();
            $table->date('expiration_date');
            $table->text('payment_conditions')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['pendiente', 'autorizada', 'rechazada', 'finalizada'])
                  ->default('pendiente');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cliente_id')->references('cliente_id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
}
