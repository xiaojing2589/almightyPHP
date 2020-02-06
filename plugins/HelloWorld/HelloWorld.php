<?php

namespace plugins\HelloWorld;

use app\common\controller\Plugin;

/**
 * 演示插件
 * @package plugin\HelloWorld
 * @author 仇仇天
 */
class HelloWorld extends Plugin
{
    /**
     * page_tips钩子方法
     * @param $params
     * @author 仇仇天
     */
    public function pageTips($params)
    {
        echo '<div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p>Hello World</p>
        </div>';
    }

    /**
     * 安装方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 仇仇天
     * @return bool
     */
    public function install(){
        return true;
    }

    /**
     * 卸载方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @author 仇仇天
     * @return bool
     */
    public function uninstall(){
        return true;
    }
}
