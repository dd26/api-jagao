<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDetailRequestServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_request_services', function (Blueprint $table) {
            $table->decimal('comision_app', 8, 2)->default(0)->after('service_price');
            $table->decimal('comision_espcialist', 8, 2)->default(0)->after('comision_app');
            $table->boolean('comision_is_porcentage')->default(false)->after('comision_espcialist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_request_services', function (Blueprint $table) {
            $table->dropColumn('comision_app');
            $table->dropColumn('comision_espcialist');
            $table->dropColumn('comision_is_porcentage');
        });
    }
}
