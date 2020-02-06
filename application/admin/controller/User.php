<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminUser as UserModel;
use app\common\model\AdminRole as RoleModel;
use think\helper\Hash;

/**
 * 用户默认控制器
 * @package app\user\admin
 */
class User extends Admin
{
    /**
     * 用户首页
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 筛选参数设置
            $where = [];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])){

                if ($data['searchCondition'] == 'like'){
                    $where[] = ['a.'.$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                }else{
                    $where[] = ['a.'.$data['searchField'], $data['searchCondition'], "%" . $data['searchKeyword'] . "%"];
                }
            }

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = UserModel::alias('a')
                ->field('a.*,b.name AS rolename')
                ->join('admin_role b', 'b.id=a.role')
                ->where($where)
                ->order('a.id ASC')
                ->paginate($list_rows);

            foreach ($data_list as $key => $value) {
                $data_list[$key]['avatar'] = !empty($value['avatar']) ? attaUrl($value['avatar']) : '';
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('用户管理');

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'id','condition'=>'=', 'default' => true],
            ['title' => '用户名', 'field' => 'username','condition'=>'like', 'default' => false],
            ['title' => '邮箱', 'field' => 'email','condition'=>'like', 'default' => false]
        ]);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('user/add')]);

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('user/delete'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮 启用
        $view->addTopButton('enable', ['url' => url('user/editstatus'), 'query_data' => '{"status":1}']);

        // 设置头部按钮 禁用
        $view->addTopButton('disable', ['url' => url('user/editstatus'), 'query_data' => '{"status":0}']);

        // 设置头部按钮 设置列
        $view->setColumn([
            [
                'field' => 'asdasd',
                'title' => '全选',
                'align' => 'center',
                'checkbox' => true
            ],
            [
                'field' => 'id',
                'title' => 'ID',
                'align' => 'center'
            ],
            [
                'field' => 'avatar',
                'title' => '头像',
                'align' => 'center',
                'show_type' => 'avatar_image',
            ],
            [
                'field' => 'username',
                'title' => '用户名',
                'align' => 'center'
            ],
            [
                'field' => 'nickname',
                'title' => '昵称',
                'align' => 'center',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field' => 'rolename',
                'title' => '角色',
                'align' => 'center'
            ],
            [
                'field' => 'email',
                'title' => '邮箱',
                'align' => 'center',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field' => 'mobile',
                'title' => '手机号',
                'align' => 'center',
                'editable' => [
                    'type' => 'number'
                ]
            ],
            [
                'field' => 'create_time',
                'title' => '创建时间',
                'align' => 'center',
                'show_type' => 'datetime'
            ],
            [
                'field' => 'status',
                'title' => '状态',
                'align' => 'center',
                'editable' => [
                    'type' => 'switch',
                    'config' => ['on_text' => '启用', 'on_value' => 1, 'off_text' => '禁用', 'off_value' => 0]
                ],
                'hide' => <<<javascript
                if(row.id == 1){
                    return '<span class="label label-sm label-danger">不可操作</span>';
                }
javascript
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type' => 'btn',
                'btn' => [
                    [
                        'field' => 'd',
                        'confirm' => '确认删除',
                        'query_jump' => 'ajax',
                        'url' => url('user/delete'),
                        'query_data' => '{"field":["id"],"extentd_field":{"action":"delete"}}',
                        'query_type' => 'post',

                    ],
                    [
                        'field' => 'u',
                        'url' => url('user/edit'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ],
                'peration_hide' => <<<javascript
                    $.each(perationArr,function(i,v){
                        if(v.indexOf('hide_d') > -1){
                            if(row.id == 1){   
                                delete perationArr[i]
                            }
                        }
                    });   
javascript
            ]
        ]);

        // 设置行内编辑地址
        $view->editableUrl(url('user/edit'));

        return $view->fetch();
    }

    /**
     * 新增
     * @author 仇仇天
     * @return mixed
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {

            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'User');

            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);

            // 非超级管理需要验证可选择角色
            if (session('admin_user_info.role') != 1) {
                if ($data['role'] == session('admin_user_info.role')) {
                    $this->error('禁止创建与当前角色同级的用户');
                }
                $role_list = RoleModel::getChildsId(session('admin_user_info.role'));
                if (!in_array($data['role'], $role_list)) {
                    $this->error('权限不足，禁止创建非法角色的用户');
                }
            }

            // 图片处理
            $files = $this->request->file();

            foreach ($files as $file_key => $file) {

                // 创建图片
                $file_info = attaAdd($file, 'admin_avatar');

                // 上传错误
                if (!$file_info['status']) {
                    $this->error($file_info['status']);
                }

                // 设置存储
                $data[$file_key] = $file_info['data']['relative_path_url'];

            }

            if ($user = UserModel::create($data)) {
                // 记录行为
                adminActionLog('admin.user_add');
                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('后台用户 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('user/index'));

        // 角色数据
        $role_list = RoleModel::where('id<>1')->field('id,name')->select();
        $role_list_arr = [];
        foreach ($role_list as $key => $value) {
            $role_list_arr[] = ['title' => $value['name'], 'value' => $value['id']];
        }

        // 表单项
        $form->addFormItems([
            [
                'field'     => 'username',
                'name'      => 'username',
                'form_type' => 'text',
                'title'     => '用户名',
                'tips'      => '必填，可由英文字母、数字组成'
            ],
            [
                'field'     => 'nickname',
                'name'      => 'nickname',
                'form_type' => 'text',
                'title'     => '昵称',
                'tips'      => '可以是中文'
            ],
            [
                'field'     => 'role',
                'name'      => 'role',
                'form_type' => 'select',
                'title'     => '角色',
                'option'    => $role_list_arr,
                'tips'      => '非超级管理员，禁止创建与当前角色同级的用户'
            ],
            [
                'field'     => 'email',
                'name'      => 'email',
                'form_type' => 'text',
                'title'     => '邮箱'
            ],
            [
                'field'     => 'password',
                'name'      => 'password',
                'form_type' => 'password',
                'title'     => '密码',
                'tips'      => "必填，6-20位"
            ],
            [
                'field'     => 'mobile',
                'name'      => 'mobile',
                'form_type' => 'text',
                'title'     => '手机号'
            ],
            [
                'field'     => 'avatar',
                'name'      => 'avatar',
                'form_type' => 'image',
                'title'     => '头像'
            ]
        ]);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     * @param null $id 用户id
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {

            $data = $this->request->post();

            // 行内编辑
            if (!empty($data['extend_field'])) {

                // 禁止修改超级管理员的状态
                if ($data['id'] == 1) {
                    $this->error('禁止修改超级管理员状态');
                }

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                if ($data['extend_field'] != 'status') {
                    // 验证
                    $result = $this->validate($data, 'User.' . $data['extend_field']);
                    // 验证提示报错
                    if (true !== $result) $this->error($result);
                }
                $save_data['id'] = $data['id'];
                $data = $save_data;
            }

            // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'User.update');

                // 验证失败 输出错误信息
                if (true !== $result) $this->error($result);

                // 如果没有填写密码，则不更新密码
                if (empty($data['password'])) {
                    unset($data['password']);
                }else{
                    // 设置密码
                    $data['password'] = UserModel::setPasswordAttr($data['password']);
                }

                // 图片处理
                $files = $this->request->file();
                foreach ($files as $file_key => $file) {

                    // 创建图片
                    $file_info = attaAdd($file, 'adminavatar');

                    // 上传错误
                    if (!$file_info['status']) {
                        $this->error($file_info['status']);
                    }

                    // 设置存储
                    $data[$file_key] = $file_info['data']['relative_path_url'];
                }
            }
            if (UserModel::where(['id' => $data['id']])->update($data)) {
                // 重新设置session
                $userModel = new UserModel();
                $userModel->refreshLoginSession();
                // 记录行为
                adminActionLog('admin.user_edit');
                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = UserModel::alias('a')
            ->field('a.*,b.name AS bname')
            ->join('admin_role b', 'b.id = a.role', 'LEFT')
            ->where('a.id', $id)
            ->find();

        if (empty($info)) $this->error('该用户不存在');

        // 设置头像
        $info['avatar'] = attaUrl($info['avatar']);

        // 删除密码
        unset($info['password']);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('后台用户 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('user/index'));

        // 角色数据
        $role_list = RoleModel::where('id<>1')->field('id,name')->select();
        $role_list_arr = [];
        foreach ($role_list as $key => $value) {
            $role_list_arr[] = ['title' => $value['name'], 'value' => $value['id']];
        }

        // 表单项
        $FormItem = [
            [
                'field' => 'username',
                'name' => 'username',
                'form_type' => 'static',
                'title' => '用户名'
            ],
            [
                'field' => 'nickname',
                'name' => 'nickname',
                'form_type' => 'text',
                'title' => '昵称',
                'tips' => '可以是中文'
            ],
            [
                'field' => 'role',
                'name' => 'role',
                'form_type' => 'select',
                'title' => '角色',
                'option' => $role_list_arr,
                'tips' => '非超级管理员，禁止创建与当前角色同级的用户'
            ],
            [
                'field' => 'email',
                'name' => 'email',
                'form_type' => 'text',
                'title' => '邮箱'
            ],
            [
                'field' => 'password',
                'name' => 'password',
                'form_type' => 'password',
                'title' => '密码',
                'tips' => "必填，6-20位"
            ],
            [
                'field' => 'mobile',
                'name' => 'mobile',
                'form_type' => 'text',
                'title' => '手机号'
            ],
            [
                'field' => 'avatar',
                'name' => 'avatar',
                'form_type' => 'image',
                'title' => '头像'
            ]
        ];

        // 如果是管理员用户
        if ($info['id'] == 1) {
            $FormItem[2] = [
                'field' => 'bname',
                'name' => 'bname',
                'form_type' => 'static',
                'title' => '角色'
            ];
        }
        $form->addFormItems($FormItem);

        // 设置隐藏表单数据
        $hiddenData = [['name' => 'id', 'value' => $id]];
        if ($info['id'] == 1) {
            $hiddenData[] = ['name' => 'role', 'value' => $info['role']];
        }
        $form->setFormHiddenData($hiddenData);

        // 设置表单数据
        $form->setFormdata($info);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 删除用户
     * @author 仇仇天
     * @param array $ids 用户id
     */
    public function delete()
    {
        $data = $this->request->post();

        $where = [];

        // 批量删除
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            if (empty($data['batch_data'])) $this->error('请选择需要删除的数据');
            $ids = [];
            foreach ($data['batch_data'] as $value) {
                $ids[] = $value['id'];
            }
            $where = [['id', 'in', $ids]];
        }

