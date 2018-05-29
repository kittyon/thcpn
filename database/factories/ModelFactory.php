<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function(Faker $faker){
  return [
    'name' => $faker->name,
    'email' => $faker->email,
    'password' =>bcrypt('123456'),
  ];
});

$factory->define(App\Models\Organization::class, function(Faker $faker){
  return [
    'name' => $faker->word,
    'description' =>$faker->word,
  ];
});

$factory->define(App\Models\Device::class, function(Faker $faker) {

    return [
        'name' => $faker->word,
        'iccid' => $faker->word,
        'status' =>$faker->randomElement(['normal', 'abnormal']),
        'version' => $faker->randomElement(['1.0','2.0']),
    ];
});

$factory->define(App\Models\DeviceConfig::class, function(Faker $faker) {
    $key = 't_1';
    $data = [
        'data' => [$key => [ 'unit' => $faker->numberBetween(1, 100)]],
        'control' => ['t_30' => '*'],
        'device_id' => App\Models\Device::first()->id,
    ];
    return $data;
});

$factory->define(App\Models\DeviceData::class, function(Faker $faker) {
    $key = 't_1';
    $data = [
        'ts' => $faker->dateTimeThisMonth,
        'data' => [$key => [ 'value' => $faker->numberBetween(1, 100)]],
        'device_config_id' => $faker->numberBetween(1, 1),
        'device_id' => $faker->numberBetween(1,2),
    ];
    return $data;
});
