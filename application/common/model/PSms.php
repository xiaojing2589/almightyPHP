<?php
namespace app\common\model;

use think\facade\Env;
use think\Model;

/**
 *  短信模型
 * @package app\common\model
 */
class PSms extends Model
{

    protected $name = 'p_sms'; // 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'sms'; // 缓存名称


    protected $autoWriteTimestamp = true; // 自动写入时间戳


    /**
     * 获取所有短信数据(取缓存)
     * @author 仇仇天
     */
    public static function getSmsDataInfo()
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

        $path = Env::get('extend_path').'/sms/'; // 插件路径

        $dirs = array_map('basename', glob($path.'*', GLOB_ONLYDIR)); // 获取插件目录名称 Array

        if ($dirs === false || !file_exists(Env::get('extend_path'))) {
            $error = '模块目录不可读或者不存在';
            return false;
        }

        // 读取数据短信表
        $sms = $this->order('mark ASC,id DESC')->column(true, 'mark');

        // 读取未安装的短信
        foreach ($dirs as $sms_value) {
            if (!isset($sms[$sms_value])) {
                // 获取模块信息
                $info = self::getInfoFromFile($sms_value);

                $sms[$sms_value]['mark'] = $sms_value;

                // 模块模块信息缺失
                if (empty($info)) {
                    $sms[$sms_value]['status'] = 2;
                    continue;
                }

                // 模块模块信息不完整
                if (!$this->checkInfo($info)) {
                    $sms[$sms_value]['status'] = 3;
                    continue;
                }

                // 模块未安装
                $sms[$sms_value] = $info;

                $sms[$sms_value]['status'] = 4; // 模块未安装
            }
        }

        $result = ['total' => count($sms), 'sms' => $sms];

        return $result;

    }

    /**
     * 从文件获取短信信息
     * @author 仇仇天
     * @param string $name 短信名称
     * @return array|mixed
     */
    public static function getInfoFromFile($name = '')
    {
        $path = Env::get('extend_path').'/sms/'; // 插件路径
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
     * 检查短信信息是否完整
     * @author 仇仇天
     * @param string $info 短信信息
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