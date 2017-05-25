<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(GetCandy\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\GetCandy\Api\Models\AttributeGroup::class, function (Faker\Generator $faker, $data = []) {
    $name = ucfirst($faker->unique()->domainWord());
    return [
        'name' => $name,
        'handle' => str_slug($name),
        'position' => $data['position']
    ];
});

$factory->define(\GetCandy\Api\Models\Attribute::class, function (Faker\Generator $faker, $data = []) {

    $name = ucfirst($faker->unique()->domainWord);
    return [
        'name' => $name,
        'handle' => str_slug($name),
        'variant' => $faker->boolean,
        'position' => $data['position'],
        'searchable' => $faker->boolean(90),
        'filterable' => $faker->boolean(80)
    ];
});

$factory->define(\GetCandy\Api\Models\ProductFamily::class, function (Faker\Generator $faker) {
    return [
        'name' => ucfirst($faker->domainWord)
    ];
});

$factory->define(\GetCandy\Api\Models\Product::class, function (Faker\Generator $faker) {
    $name = ['en' => ucfirst($faker->domainWord), 'fr' => ucfirst($faker->domainWord)];
    return [
        'name' => json_encode($name),
        'price' => $faker->randomNumber(2),
        'family_id' => \GetCandy\Api\Models\ProductFamily::inRandomOrder()->first()->id
    ];
});
