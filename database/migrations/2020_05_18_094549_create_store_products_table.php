<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned();
            $table->string('servername', 32)->nullable();
            $table->string('name');
            $table->string('name_en');
            $table->text('descr');
            $table->text('descr_en');
            $table->integer('price_rub')->unsigned()->nullable();
            $table->integer('price_coins')->unsigned()->nullable();
            $table->bigInteger('discount_id')->unsigned()->nullable();
            $table->text('data');
            $table->integer('amount')->unsigned();
            $table->bigInteger('count_buys')->unsigned()->default(0);
            $table->boolean('enabled');
            $table->string('img');
            $table->timestamp('created_at');

            $table->foreign('category_id')->on('store_categories')->references('id')->onDelete('cascade');
            $table->foreign('discount_id')->on('store_discounts')->references('id')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['discount_id']);

            $table->dropIfExists();
        });
    }
}
