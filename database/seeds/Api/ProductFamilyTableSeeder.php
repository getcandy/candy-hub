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
                    'ecommerce' => [
                        'gb' => 'Shoes',
                        'se' => 'Skodon'
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'gb' => 'Look at our shoes',
                        'se' => 'Titta på våra skor'
                    ],
                    'mobile' => [
                        'gb' => 'Just a tap away from some shoes',
                        'se' => 'Bara en kran från några skor'
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);


        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'gb' => 'Bags',
                        'se' => 'Påsar'
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'gb' => 'Buy a bag online',
                        'se' => 'Köp en väska online'
                    ],
                    'mobile' => [
                        'gb' => 'A bag for your phone',
                        'se' => 'En väska till din telefon'
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'gb' => 'Jewellery',
                        'se' => 'Smycke'
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'gb' => 'The finest jewellery online',
                        'se' => 'De finaste smycken'
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

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'gb' => 'House items',
                        'se' => 'Husartiklar'
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'gb' => 'Decorate your home from stuff on our website',
                        'se' => 'Dekorera ditt hem från saker på vår hemsida'
                    ],
                    'mobile' => [
                        'gb' => 'Just a tap away from awesome stuff for your house',
                        'se' => 'Bara en kran från fantastiska saker till ditt hus'
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]
            ]
        ]);


        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }
    }
}
