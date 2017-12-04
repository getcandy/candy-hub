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

        $this->call(SettingsTableSeeder::class);
        $this->call(AssociationGroupTableSeeder::class);
        $this->call(CustomerGroupTableSeeder::class);
        $this->call(ChannelTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductFamilyTableSeeder::class);
        $this->call(CollectionTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        // $this->call(TaxTableSeeder::class);
        $this->call(AssetSourceTableSeeder::class);
        $this->call(TagsTableSeeder::class);

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
