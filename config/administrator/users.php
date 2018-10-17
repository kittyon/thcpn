<?php
use App\Models\User;

return [
    'title' =>  '用户',
    'heading' =>  '用户管理',
    'single' => '用户',
    'model' => User::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '用户名',
        ],
        'email' => [
            'title' => 'Email',
        ],
        'devices' =>[
          'title' => '设备',
          'relationship' => 'devices',
          'select' => "(:table).name"
        ],
        'created_at',
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '用户名',
            'type' => 'text'
        ],
        'email' => [
            'title' => 'Email',
        ],
        'password' =>[
            'title' => '密码',
            'type' => 'password'
        ]
    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '用户名',
        ],
        'email' => [
            'title' => 'Email',
        ],
    ],
];
