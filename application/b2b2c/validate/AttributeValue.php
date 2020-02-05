<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 属性值验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class AttributeValue extends Validate
{
    //定义验证规则
    protected $rule = [
        'attr_value|属性值名称' => 'require|length:1,100'
    ];

    // 定义验证场景
    protected $scene = [
        'attr_value' => ['attr_value']
    ];
}
