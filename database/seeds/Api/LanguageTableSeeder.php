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
            'lang' => 'en',
            'iso' => 'gb',
            'name' => 'English',
            'default' => true
        ]);

        Language::create([
            'lang' => 'fr',
            'iso' => 'fr',
            'name' => 'Français',
            'default' => false
        ]);
    }
}
