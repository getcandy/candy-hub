<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeProductFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_product_family', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->unsigned();
            $table->foreign('attribute_id')->references('id')->onDelete('cascade')->on('attributes');
            $table->integer('product_family_id')->unsigned();
            $table->foreign('product_family_id')->references('id')->onDelete('cascade')->on('product_families');
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
        Schema::table('attribute_product_family', function (Blueprint $table) {
            $table->dropForeign(['product_family_id']);
            $table->dropForeign(['attribute_id']);
        });
        Schema::dropIfExists('attribute_product_family');
    }
}
