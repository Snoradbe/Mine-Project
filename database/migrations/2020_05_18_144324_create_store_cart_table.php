<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->bigInteger('purchase_id')->unsigned()->nullable();

            $table->foreign('product_id')->on('store_products')->references('id')->onDelete('cascade');
            $table->foreign('purchase_id')->on('store_purchases')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_cart', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['purchase_id']);
            $table->dropIfExists();
        });
    }
}
