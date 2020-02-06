<?php
// 模块信息
return [
    // 模块信息[必填]
    'info'   => [

        // [必填]模块名
        'name'            => 'cms',

        // [必填]模块标题
        'title'           => '门户',

        // [必填]模块唯一标识，格式：模块名.开发者标识.module
        'identifier'      => 'cms.ming.module',

        // [选填]模块图标
        'icon'            => 'fa fa-fw fa-newspaper-o',

        // [可选]模块描述
        'description'     => '门户模块',

        // [必填]开发者
        'author'          => 'CaiWeiMing',

        // [选填]开发者网址
        'author_url'      => 'http://www.dolphinphp.com',

        // [必填]版本,格式采用三段式：主版本号.次版本号.修订版本号
        'version'         => '1.0.0',

        // [可选]模块依赖，格式[[模块名, 模块唯一标识, 依赖版本, 对比方式]]
        'need_module'     => [
            ['admin', 'admin.worldtreephp.module', '1.0.0']
        ],

        // [可选]插件依赖，格式[[插件名, 插件唯一标识, 依赖版本, 对比方式]]
        'need_plugin'     => [],

        // 有数据库表时[必填]数据表
        'tables'          => [
            'cms_advert',
            'cms_advert_type',
            'cms_column',
            'cms_document',
            'cms_document_article',
            'cms_field',
            'cms_link',
            'cms_menu',
            'cms_model',
            'cms_nav',
            'cms_page',
            'cms_slider',
            'cms_support',
        ],

        // 有数据库表时[必填]原始数据库表前缀,用于在导入模块sql时，将原有的表前缀转换成系统的表前缀 一般模块自带sql文件时才需要配置
        'database_prefix' => 'dp_',
    ],

    // [可选]模块配置
    'config' => [

        // name=配置名称，title=配置标题，sort=排序
        'group' => [
            ['name' => 's0', 'title' => 'cms测试分组0', 'sort' => 0],
            ['name' => 's1', 'title' => 'cms测试分组1', 'sort' => 1],
            ['name' => 's2', 'title' => 'cms测试分组2', 'sort' => 2]
        ],

        // name=配置名称，title=配置标题，group=配置分组，type=配置类型，value=配置默认值，options=配置项，tips=提示，options=配置项，is_hide=是否隐藏(0=否,1=是)
        'info'  => [
            [
                'name'    => 'summary',
                'title'   => '默认摘要字数',
                'group'   => '',
                'type'    => 'text',
                'value'   => 0,
                'options' => '',
                'tips'    => '发布文章时，如果没有填写摘要，则自动获取文档内容为摘要。如果此处不填写或填写0，则不提取摘要。',
            ],
            [
                'name'    => 'contact',
                'title'   => '联系方式',
                'group'   => '',
                'type'    => 'textarea',
                'value'   => '<div class="font-s13 push"><strong>河源市卓锐科技有限公司</strong><br />',
                'options' => '',
                'tips'    => '',
            ],
            [
                'name'    => 'meta_head',
                'title'   => '顶部代码',
                'group'   => '',
                'type'    => 'textarea',
                'value'   => '',
                'options' => '',
                'tips'    => '代码会放在 <code>&lt;/head&gt;</code> 标签以上',
            ],
            [
                'name'    => 'meta_foot',
                'title'   => '底部代码',
                'group'   => '',
                'type'    => 'textarea',
                'value'   => '',
                'options' => '',
                'tips'    => '代码会放在 <code>&lt;/body&gt;</code> 标签以上'
            ],
            [
                'name'    => 'support_status',
                'title'   => '在线客服',
                'group'   => '',
                'type'    => 'switch',
                'value'   => 1,
                'options' => [
                    'on_text'   => '开启',
                    'off_text'  => '关闭',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'    => '代码会放在 <code>&lt;/body&gt;</code> 标签以上'
            ],
            [
                'name'    => 'support_color',
                'title'   => '在线客服配色',
                'group'   => '',
                'type'    => 'colorpicker',
                'value'   => 'rgba(0,158,232,1)',
                'options' => '',
                'tips'    => ''
            ],
            [
                'name'    => 'support_wx',
                'title'   => '在线客服微信二维码',
                'group'   => '',
                'type'    => 'image',
                'value'   => '',
                'options' => '',
                'tips'    => '在线客服微信二维码'
            ],
            [
                'name'    => 'support_extra',
                'title'   => '在线客服额外内容',
                'group'   => '',
                'type'    => 'textarea',
                'value'   => '',
                'options' => '',
                'tips'    => '在线客服额外内容，可填写电话或其他说明'
            ]
        ]
    ],

    // [可选]模块行为 name=行为标识，title=行为标题，remark=行为描述
    'action' => [
        [
            'name'   => 'slider_delete',
            'title'  => '删除滚动图片',
            'remark' => '删除滚动图片'
        ],
        [
            'name'   => 'slider_edit',
            'title'  => '编辑滚动图片',
            'remark' => '编辑滚动图片'
        ],
        [
            'name'   => 'slider_add',
            'title'  => '添加滚动图片',
            'remark' => '添加滚动图片'
        ],
        [
            'name'   => 'document_delete',
            'title'  => '删除文档',
            'remark' => '删除文档'
        ],
        [
            'name'   => 'document_restore',
            'title'  => '还原文档',
            'remark' => '还原文档'
        ],
        [
            'name'   => 'nav_disable',
            'title'  => '禁用导航',
            'remark' => '禁用导航'
        ],
        [
            'name'   => 'nav_enable',
            'title'  => '启用导航',
            'remark' => '启用导航'
        ],
        [
            'name'   => 'nav_delete',
            'title'  => '删除导航',
            'remark' => '删除导航'
        ],
        [
            'name'   => 'nav_edit',
            'title'  => '编辑导航',
            'remark' => '编辑导航'
        ],
        [
            'name'   => 'nav_add',
            'title'  => '添加导航',
            'remark' => '添加导航'
        ],
        [
            'name'   => 'model_disable',
            'title'  => '禁用内容模型',
            'remark' => '禁用内容模型'
        ],
        [
            'name'   => 'model_enable',
            'title'  => '启用内容模型',
            'remark' => '启用内容模型'
        ],
        [
            'name'   => 'model_delete',
            'title'  => '删除内容模型',
            'remark' => '删除内容模型'
        ],
        [
            'name'   => 'model_edit',
            'title'  => '编辑内容模型',
            'remark' => '编辑内容模型'
        ],
        [
            'name'   => 'model_add',
            'title'  => '添加内容模型',
            'remark' => '添加内容模型'
        ],
        [
            'name'   => 'menu_disable',
            'title'  => '禁用导航菜单',
            'remark' => '禁用导航菜单'
        ],
        [
            'name'   => 'menu_enable',
            'title'  => '启用导航菜单',
            'remark' => '启用导航菜单'
        ],
        [
            'name'   => 'menu_delete',
            'title'  => '删除导航菜单',
            'remark' => '删除导航菜单'
        ],
        [
            'name'   => 'menu_edit',
            'title'  => '编辑导航菜单',
            'remark' => '编辑导航菜单'
        ],
        [
            'name'   => 'menu_add',
            'title'  => '添加导航菜单',
            'remark' => '添加导航菜单'
        ],
        [
            'name'   => 'link_disable',
            'title'  => '禁用友情链接',
            'remark' => '禁用友情链接'
        ],
        [
            'name'   => 'link_enable',
            'title'  => '启用友情链接',
            'remark' => '启用友情链接'
        ],
        [
            'name'   => 'link_delete',
            'title'  => '删除友情链接',
            'remark' => '删除友情链接'
        ],
        [
            'name'   => 'link_edit',
            'title'  => '编辑友情链接',
            'remark' => '编辑友情链接'
        ],
        [
            'name'   => 'link_add',
            'title'  => '添加友情链接',
            'remark' => '添加友情链接'
        ],
        [
            'name'   => 'field_disable',
            'title'  => '禁用模型字段',
            'remark' => '禁用模型字段'
        ],
        [
            'name'   => 'field_enable',
            'title'  => '启用模型字段',
            'remark' => '启用模型字段'
        ],
        [
            'name'   => 'field_delete',
            'title'  => '删除模型字段',
            'remark' => '删除模型字段'
        ],
        [
            'name'   => 'field_edit',
            'title'  => '编辑模型字段',
            'remark' => '编辑模型字段'
        ],
        [
            'name'   => 'field_add',
            'title'  => '添加模型字段',
            'remark' => '添加模型字段'
        ],
        [
            'name'   => 'column_disable',
            'title'  => '禁用栏目',
            'remark' => '禁用栏目'
        ],
        [
            'name'   => 'column_enable',
            'title'  => '启用栏目',
            'remark' => '启用栏目'
        ],
        [
            'name'   => 'column_delete',
            'title'  => '删除栏目',
            'remark' => '删除栏目'
        ],
        [
            'name'   => 'column_edit',
            'title'  => '编辑栏目',
            'remark' => '编辑栏目'
        ],
        [
            'name'   => 'column_add',
            'title'  => '添加栏目',
            'remark' => '添加栏目'
        ],
        [
            'name'   => 'advert_type_disable',
            'title'  => '禁用广告分类',
            'remark' => '禁用广告分类'
        ],
        [
            'name'   => 'advert_type_enable',
            'title'  => '启用广告分类',
            'remark' => '启用广告分类'
        ],
        [
            'name'   => 'advert_type_delete',
            'title'  => '删除广告分类',
            'remark' => '删除广告分类'
        ],
        [
            'name'   => 'advert_type_edit',
            'title'  => '编辑广告分类',
            'remark' => '编辑广告分类'
        ],
        [
            'name'   => 'advert_type_add',
            'title'  => '添加广告分类',
            'remark' => '添加广告分类'
        ],
        [
            'name'   => 'advert_disable',
            'title'  => '禁用广告',
            'remark' => '禁用广告'
        ],
        [
            'name'   => 'advert_enable',
            'title'  => '启用广告',
            'remark' => '启用广告'
        ],
        [
            'name'   => 'advert_delete',
            'title'  => '删除广告',
            'remark' => '删除广告'
        ],
        [
            'name'   => 'advert_edit',
            'title'  => '编辑广告',
            'remark' => '编辑广告'
        ],
        [
            'name'   => 'advert_add',
            'title'  => '添加广告',
            'remark' => '添加广告'
        ],
        [
            'name'   => 'document_disable',
            'title'  => '禁用文档',
            'remark' => '禁用文档'
        ],
        [
            'name'   => 'document_enable',
            'title'  => '启用文档',
            'remark' => '启用文档'
        ],
        [
            'name'   => 'document_trash',
            'title'  => '回收文档',
            'remark' => '回收文档'
        ],
        [
            'name'   => 'document_edit',
            'title'  => '编辑文档',
            'remark' => '编辑文档'
        ],
        [
            'name'   => 'document_add',
            'title'  => '添加文档',
            'remark' => '添加文档'
        ],
        [
            'name'   => 'slider_enable',
            'title'  => '启用滚动图片',
            'remark' => '启用滚动图片'
        ],
        [
            'name'   => 'slider_disable',
            'title'  => '禁用滚动图片',
            'remark' => '禁用滚动图片'
        ],
        [
            'name'   => 'support_add',
            'title'  => '添加客服',
            'remark' => '添加客服'
        ],
        [
            'name'   => 'support_edit',
            'title'  => '编辑客服',
            'remark' => '编辑客服'
        ],
        [
            'name'   => 'support_delete',
            'title'  => '删除客服',
            'remark' => '删除客服'
        ],
        [
            'name'   => 'support_enable',
            'title'  => '启用客服',
            'remark' => '启用客服'
        ],
        [
            'name'   => 'support_disable',
            'title'  => '禁用客服',
            'remark' => '禁用客服'
        ]
    ],

    // [选填]菜单信息
    'menus'  => [
        [
            'title'      => '门户',
            'icon'       => 'fa fa-fw fa-newspaper-o',
            'url_value'  => 'cms/index/index',
            'url_target' => '_self',
            'sort'       => 100,
            'child'      => [
                [
                    'title'      => '常用操作',
                    'icon'       => 'fa fa-fw fa-folder-open-o',
                    'url_value'  => '',
                    'url_target' => '_self',
                    'sort'       => 100,
                    'child'      => [
                        [
                            'title'      => '仪表盘',
                            'icon'       => 'fa fa-fw fa-tachometer',
                            'url_value'  => 'cms/index/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                        ],
                        [
                            'title'      => '发布文档',
                            'icon'       => 'fa fa-fw fa-plus',
                            'url_value'  => 'cms/document/add',
                            'url_target' => '_self',
                            'sort'       => 100,
                        ],
                        [
                            'title'      => '文档列表',
                            'icon'       => 'fa fa-fw fa-list',
                            'url_value'  => 'cms/document/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/document/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/document/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/document/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/document/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/document/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                        [
                            'title'      => '单页管理',
                            'icon'       => 'fa fa-fw fa-file-word-o',
                            'url_value'  => 'cms/page/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/page/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                        [
                            'title'      => '回收站',
                            'icon'       => 'fa fa-fw fa-recycle',
                            'url_value'  => 'cms/recycle/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/recycle/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '还原',
                                    'icon'       => '',
                                    'url_value'  => 'cms/recycle/restore',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => '内容管理',
                    'icon'       => 'fa fa-fw fa-th-list',
                    'url_value'  => '',
                    'url_target' => '_self',
                    'sort'       => 100,
                    'child'      => [],
                ],
                [
                    'title'      => '营销管理',
                    'icon'       => 'fa fa-fw fa-money',
                    'url_value'  => '',
                    'url_target' => '_self',
                    'sort'       => 100,
                    'child'      => [
                        [
                            'title'      => '广告管理',
                            'icon'       => 'fa fa-fw fa-handshake-o',
                            'url_value'  => 'cms/advert/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '广告分类',
                                    'icon'       => '',
                                    'url_value'  => 'cms/advert_type/index',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                    'child'      => [
                                        [
                                            'title'      => '新增',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/add',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/edit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '删除',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/delete',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '启用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/enable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '禁用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/disable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '快速编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/advert_type/quickedit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'title'      => '滚动图片',
                            'icon'       => 'fa fa-fw fa-photo',
                            'url_value'  => 'cms/slider/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/slider/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                        [
                            'title'      => '友情链接',
                            'icon'       => 'fa fa-fw fa-link',
                            'url_value'  => 'cms/link/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/link/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                        [
                            'title'      => '客服管理',
                            'icon'       => 'fa fa-fw fa-commenting',
                            'url_value'  => 'cms/support/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/support/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'title'      => '门户设置',
                    'icon'       => 'fa fa-fw fa-sliders',
                    'url_value'  => '',
                    'url_target' => '_self',

                    'sort'  => 100,
                    'child' => [
                        [
                            'title'      => '栏目分类',
                            'icon'       => 'fa fa-fw fa-sitemap',
                            'url_value'  => 'cms/column/index',
                            'url_target' => '_self',
                            'is_hide'    => 1,
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/column/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                            ],
                        ],
                        [
                            'title'      => '内容模型',
                            'icon'       => 'fa fa-fw fa-th-large',
                            'url_value'  => 'cms/model/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/model/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '字段管理',
                                    'icon'       => '',
                                    'url_value'  => 'cms/field/index',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                    'child'      => [
                                        [
                                            'title'      => '新增',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/add',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/edit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '删除',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/delete',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '启用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/enable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '禁用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/disable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '快速编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/field/quickedit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'title'      => '导航管理',
                            'icon'       => 'fa fa-fw fa-map-signs',
                            'url_value'  => 'cms/nav/index',
                            'url_target' => '_self',
                            'sort'       => 100,
                            'child'      => [
                                [
                                    'title'      => '新增',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/add',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/edit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '删除',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/delete',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '启用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/enable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '禁用',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/disable',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '快速编辑',
                                    'icon'       => '',
                                    'url_value'  => 'cms/nav/quickedit',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                ],
                                [
                                    'title'      => '菜单管理',
                                    'icon'       => '',
                                    'url_value'  => 'cms/menu/index',
                                    'url_target' => '_self',
                                    'sort'       => 100,
                                    'child'      => [
                                        [
                                            'title'      => '新增',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/add',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/edit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '删除',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/delete',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '启用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/enable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '禁用',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/disable',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                        [
                                            'title'      => '快速编辑',
                                            'icon'       => '',
                                            'url_value'  => 'cms/menu/quickedit',
                                            'url_target' => '_self',
                                            'sort'       => 100,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]
    ]
];