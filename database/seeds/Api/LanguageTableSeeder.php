<?php

use Illuminate\Database\Seeder;
use GetCandy\Api\Languages\Models\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'code' => 'en',
            'name' => 'English',
            'default' => true
        ]);
    }
}
