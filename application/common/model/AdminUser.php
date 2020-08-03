<?php

namespace app\common\model;

use app\admin\model\AdminRole as AdminRoleModel;

use think\Model;
use think\helper\Hash;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class AdminUser extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_user';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 对密码进行加密
     * @author 仇仇天
     * @return mixed
     */
    public static function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    /**
     * 用户登录
     * @author 仇仇天
     * @param string $username 用户名
     * @param string $password 密码
     * @param bool $rememberme 记住登录 true=记住 false=相反
     * @return bool|mixed
     */
    public function login($username = '', $password = '', $rememberme = false)
    {
        // 用户名
        $username = trim($username);

        // 密码
        $password = trim($password);

        // 查找用户
        $user = $this::get(['status' => 1, 'username' => $username]);

        if (!$user) {

            // 用户不存在或被禁用
            $this->error = '账号或密码错误！';

        } else {

            // 检查是否分配用户组
            if ($user['role'] == 0) {
                // 禁止访问，原因：未分配角色
                $this->error = '账号或密码错误！';
                return false;
            }

            // 检测密码是否正确
            if (!Hash::check((string)$password, $user['password'])) {
                $this->error = '账号或者密码错误！';
            } else {

                // 用户id
                $uid = $user['id'];

                // 登录时间
                $user['last_login_time'] = request()->time();

                // 登录ip
                $user['last_login_ip'] = request()->ip();

                if ($user->save()) {
                    // 设置自动登录
                    return $this->autoLogin($this::get($uid), $rememberme);
                } else {
                    // 更新登录信息失败
                    $this->error = '账号或者密码错误！';
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 自动登录
     * @author 仇仇天
     * @param $user 用户对象
     * @param bool $rememberme 是否记住登录，默认7天
     * @return bool
     */
    public function autoLogin($user, $rememberme = false)
    {
        // 记录登录SESSION和COOKIES 信息
        $user_info = array(
            // 用户id
            'uid'             => $user->id,
            // 角色id
            'role'            => $user->role,
            // 角色名称
            'role_name'       => AdminRoleModel::where('id', $user->role)->value('name'),
            // 头像
            'avatar'          => $user->avatar,
            // 账号
            'username'        => $user->username,
            // 昵称
            'nickname'        => $user->nickname,
            // 登录时间
            'last_login_time' => $user->last_login_time,
            // 登录ip
            'last_login_ip'   => get_client_ip(),
        );

        // 存储用户信息
        session('admin_user_info', $user_info);

        // 生成用户认证
        session('admin_user_auth_sign', data_auth_sign($user_info));

        // 角色非超级管理员
        if ($user->role > 2) {

            // 角色菜单权限
            $menu_auth = AdminRoleModel::where('id', $user->role )->value('menu_auth');

            if (!$menu_auth) {

                // 置空后台登录用户信息
                session('admin_user_info', null);

                // 置空后台用户登录凭证
                session('admin_user_auth_sign', null);

                // 未分配任何节点权限
                $this->error = '未分配任何节点权限！';

                return false;
            }
        }

        // 记住登录
        if ($rememberme) {

            $signin_token = $user->username . $user->id . $user->last_login_time;

            cookie('admin_uid', $user->id, 24 * 3600 * 7);

            cookie('admin_signin_token', data_auth_sign($signin_token), 24 * 3600 * 7);

        }

        return $user->id;
    }

    /**
     * 刷新登录session
     * @author 仇仇天
     */
    public function refreshLoginSession()
    {
        $uid = session('admin_user_info.uid');
        $this->autoLogin($this::get($uid));
    }
}
