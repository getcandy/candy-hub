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
                        ['gb' => 'Early Sale', 'se' => 'Tidig försäljning']
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);
        Collection::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'House', 'se' => '']
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);
    }
}
