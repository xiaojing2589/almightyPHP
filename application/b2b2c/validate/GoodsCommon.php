<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 商品公共内容验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class GoodsCommon extends Validate
{
    //定义验证规则
    protected $rule = [
        'goods_stateremark|违规下架理由'         => 'require|length:1,255'
    ];

    // 定义验证场景
    protected $scene = [
        'goods_stateremark'         => ['goods_stateremark']
    ];
}
