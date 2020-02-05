<?php

namespace app\common\validate;

use think\Validate;

/**
 * 节点验证器
 * @author 仇仇天
 * @package app\admin\validate
 */
class Menu extends Validate
{
    //定义验证规则
    protected $rule = [
        'module|所属模块' => 'require',
        'pid|所属节点'    => 'require',
        'title|节点标题'  => 'require',
        'mark|节点标识'   => 'regex:^[a-zA-Z]\w{0,39}$|unique:admin_menu',
    ];

    //定义验证提示
    protected $message = [
        'mark.regex' => '标识由字母和下划线组成'
    ];

    // 定义验证场景
    protected $scene = [
        'mark' => ['mark'], // 检测标识
    ];
}
