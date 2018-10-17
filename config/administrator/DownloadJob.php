<?php
use App\Models\DownloadJob;

return [
    'title' =>  '用户-下载',
    'heading' =>  '用户-下载管理',
    'single' => '下载',
    'model' => DownloadJob::class,
    'columns' => [

        'user' =>[
          'title' => '用户',
          'relationship' => 'user',
          'select' => "CONCAT((:table).id,'-',(:table).name)"
        ],
        'url' =>[
          'title' => '下载地址',
          'type' => 'file',
          'location' => 'http://thc-platfrom-storage.b0.upaiyun.com/',
        ],
        'options' => [
          'title' => '参数',
        ],
        'status' =>[
          'title' => '状态',
          'type' =>'enum',
          'options' => ['completed', 'processing']
        ],
        'create_at',
        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
      'user' => [
        'title' => '用户',
        'type' => 'relationship',
        'name_field' => 'full_name'
      ],
      'status' =>[
        'title' => '状态',
        'type' => 'enum',
        'options' => ['completed', 'processing']
      ]
    ],
    'filters' => [
        'user' => [
          'title' => '用户',
          'type' => 'relationship',
          'name_field' => 'full_name'
        ],
        'status' =>[
          'title' => '状态',
          'type' => 'enum',
          'options' => ['completed', 'processing']
        ],
        'create_at'=>[
          'title' => 'create_at',
          'type' => 'datetime',
          'date_format' => 'yy-mm-dd',
          'time_format' => 'HH:mm',
        ]
    ],
];
