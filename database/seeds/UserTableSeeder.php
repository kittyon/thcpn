<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Models\User::class, 2)->create()->each(function($u){
          $u->devices()->save(factory(App\Models\Device::class)->make());
          $u->organizations()->save(factory(App\Models\Organization::class)->make());
        });

        factory(App\Models\Organization::class,2)->create()->each(function($o){
          $o->devices()->save(factory(App\Models\Device::class)->make());
        });
    }
}
