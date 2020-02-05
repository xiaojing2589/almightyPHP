<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 商品类型验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class Type extends Validate
{
    //定义验证规则
    protected $rule = [
        'type_name|类型名称' => 'require|length:1,100'
    ];

    // 定义验证场景
    protected $scene = [
        'type_name' => ['type_name']
    ];
}
