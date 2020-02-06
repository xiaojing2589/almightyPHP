<?php

namespace app\b2b2c\model;

use think\Model;

/**
 *  店铺消息设置模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cStoreMsgSetting extends Model
{
    protected $name = 'b2b2c_store_msg_setting';// 设置当前模型对应的完整数据表名称

    protected static $cacheName = 'b2b2c_store_msg_setting'; // 缓存名称

    /**
     * 获取所有店铺消息设置数据(取缓存)
     * @author 仇仇天
     */
    public static function getStoreMsgSettingDataInfo()
    {
        $goodsClassData = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $goodsClassData;
    }

    /**
     * 根据字段获取店铺消息设置模板(取缓存)
     * @param $value 值
     * @param string $field 字段 smt_code
     * @return array
     * @author 仇仇天
     */
    public static function getStoreMsgSettingInfo($value, $field = 'smt_code')
    {
        $data    = self::getStoreMsgSettingDataInfo();
        $resData = [];
        if(!empty($data)){
            if($field =='smt_code'){
                $resData = $data[$value];
            }else{
                foreach ($data as $v) {
                    if ($v[$field] == $value) {
                        $resData = $v;
                    }
                }
            }
        }
        return $resData;
    }

    /**
     * 删除（包括重置缓存）
     * @author 仇仇天
     * @param array $where 条件
     * @throws \Exception
     */
    public static function del($where = [])
    {
        if(false !== self::where($where)->delete()){
            self::delCache(); // 删除缓存
            return true;
        }else{
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
