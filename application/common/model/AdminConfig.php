<?php

namespace app\common\model;

use think\Model;

/**
 * 后台配置模型
 * @package app\common\model
 */
class AdminConfig extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_config';

    protected static $cacheName = 'system_config_info'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有配置信息数据(取缓存)
     * @param string $field 字段
     * @param string $valueField 字段值
     * @return bool|mixed|string
     * @author 仇仇天
     */
    public static function getConfigDataInfo($field = '', $valueField = '', $status = false)
    {
        $data   = rcache(self::$cacheName);
        $resArr = [];
        foreach ($data as $key => $value) {
            if ($field != '' && $valueField != '') {
                if ($status && $value['is_hide'] == 1) {
                    $resArr[$value[$field]] = $value[$valueField];
                } else {
                    $resArr[$value[$field]] = $value[$valueField];
                }
            } else {
                if ($status && $value['is_hide'] == 1) {
                    $resArr[$key] = $value;
                } else {
                    $resArr[$key] = $value;
                }
            }
        }
        return $resArr;
    }

    /**
     * 获取配置信息数据(取缓存)
     * @param string $name 配置名
     * @param string $Field 字段值
     * @return bool|mixed|string
     * @author 仇仇天
     */
    public static function getConfigInfo($name = '', $field = 'value')
    {
        $data = self::getConfigDataInfo();
        if (empty($name)) return [];
        if (empty($data[$name])) return [];
        return $data[$name][$field];
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
}
