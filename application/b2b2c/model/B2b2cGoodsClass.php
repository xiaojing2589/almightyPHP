<?php

namespace app\b2b2c\model;

use think\Model;
use util\Tree;

/**
 * 商品分类模型
 * Class Advert
 * @package app\b2b2c\model
 */
class B2b2cGoodsClass extends Model
{
    // 缓存名称
    protected static $cacheName = 'b2b2c_goods_class';

    /**
     * 获取所有商品分类数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsClassDataAll()
    {
        $data = rcache(self::$cacheName, '', ['module' => 'b2b2c']);
        return $data;
    }

    /**
     * 获取所有商品分类树数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsClassTreeDataAll(){
        $data = self::getGoodsClassDataAll();
        if(!empty($data)){
            $treeConfig = ['id' => 'gc_id', 'pid' => 'gc_parent_id', 'title' => 'gc_name'];
            $Tree       = Tree::config($treeConfig);
            $data      = $Tree::toList($data);
        }
        return to_arrays($data);
    }

    /**
     * 获取所有商品分类树数据(取缓存)
     * @author 仇仇天
     */
    public static function getGoodsClassTopData(){
        $data = self::getGoodsClassDataAll();
        $resData = [];
        foreach ($data as $value){
            if($value['gc_parent_id'] == 0){
                $resData[] = $value;
            }
        }
        return $resData;
    }






    /**
     * 根据字段获取分类信息(取缓存)
     * @param $value 值
     * @param string $field 字段 默认id
     * @return array
     * @author 仇仇天
     */
    public static function getGoodsClassInfo($value, $field = 'gc_id')
    {
        $data    = self::getGoodsClassDataAll();
        $resData = [];
        foreach ($data as $v) {
            if ($v[$field] == $value) {
                $resData = $v;
            }
        }
        return $resData;
    }

    /**
     * 所有获取一维数组树形节点(取缓存)
     * @author 仇仇天
     * @param int $id
     * @param string $param
     * @return mixed
     */
    public static function getMenuDataTree($id = 0,$param = [])
    {
        $result = [];
        $data = self::getGoodsClassDataInfo(); // 全部节点数据
        // 获取节点
        $treeConfig = ['id' => 'gc_id', 'pid' => 'gc_parent_id', 'title' => 'gc_name'];
        if(!empty($param['tree_config'])){
            $treeConfig = array_merge($treeConfig,$param['tree_config']);
        }
        $Tree  = Tree::config($treeConfig);
        $menus = $Tree::toList($data);
        foreach ($menus as $menu) {
            if(!empty($id)){
                if($menu['gc_id'] == $id){
                    return $menu;
                }
            }else{
                $result[] = $menu;
            }

        }
        return $result;
    }


    /**
     * 获取所有子节点(取缓存)
     * @param int $pid 父级id
     * @return array
     * @author 仇仇天
     */
    public static function getChilds($pid = 0)
    {
        // 获取所有分类数据(缓存)
        $data = self::getGoodsClassDataInfo();

        // 配置
        $treeConfig = ['id' => 'gc_id', 'pid' => 'gc_parent_id'];

        //设置配置
        $Tree  = Tree::config($treeConfig);

        // 获取所有子分类
        $childsData = $Tree::getChilds($data,$pid);

        return $childsData;
    }

    /**
     * 获取所有子节点id(取缓存)
     * @param int $pid 父级id
     * @return array
     * @author 仇仇天
     */
    public static function getChildsId($pid = 0)
    {
        // 获取所有分类数据(缓存)
        $data = self::getGoodsClassDataInfo();

        // 配置
        $treeConfig = ['id' => 'gc_id', 'pid' => 'gc_parent_id'];

        //设置配置
        $Tree  = Tree::config($treeConfig);

        $dataArr = [];

        // 获取所有子分类id
        $dataArr = $Tree::getChildsId($data,$pid);

        return $dataArr;
    }

    /**
     * 获取子节点(取缓存)
     * @param int $pid 父级id
     * @return array
     * @author 仇仇天
     */
    public static function getChildsData($pid = 0)
    {
        // 获取所有分类数据(缓存)
        $data = self::getGoodsClassDataInfo();

        $dataArr = [];

        foreach ($data as $value){
            if($pid == $value['gc_parent_id']){
                $dataArr[] = $value;
            }
        }

        return $dataArr;
    }

    /**
     * 获取所有父节点(取缓存)
     * @param $id 分类id
     * @return array
     * @author 仇仇天
     */
    public static function getLocation($id)
    {
        $data     = self::getGoodsClassDataInfo(); // 全部节点数据
        $Tree    = Tree::config(['id'=>'gc_id','pid'=>'gc_parent_id','title' => 'gc_name']);
        $location = $Tree::getParents($data, $id); // 获取所有夫级节点
        return $location;
    }

    /**
     * 获取上一级数据(取缓存)
     * @author 仇仇天
     * @param $id
     */
    public static function getLocationInfo($id){
        $info = self::getGoodsClassInfo($id);
        $res = [];
        if(!empty($info)){
            $res = self::getGoodsClassInfo($info['gc_parent_id']);
        }
        return $res;
    }

    /**
     * 获取某个级别所有的数据(取缓存)
     * @param int $leve
     * @return array|mixed|string
     * @author 仇仇天
     */
    public static function getLevelData($level = 0)
    {
        $data    = self::getGoodsClassDataInfo();
        $Tree    = Tree::config(['id'=>'gc_id','pid'=>'gc_parent_id','title' => 'gc_name']);
        $data    = $Tree::toList($data);
        $resData = [];
        if ($level == 0) return $data;
        foreach ($data as $value) {
            if ($value['level'] == $level) {
                $resData[] = $value;
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
        $data = self::where($where)->select();
        if(!empty($data)){
            if(false !== self::where($where)->delete()){
                self::delCache(); // 删除缓存
                foreach ($data as $value){
                    attaDel($value['gc_img']); // 删除图片
                }
                return true;
            }else{
                return false;
            }
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
}
