<?php

namespace app\b2b2c\model;

use think\Model;
use app\b2b2c\model\B2b2cStoreMsg as B2b2cStoreMsgModel;
use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use think\Queue\Job;

/**
 * 队列
 * @package app\admin\model
 */
class QueueS extends Model
{
    /**
     * 发送店铺消息
     * @param Job $job 队列对象
     * @param $data 参数
     * @return bool
     * @author 仇仇天
     */
    public function sendStoreMsg(Job $job, $data)
    {
        $send = new B2b2cStoreMsgModel();

        // 发送店铺消息
        $send->send($data['code'], $data['store_id'], $data['param']);

        // 删除任务
        $job->delete();

        return callback();
    }

    /**
     * 根据商品id更新促销价格
     * @param $goods_id
     * @return mixed
     * @author 仇仇天
     */
    public function updateGoodsPromotionPriceByGoodsId(Job $job,$goods_id)
    {
        $update = B2b2cGoodsModel::editGoodsPromotionPrice(array('goods_id' => array('in', $goods_id)));
        if (!$update) {
            return callback(false,'根据商品ID更新促销价格失败');
        } else {
            return callback();
        }
    }

    /**
     * 跟据商品ID更新
     * @param unknown $goods_ids 商品id 数组/字符/数字
     * @return boolean
     */
    public function updateXS(Job $job,$goods_ids)
    {
        // 功能未完成
        return true;
        try {
            $obj_xs = new \XS('almightyPHP');
            $obj_xs->index->del($goods_ids);
            $obj_xs->index->flushIndex();
            return callback();
        } catch (XSException $e) {
            return callback(false,'全文搜索出现异常');
        }
    }
}
