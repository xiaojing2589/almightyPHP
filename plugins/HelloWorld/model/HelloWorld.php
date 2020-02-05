<?php

namespace plugins\HelloWorld\model;

use app\common\model\AdminPlugin;

/**
 * 后台插件模型
 * @package plugins\HelloWorld\model
 */
class HelloWorld extends AdminPlugin
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'plugin_hello';

    public function test()
    {
        // 获取插件的设置信息
        halt('test');
    }
}
