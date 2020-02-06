<?php
namespace app\common\model;

use think\facade\Env;
use think\Model;

/**
 *  支付插件模型
 * @package app\common\model
 */
class PPay extends Model
{
    protected $name = 'p_pay'; // 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'pay'; // 缓存名称

    protected $autoWriteTimestamp = true; // 自动写入时间戳

    /**
     * 获取所有支付数据(取缓存)
     * @author 仇仇天
     */
    public static function getPayDataInfo()
    {
        $Data = rcache(self::$cacheName);
        return $Data;
    }

    /**
     * 所有数据包括未安装
     * @author 仇仇天
     * @return array|bool
     */
    public function getAll(){

        $path = Env::get('extend_path').'/pay/'; // 插件路径

        $dirs = array_map('basename', glob($path.'*', GLOB_ONLYDIR)); // 获取插件目录名称 Array

        if ($dirs === false || !file_exists(Env::get('extend_path'))) {
            $error = '插件目录不可读或者不存在';
            return false;
        }

        // 读取数据支付表
        $pay = $this->order('mark ASC,id DESC')->column(true, 'mark');

        // 读取未安装的支付
        foreach ($dirs as $pay_value) {
            if (!isset($pay[$pay_value])) {

                // 获取模块信息
                $info = self::getInfoFromFile($pay_value);

                $pay[$pay_value]['mark'] = $pay_value;

                // 模块模块信息缺失
                if (empty($info)) {
                    $pay[$pay_value]['status'] = 2;
                    continue;
                }

                // 模块模块信息不完整
                if (!$this->checkInfo($info)) {
                    $pay[$pay_value]['status'] = 3;
                    continue;
                }

                // 模块未安装
                $pay[$pay_value] = $info;

                $pay[$pay_value]['status'] = 4; // 模块未安装
            }
        }

        $result = ['total' => count($pay), 'pay' => $pay];

        return $result;

    }

    /**
     * 从文件获取支付信息
     * @author 仇仇天
     * @param string $name 支付名称
     * @return array|mixed
     */
    public static function getInfoFromFile($name = '')
    {
        // 插件路径
        $path = Env::get('extend_path').'/pay/';
        $info = [];
        if ($name != '') {
            // 从配置文件获取
            if (is_file($path. $name . '/config/config.php')) {
                $info = include $path. $name . '/config/config.php';
            }
        }
        return $info;
    }

    /**
     * 检查支付信息是否完整
     * @author 仇仇天
     * @param string $info 支付信息
     * @return bool
     */
    private function checkInfo($info = '')
    {
        $default_item = ['name','mark','config'];
        foreach ($default_item as $item) {
            if (!isset($info[$item]) || $info[$item] == '') {
                return false;
            }
        }
        return true;
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @throws \Exception
     */
    public static function del($where = [])
    {
        if(false !== self::where($where)->delete()){
            self::delCache(); // 删除缓存
            return true;
        }else{
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