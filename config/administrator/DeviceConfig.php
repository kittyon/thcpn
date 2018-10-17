<?php
use App\Models\DeviceConfig;

return [
    'title' =>  '设备-Config',
    'heading' =>  '设备-Config管理',
    'single' => 'Config',
    'model' => DeviceConfig::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'device' =>array(
           'title' => '设备',
           'relationship' =>'device',
           'select' => "CONCAT((:table).id,'-',(:table).name)"
        ),
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
        'data_text' =>[
          'title' => 'data'
        ],
        'control_text' =>[
          'title' => 'control',
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
        'device' => [
            'title' => '设备',
            'type' => 'relationship',
            'name_field' => 'full_name'
        ],
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
        'data_text' => [
            'title' => 'data',
        ],
        'control_text' =>[
          'title' => 'control',
        ],
    ],
    'filters' => [
        'device' => [
            'title' => '设备',
            'type' => 'relationship',
            'name_field' => 'full_name'
        ],
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
    ],
];
