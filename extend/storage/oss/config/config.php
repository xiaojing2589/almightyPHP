<?php
return [
    // 配置[必填]
    'name'        => '阿里云对象存储',                         // 插件名称 [必填]
    'mark'        => 'oss',                            // 插件标识必须和目录同名区分大小写 [必填]
    'ico'         =>'<i class="fa fa-th-large"></i>',  // 插件图标，配置项必须有，可以为空
    'doc'         =>'',                                // 插件描述，配置项必须有，可以为空
    'config'      => [
        [
            'field'=>'key',
            'name'=>'key',
            'form_type'=>'text',
            'value'=>'',
            'title'=>'AccessKeyId',
            'tips'=>'您的Access Key ID'
        ],
        [
            'field'=>'secret',
            'name'=>'secret',
            'form_type'=>'password',
            'value'=>'',
            'title'=>'AccessKeySecret',
            'tips'=>'您的Access Key Secret'
        ],
        [
            'field'=>'bucket',
            'name'=>'bucket',
            'form_type'=>'text',
            'value'=>'',
            'title'=>'Bucket',
            'tips'=>'Bucket名称'
        ]
    ]
];