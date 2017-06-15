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
            'name' => json_encode(['en' => 'Shoes', 'sv' => 'Skodon'])
        ]);
        ProductFamily::create([
            'name' => json_encode(['en' => 'Bags', 'sv' => 'Väska'])
        ]);
        ProductFamily::create([
            'name' => json_encode(['en' => 'Jewellery', 'sv' => 'Smycke'])
        ]);
        ProductFamily::create([
            'name' => json_encode(['en' => 'House items', 'sv' => 'Husartiklar'])
        ]);
    }
}
