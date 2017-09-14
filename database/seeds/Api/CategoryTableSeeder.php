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
                        'en' => 'Fashion', 'se' => 'Mode'
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

        $fashion->routes()->create([
            'default' => true,
            'slug' => str_slug('Fashion'),
            'locale' => 'en'
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
                        'en' => 'Tops & Blouses', 'se' => 'Toppar & Blusar'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Tops & Blouses'),
            'locale' => 'en'
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
                        'en' => 'Knitwear', 'se' => 'Stickat'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Knitwear'),
            'locale' => 'en'
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
                        'en' => 'Cardigans & Wraps'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Cardigans & Wraps'),
            'locale' => 'en'
        ]);

        // Item 4

        $acc = $fashion->children()->create([
            'attribute_data' => [
                'name' => [
                    'ecommerce' => [
                        'en' => 'Accessories', 'se' => 'Tillbehör'
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

        $acc->routes()->create([
            'default' => true,
            'slug' => str_slug('Accessories'),
            'locale' => 'en'
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
                        'en' => 'Eye lashes', 'se' => 'Ögonfransar'
                    ],
                    'mobile' => [
                        'en' => '',
                        'se' => ''
                    ],
                    'print' => [
                        'en' => '',
                        'se' => ''
                    ]
                ]///////////////////////////////////////////////////////////////////////////////////////////
        ];

        $thing->appendToNode($acc)->save();

        $thing->routes()->create([
            'default' => true,
            'slug' => str_slug('Eye lashes'),
            'locale' => 'en'
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
                        'en' => 'Coats & Jackets', 'se' => 'Jackor'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Coats & Jackets'),
            'locale' => 'en'
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
                        'en' => 'Dresses & Skirts', 'se' => 'Klänningar & Kjolar'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Dresses & Skirts'),
            'locale' => 'en'
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
                        'en' => 'Shoes', 'se' => 'Skor'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Shoes'),
            'locale' => 'en'
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
                        'en' => 'Bags', 'se' => 'påsar'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Bags'),
            'locale' => 'en'
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
                        'en' => 'Jewellery', 'se' => 'Smycke'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Jewellery'),
            'locale' => 'en'
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
                        'en' => 'House', 'se' => 'Hem'
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

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('House'),
            'locale' => 'en'
        ]);

        $item->routes()->create([
            'default' => true,
            'slug' => str_slug('Hem'),
            'locale' => 'se'
        ]);
    }
}
