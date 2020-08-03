<?php
namespace app\b2b2c\model;

use think\Model;

/**
 * 店铺分类模型
 */
class B2b2cStoreClass extends Model
{

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 缓存名称
    protected static $cacheName = 'b2b2c_store_class';

    /**
     * 获取所有店铺分类数据(取缓存)
     * @author 仇仇天
     */
    public static function getStoreClassDataAll()
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
            // 删除缓存
            self::delCache();
            return true;
        }else{
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
