<?php
use App\Models\DeviceOrganization;

return [
    'title' =>  '组织-设备',
    'heading' =>  '组织-设备管理',
    'single' => '组织-设备',
    'model' => DeviceOrganization::class,
    'columns' => [

        'device' =>array(
           'title' => '设备',
           'relationship' =>'device',
           'select' => "CONCAT((:table).id,'-',(:table).name)"
        ),
        'organization' =>[
          'title' => '组织',
          'relationship' => 'organization',
          'select' => "CONCAT((:table).id,'-',(:table).name)"
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
        'organization' => [
          'title' => '组织',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],

    ],
    'filters' => [
        'device' =>[
           'title' => '设备',
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
