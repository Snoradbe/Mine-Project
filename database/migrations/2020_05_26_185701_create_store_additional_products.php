<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAdditionalProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_additional_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned()->comment('Товар, к которому относится этот товар');
            $table->bigInteger('additional_id')->unsigned()->comment('Дополнительный товар');
            $table->integer('amount')->unsigned()->comment('Количество');

            $table->foreign('product_id')->on('store_products')->references('id')->onDelete('cascade');
            $table->foreign('additional_id')->on('store_products')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_additional_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['additional_id']);
            $table->dropIfExists();
        });
    }
}
