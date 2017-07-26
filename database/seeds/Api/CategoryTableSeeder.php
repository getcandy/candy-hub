<?php

use Faker\Factory;
use GetCandy\Api\Categories\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fashion = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Fashion', 'se' => 'Mode']
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

        $fashion->routes()->create([
            'default' => true,
            'slug' => str_slug('Fashion'),
            'locale' => 'gb'
        ]);

        $fashion->routes()->create([
            'default' => true,
            'slug' => str_slug('Mode'),
            'locale' => 'se'
        ]);

        // Item 1

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Tops & Blouses', 'se' => 'Toppar & Blusar']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Tops & Blouses'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Toppar & Blusar'),
            'locale' => 'se'
        ]);

        // Item 2

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Knitwear', 'se' => 'Stickat']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Knitwear'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Stickat'),
            'locale' => 'se'
        ]);

        // Item 3

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Cardigans & Wraps']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Cardigans & Wraps'),
            'locale' => 'gb'
        ]);

        // Item 4

        $acc = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Accessories', 'se' => 'Tillbehör']
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

        $acc->routes()->create([
            'default' => true,
            'slug' => str_slug('Accessories'),
            'locale' => 'gb'
        ]);

        $acc->routes()->create([
            'default' => true,
            'slug' => str_slug('Tillbehör'),
            'locale' => 'se'
        ]);

        // Item 5

        $thing = new Category();

        $thing->attribute_data = [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Eye lashes', 'se' => 'Ögonfransar']
                    ],
                    'mobile' => [
                        'gb' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'gb' => '',
                        'se' => ''
                    ]
                ]///////////////////////////////////////////////////////////////////////////////////////////
        ];

        $thing->appendToNode($acc)->save();

        $thing->routes()->create([
            'default' => true,
            'slug' => str_slug('Eye lashes'),
            'locale' => 'gb'
        ]);

        $thing->routes()->create([
            'default' => true,
            'slug' => str_slug('Ögonfransar'),
            'locale' => 'se'
        ]);

        // Item 6

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Coats & Jackets', 'se' => 'Jackor']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Coats & Jackets'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Jackor'),
            'locale' => 'se'
        ]);

        // Item 7

        $item = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Dresses & Skirts', 'se' => 'Klänningar & Kjolar']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Dresses & Skirts'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Klänningar & Kjolar'),
            'locale' => 'se'
        ]);

        // Item 8

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Shoes', 'se' => 'Skor']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Shoes'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Skor'),
            'locale' => 'se'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Bags', 'se' => 'påsar']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Bags'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Påsar'),
            'locale' => 'se'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'Jewellery', 'se' => 'Smycke']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Jewellery'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Smycke'),
            'locale' => 'se'
        ]);

        $item = Category::create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        ['gb' => 'House', 'se' => 'Hem']
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('House'),
            'locale' => 'gb'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Hem'),
            'locale' => 'se'
        ]);
    }
}
