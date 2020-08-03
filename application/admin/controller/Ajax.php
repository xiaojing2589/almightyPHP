<?php
namespace app\admin\controller;

use app\common\controller\Common;
use app\admin\model\AdminMenu as AdminMenuModel;

/**
 * 用于处理ajax请求的控制器
 */
class Ajax extends Common
{
    /**
     * 获取侧栏菜单
     * @author 仇仇天
     * @param string $mark
     */
    public function getSidebarMenu($mark = '')
    {
        $menus = AdminMenuModel::getSidebarMenu($mark);
        $this->success('操作成功',null,$menus);
    }

    /**
     * 检测是否锁定
     * @author 仇仇天
     */
    public function lock(){
        $data = [
            // 锁定状态 0=未锁定 1=锁定
            'lock_status'=>0
        ];

        // 用户锁定状态
        $data['lock_status'] = locksTatus();

        $this->success('操作成功','',$data);
    }
}
