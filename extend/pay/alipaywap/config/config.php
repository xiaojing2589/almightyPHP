<?php
return [
    // 配置[必填]
    'name'   => '支付宝手机网站支付',              // 插件名称 [必填]
    'mark'   => 'alipaywap',                       // 插件标识必须和目录同名区分大小写 [必填]
    'ico'    => '<i class="fa fa-th-large"></i>',  // 插件图标，配置项必须有，可以为空
    'doc'    => '',                                // 插件描述，配置项必须有，可以为空
    'config' => [
        [
            'field'     => 'app_id',
            'name'      => 'app_id',
            'form_type' => 'text',
            'value'     => '',
            'title'     => '支付宝 APPID',
            'tips'      => '支付宝分配给开发者的应用ID'
        ],
        [
            'field'     => 'ali_public_key',
            'name'      => 'ali_public_key',
            'form_type' => 'textarea',
            'value'     => '',
            'title'     => '支付宝公钥',
            'tips'      => '支付宝公钥，由支付宝生成，详细参考：https://docs.open.alipay.com/291/105971'
        ],
        [
            'field'     => 'private_key',
            'name'      => 'private_key',
            'form_type' => 'textarea',
            'value'     => '',
            'title'     => '开发者私钥',
            'tips'      => '开发者私钥，由开发者自己生成，详细参考：https://docs.open.alipay.com/291/105971'
        ]
    ]
];