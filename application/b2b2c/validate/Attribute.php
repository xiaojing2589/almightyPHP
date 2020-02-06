<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 属性验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class Attribute extends Validate
{
    //定义验证规则
    protected $rule = [
        'attr_name|属性名称'         => 'require|length:1,100'
    ];

    // 定义验证场景
    protected $scene = [
        'type_name'         => ['type_name']
    ];
}
