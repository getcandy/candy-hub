<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->after('name')->nullable();
            $table->string('firstname')->after('title')->nullable();
            $table->string('lastname')->after('firstname')->nullable();
            $table->string('contact_number')->after('lastname')->nullable();
            $table->string('vat_no')->after('contact_number')->nullable();
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('contact_number');
            $table->dropColumn('vat_no');
        });
    }
}
