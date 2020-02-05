<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\AdminHook as HookModel;
use app\common\builder\ZBuilder;

/**
 * 钩子控制器
 * @package app\admin\controller
 */
class Hook extends Admin
{
    /**
     * 钩子管理
     * @return mixed
     * @author 仇仇天
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $where = [];       // 筛选参数设置

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])) {

                if ($data['searchCondition'] == 'like') {
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                } else {
                    $where[] = [$data['searchField'], $data['searchCondition'], "%" . $data['searchKeyword'] . "%"];
                }
            }

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = HookModel::where($where)->order('id ASC')->paginate($list_rows);

            foreach ($data_list as $key => $value) {
                $data_list[$key]['plugin'] = empty($value['plugin']) ? '系统' : $value['plugin'];
            }

            // 设置表格数据
            $view->setRowList($data_list);

        }

        // 设置页面标题
        $view->setPageTitle('钩子管理');

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'id', 'condition' => '=', 'default' => true],
            ['title' => '名称', 'field' => 'name', 'condition' => 'like', 'default' => false]
        ]);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('hook/add')]);

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('hook/del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮 启用
        $view->addTopButton('enable', ['url' => url('hook/editstatus'), 'query_data' => '{"status":1}']);

        // 设置头部按钮 禁用
        $view->addTopButton('disable', ['url' => url('hook/editstatus'), 'query_data' => '{"status":0}']);

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field' => 'id',
                'title' => 'ID',
                'align' => 'center'
            ],
            [
                'field' => 'name',
                'title' => '名称',
                'align' => 'center'
            ],
            [
                'field' => 'plugin',
                'title' => '所属插件',
                'align' => 'center',
            ],
            [
                'field' => 'description',
                'title' => '描述',
                'align' => 'center'
            ],
            [
                'field'     => 'system',
                'title'     => '是否系统插件',
                'align'     => 'center',
                'show_type' => 'yesno'
            ],
            [
                'field'    => 'status',
                'title'    => '状态',
                'align'    => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '启用', 'on_value' => 1, 'off_text' => '禁用', 'off_value' => 0]
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('delete'),
                        'query_data' => '{"field":["id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 编辑
     * @param null $id 角色id
     * @return mixed
     * @author 仇仇天
     */
    public function edit($id = null)
    {
        if ($id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            $data = input();

            $save_data = [];

            // 行内编辑
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
            } // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'Hook');

                // 验证失败 输出错误信息
                if (true !== $result) $this->error($result);

                $save_data['name']        = $data['name'];
                $save_data['description'] = $data['description'];

            }

            if (HookModel::where(['id' => $data['id']])->update($save_data)) {

                // 更新 钩子缓存
                dkcache('hooks');

                // 记录行为
                adminActionLog('admin.hook_edit');

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        $info = HookModel::get($id);

        if (empty($info)) $this->error('数据不存在');

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('钩子 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // 表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '钩子名称',
                'tips'      => '由字母和下划线组成，如：<code>page_tips</code>'
            ],
            [
                'field'     => 'description',
                'name'      => 'description',
                'form_type' => 'textarea',
                'title'     => '钩子描述'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 新增
     * @author 仇仇天
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data           = $this->request->post();
            $data['system'] = 1;
            $result         = $this->validate($data, 'Hook');
            if (true !== $result) $this->error($result);

            if ($hook = HookModel::create($data)) {
                $this->refreshCache();
                adminActionLog('admin.hook_add');
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('钩子 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '钩子名称',
                'tips'      => '由字母和下划线组成，如：<code>page_tips</code>'
            ],
            [
                'field'     => 'description',
                'name'      => 'description',
                'form_type' => 'textarea',
                'title'     => '钩子描述'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 禁用/启用
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
        $where = [['id', 'in', $ids]];

        $result = HookModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {

            adminActionLog('admin.hook_status');

            $this->success('操作成功');

        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 删除钩子
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
        } // 删除
        else {
            if (empty($data['id'])) $this->error('参数错误');
            $where = ['id' => $data['id']];
        }

        if (false !== HookModel::del($where)) {
            // 记录日志
            adminActionLog('admin.delete');

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        HookModel::delCache();
    }
}
