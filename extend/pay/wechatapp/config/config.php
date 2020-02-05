<?php
return [
    // 配置[必填]
    'name'   => '微信APP支付',                     // 插件名称 [必填]
    'mark'   => 'wechatapp',                       // 插件标识必须和目录同名区分大小写 [必填]
    'ico'    => '<i class="fa fa-th-large"></i>',  // 插件图标，配置项必须有，可以为空
    'doc'    => '',                                // 插件描述，配置项必须有，可以为空
    'config' => [
        [
            'field'     => 'appid',
            'name'      => 'appid',
            'form_type' => 'text',
            'value'     => '',
            'title'     => 'APP APPID'
        ],
        [
            'field'     => 'mch_id',
            'name'      => 'mch_id',
            'form_type' => 'text',
            'value'     => '',
            'title'     => '商户号'
        ],
        [
            'field'     => 'key',
            'name'      => 'key',
            'form_type' => 'password',
            'value'     => '',
            'title'     => 'API密钥'
        ]

    ]
];