<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 商品分类验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class GoodsClass extends Validate
{
    //定义验证规则
    protected $rule = [
        'gc_name|分类名称'      => 'require|length:1,100',
        'gc_keywords|关键词'   => 'length:0,80',
        'gc_description|描述' => 'length:0,255'
    ];

    // 定义验证场景
    protected $scene = [
        'name'           => ['gc_name'],
        'keywords'       => ['gc_keywords'],
        'gc_description' => ['gc_description'],
    ];
}
