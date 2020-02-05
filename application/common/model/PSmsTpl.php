<?php

namespace app\common\model;

use think\facade\Env;
use think\Model;

/**
 *  短信模型
 * @package app\common\model
 */
class PSmsTpl extends Model
{

    protected $name = 'p_sms_tpl'; // 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'sms_tpl'; // 缓存名称

    /**
     * 获取所有短信数据(取缓存)
     * @author 仇仇天
     */
    public static function getSmsTplDataInfo()
    {
        $Data = rcache(self::$cacheName);
        return $Data;
    }

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
