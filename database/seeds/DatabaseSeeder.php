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

        // Truncate all tables, except migrations
        $tables = DB::select('SHOW TABLES');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $table) {
            if ($table->Tables_in_homestead !== 'migrations') {
                DB::table($table->Tables_in_homestead)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

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
        \Artisan::call('passport:install');
    }
}
