<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('attribute_groups');
            $table->string('name');
            $table->string('handle')->unique();
            $table->integer('position');
            $table->boolean('variant')->nullable();
            $table->boolean('searchable')->nullable();
            $table->boolean('filterable')->nullable();
            $table->timestamps();

            $table->index(['handle']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropIndex(['handle']);
        });
        Schema::dropIfExists('attributes');
    }
}
