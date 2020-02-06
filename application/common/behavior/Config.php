<?php

namespace app\common\behavior;

use think\facade\Env;
use think\facade\Request;

/**
 * 初始化配置信息行为,将系统配置信息合并到本地配置
 * @author 仇仇天
 * Class Config
 * @package app\common\behavior
 */
class Config
{
    /**
     * 执行行为 run方法是Behavior唯一的接口
     * @throws \Exception
     * @author 仇仇天
     */
    public function run()
    {
        // 如果是安装操作，直接返回
        if (defined('BIND_MODULE') && BIND_MODULE === 'install') return;

        // 获取请求信息
        $path = Request::path();
        $path = explode(config('pathinfo_depr'), $path);

        // 获取当前模块名称
        $module = '';
        if (isset($path[0])) {
            $module = $path[0];
        }

        // 获取入口目录
        $base_file = Request::baseFile();
        $base_dir  = substr($base_file, 0, strripos($base_file, '/') + 1);
        define('PUBLIC_PATH', $base_dir);

        // 视图输出字符串内容替换
        $view_replace_str = [
            // 文件上传目录
            '__UPLOADS__'        => PUBLIC_PATH . 'uploads',
            // 静态资源目录
            '__ASSETS__'         => PUBLIC_PATH . 'static/assets',
            // 公共插件目录
            '__GLODAL_PLUGINS__' => PUBLIC_PATH . 'static/assets/vendors/general',
            // 公共插件目录
            '__GLODAL_CUSTOM__'  => PUBLIC_PATH . 'static/assets/vendors/custom',
            // 后台布局目录
            '__ADMIN_LAYOUTS__'  => PUBLIC_PATH . 'static/assets/admin/assets/layouts',
            // 后台CSS目录
            '__ADMIN_CSS__'      => PUBLIC_PATH . 'static/assets/admin/css',
            // 后台JS目录
            '__ADMIN_JS__'       => PUBLIC_PATH . 'static/assets/admin/js',
            // 后台IMG目录
            '__ADMIN_IMG__'      => PUBLIC_PATH . 'static/assets/admin/img',
            // 前台CSS目录
            '__HOME_CSS__'       => PUBLIC_PATH . 'static/assets/home/assets/css',
            // 表单项扩展目录
            '__EXTEND_FORM__'    => PUBLIC_PATH . 'static/extend/form',
        ];

        // 模板输出替换
        config('template.tpl_replace_string', $view_replace_str);

        // 如果定义了入口为admin，则修改默认的访问控制器层
        if (defined('ENTRANCE') && ENTRANCE == 'admin') {

            define('ADMIN_FILE', substr($base_file, strripos($base_file, '/') + 1));

            if ($module == '') {
                header("Location: " . $base_file . '/admin', true, 302);
                exit();
            }

            if (!in_array($module, config('module.default_controller_layer'))) {

                // 修改默认访问控制器层
                config('url_controller_layer', 'admin');

                // 修改视图模板路径
                config('template.view_path', Env::get('app_path') . $module . '/view/admin/');

            }

            // 插件静态资源目录
            config('template.tpl_replace_string.__PLUGINS__', '/plugins');

        } else {
            if ($module == 'admin') {
                header("Location: " . $base_dir . ADMIN_FILE . '/admin', true, 302);
                exit();
            }

            if ($module != '' && !in_array($module, config('module.default_controller_layer'))) {
                // 修改默认访问控制器层
                config('url_controller_layer', 'home');
            }
        }

        // 所有配置数据
        $systemConfigData = \app\common\model\AdminConfig::getConfigDataInfo('name', 'value');

        // 设置配置信息
        config($systemConfigData, 'app');
    }
}
