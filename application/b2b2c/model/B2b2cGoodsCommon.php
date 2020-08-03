<?php

namespace app\b2b2c\model;

use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use app\b2b2c\model\B2b2cGoodsImages as B2b2cGoodsImagesModel;
use think\Model;

/**
 * 商品公共内容模型
 */
class B2b2cGoodsCommon extends Model
{
    // 缓存名称
    protected static $cacheName = 'b2b2c_goods_common_data';

    // 商品规格缓存名称
    protected static $cacheName1 = 'b2b2c_goods_spec_data';


    /**
     * 根据id 获取 相应的商品公共内容数据(取缓存)
     * @param $goodsCommonId 商品公共id
     * @return bool|mixed|string
     */
    public static function getGoodsCommonByIdInfo($goodsCommonId)
    {
        $data = rcache(self::$cacheName, $goodsCommonId, ['module' => 'b2b2c']);
        return $data;
    }

    /**
     * 根据id 获取 相应的商品规格数据(取缓存)
     * @param $goodsCommonId 商品公共id
     * @return bool|mixed|string
     */
    public static function getGoodsSpeByIdInfo($goodsCommonId)
    {
        $data = rcache(self::$cacheName1, $goodsCommonId, ['module' => 'b2b2c']);
        return $data;
    }

    /**
     * 更新
     * @author 仇仇天
     * @param $where 条件
     * @param $update 需要更新的数据
     * @param bool $updateXS 更新分词搜索
     */
    public static function edit($where, $update)
    {
        $goodsCommonArray = self::where($where)->column('goods_commonid');

        if(empty($goodsCommonArray)){
            return false;
        }

        // 更新公共该商品
        if (false !== self::where($where)->update($update)) {

            // 删除公共商品缓存
            foreach ($goodsCommonArray as $value) {
                self::delCache($value['goods_commonid']);
            }

        }else{
            return false;
        }
    }

    /**
     * 删除（包括重置缓存）
     * @param array $where 条件
     * @throws \Exception
     * @author 仇仇天
     */
    public static function del($where = [])
    {
        if (empty($where)) return false;

        $goodsCommonIdArr = self::where($where)->column('goods_commonid');

        if (empty($goodsCommonIdArr)) return false;

        foreach ($goodsCommonIdArr as $val) {

            // 删除缓存
            self::delCache($val);

            // 删除商品规格
            self::delGoodsSpecCache($val);
        }

        // 公共商品id 筛选条件
        $goodsDelWhere = [
            ['goods_commonid', 'in', $goodsCommonIdArr]
        ];

        // 删除商品SKU
        B2b2cGoodsModel::del($goodsDelWhere);

        // 删除公共商品
        self::where($where)->delete();

        // 删除商品图片
        B2b2cGoodsImagesModel::del($goodsDelWhere);

        return true;
    }

    /**
     * 删除公共商品缓存
     * @param $goodsCommonId 商品公共id
     * @author 仇仇天
     */
    public static function delCache($goodsCommonId)
    {
        dkcache(self::$cacheName, $goodsCommonId);
    }

    /**
     * 删除商品规格缓存
     * @param $goodsCommonId 商品公共id
     * @author 仇仇天
     */
    public static function delGoodsSpecCache($goodsCommonId)
    {
        dkcache(self::$cacheName1, $goodsCommonId);
    }


    /**
     * 获得商品数量
     * @author 仇仇天
     * @param array $where 条件
     * @return int
     */
    public static function getGoodsCommonCount($where) {
        return self::where($where)->count();
    }

    /**
     * 出售中的商品数量
     * @author 仇仇天
     * @param array $where 条件
     * @return int
     */
    public static function getGoodsCommonOnlineCount($where) {
        $where['goods_state']   = config('b2b2c.STATE1');
        $where['goods_verify']  = config('b2b2c.VERIFY1');
        return self::getGoodsCommonCount($where);
    }

    /**
     * 仓库中的商品数量
     * @author 仇仇天
     * @param array $where 条件
     * @return int
     */
    public static function getGoodsCommonOfflineCount($where) {
        $where['goods_state']   = config('b2b2c.STATE0');
        $where['goods_verify']  = config('b2b2c.VERIFY1');
        return self::getGoodsCommonCount($where);
    }

}
