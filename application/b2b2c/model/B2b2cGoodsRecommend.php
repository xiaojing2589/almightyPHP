<?php

namespace app\b2b2c\model;
use app\b2b2c\model\B2b2cAlbumPic as B2b2cAlbumPicModel;
use think\Model;

/**
 * 商品推荐模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cGoodsRecommend extends Model
{
    protected $name = 'b2b2c_goods_recommend';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_goods_recommend'; // 缓存名称

    /**
     * 获取所有商品推荐数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsRecommendDataInfo()
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
