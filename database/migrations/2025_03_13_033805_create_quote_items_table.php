<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id('quote_item_id');
            $table->unsignedBigInteger('cotizacion_id');
            $table->integer('line_order')->default(1);
            $table->string('modelo')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('cotizacion_id')
                  ->references('cotizacion_id')
                  ->on('cotizaciones')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
}
