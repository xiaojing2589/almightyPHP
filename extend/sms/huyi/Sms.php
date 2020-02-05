<?php

namespace sms\huyi;

use \util\Http;

class Sms
{
    // 配置参数
    protected $config = [
        'account'  => '',       // 账号
        'password' => 'asdasda',// 密码
        'Endpoint' => 'https://106.ihuyi.com/webservice/sms.php?method=Submit',  // 阿里云oss 外网地址endpoint
    ];

    public $error; // 错误信息

    /**
     * Storage constructor.
     * @param array $config 是插件配置
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        return false;
    }

    /**
     * 短信发送行为
     * @param $mobile
     * @param $msg
     * @return mixed
     * @author 仇仇天
     */
    public function send($mobile, $msg)
    {
        $postArr = array(
            'account'  => $this->config['account'],  // 账号
            'password' => $this->config['password'], // 密码
            'mobile'   => $mobile,   // 发送手机
            'content'  => $msg,  // 发送内容
        );
        $result  = Http::doPost('https://106.ihuyi.com/webservice/sms.php?method=Submit', $postArr);
        $data    = $this->xmlToArray($result);
        if ($data['SubmitResult']['code'] == 2) {
            return true;
        } else {
            $this->error = $data['SubmitResult']['msg'];
            return false;
        }
        return false;
    }

    /**
     * 解析返回值
     * @param $xml
     * @return mixed
     * @author 仇仇天
     */
    private function xmlToArray($xml)
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key    = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = $this->xmlToArray($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

}
