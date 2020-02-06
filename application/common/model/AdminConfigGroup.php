<?php
namespace app\common\model;

use think\Model;

/**
 * 后台配置分组模型
 * @package app\admin\model
 */
class AdminConfigGroup extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_config_group';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}
