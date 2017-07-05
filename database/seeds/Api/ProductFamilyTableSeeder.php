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
                        'en' => 'Shoes',
                        'sv' => 'Skodon'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Look at our shoes',
                        'sv' => 'Titta på våra skor'
                    ],
                    'mobile' => [
                        'en' => 'Just a tap away from some shoes',
                        'sv' => 'Bara en kran från några skor'
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
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
                        'en' => 'Bags',
                        'sv' => 'Påsar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Buy a bag online',
                        'sv' => 'Köp en väska online'
                    ],
                    'mobile' => [
                        'en' => 'A bag for your phone',
                        'sv' => 'En väska till din telefon'
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
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
                        'en' => 'Jewellery',
                        'sv' => 'Smycke'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'The finest jewellery online',
                        'sv' => 'De finaste smycken'
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

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'House items',
                        'sv' => 'Husartiklar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'sv' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Decorate your home from stuff on our website',
                        'sv' => 'Dekorera ditt hem från saker på vår hemsida'
                    ],
                    'mobile' => [
                        'en' => 'Just a tap away from awesome stuff for your house',
                        'sv' => 'Bara en kran från fantastiska saker till ditt hus'
                    ],
                    'print' => [
                        'en' => '',
                        'sv' => ''
                    ]
                ]
            ]
        ]);


        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }
    }
}
