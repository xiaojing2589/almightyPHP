<?php

/**
 * 判断是否登录
 * @return mixed
 * @author 仇仇天
 */
function adminIsSignin()
{
    $user = session('admin_user_info');
    if (empty($user)) {
        // 判断是否记住登录
        if (cookie('?admin_uid') && cookie('?admin_signin_token')) {
            $UserModel = new AdminUserModel();
            $user      = $UserModel::get(cookie('admin_uid'));
            if ($user) {
                $signin_token = data_auth_sign($user['username'] . $user['id'] . $user['last_login_time']);
                if (cookie('admin_signin_token') == $signin_token) {
                    // 自动登录
                    $UserModel->autoLogin($user);
                    return $user['id'];
                }
            }
        }
        return 0;
    } else {
        return session('admin_user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测是否锁定
 * @author 仇仇天
 */
function locksTatus()
{

    // 状态
    $resStatus = 0;

    // 获取后台登录用户信息
    $userInfo = session('admin_user_info');

    if (!empty($userInfo)) {
        // 用户锁定状态
        $resStatus = app\common\model\AdminUser::where(['id' => $userInfo['uid']])->value('lock_status');
    }

    return $resStatus;
}

/**
 * 根据用户ID获取用户昵称
 * @param int $uid 用户ID
 * @return mixed|string
 * @author 仇仇天
 */
function adminGetNickname($uid = 0)
{
    static $list;

    // 获取当前登录用户名
    if (!($uid && is_numeric($uid))) {
        return session('user_auth.username');
    }

    // 获取缓存数据
    if (empty($list)) {
        $list = cache('sys_user_nickname_list');
    }

    // 查找用户信息
    $key = "u{$uid}";
    if (isset($list[$key])) {
        // 已缓存，直接使用
        $name = $list[$key];
    } else {
        // 调用接口获取用户信息
        $info = model('user/user')->field('nickname')->find($uid);
        if ($info !== false && $info['nickname']) {
            $nickname = $info['nickname'];
            $name     = $list[$key] = $nickname;
            // 缓存用户
            $count = count($list);
            $max   = config('user_max_cache');
            while ($count-- > $max) {
                array_shift($list);
            }
            cache('sys_user_nickname_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action
 * @author  仇仇天
 */
function adminActionLog($action = null)
{

    // 后台用户信息
    $adminUserInfo = session('admin_user_info');

    action_log($action, ['username' => $adminUserInfo['username'], 'userid' => $adminUserInfo['uid']]);
}
