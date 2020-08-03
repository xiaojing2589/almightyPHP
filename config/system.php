<?php
return [
    // 拒绝ie访问
    'deny_ie'       => false,
    // 模块管理中，不读取模块信息的目录
    'except_module' => ['common', 'admin', 'index', 'extra', 'user', 'install', 'lang','document'],
    // 语言
    'language_type'      => [
        'en'    => [
            'title' => '英文',
            'name'  => 'en'
        ],
        'zh-cn' => [
            'title' => '中文',
            'name'  => 'zh-cn'
        ]
    ],
    // 管理员不展示的菜单节点
    'admin_except_menu_node'=>[
        'controller'=>['menu'],
        'action'=>[],
        'node' =>['system_function_menu','systme_extension_module','systme_extension_hook'],
    ]
];
