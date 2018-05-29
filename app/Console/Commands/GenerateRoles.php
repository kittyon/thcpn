<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Urole;
use App\Models\Orole;
use App\Models\Permission;

class GenerateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thc:generate-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速生成uroles oroles permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $deviceManager = Urole::updateOrCreate(['name' => 'deviceManager'], ['display_name' => '设备管理员']);
        $Manager = Urole::updateOrCreate(['name'=> 'manager'],['display_name'=>'经理']);
        $user = Urole::updateOrCreate(['name'=> 'user'], ['display_name'=> '用户']);

        $l_level = Orole::updateOrCreate(['name'=> 'l_level'],['display_name' => '初级']);
        $m_level = Orole::updateOrCreate(['name'=> 'm_level'],['display_name' => '中级']);
        $h_level = Orole::updateOrCreate(['name'=> 'h_level'],['display_name' => '高级']);

        $dev_w = Permission::updateOrCreate(['name' => 'dev_w'], ['display_name' => '设备写']);
        $dev_r = Permission::updateOrCreate(['name' => 'dev_r'], ['display_name' => '设备读']);
        $org_w = Permission::updateOrCreate(['name' => 'org_w'], ['display_name' => '组织写']);
        $org_r = Permission::updateOrCreate(['name' => 'org_r'], ['display_name' => '组织读']);
        $is_draw = Permission::updateOrCreate(['name' => 'is_draw'], ['display_name' => '原始下载']);
        $is_a = Permission::updateOrCreate(['name' => 'is_a'], ['display_name' => '数据分析']);

        $deviceManager->permissions()->sync([$dev_w->id, $dev_r->id]);

        $Manager->permissions()->sync([$org_w->id, $org_r->id]);

        $user->permissions()->sync([$dev_r->id]);

        $l_level->permissions()->sync([$dev_r->id]);
        $m_level->permissions()->sync([$dev_r->id, $is_draw->id]);
        $h_level->permissions()->sync([$dev_r->id, $is_draw->id, $is_a->id]);



    }
}
