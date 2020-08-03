<?php

namespace app\common\controller;

use app\admin\model\AdminMenu as AdminMenuModel;
use app\admin\model\AdminIcon as AdminIconModel;
use app\admin\model\AdminRole as AdminRoleModel;


/**
 * 后台公共类
 * Class Admin
 * @package app\common\controller
 */
class Admin extends Common
{
    /**
     * 初始化
     * @author 仇仇天
     */
    protected function initialize()
    {

        //  调用父类 初始方法
        parent::initialize();

        // 后台公共模板
        $this->assign('_admin_base_layout', config('admin_base_layout'));

        // 是否拒绝ie浏览器访问
        if (config('system.deny_ie') && get_browser_type() == 'ie') {
            $this->redirect('admin/ie/index');
        }

        // 检测是否登录
        $this->isLogin();

        // 检测是否锁定
        $this->isLocking();

        // 检查权限
        if (!AdminRoleModel::checkAuth()) {
            $this->error('权限不足！');
        }

        // 如果不是ajax请求，则读取菜单
        if (!$this->request->isAjax()) {

            // 读取顶部菜单
            $this->assign('_top_menus', AdminMenuModel::getByRoleIdAuthTopMenu(session('admin_user_info.role')));

            // 获取侧边栏菜单
            $this->assign('_sidebar_menus', AdminMenuModel::getSidebarMenu());

            // 获取面包屑导航
            $this->assign('_location', AdminMenuModel::getLocation());

            // 获取自定义图标
            $this->assign('_icons', AdminIconModel::getUrls());

        }

        // 可设置语言列表
        $languageType    = config('system.language_type');

        $this->assign('languageType', $languageType);

        // 默认语言
        $this->assign('defaultLangType', $this->request->langset());

    }

    /**
     * 检查是 否登录，没有登录则跳转到登录页面
     * @return mixed
     * @author 仇仇天
     */
    public function isLogin()
    {
        // 判断是否登录
        if ($uid = adminIsSignin()) {
            // 已登录
            return $uid;
        } else {
            // 未登录
            $this->redirect('admin/publics/signin');
        }
    }

    /**
     * 后台用户锁定
     */
    public function isLocking()
    {
        // 检测该用户是否已锁定
        if (locksTatus() == 1) {

            // 获取当前访问控制器
            $controller = $this->request->controller();

            // 获取当前访问控方法
            $action = $this->request->action();

            if ($controller != 'user' && $action != 'lock') {
                $this->error('该用户已锁定，请先解锁', url('user/lock'));
            }
        }
    }
}
