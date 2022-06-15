<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailRequestServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_request_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_request_service_id');
            $table->foreign('master_request_service_id')->references('id')->on('master_request_services')->onDelete('cascade');
            $table->unsignedBigInteger('service_id');
            $table->string('service_name');
            $table->string('service_description');
            $table->decimal('service_price', 10, 2);
            $table->integer('quantity');
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
        Schema::dropIfExists('detail_request_services');
    }
}
