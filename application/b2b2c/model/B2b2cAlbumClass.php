<?php

namespace app\b2b2c\model;
use app\b2b2c\model\B2b2cAlbumPic as B2b2cAlbumPicModel;
use think\Model;

/**
 * 店铺相册模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cAlbumClass extends Model
{
    protected $name = 'b2b2c_album_class';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_album_class'; // 缓存名称

    /**
     * 获取所有店铺相册数据(取缓存)
     * @author 仇仇天
     */
    public static function getAlbumClassDataInfo()
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
        $data = self::where($where)->select();
        if(!empty($data)){
            if(false !== self::where($where)->delete()){
                self::delCache(); // 删除缓存
                foreach ($data as $value){
                    attaDel($value['aclass_cover']); // 删除图片
                    B2b2cAlbumPicModel::del(['aclass_id'=>$value['aclass_id']]);
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
