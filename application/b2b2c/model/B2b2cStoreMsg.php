<?php

namespace app\b2b2c\model;

use app\b2b2c\model\B2b2cMsgTpl as B2b2cMsgTplModel;
use app\b2b2c\model\B2b2cStoreMsgSetting as B2b2cStoreMsgSettingModel;
use think\Model;

/**
 *  店铺消息模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cStoreMsg extends Model
{
    protected $name = 'b2b2c_store_msg';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_store_msg'; // 缓存名称

    public $error = ''; // 错误信息

    /**
     * 获取所店铺消息数据(取缓存)
     * @author 仇仇天
     */
    public static function getStoreMsgDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
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

    /**
     * 店铺发送消息
     * @param $code  消息模板代码
     * @param $store_id 店铺id
     * @param array $param 参数
     * @return bool
     * @author 仇仇天
     */
    public function send($code, $store_id, $param = [])
    {
        if (empty($code)) return false;

        if (empty($store_id)) return false;

        $msgTpInfo = B2b2cMsgTplModel::getMsgTpInfo($code); // 获取消息模板配置

        $storeSettingInfo = B2b2cStoreMsgSettingModel::getStoreMsgSettingInfo($code);// 获取店铺消息设置

        $storeSettingInfo['sms_message_switch'] = empty($storeSettingInfo['sms_message_switch']) ? 0 : $storeSettingInfo['sms_message_switch'];
        $storeSettingInfo['sms_short_switch']   = empty($storeSettingInfo['sms_short_switch']) ? 0 : $storeSettingInfo['sms_short_switch'];
        $storeSettingInfo['sms_mail_switch']    = empty($storeSettingInfo['sms_mail_switch']) ? 0 : $storeSettingInfo['sms_mail_switch'];
        $storeSettingInfo['sms_short_number']   = empty($storeSettingInfo['sms_short_number']) ? '' : $storeSettingInfo['sms_short_number'];
        $storeSettingInfo['sms_mail_number']    = empty($storeSettingInfo['sms_mail_number']) ? 0 : $storeSettingInfo['sms_mail_number'];


        if (empty($msgTpInfo) || $msgTpInfo['type'] != 2) return false;

        // 发送站内信
        if ($msgTpInfo['message_switch'] && ($msgTpInfo['message_forced'] || $storeSettingInfo['sms_message_switch'])) {
            $message                      = ncReplaceText($msgTpInfo['message_content'], $param);
            $storeMsgInsert               = array();
            $storeMsgInsert['smt_code']   = $code;
            $storeMsgInsert['store_id']   = $store_id;
            $storeMsgInsert['sm_content'] = $message;
            if (false === self::insert($storeMsgInsert)) {
                $this->error = '商家站内信息发送失败';
            }
        }

        // 发送邮件
        if ($msgTpInfo['mail_switch'] && $storeSettingInfo['sms_mail_number'] != '' && ($msgTpInfo['mail_forced'] || $storeSettingInfo['sms_mail_switch'])) {
            $param['site_name']      = config('web_site_title'); // 平台名称
            $param['mail_send_time'] = date('Y-m-d H:i:s');
            $title                   = ncReplaceText($msgTpInfo['mail_title'], $param);
            $message                 = ncReplaceText($msgTpInfo['mail_content'], $param);
            if (false === sendMail($storeSettingInfo['sms_mail_number'], $title, $message)) {
                $this->error = '商家短信发送失败';
            }

        }

        // 发送短消息
        if ($msgTpInfo['short_switch'] && $storeSettingInfo['sms_short_number'] != '' && ($msgTpInfo['short_forced'] || $storeSettingInfo['sms_short_switch'])) {
            $param['site_name'] = config('web_site_title'); // 平台名称
            $resData            = b2b2cSendSms($storeSettingInfo['sms_short_number'], $code, $param);
            if ($resData['status'] == false) {
                $this->error = $resData['msg'];
            }
        }

        if ($this->error != '') return false;

        return true;
    }
}
