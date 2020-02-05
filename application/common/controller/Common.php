<?php

namespace app\common\controller;

use think\Controller;
use think\Request;

/**
 * 项目公共控制器
 * @package app\common\controller
 */
class Common extends Controller
{
    /**
     * 初始化
     * @author 仇仇天
     */
    protected function initialize()
    {
        // 后台公共模板
        $this->assign('_admin_base_layout', config('admin_base_layout'));

        // 输出弹出层参数
        $this->assign('_pop', $this->request->param('_pop'));
    }

    /**
     * 渲染插件模板
     * @param string $template 模板文件名
     * @param string $suffix 模板后缀
     * @param array $vars 模板输出变量
     * @param array $config 模板参数
     * @return mixed
     * @author 仇仇天
     */
    final protected function pluginView($template = '', $suffix = '', $vars = [], $config = [])
    {
        $plugin_name = input('param.plugin_name');

        if ($plugin_name != '') {
            $plugin = $plugin_name;
            $action = 'index';
        } else {
            $plugin = input('param._plugin');
            $action = input('param._action');
        }
        $suffix        = $suffix == '' ? 'html' : $suffix;
        $template      = $template == '' ? $action : $template;
        $template_path = config('plugin_path') . "{$plugin}/view/{$template}.{$suffix}";
        return $this->fetch($template_path, $vars, $config);
    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $config 模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        $box_is_pjax = $this->request->isPjax();
        if ($box_is_pjax) {
            echo parent::fetch($template, $vars, $config);
            exit;
        }
        return parent::fetch($template, $vars, $config);
    }
}
