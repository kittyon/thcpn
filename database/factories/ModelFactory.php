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
    $data = [
        'data' => ['solar' => [ 'desc'=>'太阳辐照',"port"=> "AD8","type"=> "solar-radiation", "unit"=> "lux", "max_v"=> 200000, "min_v"=> 0, "sensor_type"=> "NHZD1CI_S"],
                  'img_env'=>["desc"=> "图像", "port"=> "Img1", "type"=> "image", "unit"=> "", "sensor_type"=> "HIKVISION_CAM_I"],
                  'voltage'=>["desc"=> "电压", "port"=> "AD12", "type"=> "voltage", "unit"=> "V", "max_v"=> 20, "min_v"=> 0, "sensor_type"=> "KAVT2_V"],
                  'Ta'=>["desc"=> "环境温度", "port"=> "AD1", "type"=> "temperature", "unit"=> "℃", "max_v"=> 60, "min_v"=> -20, "sensor_type"=> "CWS1806A1AG_T"],
                  'wind_dir'=>["desc"=> "风向", "port"=> "AD3", "type"=> "wind-direction", "unit"=> "°", "max_v"=> 360, "min_v"=> 0, "sensor_type"=> "NHFX46A1_WD"],
                  'rain_fall'=>["desc"=> "降雨量", "port"=> "PI1", "type"=> "rainfall", "unit"=> "mm", "sensor_type"=> "DAVIS785_R"],
                  'temp_soil'=>["desc"=> "土壤温度", "port"=> "AD7", "type"=> "temperature", "unit"=> "℃", "max_v"=> 60, "min_v"=> -20, "sensor_type"=> "SOILMTYXR1_T"],
                  'hum_soil'=>["desc"=> "土壤湿度", "port"=> "AD6", "type"=> "humidity", "unit"=> "%", "max_v"=> 100, "min_v"=> 0, "sensor_type"=> "SOILMTYXR1_H"],
                  'wind_velocity'=>["desc"=> "风速", "port"=> "AD7", "type"=> "wind-velocity", "unit"=> "m/s", "max_v"=> 60, "min_v"=> -20, "sensor_type"=> "SOILMTYXR1_T"],
                  'Ua'=>["desc"=> "空气湿度", "port"=> "AD2", "type"=> "humidity", "unit"=> "%", "max_v"=> 100, "min_v"=> 0, "sensor_type"=> "CWS1806A1AG_H"],],
        /*
        'data' =>[
        ["port"=> "Img","port_num"=> 0,"sensor_type"=> "hisi", "params"=> [["key"=> "img_env", "desc"=> "", "name"=> "图片1", "type"=> "image", "unit"=> "", "data_num"=> 0]]],
        ["port"=> "485", "params"=> [["key"=> "Ta", "desc"=> "", "name"=> "空气温度", "type"=> "temperature", "unit"=> "℃", "data_num"=> 0], ["key"=> "Ua", "desc"=> "", "name"=> "空气湿度", "type"=> "humdity", "unit"=> "%", "data_num"=> 1], ["key"=> "Pa", "desc"=> "", "name"=> "大气压", "type"=> "pressure", "unit"=> "hPa", "data_num"=> 2]], "port_num"=> 0, "sensor_type"=> "nh122"], ["port"=> "AD", "params"=> [["key"=> "solar", "desc"=> "", "name"=> "光照", "type"=> "solar", "unit"=> "lux", "data_num"=> 0]], "port_num"=> 0, "sensor_type"=> "nhzd10"]],
        */
        'control' => ['img_collector_invl' => '* * * * *',
                      'data_capture_invl' => '* * * * *'],
        'version'=>'1.0',
        /*
        'version'=>'2.0'
        */
        'device_id' => App\Models\Device::first()->id,
    ];
    return $data;
});

$factory->define(App\Models\DeviceData::class, function(Faker $faker) {

    $data = [
        'ts' => $faker->dateTimeThisMonth,
        'data' => ['solar' => [ 'value' => $faker->numberBetween(1, 100)],
                   'voltage' => ['value' => $faker->numberBetween(1,100)],
                   'Ta' => ['value' => $faker->numberBetween(1,20)],
                   'wind_dir' => ['value' => $faker->numberBetween(1,100)],
                   'rain_fall' =>['value' => $faker->numberBetween(0,2)],
                   'temp_soil' =>['value' => $faker->numberBetween(1,20)],
                   'wind_velocity' =>['value' => $faker->numberBetween(1,20)],
                   'Ua' =>['value' => $faker->numberBetween(1,100)]],
        'device_config_id' => 4,
        'device_id' => 1,
    ];
    return $data;
});

$factory->define(App\Models\Invitation::class, function (Faker $faker) {
    return [
        'user_id' => 4,
        'owner_id'=> 1,
        'invitationable_id' => 2,
        'role_id'=> 3,
        'status'=> 'pending',
        'invitationable_type' => App\Models\Organization::TABLE,
    ];

//    return [
//        'body' => $faker->text,
//        'commentable_id' => factory(Video::class)->create()->id,
//        'commentable_type' => Video::TABLE,
//    ];
});
