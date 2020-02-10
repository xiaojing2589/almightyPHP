<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminAction as ActionModel;
use app\common\model\AdminModule as ModuleModel;

/**
 * 行为管理控制器
 */
class Action extends Admin
{
    /**
     * 首页
     * @author 仇仇天
     * @param string $module_group 模块
     * @return mixed
     */
    public function index($module_group = 'admin')
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        // 加载表格数据
        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            // 设置所属模块
            $where[] = ['a.module','=',$module_group];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])){
                if ($data['searchCondition'] == 'like'){
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                }else{
                    $where[] = [$data['searchField'], $data['searchCondition'], "%" . $data['searchKeyword'] . "%"];
                }
            }

            //  排序字段
            $orderSort = empty($data['sort']) ?  '' : $data['sort'];

            // 排序方式
            $orderMode = $data['order'];

            // 拼接排序语句
            $order = 'a.'.$orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'a.id DESC,a.name DESC' : $order;

            // 数据查询
            $data_list = ActionModel::alias('a')
                ->field('a.*,b.title AS btitle')
                ->join('admin_module b', 'a.module = b.name')
                ->where($where)
                ->order($order)
                ->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 头部按钮 新增
        $view->addTopButton('add', ['url' => url('action/add',['module'=>$module_group])]);

        // 头部按钮 删除
        $view->addTopButton('delete', ['url' => url('action/delete'), 'query_data' => '{"action":"delete_batch"}']);

        // 标签分组信息
        $list_group = ModuleModel::getModuleDataInfo();
        $tab_list   = [];
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title'] = $value['title'];
            $tab_list[$key]['value'] = $value['name'];
            $tab_list[$key]['ico'] = $value['icon'];
            $tab_list[$key]['url']   = url('index', ['module_group' => $value['name']]);
            $tab_list[$key]['default'] = ($module_group == $value['name']) ? true : false;
        }
        $view->setGroup($tab_list);

        // 设置行内编辑地址
        $view->editableUrl(url('action/edit'));

        // 设置页面标题
        $view->setPageTitle('行为管理');

        // 设置快捷搜索框
        $view->setSearch([
            [
                // 快捷搜索标题
                'title' => '名称',
                // 快捷搜索字段
                'field' => 'a.title',
                // 快捷搜索条件
                'condition'=>'like',
                // 快捷搜索是否默认搜索项
                'default' => true
            ],
            [
                // 快捷搜索标题
                'title' => '标识',
                // 快捷搜索字段
                'field' => 'a.name',
                // 快捷搜索条件
                'condition'=>'like',
                // 快捷搜索是否默认搜索项
                'default' => false
            ]
        ]);

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
                'align' => 'center',
                'sortable' => true
            ],
            [
                'field' => 'name',
                'title' => '标识',
                'align' => 'center',
                'sortable' => true,
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field' => 'title',
                'title' => '名称',
                'align' => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field' => 'btitle',
                'title' => '所属模块',
                'align' => 'center',
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
                        'url' => url('action/delete'),
                        'query_data' => '{"field":["id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field' => 'u',
                        'url' => url('action/edit',['module'=>$module_group]),
                        'query_data' => '{"field":["id"]}'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 新增
     * @author 仇仇天
     * @param string $module 所属模块
     * @return mixed
     */
    public function add($module='admin')
    {
        // 保存数据
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'Action');

            //提示报错
            if (true !== $result) $this->error($result);

            if (false !== ActionModel::create($data)) {

                // 删除缓存
                ActionModel::delCache();

                // 记录日志
                adminActionLog('admin.action_add');

                $this->success('新增成功', url('index',['module_group'=>$module]));

            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('行为管理 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('action/add'));

        // 设置表单项
        $form->addFormItems([
            [
                'field' => 'name',
                'name' => 'name',
                'form_type' => 'text',
                'title' => '标识'
            ],
            [
                'field' => 'title',
                'name' => 'title',
                'form_type' => 'text',
                'title' => '行为名称'
            ],
            [
                'field' => 'remark',
                'name' => 'remark',
                'form_type' => 'textarea',
                'title' => '行为描述'
            ]
        ]);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name'=>'module','value'=>$module]]);

        return $form->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     * @param int $id  ID
     * @param string $module 所属模块
     * @return mixed
     */
    public function edit($id = 0,$module='admin')
    {
        if ($id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            // 行内编辑
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                // 验证
                $result = $this->validate($data, 'Action.' . $data['extend_field']);
                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            // 普通编辑
            else {
                // 验证
                $result = $this->validate($data, 'Action');
                if (true !== $result) $this->error($result);
            }

            if (false !== ActionModel::update($data, ['id' => $id])) {

                // 删除缓存
                ActionModel::delCache();

                // 记录日志
                adminActionLog('admin.action_edit');

                $this->success('新增成功', url('index',['module_group'=>$module]));
            } else {
                $this->error('新增失败');
            }
        }

        // 获取数据
        $info = ActionModel::get($id);

        // 模块
        $role_menu_data = ModuleModel::getModuleDataInfo();
        $role_menu_data_arr = [];
        foreach ($role_menu_data as $role_menu_data_arr_key => $role_menu_data_arr_value) {
            $role_menu_data_arr[] = ['title' => $role_menu_data_arr_value, 'value' => $role_menu_data_arr_key];
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('行为管理 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('action/edit',['module_group'=>$module]));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id],['name'=>'module','value'=>$module]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field' => 'name',
                'name' => 'name',
                'form_type' => 'text',
                'title' => '标识'
            ],
            [
                'field' => 'title',
                'name' => 'title',
                'form_type' => 'text',
                'title' => '行为名称'
            ],
            [
                'field' => 'remark',
                'name' => 'remark',
                'form_type' => 'textarea',
                'title' => '行为描述'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 删除
     * @author 仇仇天
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

        if (false !== ActionModel::del($where)) {

            // 记录日志
            adminActionLog('admin.action_del');

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}
