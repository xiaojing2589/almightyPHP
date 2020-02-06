<?php
namespace app\admin\validate;

use think\Validate;

/**
 * 角色验证器
 * @author 仇仇天
 * @package app\admin\validate
 */
class Role extends Validate
{
    // 定义验证规则
    protected $rule = [
        'name|角色名称' => 'require|unique:admin_role',
    ];
}
