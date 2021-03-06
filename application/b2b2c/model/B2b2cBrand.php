<?php
namespace app\b2b2c\model;

use think\Model;

/**
 * 商品品牌模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cBrand extends Model
{
    // 缓存名称
    protected static $cacheName = 'b2b2c_brand';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有商品品牌数据(取缓存)
     * @author 仇仇天
     */
    public static function getBrandDataAll()
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
                    attaDel($value['brand_pic']); // 删除图片
                    attaDel($value['brand_bgpic']); // 删除图片
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
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }
}
