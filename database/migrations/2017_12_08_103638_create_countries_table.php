<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->json('name');
            $table->string('region')->index();
            $table->string('sub_region')->index()->nullable();
            $table->string('iso_a_2')->unique();
            $table->string('iso_a_3')->unique();
            $table->string('iso_numeric')->unique();
            $table->timestamps();
        });

        $countries = json_decode(file_get_contents(storage_path('app/imports/countries.json')), true);

        foreach ($countries as $country) {
            $name = ['en' => $country['name']['common']];
            
            foreach ($country['translations'] as $code => $data) {
                $name[$code] = $data['common'];
            }

            \GetCandy\Api\Countries\Models\Country::create([
                'name' => json_encode($name),
                'iso_a_2' => $country['cca2'],
                'iso_a_3' => $country['cca3'],
                'iso_numeric' => $country['ccn3'],
                'region' => $country['region'],
                'sub_region' => $country['subregion']
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
