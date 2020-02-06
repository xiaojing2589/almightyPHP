<?php
namespace app\b2b2c\model;
use app\b2b2c\model\B2b2cStoreMsg;
use think\Model;
use think\Queue\Job;

/**
 * 队列
 * @package app\admin\model
 */
class QueueS extends Model
{
    /**
     * 发送店铺消息
     * @author 仇仇天
     * @param Job $job 队列对象
     * @param $data 参数
     * @return bool
     */
    public function sendStoreMsg(Job $job,$data) {
        $send = new B2b2cStoreMsg();
        $send->send($data['code'],$data['store_id'],$data['param']); // 发送店铺消息
        $job->delete(); // 删除任务
        return true;
    }
}
