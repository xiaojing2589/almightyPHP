<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 配置验证器
 * @author 仇仇天
 * @package app\admin\validate
 */
class Config extends Validate
{
    // 定义验证规则
    protected $rule = [
        'type|配置类型'  => 'require',
        'name|配置名称'  => 'require|regex:^[a-zA-Z]\w{0,39}$|unique:admin_config',
        'title|配置标题' => 'require',
    ];

    // 定义验证提示
    protected $message = [
        'name.regex' => '配置名称由字母和下划线组成',
    ];

    // 定义场景，供快捷编辑时验证
    protected $scene = [
        'name'  => ['name'],
        'title' => ['title'],
        // 编辑验证
        'edit'  => ['type', 'name', 'title'],
        // 新增验证
        'add'   => ['type', 'name', 'title'],
    ];
}
