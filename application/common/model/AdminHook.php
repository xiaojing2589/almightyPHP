<?php

namespace app\common\model;

use think\Model;

/**
 * 钩子模型
 * @package app\common\model
 */
class AdminHook extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_hook';

    protected static $cacheName = 'hooks'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 添加钩子
     * @param array $hooks 钩子
     * @param string $plugin_name
     * @return bool
     * @author 仇仇天
     */
    public static function add($hooks = [], $plugin_name = '')
    {
        if (!empty($hooks) && is_array($hooks)) {
            $data = [];
            foreach ($hooks as $name => $description) {
                if (is_numeric($name)) {
                    $name        = $description;
                    $description = '';
                }
                if (self::where('name', $name)->find()) {
                    continue;
                }
                $data[] = [
                    'name'        => $name,
                    'plugin'      => $plugin_name,
                    'description' => $description,
                    'create_time' => request()->time(),
                    'update_time' => request()->time(),
                ];
            }
            if (!empty($data) && false === self::insertAll($data)) {
                return false;
            }
        }
        return true;
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
