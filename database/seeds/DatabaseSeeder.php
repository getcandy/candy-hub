<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit();
        }

        Eloquent::unguard();

        $this->call(SettingsTableSeeder::class);
        $this->call(CustomerGroupTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ProductFamilyTableSeeder::class);
        $this->call(CollectionTableSeeder::class);
        $this->call(ChannelTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(AssetSourceTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        \Artisan::call('passport:install');
    }
}
