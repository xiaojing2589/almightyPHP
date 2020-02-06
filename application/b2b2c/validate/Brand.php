<?php

namespace app\b2b2c\validate;

use think\Validate;

/**
 * 商品品牌验证器
 * Class GoodsClass
 * @package app\b2b2c\validate
 */
class Brand extends Validate
{
    //定义验证规则
    protected $rule = [
        'brand_name|品牌名称'         => 'require|length:1,100',
        'brand_initial|品牌首字母'     => 'require|length:1,1',
        'brand_tjstore|品牌副标题'     => 'length:0,100',
        'brand_introduction|品牌介绍' => 'length:0,255'
    ];

    // 定义验证场景
    protected $scene = [
        'brand_name'         => ['brand_name'],
        'brand_initial'      => ['brand_initial'],
        'brand_tjstore'      => ['brand_tjstore'],
        'brand_introduction' => ['brand_introduction'],
    ];
}
