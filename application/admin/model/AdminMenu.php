<?php

namespace app\admin\model;

use app\admin\model\AdminRole as AdminRoleModel;

use think\Model;
use util\Tree;

/**
 * 节点模型
 * @package app\common\model
 */
class AdminMenu extends Model
{

    // 所有以“mark”为下标的缓存
    protected static $cacheName = 'admin_menu_data';

    // 所有以“url_value”为下标的缓存
    protected static $cacheValuesName = 'admin_menu_value_data';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有节点数据
     * @author 仇仇天
     */
    public static function getMenuAll(){
        return rcache(self::$cacheName,'',['module'=>'admin']);
    }

    /**
     * 获取所有开启节点数据(缓存)
     * @author 仇仇天
     */
    public static function getMenuOpenAll(){
        // 获取所有数据
        $data = self::getMenuAll();

        // 所有顶部菜单节点数据
        $topMenu = [];
        foreach ($data as $key=>$value){
            if($value['status'] == 1){
                $topMenu[$key] = $value;
            }
        }
        return $topMenu;
    }

    /**
     * 获取所有管理员可访问的开启节点数据(缓存)
     * @author 仇仇天
     */
    public static function getAdminExceptMenuOpenAll(){

        // 当前用户的角色id
        $roleId = session('admin_user_info.role');

        // 所有开启的节点
        $data = self::getMenuOpenAll();

        // 管理员不能访问节点
        $adminExceptMenuNode = config('system.admin_except_menu_node');
        $adminExceptMenuNodeNode = $adminExceptMenuNode['node'];

        if($roleId == 2){
            $resData = [];
            foreach ($data as $value){
                if(!in_array($value['mark'],$adminExceptMenuNodeNode)){
                    $resData[] = $value;
                }
            }
            return $resData;
        }else{
            return $data;
        }
    }

    /**
     * 获取所有以“url_value”节点数据(缓存)
     * @author 仇仇天
     */
    public static function getMenuValuesAll(){
        return rcache(self::$cacheValuesName,'',['module'=>'admin']);
    }

    /**
     * 获取所有以“url_value”开启节点数据(缓存)
     * @author 仇仇天
     */
    public static function getMenuValuesOpenAll(){

        // 获取所有数据
        $data = self::getMenuValuesAll();

        // 所有顶部菜单节点数据
        $topMenu = [];
        foreach ($data as $key=>$value){
            if($value['status'] == 1){
                $topMenu[$key] = $value;
            }
        }
        return $topMenu;
    }

    /**
     * 根据 id 查询父节点信息(缓存)
     * @author 仇仇天
     * @param $id  节点id
     */
    public static function getByIdParents($id){

        if(empty($id)) return [];

        // 全部节点数据
        $adminMenuOpenData = self::getMenuOpenAll();

        // 根据子节点返回所有父节点
        $parentsLocation = Tree::getParents($adminMenuOpenData, $id);

        return $parentsLocation;
    }

    /**
     * 获取所有顶部节点 缓存
     * @author 仇仇天
     * @return array
     */
    public static function getTopMenu()
    {
        // 全部节点数据
        $adminMenuData = self::getAdminExceptMenuOpenAll();

        // 所有顶部菜单节点数据
        $topMenu   = [];
        foreach ($adminMenuData as $key=>$value){
            if($value['pid'] == 0){
                $topMenu[$key] = $value;
            }
        }
        return $topMenu;
    }

    /**
     * 根据角色id获取顶部认证菜单节点
     * @author 仇仇天
     * @param $roleId 角色id
     */
    public static function getByRoleIdAuthTopMenu($roleId){

        // 获取所有顶部节点菜单
        $topMenuData = self::getTopMenu();

        // 最终的权限数据
        $resTopMenu = [];

        if($roleId > 2){
            // 非超级管理员角色
            // 获取相应角色id 权限
            $admin_role_menu_auth = AdminRoleModel::getByAdminRoleIdAuth($roleId);
            if(empty($admin_role_menu_auth)){
                return $resTopMenu;
            }
            $admin_role_menu_auth = json_decode($admin_role_menu_auth);
            foreach ($topMenuData as $topMmenuKey=>$topMmenuVlue){
                if(in_array($topMmenuVlue['mark'], $admin_role_menu_auth)){
                    $resTopMenu[$topMmenuKey] = $topMmenuVlue;
                }
            }
        }else{
            if($roleId == 1){
                // 超级管理员角色
                $resTopMenu = $topMenuData;
            }else{
                // 管理员
                $adminExceptMenuNode = config('system.admin_except_menu_node');
                $adminExceptMenuNodeNode = $adminExceptMenuNode['node'];
                foreach ($topMenuData as $value){
                    if(!in_array($value['mark'],$adminExceptMenuNodeNode)){
                        $resTopMenu[] = $value;
                    }
                }
            }
        }

        return $resTopMenu;

    }

