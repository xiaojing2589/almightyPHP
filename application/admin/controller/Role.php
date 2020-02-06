<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminRole as RoleModel;
use app\common\model\AdminMenu as MenuModel;

/**
 * 角色控制器
 * @package app\admin\controller
 */
class Role extends Admin
{
    /**
     * 角色列表页
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        // 设置页面标题
        $view->setPageTitle('角色管理');

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'id','condition'=>'=', 'default' => true],
            ['title' => '角色名称', 'field' => 'name','condition'=>'like', 'default' => false]
        ]);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('role/add')]); // 新增

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('role/delete'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮 启用
        $view->addTopButton('enable', ['url' => url('role/editstatus'), 'query_data' => '{"status":1}']);

        // 设置头部按钮 禁用
        $view->addTopButton('disable', ['url' => url('role/editstatus'), 'query_data' => '{"status":0}']);

        // 设置列
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
                'field' => 'name',
                'title' => '角色名称',
                'align' => 'center',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field' => 'create_time',
                'title' => '创建时间',
                'align' => 'center',
            ],
            [
                'field' => 'description',
                'title' => '描述',
                'align' => 'center',
                'editable' => [
                    'type' => 'textarea'
                ]
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
                    return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">不可操作</span>';
                }
javascript
            ],
            [
                'field' => 'sort',
                'title' => '排序',
                'align' => 'center',
                'editable' => [
                    'type' => 'number'
                ]
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
                        'url' => url('role/delete'),
                        'query_data' => '{"field":["id"],"extentd_field":{"action":"delete"}}',
                        'query_type' => 'post'
                    ],
                    [
                        'field' => 'u',
                        'url' => url('role/edit'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ],
                'hide' => <<<javascript
                if(row.id == -3){
                    return '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">不可操作，模块信息不完整</span>';
                }
javascript
            ]
        ]);

        // 设置行内编辑地址
        $view->editableUrl(url('role/edit'));

        if ($this->request->isAjax()) {

            // 筛选参数设置
            $map = [];

            // 筛选参数
            $search_field = input('param.searchField/s', '', 'trim'); // 关键词搜索字段名

            $keyword = input('param.searchKeyword/s', '', 'trim'); // 搜索关键词

            // 普通搜索筛选
            if ($search_field != '' && $keyword !== '') $map[] = [$search_field, 'like', "%" . $keyword . "%"];

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = RoleModel::where($map)->order('id ASC')->paginate($list_rows);

            foreach ($data_list as $key => $value) {
                $data_list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 渲染模板
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
            $result = $this->validate($data, 'Role');

            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);

            // 添加数据
            if (false !== RoleModel::create($data)) {

                // 记录行为
                adminActionLog('admin.role_add');

                $this->success('新增成功', url('role/index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('后台角色 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('role/index'));

        // 设置 提交地址
        $form->setFormUrl(url('role/add'));

        // 模块数据
        $system_module = MenuModel::where('pid', 0)->column('id,title');
        $system_module_arr = [];
        foreach ($system_module as $key => $value) {
            $system_module_arr[] = ['title' => $value, 'value' => $key];
        }

        // 表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '角色名称',
            ],
            [
                'field'     => 'role',
                'name'      => 'role',
                'form_type' => 'select',
                'title'     => '默认模块',
                'option'    => $system_module_arr,
                'tips'      => '该角色登录后，默认跳转的模块。注意，该角色必须有该模块的节点访问权限。'
            ],
            [
                'field'     => 'description',
                'name'      => 'description',
                'form_type' => 'textarea',
                'title'     => '角色描述'
            ],
            [
                'field'     => 'sort',
                'name'      => 'sort',
                'value'     => 0,
                'form_type' => 'number',
                'title'     => '排序',
                'tips'      => '数字越低排序越靠前'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     * @param null $id 角色id
     * @return mixed
     */
    public function edit($id = null)
    {
        $roleId = session('admin_user_info.role');

        if ($id === null) $this->error('缺少参数');

        if ($id == 1 && $roleId != 1) $this->error('超级管理员不可修改');

        // 保存数据
        if ($this->request->isPost()) {

            $data = $this->request->post();

            // 行内编辑
            if (!empty($data['extend_field'])) {

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'Role.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);

            }

            // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'Role');

                // 验证失败 输出错误信息
                if (true !== $result) $this->error($result);
            }

            if (false !== RoleModel::update($data)) {

                // 刷新缓存
                $this->refreshCache($id);

                // 记录行为
                adminActionLog('admin.role_edit');

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = RoleModel::get($id);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('后台角色 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('role/index'));

        // 设置 提交地址
        $form->setFormUrl(url('role/edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // 设置分组标签
        $form->setGroup([
            ['title' => '角色信息', 'value' => 'role_edit', 'url' => url('edit',['id'=>$id]),'default'=>true],
            ['title' => '访问授权', 'value' => 'role_aut_edit', 'url' => url('aut',['id'=>$id]),'default'=>false]
        ]);

        // 模块数据
        $system_module = MenuModel::where('pid', 0)->column('id,title');
        $system_module_arr = [];
        foreach ($system_module as $key => $value) {
            $system_module_arr[] = ['title' => $value, 'value' => $key];
        }

        // 表单项
        $form->addFormItems([
            [
                'field' => 'name',
                'name' => 'name',
                'form_type' => 'text',
                'title' => '角色名称'
            ],
            [
                'field' => 'role',
                'name' => 'role',
                'form_type' => 'select',
                'title' => '默认模块',
                'option' => $system_module_arr,
                'tips' => '该角色登录后，默认跳转的模块。注意，该角色必须有该模块的节点访问权限。'
            ],
            [
                'field' => 'description',
                'name' => 'description',
                'form_type' => 'textarea',
                'title' => '角色描述'
            ],
            [
                'field' => 'sort',
                'name' => 'sort',
                'form_type' => 'number',
                'title' => '排序',
                'tips' => '数字越低排序越靠前'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 角色授权
     * @author 仇仇天
     */
    public function aut($id = null){

        $roleId = session('admin_user_info.role');

        if ($id === null) $this->error('缺少参数');

        if ($id == 1) $this->error('超级管理员不可修改');

        // 保存数据
        if ($this->request->isPost()) {

            $data = $this->request->post();

            if(!empty($data['action']) && $data['action'] == "auth"){

                // 获取角色权限
                $info = RoleModel::where(['id'=>$id])->find();

                // 设置前台展示
                $menu_auth = !empty($info['menu_auth']) ? $info['menu_auth'] : [];

                $menusArr = [];
                $menus = MenuModel::getStatusMenu();
                foreach ($menus as $value){
                    if(in_array($value['mark'],$menu_auth)){
                        $value['checked'] = true;
                    }
                    $menusArr[] = $value;
                }

                echo json_encode($menusArr);
                exit;
            }else{

                // 设置 权限
                $menu_auth = '';
                if(!empty($data['menu_auth'])){
                    $menu_auth = json_encode(explode(',',$data['menu_auth']));
                }

                if (false !== RoleModel::where(['id'=>$data['id']])->update(['menu_auth'=>$menu_auth])) {

                    // 刷新缓存
                    $this->refreshCache($id);

                    // 记录行为
                    adminActionLog('admin.role_edit');

                    $this->success('编辑成功', url('index'));
                } else {
                    $this->error('编辑失败');
                }
            }
        }

        // 获取数据
        $info = RoleModel::get($id);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('后台角色 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('role/index'));

        // 设置 提交地址
        $form->setFormUrl(url('role/aut'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id],['name' => 'menu_auth', 'value' =>'']]);

        // 设置分组标签
        $form->setGroup([
            ['title' => '角色信息', 'value' => 'role_edit', 'url' => url('edit',['id'=>$id]),'default'=>false],
            ['title' => '访问授权', 'value' => 'role_aut_edit', 'url' => url('aut',['id'=>$id]),'default'=>true]
        ]);

        // 模块数据
        $system_module = MenuModel::where('pid', 0)->column('id,title');
        $system_module_arr = [];
        foreach ($system_module as $key => $value) {
            $system_module_arr[] = ['title' => $value, 'value' => $key];
        }

        // 设置额外js代码
        $js = <<< javascript
            function zTreeOnAsyncSuccess(event, treeId, treeNode, msg){            
                // 全部展开
                base.ztrees.serere.expandAll(true);
                
                // 默认勾选赋值
                var data = base.ztrees.serere.getCheckedNodes(true);
                var vals = [];
                $.each(data,function(i,v){
                    vals.push(v.mark);
                });               
                $('input[name="menu_auth"]').val(vals.join(","));
            }
            //勾选/取消 事件
             function zTreeonCheck(event, treeId, treeNode){        
             
                // 勾选赋值
                var data = base.ztrees.serere.getCheckedNodes(true);
                var vals = [];
                $.each(data,function(i,v){
                    vals.push(v.mark);
                });               
                $('input[name="menu_auth"]').val(vals.join(","));
            }
            
javascript;

        $form->extraJsCode($js);

        // 表单项
        $form->addFormItems([
            [
                'field' => 'name',
                'name' => 'name',
                'form_type' => 'tree',
                'title' => '授权',
                'option'=>[
                    'markname'=>'serere',
                    'data'=>'{
                        "simpleData":{
                            "enable": true,
                            "idKey":"id",
                            "pIdKey":"pid",
                            "rootPId":null
                        },
                        "key":{
                            "name":"title"
                        }
                    }',
                    'view'=>'{
                        "showLine":true,    
                    }',
                    'async'=>'{
                        "enable":true,
                        "url":"'.url('aut').'",
                        "otherParam":{"id":'.$id.',"action":"auth"}
                    }',
                    'check'=>'{
                        "enable":true ,
                        "chkStyle":"checkbox"
                    }',
                    'callback'=>'{
                        "onAsyncSuccess":"zTreeOnAsyncSuccess",
                        "onCheck":"zTreeonCheck"
                    }'
                ]
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();

    }

    /**
     * 删除角色
     * @author 仇仇天
     * @param array $ids 角色id
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

        if (false !== RoleModel::del($where)) {
            // 记录日志
            adminActionLog('admin.role_delete');

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

        $result = RoleModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {
            adminActionLog('admin.role_edit_status');
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache($id)
    {
        RoleModel::delCache($id);
    }
}
