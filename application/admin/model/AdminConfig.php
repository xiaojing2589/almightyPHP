<?php
namespace app\admin\model;

use think\Model;

/**
 * 后台配置模型
 * Class AdminConfig
 * @package app\common\model
 */
class AdminConfig extends Model
{
    // 缓存名称
    protected static $cacheName = 'admin_config_data';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有配置信息
     */
    public static function getConfigAll(){
        return rcache(self::$cacheName,'',['module'=>'admin']);
    }

    /**
     * 获取所有开启的配置信息
     */
    public static function getConfigOpenAll(){
        // 获取所有数据
        $data = self::getConfigAll();
        $dataStatusArr = [];
        foreach ($data as $key=>$value){
            if($value['status'] == 1){
                $dataStatusArr[$key] = $value;
            }
        }
        return $dataStatusArr;
    }

    /**
     * 根据name获取某个配置信息
     * @param $name
     * @return bool|mixed
     */
    public static function getByNameConfig($name){
        $data = self::getConfigOpenAll();
        if(!empty($data[$name])){
            return $data[$name];
        }
        return false;
    }

    /**
     * 获取某个配置信息
     * @param $idOrName 配置id/配置名称字段
     */
    public static function getByConfig($idOrName){
        $data = self::getConfigOpenAll();
        foreach ($data as $key=>$value){
            if($value['id'] == $idOrName || $value['name'] == $idOrName){
                return $value;
            }
        }
        return false;
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
     * 删除缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }





    /**
     * 获取所有配置信息数据(取缓存)
     * @author 仇仇天
     * @param string $field 字段
     * @param string $valueField 字段值
     * @return bool|mixed|string
     */
    public static function getConfigDataInfo($field = '',$valueField='',$status = false)
    {
        $data = self::getConfigOpenAll();
        $resArr = [];
        foreach ($data as $key=>$value){
            if($field != '' && $valueField !=''){
                if($status && $value['is_hide'] == 1){
                    $resArr[$value[$field]] = $value[$valueField];
                }else{
                    $resArr[$value[$field]] = $value[$valueField];
                }
            }else{
                if($status && $value['is_hide'] == 1){
                    $resArr[$key] =  $value;
                }else{
                    $resArr[$key] =  $value;
                }
            }
        }
        return $resArr;
    }

    /**
     * 获取配置信息数据(取缓存)
     * @author 仇仇天
     * @param string $name 配置名
     * @param string $Field 字段值
     * @return bool|mixed|string
     */
    public static function getConfigInfo($name = '',$field='value')
    {
        $data = self::getConfigDataInfo();
        if(empty($name))return [];
        if(empty($data[$name]))return [];
        return $data[$name][$field];
    }

}
