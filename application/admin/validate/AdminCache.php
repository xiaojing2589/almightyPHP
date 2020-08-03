<?php
namespace app\admin\validate;

use think\Validate;

/**
 * 缓存验证器
 * @author 仇仇天
 * @package app\admin\validate
 */
class AdminCache extends Validate
{
    //定义验证规则
    protected $rule = [
    'name|缓存标题'             => 'require|length:1,50',
    'field_name|缓存标识'       => 'require|regex:^[a-zA-Z]\w{0,39}$|unique:admin_cache,field_name^module',
    ];

    //定义验证提示
    protected $message = [
        'field_name.regex' => '缓存标识由字母和下划线组成',
    ];

    // 定义场景，供快捷编辑时验证
    protected $scene = [
        'name'  => ['name'],
        'field_name' => ['field_name'],
    ];
}
