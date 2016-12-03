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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Concert::class, function(Faker\Generator $faker){
	return [
		'title' => 'The Red Chord',
	    'subtitle' => 'with Animosity and Lethargy',
	    'date' => Carbon\Carbon::parse('+2 weeks'),
	    'ticket_price' => '2000',
	    'venue' => 'The Mosh Pit',
	    'venue_address' => '123 Lane',
	    'city' => 'Fakeville',
	    'state' => 'ON',
	    'zip' => '17916',
	    'additional_information' => 'Some info' 
    ];
});

$factory->state(App\Concert::class, 'published', function($faker){
	return [
	    'published_at' => Carbon\Carbon::parse('-1 week')
    ];
});

$factory->state(App\Concert::class, 'unpublished', function($faker){
	return [
	    'published_at' => null
    ];
});

