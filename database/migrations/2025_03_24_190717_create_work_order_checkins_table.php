<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('work_order_checkins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tecnico_id');
            $table->timestamp('checked_in_at');
            $table->timestamps();

            $table->foreign('tecnico_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('work_order_checkins');
    }
};
