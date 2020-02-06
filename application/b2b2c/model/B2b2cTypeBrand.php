<?php

namespace app\b2b2c\model;

use think\Model;

/**
 * 类型关联品牌模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cTypeBrand extends Model
{
    protected $name = 'b2b2c_type_brand';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_type_brand'; // 缓存名称

    /**
     * 获取所有类型关联品牌数据(取缓存)
     * @author 仇仇天
     */
    public static function getTypeBrandDataInfo()
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
