<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Api\Attributes\Models\Attribute;

class ProductFamilyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductFamily::create([
            'name' => 'Shoes'
        ]);
        ProductFamily::create([
            'name' => 'Bags'
        ]);
        ProductFamily::create([
            'name' => 'Jewellery'
        ]);
        ProductFamily::create([
            'name' => 'House items'
        ]);
    }
}
