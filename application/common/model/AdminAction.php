<?php

namespace app\common\model;

use think\Model;

/**
 * 日志模型
 * @package app\common\model
 */
class AdminAction extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_action';

    protected static $cacheName = 'action_config'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 删除（包括重置缓存）
     * @param array $where 条件
     * @throws \Exception
     * @author 仇仇天
     */
    public static function del($where = [])
    {
        if (false !== self::where($where)->delete()) {
            self::delCache(); // 删除缓存
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }
}
