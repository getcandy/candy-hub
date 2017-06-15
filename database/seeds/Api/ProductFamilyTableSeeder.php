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
            'name' => ['en' => 'Shoes', 'sv' => 'Skodon']
        ]);
        ProductFamily::create([
            'name' => ['en' => 'Bags', 'sv' => 'Väska']
        ]);
        ProductFamily::create([
            'name' => ['en' => 'Jewellery', 'sv' => 'Smycke']
        ]);
        ProductFamily::create([
            'name' => ['en' => 'House items', 'sv' => 'Husartiklar']
        ]);
    }
}
