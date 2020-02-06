<?php
namespace app\common\model;

use think\facade\Env;
use think\Model;

/**
 *  对象存储模型
 * @package app\common\model
 */
class PStorage extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'p_storage';

    protected static $cacheName = 'storage'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有云存储数据(取缓存)
     * @author 仇仇天
     */
    public static function getStorageDataInfo()
    {
        $Data = rcache(self::$cacheName);
        return $Data;
    }

    /**
     * 根据字段获取类型信息(取缓存)
     * @author 仇仇天
     * @param $value 值
     * @param string $field 字段 默认mark
     * @return array
     */
    public static function getStorageInfo($value,$field = 'mark')
    {
        $data  = self::getStorageDataInfo();
        $resData = [];
        foreach ($data as $v){
            if($v[$field] == $value){
                $resData = $v;
            }
        }
        return $resData;
    }

    /**
     * 获取配置值(取缓存)
     * @author 仇仇天
     * @param $mark  标识
     * @return array|mixed
     */
    public static function getStorageConfigVlue($mark){
        $Info = self::getStorageInfo($mark);
        $resArr = [];
        if(empty($Info)){
            $resArr = $Info['config_value'];
        }
        return $resArr;
    }

    public function getAll(){

        $path = Env::get('extend_path').'/storage/'; // 插件路径

        $dirs = array_map('basename', glob($path.'*', GLOB_ONLYDIR)); // 获取插件目录名称 Array

        if ($dirs === false || !file_exists(Env::get('extend_path'))) {
            $error = '模块目录不可读或者不存在';
            return false;
        }

        // 读取数据库云存储表
        $storages = $this->order('mark ASC,id DESC')->column(true, 'mark');

        // 读取未安装的云存储
        foreach ($dirs as $storage_value) {
            if (!isset($storages[$storage_value])) {
                // 获取模块信息
                $info = self::getInfoFromFile($storage_value);

                $storages[$storage_value]['mark'] = $storage_value;

                // 模块模块信息缺失
                if (empty($info)) {
                    $storages[$storage_value]['status'] = '2';
                    continue;
                }

                // 模块模块信息不完整
                if (!$this->checkInfo($info)) {
                    $storages[$storage_value]['status'] = '3';
                    continue;
                }

                // 模块未安装
                $storages[$storage_value] = $info;

                $storages[$storage_value]['status'] = '4'; // 模块未安装
            }
        }

        $result = ['total' => count($storages), 'storage' => $storages];

        return $result;

    }

    /**
     * 从文件获取云存储信息
     * @author 仇仇天
     * @param string $name 云存储名称
     * @return array|mixed
     */
    public static function getInfoFromFile($name = '')
    {
        $path = Env::get('extend_path').'/storage/'; // 插件路径

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
     * 检查云存储信息是否完整
     * @author 仇仇天
     * @param string $info 云存储信息
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
