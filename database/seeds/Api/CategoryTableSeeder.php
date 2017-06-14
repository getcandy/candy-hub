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
            'name' => json_encode(['en' => 'Fashion', 'sv' => 'Mode'])
        ]);

        $fashion->children()->create([
            'name' => json_encode(['en' => 'Tops & Blouses', 'sv' => 'Toppar & Blusar'])
        ]);
        $fashion->children()->create([
            'name' => json_encode(['en' => 'Knitwear', 'sv' => 'Stickat'])
        ]);
        $fashion->children()->create([
            'name' => json_encode(['en' => 'Cardigans & Wraps'])
        ]);
        $acc = $fashion->children()->create([
            'name' => json_encode(['en' => 'Accessories', 'sv' => 'Tillbehör'])
        ]);

        $thing = new Category();

        $thing->name = json_encode(['en' => 'Eye lashes', 'sv' => 'Ögonfransar']);

        $thing->appendToNode($acc)->save();

        $fashion->children()->create([
            'name' => json_encode(['en' => 'Coats & Jackets', 'sv' => 'Jackor'])
        ]);
        $fashion->children()->create([
            'name' => json_encode(['en' => 'Dresses & Skirts', 'sv' => 'Klänningar & Kjolar'])
        ]);

        Category::create([
            'name' => json_encode(['en' => 'Shoes', 'sv' => 'Skor'])
        ]);
        Category::create([
            'name' => json_encode(['en' => 'Bags', 'sv' => 'påsar'])
        ]);
        Category::create([
            'name' => json_encode(['en' => 'Jewellery', 'sv' => 'Smycke'])
        ]);
        Category::create([
            'name' => json_encode(['en' => 'House', 'sv' => 'Hem'])
        ]);
    }
}
