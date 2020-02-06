<?php

namespace app\admin\validate;

use think\Validate;

/**
 * 短信模板验证器
 * @author 仇仇天
 * @package app\admin\validate
 */
class SmsTpl extends Validate
{
    // 定义验证规则
    protected $rule = [
        'code|模板编码'            => 'require|length:1,50',
        'name|模板名称'            => 'require|length:1,50',
        'message_content|消息内容' => 'require|length:0,255',
    ];

    // 定义场景，供快捷编辑时验证
    protected $scene = [
        'name'  => ['name'],
        // 编辑验证
        'edit'  => ['name', 'message_content'],
    ];
}
