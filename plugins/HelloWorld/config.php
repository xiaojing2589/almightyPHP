<?php
// 插件配置信息
return [

    // [必填] 插件信息
    'info'=>[

        // [必填] 插件名
        'name'        => 'HelloWorld',

        // [必填] 插件标题
        'title'       => '你好，世界',

        // [必填] 插件唯一标识,格式：插件名.开发者标识.plugin
        'identifier'  => 'helloworld.ming.plugin',

        // [选填] 插件图标
        'icon'        => 'fa fa-fw fa-globe',

        // [选填] 插件描述
        'description' => '这是一个演示插件，会在每个页面生成一个提示“Hello World”。您可以查看源码，里面包含了绝大部分插件所用到的方法，以及能做的事情。',

        // [必填] 插件作者
        'author'      => '仇仇天',

        // [选填] 作者主页
        'author_url'  => 'http://www.xxx.com',

        // [必填] 插件版本格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',

        // [必选] 是否有后台管理功能 1=有该台管理 ，0=没有
        'admin'       => '1',
    ],

    // [可选] 插件钩子
    'hook'=>[

        // 钩子名称
        'page_tips',

        // 钩子说明
        'my_hook' => '我的钩子',
    ],

    // 原数据库表前缀,用于在导入插件sql时，将原有的表前缀转换成系统的表前缀,一般插件自带sql文件时才需要配置
    'database_prefix'=>'dolphin_',

    // [可选] 配置
    'config'=>[
        [
            'field'=>'status',
            'name'=>'status',
            'form_type'=>'radio',
            'value'=>'',
            'title'=>'状态',
            'tips'=>'您的secretId',
            'option'=>[
                ['title'=>'开启','value'=>1],
                ['title'=>'关闭','value'=>0]
            ]
        ],
        [
            'field'=>'region',
            'name'=>'region',
            'form_type'=>'text',
            'value'=>'ap-beijing',
            'title'=>'region',
            'tips'=>'默认的存储桶地域'
        ]
    ]
];
