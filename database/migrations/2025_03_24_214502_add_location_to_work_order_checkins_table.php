<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToWorkOrderCheckinsTable extends Migration
{
    public function up()
    {
         Schema::table('work_order_checkins', function (Blueprint $table) {
             // Se agregan las columnas para guardar la ubicación
             $table->decimal('latitude', 10, 7)->nullable()->after('tecnico_id');
             $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
         });
    }

    public function down()
    {
         Schema::table('work_order_checkins', function (Blueprint $table) {
             $table->dropColumn(['latitude', 'longitude']);
         });
    }
}
