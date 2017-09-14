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
                        ['en' => 'Early Sale', 'se' => 'Tidig försäljning']
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);
        Collection::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['en' => 'House', 'se' => '']
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);
    }
}
