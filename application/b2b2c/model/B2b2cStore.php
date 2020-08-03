<?php
namespace app\b2b2c\model;

use think\Model;

/**
 * 店铺数据模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cStore extends Model
{
    // 缓存名称
    protected static $cacheName = 'b2b2c_store';

    /**
     * 获取所有店铺数据(取缓存)
     * @author 仇仇天
     * @param $storeId 店铺id
     * @return bool|mixed|string
     */
    public static function getStoreDataInfo($storeId)
    {
        $goodsClassData = rcache(self::$cacheName, $storeId, ['module' => 'b2b2c']);
        return $goodsClassData;
    }


    public static function edit($where,$update)
    {
        $data = self::where($where)->select();
        if(!empty($data)){
            if(false !== self::where($where)->update($update)){
                foreach ($data as $value){
                    // 删除缓存
                    self::delCache($value['store_id']);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @throws \Exception
     */
    public static function del($where = [])
    {
        $data = self::where($where)->select();
        if(!empty($data)){
            if(false !== self::where($where)->delete()){
                foreach ($data as $value){
                    // 删除缓存
                    self::delCache($value['store_id']);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 删除缓存
     * @author 仇仇天
     */
    public static function delCache($storeId)
    {
        dkcache(self::$cacheName,$storeId);
    }
}
