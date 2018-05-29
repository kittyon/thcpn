<?php

use Illuminate\Database\Seeder;

class DeviceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Models\DeviceConfig::class)->create();
        factory(App\Models\DeviceData::class,10)->create();

    }
}
