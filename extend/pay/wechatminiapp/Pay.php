<?php

use Yansongda\Pay\Pay AS YPay;

class Pay
{
    // 配置参数
    protected $config = [
        // 微信公众号 APPID
        'app_id'      => '',
        // 商户号
        'mch_id'      => '',
        // API密钥
        'key'         => '',
        // 支付成功通知地址
        'notify_url'  => '',
        // 退款，红包等情况时需要用到
        'cert_client' => '',
        // 退款，红包等情况时需要用到
        'cert_key'    => '',
        // 日志配置
        'log'         => [
            // 日志文件路径
            'file'     => '',
            // 建议生产环境等级调整为 info，开发环境为 debug
            'level'    => 'info',
            // 可选 daily.
            'type'     => 'single',
            // optional, 当 type 为 daily 时有效，默认 30 天
            'max_file' => 30,
        ],
        // 请求配置
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        'http'        => [
            // 请求超时的秒数。使用 0 无限期的等待
            'timeout'         => 5.0,
            // 表示等待服务器响应超时的最大值，使用 0 将无限等待
            'connect_timeout' => 5.0
        ],
        // 设置此参数为dev，将进入沙箱模式
        'mode'        => '',
    ];

    // 订单 配置参数
    public $order = [
        // 商户订单号
        'out_trade_no' => '',
        // 商品描述
        'body'         => '',
        // 订单总金额，单位为分
        'total_fee'    => '0.01',
        // 用户标识
        'openid'       => '',
    ];

    // 取消订单 配置参数
    public $cancelOrder = [
        // 原支付请求的商户订单号,和支付宝交易号不能同时为空
        'out_trade_no ' => '',
        'type'          => 'miniapp'
    ];

    // 关闭订单 配置参数
    public $closeOrder = [
        // 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
        'out_trade_no ' => '',
        'type'          => 'miniapp'
    ];

    // 订单退款 配置参数
    public $returnOrder = [
        // 商户订单号
        'out_trade_no'  => '',
        // 商户退款单号
        'out_refund_no' => '',
        // 订单金额
        'total_fee'     => '0.01',
        // 退款金额
        'refund_fee'    => '0.01',
        // 退款原因
        'refund_desc'   => '',
        // 类型
        'type'          => 'miniapp'
    ];

    // 订单查询 配置参数
    public $getOrder = [
        // 商户订单号
        'out_trade_no' => '',
        'type'         => 'miniapp'
    ];

    // 退款订单查询 配置参数
    public $getReturnOrder = [
        // 商户订单号
        'out_trade_no' => '',
        'type'         => 'miniapp'
    ];

    // 转账查询 配置参数
    public $getCarryover = [
        // 商户订单号
        'partner_trade_no' => '',
        'type'             => 'miniapp'
    ];

    // 支付对象
    public $payObj;

    // 错误信息
    public $error;

    /**
     * 初始化
     * Storage constructor.
     * @param array $config 是插件配置
     */
    public function __construct($config = [])
    {
        // 初始化参数
        $this->config = array_merge($this->config, $config);
        try {
            //设置支付
            $this->payObj = YPay::wechat($this->config);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 支付
     * @param $order 订单配置信息
     * @return bool
     * @author 仇仇天
     */
    public function payment($config = [])
    {
        // 覆盖默认配置
        $this->order = array_merge($this->order, $config);

        try {
            $result = $this->payObj->miniapp($this->order);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 退款
     * @param $order 退款配置信息
     * @return mixed
     * @author 仇仇天
     */
    public function refund($config = [])
    {

        // 覆盖默认配置
        $this->returnOrder = array_merge($this->returnOrder, $config);

        try {
            $result = $this->payObj->refund($this->returnOrder);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 取消订单
     * @param $config 取消订单配置
     */
    public function cancel($config = [])
    {

        // 覆盖默认配置
        $this->cancelOrder = array_merge($this->cancelOrder, $config);

        try {
            $result = $this->payObj->close($this->cancelOrder);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 关闭订单
     * @param $config 关闭订单配置
     */
    public function close($config)
    {

        // 覆盖默认配置
        $this->closeOrder = array_merge($this->closeOrder, $config);

        try {
            $result = $this->payObj->close($this->closeOrder);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 查询订单
     * @param $config 配置信息
     * @return mixed
     * @author 仇仇天
     */
    public function getOrder($config = [])
    {

        // 覆盖默认配置
        $this->getOrder = array_merge($this->getOrder, $config);

        try {
            $result = $this->payObj->find($this->getOrder);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 查询退款订单
     * @param $config 配置信息
     * @return mixed
     * @author 仇仇天
     */
    public function getReturnOrder($config = [])
    {

        // 覆盖默认配置
        $this->getReturnOrder = array_merge($this->getReturnOrder, $config);

        try {
            $result = $this->payObj->find($this->getReturnOrder, 'refund');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 查询企业付款订单
     * @param array $config 配置信息
     */
    public function getCarryover($config = [])
    {

        // 覆盖默认配置
        $this->getCarryover = array_merge($this->getCarryover, $config);

        try {
            $result = $this->payObj->find($this->getCarryover, 'transfer');
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 支付异步通知验证
     * @author 仇仇天
     */
    public function verifyOrder()
    {
        try {
            $result = $this->payObj->verify();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return $result;
    }

    /**
     * 退款异步通知验证
     * @author 仇仇天
     */
    public function verifyReOrder()
    {
        try {
            $result = $this->payObj->verify(null, true);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return $result;
    }

    /**
     * 确认回调
     * @author 仇仇天
     */
    public function success()
    {
        try {
            $result = $this->payObj->success()->send();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return $result;
        return $result;
    }
}
