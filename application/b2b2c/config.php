<?php
// 模块信息
return [
    // 模块信息[必填]
    'info'   => [
        'name'            => 'b2b2c',// 模块名[必填]
        'title'           => '商城',// 模块标题[必填]
        'identifier'      => 'com.chouchoutian.www',// 模块唯一标识[必填]，格式：模块名.开发者标识.module
        'icon'            => 'fa fa-shopping-cart',// 模块图标[选填]
        'description'     => '多商铺商城模块',// 模块描述[选填]
        'author'          => '仇仇天',// 开发者[必填]
        'author_url'      => 'http://www.chouchoutian.com',// 开发者网址[选填]
        'version'         => '1.0.0',// 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        // 模块依赖[可选]，格式[[模块名, 模块唯一标识, 依赖版本, 对比方式]]
        'need_module'     => [
            ['admin', 'admin.worldtreephp.module', '1.0.0']
        ],
        'need_plugin'     => [], // 插件依赖[可选]，格式[[插件名, 插件唯一标识, 依赖版本, 对比方式]]
        // 数据表[有数据库表时[必填]
        'tables'          => [],
        'database_prefix' => '', // 原始数据库表前缀,用于在导入模块sql时，将原有的表前缀转换成系统的表前缀 一般模块自带sql文件时才需要配置
    ],
    // 模块配置 [选填]
    'config' => [
        // name=配置名称，title=配置标题，sort=排序
        'group' => [],
        // name=配置名称，title=配置标题，group=配置分组，type=配置类型，value=配置默认值，options=配置项，tips=提示，options=配置项
        'info'  => []
    ],
    // 模块行为 [选填]
    'action' => []
];