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
        Artisan::call('migrate:refresh');

        $this->call('UserTableSeeder');

        $this->call('AttributesTableSeeder');
        $this->call('ProductFamilyTableSeeder');
        $this->call('ChannelTableSeeder');
        $this->call('LanguageTableSeeder');
        $this->call('ProductTableSeeder');
        $this->call('CurrencyTableSeeder');
        $this->call('TaxTableSeeder');

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
