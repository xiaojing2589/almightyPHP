<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 商品规格验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class Spec extends Validate
{
    //定义验证规则
    protected $rule = [
        'sp_name|规格名称' => 'require|length:1,100'
    ];

    // 定义验证场景
    protected $scene = [
        'sp_name' => ['sp_name']
    ];
}
