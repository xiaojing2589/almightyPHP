<?php

namespace app\b2b2c\model;

use think\Model;

/**
 * 店铺相册图片模型
 */
class B2b2cAlbumPic extends Model
{
    // 缓存名称
    protected static $cacheName = 'b2b2c_album_pic';

    /**
     * 获取所有店铺相册图片数据(取缓存)
     * @author 仇仇天
     */
    public static function getAlbumPicDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @return bool
     */
    public static function del($where = [])
    {
        $data = self::where($where)->select();
        if(!empty($data)){
            if(false !== self::where($where)->delete()){
                // 删除缓存
                self::delCache();
                foreach ($data as $value){
                    // 删除图片
                    attaDel($value['apic_cover']);
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
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }
}
