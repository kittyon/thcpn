<?php
use App\Models\DeviceUser;

return [
    'title' =>  '用户-设备',
    'heading' =>  '用户-设备管理',
    'single' => '用户-设备',
    'model' => DeviceUser::class,
    'columns' => [

        'device' =>array(
           'title' => '设备',
           'relationship' =>'device',
           'select' => "CONCAT((:table).id,'-',(:table).name)"
        ),
        'user' =>[
          'title' => '用户',
          'relationship' => 'user',
          'select' => "CONCAT((:table).id,'-',(:table).name)"
        ],
        'urole_ids' =>[
          'title' => '权限ids',
          'type' => 'text'
        ],
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],
    'edit_fields' => [

        'device' =>[
           'title' => '设备',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
        'user' => [
          'title' => '用户',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],
        'urole_ids' =>[
          'title' => '权限ids',
          'type' => 'text'
        ],

    ],
    'filters' => [
        'device' =>[
           'title' => '设备',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
        'user' => [
          'title' => '用户',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],
    ],
];