    /**
     * 获取侧栏节点
     * @author 仇仇天
     * @param string $mark  标识
     * @param array $param  扩展参数
     *              module      模块
     *              controller  控制器
     *              action      方法
     * @return array
     */
    public static function getSidebarMenu($mark='',$param=[])
    {
        // 全部节点数据
        $adminMenuData = self::getAdminExceptMenuOpenAll();

        // 当前访问的模块
        $model = empty($param['module']) ? request()->module() : $param['module'] ;

        // 当前访问控制器
        $controller = empty($param['controller']) ? request()->controller() : $param['controller'];

        // 当前访问操作
        $action = empty($param['action']) ? request()->action() : $param['action'];

        // 当前节点信息
        $current_menu = strtolower($model.'/'.$controller.'/'.$action);

        $current_menu_info = [];

        foreach ($adminMenuData as $key=>$value){
            if($mark != ''){
                if($value['mark'] == $mark){
                    $current_menu_info = $value;
                }
            }else{
                if(str_replace('_','',$value['url_value']) == $current_menu){
                    $current_menu_info = $value;
                }
            }
        }

        $location = self::getLocation($current_menu_info['id']);
        $menus = Tree::toLayer($adminMenuData,$location[0]['id']);
        return $menus;
    }

    /**
     * 获取指定节点ID的位置
     * @author 仇仇天
     * @param string $id 节点id，如果没有指定，则取当前节点id
     * @return array
     */
    public static function getLocation($id = '')
    {
        // 当前访问的模块
        $model = request()->module();

        // 当前访问控制器
        $controller = request()->controller();

        // 当前访问操作
        $action = request()->action();

        // 全部节点数据
        $adminMenuData = self::getAdminExceptMenuOpenAll();

        // 当前节点信息
        $current_menu = strtolower($model.'/'.$controller.'/'.$action);

        $current_menu_info = [];

        foreach ($adminMenuData as $key=>$value){
            if(!empty($id)){
                if($value['id'] == $id){
                    $current_menu_info = $value;
                }
            }else{
                if(str_replace('_','',$value['url_value']) == $current_menu){
                    $current_menu_info = $value;
                }
            }
        }

        // 根据子节点返回所有父节点
        $location = Tree::getParents($adminMenuData, $current_menu_info['id']);

        return $location;
    }


    /**
     * 获取树形节点
     * @author 仇仇天
     * @param int $id 需要隐藏的节点id
     * @param string $default 默认第一个节点项，默认为“顶级节点”，如果为false则不显示，也可传入其他名称
     * @param string $module 模型名
     * @return mixed
     */
    public static function getMenuTree($id = 0, $default = '', $module = '')
    {
        $result[0] = '顶级节点';
        $where = [
            ['status', 'egt', 0]
        ];
        if ($module != '') {
            $where[] = ['module', '=', $module];
        }

        // 排除指定节点及其子节点
        if ($id !== 0) {
            $hide_ids = array_merge([$id], self::getChildsId($id));
            $where[] = ['id', 'not in', $hide_ids];
        }

        // 获取节点
        $menus = Tree::toList(self::where($where)->order('pid,id')->column('id,pid,title'));
        foreach ($menus as $menu) {
            $result[$menu['id']] = $menu['title_display'];
        }

        // 设置默认节点项标题
        if ($default != '') {
            $result[0] = $default;
        }

        // 隐藏默认节点项
        if ($default === false) {
            unset($result[0]);
        }

        return $result;
    }

    /**
     * 获取所有子节点id
     * @author 仇仇天
     * @param int $pid 父级id
     * @return array
     */
    public static function getChildsId($pid = 0)
    {
        $ids = self::where('pid', $pid)->column('id');
        foreach ($ids as $value) {
            $ids = array_merge($ids, self::getChildsId($value));
        }
        return $ids;
    }

    /**
     * 获取所有父节点id
     * @author 仇仇天
     * @param int $id 节点id
     * @return array
     *
     */
    public static function getParentsId($id = 0)
    {
        $pid = self::where('id', $id)->value('pid');
        $pids = [];
        if ($pid != 0) {
            $pids[] = $pid;
            $pids = array_merge($pids, self::getParentsId($pid));
        }
        return $pids;
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
        self::delValuesCache();
    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delValuesCache()
    {
        dkcache(self::$cacheValuesName);
    }

    /**
     * 根据分组获取节点
     * @author 仇仇天
     * @param string $group 分组名称
     * @param bool|string $fields 要返回的字段
     * @param array $map 查找条件
     * @return array
     */
    public static function getMenusByGroup($group = '', $fields = true, $map = [])
    {
        $map['module'] = $group;
        return self::where($map)->order('sort,id')->column($fields, 'id');
    }











    // 将节点url转为小写
    public function setUrlValueAttr($value)
    {
        return strtolower(trim($value));
    }

    /**
     * @describe 递归修改所属模型
     * @param int $id 父级节点id
     * @param string $module 模型名称
     * @return bool
     * @author 仇仇天
     */
    public static function changeModule($id = 0, $module = '')
    {
        if ($id > 0) {
            $ids = self::where('pid', $id)->column('id');
            if ($ids) {
                foreach ($ids as $id) {
                    self::where('id', $id)->setField('module', $module);
                    self::changeModule($id, $module);
                }
            }
        }
        return true;
    }

    /**
     * @describe 获取节点分组
     * @return array
     * @author 仇仇天
     */
    public static function getGroup()
    {
        $map['status'] = 1;
        $map['pid'] = 0;
        $menus = self::where($map)->order('id,sort')->column('module,title');
        return $menus;
    }

    /**
     * @describe 根据节点id获取上下级的所有id
     * @param int $id 节点id
     * @return array
     * @author 仇仇天
     */
    public static function getLinkIds($id = 0)
    {
        $childs = self::getChildsId($id);
        $parents = self::getParentsId($id);
        return array_merge((array)(int)$id, $childs, $parents);
    }

}
