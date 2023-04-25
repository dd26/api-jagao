<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeRoleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('categories'); // Almacena los ID de las categorías en formato JSON
            $table->string('resume'); // Almacena la ruta del archivo del currículum en PDF
            $table->string('identity_document'); // Almacena la ruta del archivo del documento de identidad
            $table->enum('status', ['aprobado', 'rechazado', 'en espera'])->default('en espera'); // Almacena el estado de la solicitud
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_change_requests');
    }
}
