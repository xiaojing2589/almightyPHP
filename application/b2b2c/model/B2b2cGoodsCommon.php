<?php

namespace app\b2b2c\model;

use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use think\Model;

/**
 * 商品公共内容模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cGoodsCommon extends Model
{
    protected $name = 'b2b2c_goods_common';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_goods_common'; // 缓存名称


    /**
     * 获取所有商品公共内容数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsCommonDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 根据字段获取商品公共内容信息(取缓存)
     * @param $value 值
     * @param string $field 字段 默认goods_commonid
     * @return array
     * @author 仇仇天
     */
    public static function getGoodsCommonInfo($value, $field = 'goods_commonid')
    {
        $data    = self::getGoodsCommonDataInfo();
        $resData = [];
        if ($field == 'goods_commonid') {
            $resData = empty($data[$value]) ? [] : $data[$value];
        } else {
            foreach ($data as $v) {
                if ($v[$field] == $value) {
                    $resData = $v;
                }
            }
        }
        return $resData;
    }


    /**
     * 下架
     * @author 仇仇天
     * @param $update 需要更新的字段
     * @param $where  更新条件
     */
    public function editGoodsLockUp($update, $where)
    {
        $update_param['goods_state'] = config('b2b2c.STATE10');
        $update                      = array_merge($update, $update_param);
        if (false !== self::where($where)->update($update)) {
            if (false !== B2b2cGoodsModel::where($where)->update($update)) {
                $common_list = self::where($where)->select(); // 下架的
                foreach ($common_list as $value) {
                    // 发送店铺消息
                    queueS(
                        [
                            'code'     => 'goods_violation',
                            'store_id' => $value['store_id'],
                            'param'    => [
                                'remark'    => $value['goods_stateremark'],
                                'common_id' => $value['goods_commonid']
                            ]
                        ],
                        'b2b2c',
                        'sendStoreMsg'
                    );
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 审核
     * @author 仇仇天
     * @param $update 需要更新的字段
     * @param $where 更新条件
     */
    public function editProducesVerifyFail($update, $where){
        if (false !== self::where($where)->update($update)) {
            if (false !== B2b2cGoodsModel::where($where)->update($update)) {
                $common_list = self::where($where)->select();
                foreach ($common_list as $value) {
                    // 发送店铺消息
                    if($value['goods_verify'] == config('b2b2c.VERIFY0')){
                        queueS(
                            [
                                'code'     => 'goods_verify',
                                'store_id' => $value['store_id'],
                                'param'    => [
                                    'remark'    => $value['goods_verifyremark'],
                                    'common_id' => $value['goods_commonid']
                                ]
                            ],
                            'b2b2c',
                            'sendStoreMsg'
                        );
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * 计算商品库存（批量）
     * @author 仇仇天
     * @param array $goods_list 商品公共数据
     * @return array|boolean
     */
    public static  function calculateStorageAll($goods_list) {
        if (!empty($goods_list)) {
            foreach ($goods_list as $value) {
                $goodscommonid_array[] = $value['goods_commonid'];
            }
            $goods_storage = B2b2cGoodsModel::where([['goods_commonid','in',$goodscommonid_array]])->select();
            $storage_array = [];
            foreach ($goods_storage as $val) {
                if ($val['goods_storage_alarm'] != 0 && $val['goods_storage'] <= $val['goods_storage_alarm']) {
                    $storage_array[$val['goods_commonid']]['alarm'] = true;
                }
                @$storage_array[$val['goods_commonid']]['goods_storage'] += $val['goods_storage'];
                $storage_array[$val['goods_commonid']]['goods_id'] = $val['goods_id'];
            }
            return $storage_array;
        } else {
            return false;
        }
    }

    /**
     * 计算商品库存（单个）
     * @author 仇仇天
     * @param array $goods_commonid 商品公共数据id
     * @return array|boolean
     */
    public static  function calculateStorage($goods_commonid) {
        if (!empty($goods_commonid)) {
            $goods_storage_arr = B2b2cGoodsModel::where('goods_commonid','=',$goods_commonid)->select();
            $goods_storage = 0;
            foreach ($goods_storage_arr as $val) {
                $goods_storage += $val['goods_storage'];
            }
            return $goods_storage;
        } else {
            return 0;
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
        if (false !== self::where($where)->delete()) {
            self::delCache(); // 删除缓存
            return true;
        } else {
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
