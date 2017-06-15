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
            'name' => ['en' => 'Fashion', 'sv' => 'Mode']
        ]);

        $fashion->children()->create([
            'name' => ['en' => 'Tops & Blouses', 'sv' => 'Toppar & Blusar']
        ]);
        $fashion->children()->create([
            'name' => ['en' => 'Knitwear', 'sv' => 'Stickat']
        ]);
        $fashion->children()->create([
            'name' => ['en' => 'Cardigans & Wraps']
        ]);
        $acc = $fashion->children()->create([
            'name' => ['en' => 'Accessories', 'sv' => 'Tillbehör']
        ]);

        $thing = new Category();

        $thing->name = ['en' => 'Eye lashes', 'sv' => 'Ögonfransar'];

        $thing->appendToNode($acc)->save();

        $fashion->children()->create([
            'name' => ['en' => 'Coats & Jackets', 'sv' => 'Jackor']
        ]);
        $fashion->children()->create([
            'name' => ['en' => 'Dresses & Skirts', 'sv' => 'Klänningar & Kjolar']
        ]);

        Category::create([
            'name' => ['en' => 'Shoes', 'sv' => 'Skor']
        ]);
        Category::create([
            'name' => ['en' => 'Bags', 'sv' => 'påsar']
        ]);
        Category::create([
            'name' => ['en' => 'Jewellery', 'sv' => 'Smycke']
        ]);
        Category::create([
            'name' => ['en' => 'House', 'sv' => 'Hem']
        ]);
    }
}
