<?php

namespace app\admin\controller;

use app\common\controller\Common;
use app\common\model\AdminUser as AdminUserModel;
use think\facade\Hook;
use think\captcha\Captcha;
use think\facade\Session;

/**
 * 公共控制器
 * Class Publics
 * @package app\admin\controller
 */
class Publics extends Common
{
    /**
     * 用户登录
     * @return mixed
     * @author 仇仇天
     */
    public function signin()
    {
        // 是否提交
        if ($this->request->isPost()) {

            // 获取提交信息
            $data = $this->request->post();

            // 记住7天登录
            $rememberme = isset($data['remember']) ? true : false;

            // 登录钩子
            $hook_result = Hook::listen('admin_signin', $data);

            // 钩子报错
            if (!empty($hook_result) && true !== $hook_result[0]) $this->error($hook_result[0]);

            // 验证数据
            $result = $this->validate($data, 'User.signin');

            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);


            // 获取验证码
//            $captcha = $this->request->post('captcha', '');

            // 验证码是否为空
//            $captcha == '' && $this->error('请输入验证码');

            // 验证失败
//            if (!captcha_check($captcha)) $this->error('验证码错误或失效');

            // 后台用户model对象
            $UserModel = new AdminUserModel;

            // 检测登录
            $uid = $UserModel->login($data['username'], $data['password'], $rememberme);

            if ($uid) {

                // 记录行为
                action_log('admin.user_signin');

                // 跳转
                $this->success('登录成功', url('admin/index/index'));

            } else {
                $this->error($UserModel->getError());
            }
        } // 展示页面
        else {
            if (adminIsSignin()) {
                $this->redirect('admin/index/index');
            } else {
                return $this->fetch();
            }
        }
    }

    /**
     * 退出登录
     * @author 仇仇天
     */
    public function signout()
    {
        $hook_result = Hook::listen('signout_sso');
        if (!empty($hook_result) && true !== $hook_result[0]) {
            if (isset($hook_result[0]['url'])) {
                $this->redirect($hook_result[0]['url']);
            }
            if (isset($hook_result[0]['error'])) {
                $this->error($hook_result[0]['error']);
            }
        }
        session(null);
        cookie('admin_uid', null);
        cookie('admin_signin_token', null);
        $this->redirect('signin');
    }

    /**
     *  验证码
     * @return mixed
     */
    public function signinVerify()
    {
        // 验证码配置 255,255,255
        $config = [
            'bg'       => ['255', '255', '255'],
            // 验证码字体大小
            'fontSize' => 15,
            // 验证码位数
            'length'   => 4,
            // 设置宽度
//            'imageW'   => 135,
            // 关闭验证码杂点
            'useNoise' => false,
            // 是否画混淆曲线
            'useCurve' => false,
            // 设置验证码字符为纯数字
            'codeSet'  => '0123456789'
        ];

        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
