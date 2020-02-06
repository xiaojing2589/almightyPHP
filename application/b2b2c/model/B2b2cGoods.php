<?php

namespace app\b2b2c\model;

use think\Model;

/**
 * 商品模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cGoods extends Model
{
    protected $name = 'b2b2c_goods';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_goods'; // 缓存名称

    /**
     * 获取所有商品数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @throws \Exception
     */
    public static function del($where = [])
    {
        if(false !== self::where($where)->delete()){
            self::delCache(); // 删除缓存
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }
}
