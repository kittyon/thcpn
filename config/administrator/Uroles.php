<?php
use App\Models\Urole;

return [
    'title' =>  '角色',
    'heading' =>  '角色管理',
    'single' => '角色',
    'model' => Urole::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '角色名',
        ],
        'display_name' =>[
            'title' => '显示角色名'
        ],
        'description' => [
            'title' => '描述',
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
            'title' => '角色名',
        ],
        'display_name' =>[
            'title' => '显示角色名'
        ],
        'description' => [
            'title' => '描述',
        ],
        'permissions' =>[
           'title' => '权限',
           'type' =>'relationship',
           'name_field' => 'display_name'
        ],
    ],
    'filters' => [
        'name' => [
            'title' => '组织名',
        ],
        'display_name' =>[
            'title' => '显示角色名'
        ]
    ],
];
