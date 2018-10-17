<?php
use App\Models\UserOrganization;

return [
    'title' =>  '组织-用户',
    'heading' =>  '组织-用户管理',
    'single' => '组织-用户',
    'model' => UserOrganization::class,
    'columns' => [

        'user' =>array(
           'title' => '用户',
           'relationship' =>'user',
           'select' => "(:table).name"
        ),
        'organization' =>[
          'title' => '组织',
          'relationship' => 'organization',
          'select' => "(:table).name"
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

        'user' =>[
           'title' => '用户',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
        'organization' => [
          'title' => '组织',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],
        'urole_ids' =>[
          'title' => '权限ids',
          'type' => 'text'
        ],

    ],
    'filters' => [
        'user' =>[
           'title' => '用户',
           'type' =>'relationship',
           'name_field' => 'full_name'
        ],
        'organization' => [
          'title' => '组织',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],
    ],
];
