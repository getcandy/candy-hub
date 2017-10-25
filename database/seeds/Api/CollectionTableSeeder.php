<?php

use Illuminate\Database\Seeder;

use GetCandy\Api\Collections\Models\Collection;

class CollectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['en' => 'Early Sale', 'sv' => 'Tidig försäljning']
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);
        Collection::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'House',
                        'sv' => ''
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);
    }
}
