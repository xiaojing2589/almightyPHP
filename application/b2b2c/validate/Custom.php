<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 自定义属性验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class Custom extends Validate
{
    //定义验证规则
    protected $rule = [
        'custom_name|自定义属性名称' => 'require|length:1,100'
    ];

    // 定义验证场景
    protected $scene = [
        'custom_name' => ['type_name']
    ];
}
