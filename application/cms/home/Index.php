<?php








namespace app\cms\home;

/**
 * 前台首页控制器
 * @package app\cms\admin
 */
class Index extends Common
{
    /**
     * 首页
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        return $this->fetch(); // 渲染模板
    }
}
