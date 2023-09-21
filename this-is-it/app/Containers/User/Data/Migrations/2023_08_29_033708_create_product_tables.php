<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductTables extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('ownership');
            $table->string('brand');
            $table->string('image');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
