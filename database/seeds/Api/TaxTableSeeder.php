<?php

use Illuminate\Database\Seeder;
use GetCandy\Api\Models\Tax;

class TaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tax::create([
            'percentage' => 20,
            'name' => 'VAT',
            'default' => true
        ]);
    }
}
