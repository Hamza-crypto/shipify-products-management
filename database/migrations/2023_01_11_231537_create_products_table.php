<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('product_id');
            $table->string('sku')->nullable();
            $table->string('upc')->nullable();
            $table->integer('num_of_images');
            $table->string('status');
            $table->tinyInteger('is_blacklisted');
            $table->tinyInteger('store_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
