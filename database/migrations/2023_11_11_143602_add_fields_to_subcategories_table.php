<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->boolean('comision_is_porcentage')->default(false)->after('price');
            $table->decimal('comision_app', 8, 2)->default(0)->after('comision_is_porcentage');
            $table->decimal('comision_espcialist', 8, 2)->default(0)->after('comision_app');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn('comision_is_porcentage');
            $table->dropColumn('comision_app');
            $table->dropColumn('comision_espcialist');
        });
    }
}
