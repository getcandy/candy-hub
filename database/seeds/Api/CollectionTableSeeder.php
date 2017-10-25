<?php

use Illuminate\Database\Seeder;

use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Attributes\Models\Attribute;

class CollectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collections = [
            [
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
            ],
            [
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
            ]
        ];

           $attributes = Attribute::get();
                
        foreach ($collections as $c) {
            $collection = Collection::create($c);
            foreach ($attributes as $att) {
                $collection->attributes()->attach($att);
            }
        }
    }
}
