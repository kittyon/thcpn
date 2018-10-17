<?php
use App\Models\Permission;

return [
    'title' =>  '权限',
    'heading' =>  '权限管理',
    'single' => '权限',
    'model' => Permission::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '权限名',
        ],
        'display_name' =>[
            'title' => '显示权限名'
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
            'title' => '权限名',
        ],
        'display_name' =>[
            'title' => '显示权限名'
        ],
        'description' => [
            'title' => '描述',
        ],
    ],
    'filters' => [
        'name' => [
            'title' => '权限名',
        ],
        'display_name' =>[
            'title' => '显示权限名'
        ]
    ],
];
