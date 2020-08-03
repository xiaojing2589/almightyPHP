<?php

namespace app\b2b2c\model;

use think\Model;
use app\b2b2c\model\B2b2cGoodsAttrIndex as B2b2cGoodsAttrIndexModel;
use app\b2b2c\model\B2b2cPBundling as B2b2cPBundlingModel;
use app\b2b2c\model\B2b2cPBundlingGoods as B2b2cPBundlingGoodsModel;
use app\b2b2c\model\B2b2cPBoothGoods as B2b2cPBoothGoodsModel;
use app\b2b2c\model\B2b2cGroupbuy as B2b2cGroupbuyModel;
use app\b2b2c\model\B2b2cPXianshiGoods as B2b2cPXianshiGoodsModel;
use app\b2b2c\model\B2b2cGoodsBrowse as B2b2cGoodsBrowseModel;
use app\b2b2c\model\B2b2cFavorites as B2b2cFavoritesModel;
use app\b2b2c\model\B2b2cGoodsGift as B2b2cGoodsGiftModel;
use app\b2b2c\model\B2b2cPComboGoods as B2b2cPComboGoodsModel;
use app\b2b2c\model\B2b2cPFcodeGoods as B2b2cPFcodeGoodsModel;
use app\b2b2c\model\B2b2cChainStock as B2b2cChainStockModel;
use app\b2b2c\model\B2b2cGoodsCommon as B2b2cGoodsCommonModel;


/**
 * 商品模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cGoods extends Model
{

    // 缓存名称
    protected static $cacheName = 'b2b2c_goods';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 根据获取商品数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsDataByIdInfo($id)
    {
        $goodsClassData = rcache(self::$cacheName, $id, ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 更新商品促销价 (需要验证抢购和限时折扣是否进行)
     * @param $where 条件
     * @return bool
     */
    public static function editGoodsPromotionPrice($where)
    {
        $goodsData  = self::where($where)->select();
        $goodsArray = array();
        foreach ($goodsData as $val) {
            $goodsArray[$val['goods_commonid']][$val['goods_id']] = $val;
        }
        foreach ($goodsArray as $key => $val) {

            // 验证预定商品是否进行
            foreach ($val as $k => $v) {
                if ($v['is_book'] == '1') {
                    if ($v['book_down_time'] > time()) {
                        // 更新价格
                        self::edit(['goods_id' => $k], ['goods_promotion_price' => $v['book_down_payment'] + $v['book_final_payment'], 'goods_promotion_type' => 0]);
                    } else {
                        self::edit(['goods_id' => $k], ['is_book' => 0, 'book_down_payment' => 0, 'book_final_payment' => 0, 'book_down_time' => 0]);
                    }
                }
            }

            // 查询抢购是否进行
            $groupbuy = B2b2cGroupbuyModel::getGroupbuyConductOnlineInfo(['goods_commonid' => $key]);
            if (!empty($groupbuy)) {
                // 更新价格
                self::edit(['goods_commonid' => $key, 'is_book' => 0], ['goods_promotion_price' => $groupbuy['groupbuy_price'], 'goods_promotion_type' => 1]);
                continue;
            }

            // 查询限时折扣
            foreach ($val as $k => $v) {
                if ($v['is_book'] == '1') {
                    continue;
                }
                // 查询限时折扣是否进行
                $xianshigoods = B2b2cPXianshiGoodsModel::getXianshiGoodsConductOnlineInfo(['goods_id']);
                if (!empty($xianshigoods)) {
                    // 更新价格
                    self::edit(['goods_id' => $k], ['goods_promotion_price' => $xianshigoods['xianshi_price'], 'goods_promotion_type' => 2]);
                    continue;
                }

                // 没有促销使用原价
                self::edit(['goods_id' => $k], ['goods_promotion_price' => ['exp', 'goods_price'], 'goods_promotion_type' => 0]);
            }
        }
        return true;
    }

    /**
     * 更新
     * @author 仇仇天
     * @param $where 条件
     * @param $update 更新数据
     * @param bool $updateXS 更新分词搜索
     * @return bool
     */
    public static function edit($where, $update, $updateXS = false)
    {
        $goodsIdArray = self:: where($where)->column('goods_id');

        if (empty($goodsIdArray)){
            return false;
        }

        // 更新公共该商品
        if (false !== self::where($where)->update($update)) {

            // 删除公共商品缓存
            foreach ($goodsIdArray as $value) {
                self::delCache($value['goods_id']);
            }

            if($updateXS){
                // 更新分词搜索
                queueS($goodsIdArray,'b2b2c', 'updateXS');
            }

            return true;
        }
        return false;
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @return bool|int
     */
    public static function del($where = [])
    {
        $goodsData = self::where($where)->field('goods_id,goods_commonid,store_id')->select();

        if (!empty($goodsData)) {
            $goodsIdArray = [];

            foreach ($goodsData as $val) {

                // 删除缓存
                self::delCache($val['goods_id']);

                // 删除商品规格缓存
                B2b2cGoodsCommonModel::delGoodsSpecCache($val['goods_commonid']);

                $goodsIdArray[] = $val['goods_id'];
            }

            $goodsDelWhere = [
                ['goods_id', 'in', $goodsIdArray]
            ];

            // 删除属性关联表数据
            B2b2cGoodsAttrIndexModel::del($goodsDelWhere);

            // 删除优惠套装商品
            B2b2cPBundlingGoodsModel::del($goodsDelWhere);

            // 优惠套餐活动下架
            B2b2cPBundlingModel::editBundlingCloseByGoodsIds($goodsDelWhere);

            // 删除推荐展位商品
            B2b2cPBoothGoodsModel::del($goodsDelWhere);

            // 限时折扣
            B2b2cPXianshiGoodsModel::del($goodsDelWhere);

            //删除商品浏览记录
            B2b2cGoodsBrowseModel::del($goodsDelWhere);

            // 删除买家收藏表数据
            B2b2cFavoritesModel::del([['fav_id', 'in', $goodsIdArray], ['fav_type', '=', 'goods']]);

            // 删除商品赠品
            B2b2cGoodsGiftModel::del($goodsDelWhere, [['type' => 'whereor', 'data' => [['gift_goodsid', 'in', $goodsIdArray]]]]);

            // 删除推荐组合
            B2b2cPComboGoodsModel::del($goodsDelWhere, [['type' => 'whereor', 'data' => [['combo_goodsid', 'in', $goodsIdArray]]]]);

            // 删除商品F码
            B2b2cPFcodeGoodsModel::del($goodsDelWhere);

            // 删除门店商品关联
            B2b2cChainStockModel::del($goodsDelWhere);

        }

        return self::where($where)->delete();

    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     * @param $id
     */
    public static function delCache($id)
    {
        dkcache(self::$cacheName, $id);
    }
}
