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
            $table->after('name')->string('title')->nullable();
            $table->after('title')->string('first_name')->nullable();
            $table->after('firstname')->string('last_name')->nullable();
            $table->after('lastname')->string('contact_number')->nullable();
            $table->after('contact_number')->string('vat_no')->nullable();
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
