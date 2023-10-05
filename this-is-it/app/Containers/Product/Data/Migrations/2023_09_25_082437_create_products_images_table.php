<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsImagesTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('product_images', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
