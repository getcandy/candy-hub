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
                        'se' => 'Skodon'
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Look at our shoes',
                        'se' => 'Titta på våra skor'
                    ],
                    'mobile' => [
                        'en' => 'Just a tap away from some shoes',
                        'se' => 'Bara en kran från några skor'
                    ],
                    'print' => [
                        'en' => '',
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
                        'en' => 'Bags',
                        'se' => 'Påsar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Buy a bag online',
                        'se' => 'Köp en väska online'
                    ],
                    'mobile' => [
                        'en' => 'A bag for your phone',
                        'se' => 'En väska till din telefon'
                    ],
                    'print' => [
                        'en' => '',
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
                        'en' => 'Jewellery',
                        'se' => 'Smycke'
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'The finest jewellery online',
                        'se' => 'De finaste smycken'
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

        foreach ($atts as $att) {
            $fam->attributes()->attach($att);
        }

        $fam = ProductFamily::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'House items',
                        'se' => 'Husartiklar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ],
                'description' => [
                    'ecommerce' => [
                        'en' => 'Decorate your home from stuff on our website',
                        'se' => 'Dekorera ditt hem från saker på vår hemsida'
                    ],
                    'mobile' => [
                        'en' => 'Just a tap away from awesome stuff for your house',
                        'se' => 'Bara en kran från fantastiska saker till ditt hus'
                    ],
                    'print' => [
                        'en' => '',
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
