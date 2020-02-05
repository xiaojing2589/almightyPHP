<?php
return [
    // 配置[必填]
    'name'   => '互亿',                 // 插件名称 [必填]
    'mark'   => 'huyi',                            // 插件标识必须和目录同名区分大小写 [必填]
    'ico'    => '<i class="fa fa-th-large"></i>',  // 插件图标，配置项必须有，可以为空
    'doc'    => '',                                // 插件描述，配置项必须有，可以为空
    'config' => [
        [
            'field'     => 'account',
            'name'      => 'account',
            'form_type' => 'text',
            'value'     => '',
            'title'     => '账号'
        ],
        [
            'field'     => 'password',
            'name'      => 'password',
            'form_type' => 'password',
            'value'     => '',
            'title'     => '密码'
        ]
    ]
];
