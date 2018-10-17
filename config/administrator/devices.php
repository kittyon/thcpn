<?php
use App\Models\Device;

return [
    'title' =>  '设备',
    'heading' =>  '设备管理',
    'single' => '设备',
    'model' => Device::class,
    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'name' => [
            'title' => '设备名',
        ],
        'iccid' => [
            'title' => 'ICCID',
        ],
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
        'active' =>[
          'title' => 'active'
        ],
        'lat' =>[
          'title' => '纬度',
          'type' => 'number'
        ],
        'lon' =>[
          'title' => '经度',
          'type' => 'number'
        ],
        'status'=>[
          'title' => '状态',
          'type' => 'enum',
          'options' =>['normal', 'abnormal']
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
            'title' => '设备名',
            'type' => 'text'
        ],
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
        'iccid' => [
            'title' => 'ICCID',
        ],
        'active' =>[
          'title' => 'active',
          'type' => 'number'
        ],
        'lat' =>[
          'title' => '纬度',
          'type' => 'number'
        ],
        'lon' =>[
          'title' => '经度',
          'type' => 'number'
        ],
        'status'=>[
          'title' => '状态',
          'type' => 'enum',
          'options' =>['normal', 'abnormal']
        ],
    ],
    'filters' => [
        'name' => [
            'title' => '设备名',
        ],
        'iccid' => [
            'title' => 'ICCID',
        ],
        'active' => [
            'title' => 'active'
        ],
        'status'=>[
          'title' => '状态',
          'type' => 'enum',
          'options' =>['normal', 'abnormal']
        ],
        'version' =>[
           'title' => '版本',
           'type' =>'enum',
           'options' => ['1.0','2.0']
        ],
    ],
];
