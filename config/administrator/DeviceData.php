<?php
use App\Models\DeviceData;

return [
    'title' =>  '设备-数据',
    'heading' =>  '设备-数据管理',
    'single' => '数据',
    'model' => DeviceData::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'device' =>array(
           'title' => '设备',
           'relationship' =>'device',
           'select' => "CONCAT((:table).id,'-',(:table).name)"
        ),
        'device_config_id'=>[
          'title' => 'config id',
          'type' =>'number',
        ],
        /*'device' => [
            'title' => '设备名',
            'type' => 'relationship',
            //'select' =>"CONCAT((:table).id,'-',(:table).name)"
            'select' => "(:table).name"
        ],*/


        'data_text' =>[
          'title' => 'data'
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
        'device_config_id' =>[
           'title' => 'config id',
           'type' =>'number'
        ],
        'data_text' => [
            'title' => 'data',
        ],
    ],
    'filters' => [
        'device' => [
            'title' => '设备',
            'type' => 'relationship',
            'name_field' => 'full_name'
        ],
        'device_config_id' =>[
           'title' => 'config id',
           'type' =>'number',
        ],
        'create_at'=>[
          'title' => 'create_at',
          'type' => 'datetime',
          'date_format' => 'yy-mm-dd',
          'time_format' => 'HH:mm',
        ]
    ],
];
