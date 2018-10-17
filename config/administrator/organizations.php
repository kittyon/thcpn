<?php
use App\Models\Organization;

return [
    'title' =>  '组织',
    'heading' =>  '组织管理',
    'single' => '组织',
    'model' => Organization::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '组织名',
        ],
        'description' => [
            'title' => '描述',
        ],
        'parent' =>array(
           'title' => '父组织',
           'relationship' =>'parent',
           'select' => "CONCAT((:table).id,'-',(:table).name)"
        ),

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
            'title' => '组织名',
        ],
        'description' => [
            'title' => '描述',
        ],
        'parent' =>[
           'title' => '父组织',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
    ],
    'filters' => [
        'name' => [
            'title' => '组织名',
        ],
        'description' => [
            'title' => '描述',
        ],
        'parent' =>[
           'title' => '父组织',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
    ],
];
