<?php

use Yansongda\Pay\Pay AS YPay;

class Pay
{
    // 配置参数
    protected $config = [
        // 支付宝 APPID
        'app_id'         => '',
        // 支付宝公钥 详细参考：https://docs.open.alipay.com/291/105971
        'ali_public_key' => '',
        // 开发者私钥 详细参考：https://docs.open.alipay.com/291/105971
        'private_key'    => '',
        // 支付成功通知地址
        'notify_url'     => '',
        // 退款通知地址
        'return_url'     => '',
        // 日志配置
        'log'            => [
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
        'http'           => [
            // 请求超时的秒数。使用 0 无限期的等待
            'timeout'         => 5.0,
            // 表示等待服务器响应超时的最大值，使用 0 将无限等待
            'connect_timeout' => 5.0
        ],
        // 设置此参数为dev，将进入沙箱模式
        'mode'           => '',
    ];

    // 订单 配置参数
    // 更多参数设置 https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
    public $order = [
        // 商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
        'out_trade_no' => '',
        // 订单标题
        'subject'      => '',
        // 订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。
        'total_amount' => '0.1',
        // 买家的支付宝唯一用户号（2088开头的16位纯数字）
        'buyer_id'     => ''
    ];

    // 取消订单 配置参数
    // 更多参数设置 https://docs.open.alipay.com/api_1/alipay.trade.cancel/
    public $cancelOrder = [
        // 原支付请求的商户订单号,和支付宝交易号不能同时为空
        'out_trade_no ' => ''
    ];

    // 关闭订单 配置参数
    // 更多参数设置 https://docs.open.alipay.com/api_1/alipay.trade.close/
    public $closeOrder = [
        // 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
        'out_trade_no ' => ''
    ];

    // 订单退款 配置参数
    // 更多参数设置 https://docs.open.alipay.com/api_1/alipay.trade.refund
    public $returnOrder = [
        // 订单支付时传入的商户订单号,不能和 trade_no同时为空。
        'out_trade_no'  => 'app',
        // 需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
        'refund_amount' => '',
    ];

    // 订单查询 配置参数
    // 更多参数设置 https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_2
    public $getOrder = [
        // 订单支付时传入的商户订单号,和支付宝交易号(trade_no)不能同时为空
        'out_trade_no'    => '',
        // 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
        'out_request_no ' => ''
    ];

    // 退款订单查询 配置参数
    // 更多参数设置 https://docs.open.alipay.com/api_1/alipay.trade.fastpay.refund.query
    public $getReturnOrder = [
        // 支付宝交易号，和商户订单号不能同时为空
        'out_trade_no'    => '',
        // 请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
        'out_request_no ' => ''
    ];

    // 转账查询 配置参数
    // 更多参数设置 https://docs.open.alipay.com/api_1/alipay.trade.query/
    public $getCarryover = [
        // 支付宝交易号，和商户订单号不能同时为空
        'out_trade_no' => ''
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
            $this->payObj = YPay::alipay($this->config);
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
            $result = $this->payObj->mini($this->order);
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
     * 查询转账订单
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
            $result = $this->payObj->verify();
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
