<?php

use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('GetCandy\Api\Database\Seeds\UserTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\AttributesTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\ProductFamilyTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\ChannelTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\LanguageTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\ProductTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\CurrencyTableSeeder');
        $this->call('GetCandy\Api\Database\Seeds\TaxTableSeeder');


        $client = (new \Laravel\Passport\Client)->forceFill([
            'name' => 'TestClient',
            'secret' => str_random(40),
            'redirect' => 'http://homestead.app',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false,
        ]);

        $client->save();
    }
}
