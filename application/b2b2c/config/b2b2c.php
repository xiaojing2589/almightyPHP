<?php
return [
    'common_path'                => 'b2b2c/common', // 平台公共附件路径
    'goods_path'                 => 'b2b2c/shop/goods', // 商品图片
    'goods_class_path'           => 'b2b2c/shop/goodsclass', // 商品分类图片
    'goods_class_category_path'  => 'b2b2c/shop/goodsclass/category', // 商品分类分组图片
    'goods_class_brand_path'     => 'b2b2c/shop/goodsclass/brand', // 商品分类广告图片
    'brand_path'                 => 'b2b2c/shop/brand', // 品牌图片
    /**********************商家图片**************************/
    'store_avatar_path'          => 'b2b2c/shop/store/avatar',       // 商家头像图片
    'store_logo_path'            => 'b2b2c/shop/store/logo',         // 商家logo图片
    'store_aclass_path'          => 'b2b2c/shop/store/aclass',       // 商家相册图片
    'store_cover_path'           => 'b2b2c/shop/store/cover',        // 商家相册封面图片
    'store_grade_icon_path'      => 'b2b2c/shop/store/grade',        // 商家等级图标
    'store_joinin_path'          => 'b2b2c/shop/store/joinin',       // 商家入住
    /**********************商品状态**************************/
    'STATE1'                     => 1,// 出售中
    'STATE0'                     => 0,// 下架
    'STATE10'                    => 10,// 违规
    /**********************商品审核状态**************************/
    'VERIFY1'                    => 1,// 审核通过
    'VERIFY0'                    => 0,// 审核失败
    'VERIFY10'                   => 10,// 等待审核
    /**********************抢购商品状态**************************/
    'GROUPBUY_STATE_REVIEW'      => 10, // 审核中
    'GROUPBUY_STATE_NORMAL'      => 20, // 正常
    'GROUPBUY_STATE_REVIEW_FAIL' => 30, // 审核失败
    'GROUPBUY_STATE_CANCEL'      => 31, // 管理员关闭
    'GROUPBUY_STATE_CLOSE'       => 32, // 已结束
];
