<?php

namespace app\common\model;

use app\common\model\AdminMenu as AdminMenuModel;
use think\Model;
use util\Tree;


/**
 * 角色模型
 * @package app\admin\model
 */
class AdminRole extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_role';

    protected static $cacheName = 'admin_role_menu_auth'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 写入时，将菜单id转成json格式
    public function setMenuAuthAttr($value)
    {
        return json_encode($value);
    }

    // 读取时，将菜单id转为数组
    public function getMenuAuthAttr($value)
    {
        return json_decode($value, true);
    }

    /**
     * 获取树形角色
     * @param null $id 需要隐藏的角色id
     * @param string $default 默认第一个菜单项，默认为“顶级角色”，如果为false则不显示，也可传入其他名称
     * @param null $filter 角色id，过滤显示指定角色及其子角色
     * @return mixed
     * @author 仇仇天
     */
    public static function getTree($id = null, $default = '', $filter = null)
    {
        $result[0] = '顶级角色';
        $where     = [
            ['status', '=', 1]
        ];

        // 排除指定菜单及其子菜单
        $hide_ids = [];
        if ($id !== null) {
            $hide_ids = array_merge([$id], self::getChildsId($id));
            $hide_ids = ['id', 'not in', $hide_ids];
        }

        // 过滤显示指定角色及其子角色
        if ($filter !== null) {
            $show_ids = array_merge([$filter], self::getChildsId($filter));

            if (!empty($hide_ids)) {
                $ids     = array_diff($show_ids, $hide_ids);
                $where[] = ['id', 'in', $ids];
            } else {
                $where[] = ['id', 'in', $show_ids];
            }
        } else {
            if (!empty($hide_ids)) {
                $where[] = $hide_ids;
            }
        }

        // 获取菜单
        $roles = self::where($where)->column('id,pid,name');
        $pid   = self::where($where)->order('pid')->value('pid');
        $roles = Tree::config(['title' => 'name'])->toList($roles, $pid);
        foreach ($roles as $role) {
            $result[$role['id']] = $role['title_display'];
        }

        // 设置默认菜单项标题
        if ($default != '') {
            $result[0] = $default;
        }

        // 隐藏默认菜单项
        if ($default === false) {
            unset($result[0]);
        }
        return $result;
    }

    /**
     * 获取所有子角色id
     * @param string $pid 父级id
     * @return array
     * @author 仇仇天
     */
    public static function getChildsId($pid = '')
    {
        $ids = self::where('pid', $pid)->column('id');
        foreach ($ids as $value) {
            $ids = array_merge($ids, self::getChildsId($value));
        }
        return $ids;
    }

    /**
     * 检查访问权限
     * @param int $id 需要检查的节点ID，默认检查当前操作节点
     * @param bool $url 是否为节点url，默认为节点id
     * @return bool
     * @author 仇仇天
     */
    public static function checkAuth($id = 0)
    {

        $role = session('admin_user_info.role'); // 当前用户的角色id

        // id为1的是超级管理员，或者角色为1的，拥有最高权限
        if (session('admin_user_info.uid') == '1' || $role == '1') {
            return true;
        }

        $menu = AdminMenuModel::getStatusMenu(); // 与所有开启节点菜单

        $admin_role_menu_auth = rcache('admin_role_menu_auth', $role); // 获取角色权限

        if (empty($admin_role_menu_auth)) return false;

        $admin_role_menu_auth = json_decode($admin_role_menu_auth);

        $model = request()->module(); // 当前访问的模块

        $controller = request()->controller(); // 当前访问控制器

        $action = request()->action(); // 当前访问操作

        $current_menu = strtolower($model . '/' . $controller . '/' . $action); // 当前节点信息

        $current_menu_info = [];

        foreach ($menu as $key => $value) {
            if (!empty($id)) {
                if ($value['id'] == $id) {
                    $current_menu_info = $value;
                }
            } else {
                if ($value['url_value'] == $current_menu) {
                    $current_menu_info = $value;
                }
            }
        }
        if (in_array($current_menu_info['mark'], $admin_role_menu_auth)) {
            return true;
        }
        return false;
    }

    /**
     * 读取当前角色权限
     * @return mixed
     * @author 仇仇天
     */
    public static function roleAuth()
    {
        $menu = AdminMenuModel::getStatusMenu(); // 与所有开启节点菜单

        $role = session('admin_user_info.role'); // 当前用户的角色id

        $role = 2;

        $admin_role_menu_auth = rcache('admin_role_menu_auth', $role);
        predBUG($admin_role_menu_auth);
        $roleMenuAuth = [];

        if (!empty($admin_role_menu_auth)) {
            foreach ($menu as $key => $value) {
                if (in_array($value['mark'], $admin_role_menu_auth)) {
                    $roleMenuAuth[] = $value['mark'];
                }
            }
        }

        return $roleMenuAuth;
    }

    /**
     * 根据节点id获取所有角色id和权限
     * @param string $menu_id 节点id
     * @param bool $menu_auth 是否返回所有节点权限
     * @return array
     * @author 仇仇天
     */
    public static function getRoleWithMenu($menu_id = '', $menu_auth = false)
    {
        if ($menu_auth) {
            return self::where('menu_auth', 'like', '%"' . $menu_id . '"%')->column('id,menu_auth');
        } else {
            return self::where('menu_auth', 'like', '%"' . $menu_id . '"%')->column('id');
        }
    }

    /**
     * 根据角色id获取权限
     * @param array $role 角色id
     * @return array
     * @author 仇仇天
     */
    public static function getAuthWithRole($role = [])
    {
        return self::where('id', 'in', $role)->column('id,menu_auth');
    }

    /**
     * 重设权限
     * @param null $pid 父级id
     * @param array $new_auth 新权限
     * @author 仇仇天
     */
    public static function resetAuth($pid = null, $new_auth = [])
    {
        if ($pid !== null) {
            $data = self::where('pid', $pid)->column('id,menu_auth');
            foreach ($data as $id => $menu_auth) {
                $menu_auth = json_decode($menu_auth, true);
                $menu_auth = json_encode(array_intersect($menu_auth, $new_auth));
                self::where('id', $id)->setField('menu_auth', $menu_auth);
                self::resetAuth($id, $new_auth);
            }
        }
    }

    /**
     * 删除（包括重置缓存）
     * @param array $where 条件
     * @throws \Exception
     * @author 仇仇天
     */
    public static function del($where = [])
    {
        $data = self::where($where)->select();

        if (false !== self::where($where)->delete()) {
            // 删除缓存
            foreach ($data as $value) {
                self::delCache($value['id']);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delCache($id)
    {
        dkcache(self::$cacheName, $id);
    }
}
