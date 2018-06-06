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
        $role = Urole::where(['name','=','deviceManager'])->first();
        $roles = array($role->id);
        $role_m = Urole::where(['name','=','manager']);
        $roles_m = array($role_m->id);
        factory(App\Models\User::class, 2)->create()->each(function($u){
          $u->devices()->save(factory(App\Models\Device::class)->make(),['role_ids'=>json_encode($roles)]);
          $u->organizations()->save(factory(App\Models\Organization::class)->make(),['role_ids'=>json_encode($roles_m)]);
        });

        factory(App\Models\Organization::class,2)->create()->each(function($o){
          $o->devices()->save(factory(App\Models\Device::class)->make());
        });
    }
}
