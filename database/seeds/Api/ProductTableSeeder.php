<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Routes\Models\Route;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $language = \GetCandy\Api\Languages\Models\Language::first()->id;

        $basic = \GetCandy\Api\Layouts\Models\Layout::create([
            'name' => 'Basic product',
            'handle' => 'basic-product'
        ])->id;

        $featured = \GetCandy\Api\Layouts\Models\Layout::create([
            'name' => 'Featured product',
            'handle' => 'featured-product'
        ])->id;


        $products = [
            // Boots
            'Shoes' => [
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Black Bamboosh',
                                'sv' => 'Svart Bamboosh'
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Leather',
                                'sv' => 'Läder'
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
                                'en' => 'Moroccan babouche made from butter-soft black leather with slim rubber soles. Indoor wear recommended.',
                                'sv' => 'Marockansk babouche tillverkad av smörmjuk svart läder med smala gummisulor. Inomslitage rekommenderas'
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
                    'layout' => $featured,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Camber Shoes',
                                'sv' => 'Camber Skor',
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Suede',
                                'sv' => 'Mockaskinn'
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
                                'en' => 'Slip-on soft pink suede brogues toughened up with comfortable crepe soles and leather lining. Penelope Chilvers.',
                                'sv' => 'Slip-on mjuka rosa suede brogues härdade med bekväma crepe sulor och läderfodral. Penelope Chilvers.'
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
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Cross over sandals',
                                'sv' => 'Korsa över sandaler'
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Suede',
                                'sv' => 'Mockaskinn'
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
                                'en' => 'Sleek slides with wide cross-over straps made from chunky-ribbed leather and suede. With padded leather footbed and grooved rubber soles.',
                                'sv' => 'Snygga glidbanor med breda överkantsremmar av chunky-ribbet läder och mocka. Med vadderade läderfotbäddar och räfflade gummisulor.'
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
            ],
            'Bags' => [
                // Bags
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Knot leather bag',
                                'sv' => 'Knot läderväska'
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Leather',
                                'sv' => 'Läder'
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
                                'en' => 'Sleek suede bag with chunky rolled leather strap and exaggerated knots at either end. Closes with zip across the top and has interior pockets.',
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
                ],
                [
                    'layout' => $featured,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Arizona bag',
                                'sv' => 'Arizona väska'
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Leather',
                                'sv' => 'Läder'
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
                                'en' => 'Artisan crafted cross body bag made from waxy umber leather with a wide buckled strap and deep front flap. Lined in twill with interior pockets. ',
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
                ],
                [
                    'layout' => $basic,
                    'attribute_data' => [
                        'name' => [
                            'ecommerce' => [
                                'en' => 'Beet bag',
                                'sv' => 'Köttväska'
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
                        'material' => [
                            'ecommerce' => [
                                'en' => 'Cotton',
                                'sv' => 'Bomull'
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
                                'en' => 'Capacious beach bag with room for just about anything. Cut from geometric-print cotton with fully lined interior and single zip pocket. Tie closure.',
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
            ],
            // 'Jewellery' => [
            //     [
            //         'name' => ['en' => 'Mesh watch', 'sv' => 'Mesh klocka'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['en' => 'Stainless Steel']]
            //     ],
            //     [
            //         'name' => ['en' => '3 Square earrings', 'sv' => '3 kvadratiska örhängen'],
            //         'layout' => $featured,
            //         'attribute_data' => ['material' => ['en' => 'Silver']]
            //     ],
            //     [
            //         'name' => ['en' => 'Bird Brooch', 'sv' => 'Fågelbrosch'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['en' => 'Silver']]
            //     ]
            // ],
            // 'House items' => [
            //     [
            //         'name' => ['en' => 'Feather dreamcatcher', 'sv' => 'Fjäderdrömskådare'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['en' => 'Leather, Feathers, Wool']]
            //     ],
            //     [
            //         'name' => ['en' => 'Driftwood fish', 'sv' => 'Driftwood fisk'],
            //         'layout' => $featured,
            //         'attribute_data' => ['material' => ['en' => 'Wood']]
            //     ],
            //     [
            //         'name' => ['en' => 'Mirror Candleholder', 'sv' => 'Spegel ljushållare'],
            //         'layout' => $basic,
            //         'attribute_data' => ['material' => ['en' => 'Glass, Metal']]
            //     ]
            // ]
        ];
        $i = 1;
        $attributes = Attribute::get();
        foreach ($products as $family => $products) {
            $family = ProductFamily::find($i);
            foreach ($products as $data) {
                $product = Product::create([
                    'attribute_data' => $data['attribute_data']
                ]);

                foreach ($attributes as $att) {
                    $product->attributes()->attach($att);
                }

                $product->layout()->associate($data['layout']);
                $product->family()->associate($family);

                foreach ($product->attribute_data['name'] as $channel => $data) {
                    if ($channel == 'ecommerce') {
                        $product->route()->create([
                            'default' => true,
                            'slug' => str_slug($data['en']),
                            'locale' => app()->getLocale()
                        ]);
                    }
                }
                $product->save();
            }
            $i++;
        }
    }
}
