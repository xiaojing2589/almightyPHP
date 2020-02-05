<?php

namespace app\b2b2c\model;

use think\Model;

/**
 * 店铺分类模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cStoreClass extends Model
{

    protected $name = 'b2b2c_store_class';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_store_class'; // 缓存名称

    /**
     * 获取所有店铺分类数据(取缓存)
     * @author 仇仇天
     */
    public static function getStoreClassDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 删除（包括重置缓存）
     * @param array $where 条件
     * @throws \Exception
     * @author 仇仇天
     */
    public static function del($where = [])
    {
        $data = self::where($where)->select();
        if (!empty($data)) {
            if (false !== self::where($where)->delete()) {
                self::delCache(); // 删除缓存
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 删除缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }
}
