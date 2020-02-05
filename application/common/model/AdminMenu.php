<?php

namespace app\common\model;


use think\Model;
use util\Tree;

/**
 * 节点模型
 * @package app\common\model
 */
class AdminMenu extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__ADMIN_MENU__';

    protected static $cacheName = 'admin_menu'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

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
     * @describe 获取树形节点
     * @param int $id 需要隐藏的节点id
     * @param string $default 默认第一个节点项，默认为“顶级节点”，如果为false则不显示，也可传入其他名称
     * @param string $module 模型名
     * @return mixed
     * @author 仇仇天
     */
    public static function getMenuTree($id = 0, $default = '', $module = '')
    {
        $result[0] = '顶级节点';
        $where     = [
            ['status', 'egt', 0]
        ];
        if ($module != '') {
            $where[] = ['module', '=', $module];
        }

        // 排除指定节点及其子节点
        if ($id !== 0) {
            $hide_ids = array_merge([$id], self::getChildsId($id));
            $where[]  = ['id', 'not in', $hide_ids];
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
     * @describe 获取顶部节点
     * @param string $role_id 角色id
     * @param string $cache_tag 缓存标签
     * @return array
     * @author 仇仇天
     */
    public static function getTopMenu($role_id)
    {

        $adminMenuData = self::getStatusMenu(); // 全部节点数据
        $topMenu       = []; // 所有顶部菜单节点数据
        foreach ($adminMenuData as $key => $value) {
            if ($value['pid'] == 0) {
                $topMenu[$key] = $value;
            }
        }

        $resTopMenu = []; // 最终的权限数据

        // 非超级管理员角色
        if ($role_id != 1) {
            $admin_role_menu_auth = rcache('admin_role_menu_auth', $role_id); // 获取相应角色id 权限
            if (empty($admin_role_menu_auth)) return $resTopMenu;
            $admin_role_menu_auth = json_decode($admin_role_menu_auth);
            foreach ($topMenu as $topMmenuKey => $topMmenuVlue) {
                if (in_array($topMmenuVlue['mark'], $admin_role_menu_auth)) {
                    $resTopMenu[$topMmenuKey] = $topMmenuVlue;
                }
            }
        } // 超级管理员角色
        else {
            $resTopMenu = $topMenu;
        }


        return $resTopMenu;
    }

    /**
     * @describe 获取侧栏节点
     * @param string $module 模块名
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @return array|mixed
     * @author 仇仇天
     */
    public static function getSidebarMenu($mark = '', $param = [])
    {
        $adminMenuData = self::getStatusMenu(); // 全部节点数据

        $model = empty($param['module']) ? request()->module() : $param['module'];  // 当前访问的模块

        $controller = empty($param['controller']) ? request()->controller() : $param['controller']; // 当前访问控制器

        $action = empty($param['action']) ? request()->action() : $param['action']; // 当前访问操作

        $current_menu = strtolower($model . '/' . $controller . '/' . $action); // 当前节点信息

        $current_menu_info = [];

        foreach ($adminMenuData as $key => $value) {
            if ($mark != '') {
                if ($value['mark'] == $mark) {
                    $current_menu_info = $value;
                }
            } else {
//                echo str_replace('_','',$value['url_value']).'</br>';
                if (str_replace('_', '', $value['url_value']) == $current_menu) {
                    $current_menu_info = $value;
                }
            }
        }

        $location = self::getLocation($current_menu_info['id']);

        $menus = Tree::toLayer($adminMenuData, $location[0]['id']);

        return $menus;
    }

    /**
     * @describe 获取状态开启所有菜单
     * @author 仇仇天
     */
    public static function getStatusMenu()
    {
        $adminMenuData = rcache('admin_menu'); // 全部节点数据
        $topMenu       = []; // 所有顶部菜单节点数据
        foreach ($adminMenuData as $key => $value) {
            if ($value['status'] == 1) {
                $topMenu[$key] = $value;
            }
        }
        return $topMenu;
    }

    /**
     * @describe 获取指定节点ID的位置
     * @param string $id 节点id，如果没有指定，则取当前节点id
     * @return array
     * @author 仇仇天
     */
    public static function getLocation($id = '')
    {
        $model = request()->module(); // 当前访问的模块

        $controller = request()->controller(); // 当前访问控制器

        $action = request()->action(); // 当前访问操作

        $adminMenuData = self::getStatusMenu(); // 全部节点数据

        $current_menu = strtolower($model . '/' . $controller . '/' . $action); // 当前节点信息

        $current_menu_info = [];

        foreach ($adminMenuData as $key => $value) {
            if (!empty($id)) {
                if ($value['id'] == $id) {
                    $current_menu_info = $value;
                }
            } else {
                if (str_replace('_', '', $value['url_value']) == $current_menu) {
                    $current_menu_info = $value;
                }
            }
        }

        $location = Tree::getParents($adminMenuData, $current_menu_info['id']); // 获取所有夫级节点

        return $location;
    }


    /**
     * @describe 根据分组获取节点
     * @param string $group 分组名称
     * @param bool|string $fields 要返回的字段
     * @param array $map 查找条件
     * @return array
     * @author 仇仇天
     */
    public static function getMenusByGroup($group = '', $fields = true, $map = [])
    {
        $map['module'] = $group;
        return self::where($map)->order('sort,id')->column($fields, 'id');
    }

    /**
     * @describe 获取节点分组
     * @return array
     * @author 仇仇天
     */
    public static function getGroup()
    {
        $map['status'] = 1;
        $map['pid']    = 0;
        $menus         = self::where($map)->order('id,sort')->column('module,title');
        return $menus;
    }

    /**
     * @describe 获取所有子节点id
     * @param int $pid 父级id
     * @return array
     * @author 仇仇天
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
     * @describe 获取所有父节点id
     * @param int $id 节点id
     * @return array
     * @author 仇仇天
     */
    public static function getParentsId($id = 0)
    {
        $pid  = self::where('id', $id)->value('pid');
        $pids = [];
        if ($pid != 0) {
            $pids[] = $pid;
            $pids   = array_merge($pids, self::getParentsId($pid));
        }
        return $pids;
    }

    /**
     * @describe 根据节点id获取上下级的所有id
     * @param int $id 节点id
     * @return array
     * @author 仇仇天
     */
    public static function getLinkIds($id = 0)
    {
        $childs  = self::getChildsId($id);
        $parents = self::getParentsId($id);
        return array_merge((array)(int)$id, $childs, $parents);
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
