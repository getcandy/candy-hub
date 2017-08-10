<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use GetCandy\Api\Tags\Models\Tag;
use GetCandy\Api\Tags\Models\Taggable;
use GetCandy\Api\Products\Models\Product;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tag = new Tag();
        $tag->name = 'Blue';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Red';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Pink';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Purple';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Orange';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Video';
        $tag->save();

        $tag = new Tag();
        $tag->name = 'Image';
        $tag->save();

        $tag = new Taggable();
        $tag->tag_id = 1;
        $tag->taggable_id = 1;
        $tag->taggable_type = 'GetCandy\Api\Products\Models\Product';
        $tag->save();

        $tag = new Taggable();
        $tag->tag_id = 2;
        $tag->taggable_id = 1;
        $tag->taggable_type = 'GetCandy\Api\Products\Models\Product';
        $tag->save();

        $tag = new Taggable();
        $tag->tag_id = 3;
        $tag->taggable_id = 1;
        $tag->taggable_type = 'GetCandy\Api\Products\Models\Product';
        $tag->save();

        $tag = new Taggable();
        $tag->tag_id = 4;
        $tag->taggable_id = 1;
        $tag->taggable_type = 'GetCandy\Api\Products\Models\Product';
        $tag->save();

    }
}