        // 删除
        else {
            if (empty($data['id'])) $this->error('参数错误');
            $where = ['id' => $data['id']];
        }

        if (false !== UserModel::where($where)->delete()) {
            // 记录日志
            adminActionLog('admin.user_delete');

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 状态编辑
     * @author 仇仇天
     */
    public function editStatus()
    {
        // 表单数据
        $data = $this->request->post();

        // 需要修改的数据id
        $ids = [];
        foreach ($data['batch_data'] as $value) {
            $ids[] = $value['id'];
        }
        $where= [['id', 'in', $ids]];

        $result = UserModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {
            adminActionLog('admin.user_edit_status');
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 锁定
     * @author 仇仇天
     */
    public function lock(){

        // 获取用户信息
        $info = UserModel::where(['id'=>session('admin_user_info.uid')])->find();

        // 解锁
        if ($this->request->isAjax()) {

            $data = input();

            // 输入密码
            $inputPassword = trim($data['password']);

            // 系统定义锁定次数
            $lock_count = config('lock_count');

            // 系统定义锁定时限
            $lock_time = config('lock_time');

            // 已锁定
            if($info['lock_count'] >= $lock_count){
                // 判断时间是否过时
                if(time() - $info['lock_time'] > $lock_time){
                    // 取消锁定
                    UserModel::where(['id'=>$info['id']])
                        ->update(['lock_count'=>0,'lock_time'=>0,'lock_status'=>0]);
                }else{
                    return $this->error('密码错误次数过多已锁定，请五分钟后解锁');
                }
            }

            // 验证密码
            if (!Hash::check((string)$inputPassword, $info['password'])){
                // 记录锁定
                UserModel::where(['id'=>$info['id']])
                    ->update(['lock_count'=>$info['lock_count']+1,'lock_time'=>time(),'lock_status'=>1]);
                $this->error('解锁密码错误');
            }
            // 取消锁定
            UserModel::where(['username'=>session('admin_user_info.username')])
                ->update(['lock_count'=>0,'lock_time'=>0,'lock_status'=>0]);
            $this->success('解锁成功', 'index/index');

        }

        // 锁定
        UserModel::where(['username'=>session('admin_user_info.username')])->update(['lock_status'=>1]);

        return $this->fetch();
    }

}
