<?php

use Illuminate\Database\Seeder;

use GetCandy\Api\Models\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::create([
            'code' => 'GBP',
            'name' => 'British Pound',
            'enabled' => true,
            'exchange_rate' => 1,
            'format' => '&#xa3;{price}',
            'decimal_point' => '.',
            'thousand_point' => ',',
            'default' => true
        ]);
    }
}
