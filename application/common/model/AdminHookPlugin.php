<?php

namespace app\common\model;

use think\Model;
use app\common\model\AdminHook as HookModel;

/**
 * 钩子-插件模型
 * @package app\common\model
 */
class AdminHookPlugin extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_hook_plugin';

    protected static $cacheName = 'hook_plugins'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * @describe 启用插件钩子
     * @param string $plugin 插件名称
     * @return bool
     * @author 仇仇天
     */
    public static function enable($plugin = '')
    {
        return self::where('plugin', $plugin)->setField('status', 1);
    }

    /**
     * @describe 禁用插件钩子
     * @param string $plugin 插件名称
     * @return int
     * @author 仇仇天
     */
    public static function disable($plugin = '')
    {
        return self::where('plugin', $plugin)->setField('status', 0);
    }

    /**
     * @describe 添加钩子-插件对照
     * @param array $hooks 钩子
     * @param string $plugin_name 插件名称
     * @return bool|int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 仇仇天
     */
    public static function addHooks($hooks = [], $plugin_name = '')
    {
        if (!empty($hooks) && is_array($hooks)) {
            // 添加钩子
            if (!HookModel::add($hooks, $plugin_name)) {
                return false;
            }

            $data = [];
            foreach ($hooks as $name => $description) {
                if (is_numeric($name)) {
                    $name = $description;
                }
                $data[] = [
                    'hook'        => $name,
                    'plugin'      => $plugin_name,
                    'create_time' => request()->time(),
                    'update_time' => request()->time(),
                ];
            }

            return self::insertAll($data);
        }
        return false;
    }

    /**
     * @describe 删除钩子
     * @param string $plugin_name 钩子名称
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public static function deleteHooks($plugin_name = '')
    {
        if (!empty($plugin_name)) {
            // 删除钩子
            if (!HookModel::del(['name' => $plugin_name])) {
                return false;
            }
            if (false === self::where('plugin', $plugin_name)->delete()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @describe 钩子插件排序
     * @param string $hook 钩子
     * @param string $plugins 插件名
     * @return bool
     * @author 仇仇天
     */
    public static function sort($hook = '', $plugins = '')
    {
        if ($hook != '' && $plugins != '') {
            $plugins = is_array($plugins) ? $plugins : explode(',', $plugins);

            foreach ($plugins as $key => $plugin) {
                $map = [
                    'hook'   => $hook,
                    'plugin' => $plugin
                ];
                self::where($map)->setField('sort', $key + 1);
            }
        }

        return true;
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
