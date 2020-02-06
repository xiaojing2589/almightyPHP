<?php

namespace app\common\model;

use think\Model;

/**
 * 短信模型
 * @package app\common\model
 */
class Sms extends Model
{

    protected $name = 'sms'; // 设置当前模型对应的完整数据表名称

    protected static $expire = 120; // 验证码有效时长

    protected static $maxCheckNums = 10; // 最大允许检测的次数

    /**
     * 获取最后一次手机发送的数据
     * @author 仇仇天
     * @param   int    $mobile 手机号
     * @param   string $event  事件
     * @return  Sms
     */
    public static function get($mobile, $event = 'default')
    {
        $sms = self:: where(['mobile' => $mobile, 'event' => $event])->order('id', 'DESC')->find();
        return $sms ? $sms : null;
    }

    /**
     * 发送验证码
     * @author 仇仇天
     * @param   int    $mobile 手机号
     * @param   string $type   类型
     * @param   int    $code   验证码,为空时将自动生成4位数字
     * @param   string $event  事件
     * @return  boolean
     */
    public static function send($mobile, $code = null, $event = 'default')
    {
        $code = is_null($code) ? mt_rand(1000, 9999) : $code;
        $time = time();
        $ip = request()->ip();
        $sms = self::create(['event' => $event, 'mobile' => $mobile, 'code' => $code, 'ip' => $ip, 'createtime' => $time]);
        $result = Hook::listen('sms_send', $sms, null, true);
        if (!$result) {
            $sms->delete();
            return false;
        }
        return $code;
        return true;
    }

    /**
     * 发送通知
     * @author 仇仇天
     * @param   mixed  $mobile   手机号,多个以,分隔
     * @param   string $msg      消息内容
     * @param   string $template 消息模板
     * @return  boolean
     */
    public static function notice($mobile, $msg = '', $template = null)
    {
        $params = [
            'mobile'   => $mobile,
            'msg'      => $msg,
            'template' => $template
        ];
        $result = Hook::listen('sms_notice', $params, null, true);
        return $result ? true : false;
    }

    /**
     * 校验验证码
     * @author 仇仇天
     * @param   int    $mobile 手机号
     * @param   int    $code   验证码
     * @param   string $event  事件
     * @return  boolean
     */
    public static function check($mobile, $code, $event = 'default')
    {
        $time = time() - self::$expire;
        $sms = self::where(['mobile' => $mobile, 'event' => $event])->order('id', 'DESC')->find();
        if ($sms) {
            if ($sms['createtime'] > $time && $sms['times'] <= self::$maxCheckNums) {
                $correct = $code == $sms['code'];
                if (!$correct) {
                    $sms->times = $sms->times + 1;
                    $sms->save();
                    return false;
                } else {
                    $result = Hook::listen('sms_check', $sms, null, true);
                    return $result;
                }
            } else {
                // 过期则清空该手机验证码
                self::flush($mobile, $event);
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 清空指定手机号验证码
     * @author 仇仇天
     * @param   int    $mobile 手机号
     * @param   string $event  事件
     * @return  boolean
     */
    public static function flush($mobile, $event = 'default')
    {
        self::where(['mobile' => $mobile, 'event' => $event])->delete();
        return true;
    }


}
