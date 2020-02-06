<?php
namespace app\admin\controller;

use app\common\controller\Common;
use app\common\model\AdminMenu as AdminMenuModel;
use app\common\model\AdminUser as AdminUserModel;
/**
 * 用于处理ajax请求的控制器
 * @package app\admin\controller
 */
class Ajax extends Common
{
    /**
     * 获取侧栏菜单
     * @param string $module_id 模块id
     * @param string $module 模型名
     * @param string $controller 控制器名
     * @author 仇仇天
     * @return string
     */
    public function getSidebarMenu($mark = '')
    {
        $menus = AdminMenuModel::getSidebarMenu($mark);
        $this->success('操作成功',null,$menus);
    }

    /**
     * 轮询任务
     * @author 仇仇天
     */
    public function getPollingHandle(){

        $data = [
            // 锁定状态 0=未锁定 1=锁定
            'lock_status'=>0
        ];

        // 获取后台登录用户信息
        $userInfo = session('admin_user_info');

        // 用户锁定状态
        $data['lock_status'] = locksTatus();

        $this->success('操作成功','',$data);
    }

}