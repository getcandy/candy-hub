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
        $atts = Attribute::where('group_id', '=', 1)->get();

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'en' => 'Shoes',
                    'sv' => 'Skodon'
                ],
                'description' => [
                    'en' => 'Look at our shoes',
                    'sv' => 'Titta på våra skor'
                ]
            ]
        ]);


        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'en' => 'Bags',
                    'sv' => 'Påsar'
                ],
                'description' => [
                    'en' => 'Buy a bag online',
                    'sv' => 'Köp en väska online'
                ]
            ]
        ]);

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'en' => 'Jewellery',
                    'sv' => 'Smycke'
                ],
                'description' => [
                    'en' => 'The finest jewellery online',
                    'sv' => 'De finaste smycken'
                ]
            ]
        ]);

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'en' => 'House items',
                    'sv' => 'Husartiklar'
                ],
                'description' => [
                    'en' => 'Decorate your home from stuff on our website',
                    'sv' => 'Dekorera ditt hem från saker på vår hemsida'
                ]
            ]
        ]);


        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }
    }
}
