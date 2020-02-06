<?php

namespace app\common\behavior;

/**
 * 注册钩子
 */
class Hook
{
    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @author 仇仇天
     */
    public function run()
    {
        // 如果是安装操作，直接返回
        if (defined('BIND_MODULE') && BIND_MODULE === 'install') return;

        // 获取插件钩子缓存
        $hook_plugins = rcache('hook_plugins');

        // 获取钩子缓存
        $hooks = rcache('hooks');

        // 获取插件缓存
        $plugins = rcache('plugins');

        if ($hook_plugins) {
            foreach ($hook_plugins as $value) {
                if (isset($hooks[$value['hook']]) && isset($plugins[$value['plugin']])) {
                    \think\facade\Hook::add($value['hook'], get_plugin_class($value['plugin']));
                }
            }
        }
    }
}
