<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\PSmsTpl as PSmsTplModel;
use app\common\builder\ZBuilder;

/**
 *  短信模板控制器
 */
class SmsTpl extends Admin
{
    /**
     * 首页
     * @return mixed
     * @author 仇仇天
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $data = input();

            // 关键词搜索字段名
            $search_field = input('param.searchField/s', '', 'trim');

            // 搜索关键词
            $keyword = input('param.searchKeyword/s', '', 'trim');

            // 筛选参数设置
            $where = [];

            // 普通搜索筛选
            if ($search_field != '' && $keyword !== '') $where[] = [$search_field, 'like', "%" . $keyword . "%"];

            // 排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'code ASC' : $order;

            // 数据列表
            $data_list = PSmsTplModel::where($where)->order($order)->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('短信模板列表');

        // 设置搜索框
        $view->setSearch([
            ['title' => '模板编码', 'field' => 'code', 'condition' => 'like', 'default' => true],
            ['title' => '模板名称', 'field' => 'name', 'condition' => 'like', 'default' => false]
        ]);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置分组标签
        $view->setGroup([
            ['title' => '短信管理', 'value' => 'sms', 'url' => url('sms/index'), 'default' => false],
            ['title' => '短信模板', 'value' => 'sms_template', 'url' => url('smstemplate'), 'default' => true],
            ['title' => '设置', 'value' => 'sms_setting', 'url' => url('sms/smssetting'), 'default' => false]
        ]);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field'    => 'code',
                'title'    => '模板编码',
                'align'    => 'center',
                'sortable' => true,
            ],
            [
                'field'    => 'name',
                'title'    => '模板名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
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
                        'url'        => url('del'),
                        'query_data' => '{"field":["code"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["code"]}'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 新增
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'SmsTpl');

            if (true !== $result) $this->error($result);

            // 入库数据加工
            $save_data = $data;

            if ($config = PSmsTplModel::create($save_data)) {

                // 删除缓存
                PSmsTplModel::delCache();

                // 日志
                adminActionLog('admin.sms_tpl_add');

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('配置管理 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'code',
                'name'      => 'code',
                'form_type' => 'text',
                'title'     => '模板编号'
            ],
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '模板名称'
            ],
            [
                'field'     => 'message_content',
                'name'      => 'message_content',
                'form_type' => 'textarea',
                'title'     => '消息内容'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 编辑
     * @param int $code 模板编码
     * @return mixed
     * @author 仇仇天
     */
    public function edit($code = '')
    {
        if ($code === '') $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = input();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                // 验证
                $result = $this->validate($data, 'SmsTpl.' . $data['extend_field']);
                // 验证提示报错
                if (true !== $result) $this->error($result);
            } // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'SmsTpl.edit');

                // 验证提示报错
                if (true !== $result) $this->error($result);

                $save_data['name']            = $data['name'];
                $save_data['message_content'] = $data['message_content'];
            }

            if ($config = PSmsTplModel::update($save_data, ['code' => $data['code']])) {

                // 删除缓存
                PSmsTplModel::delCache();

                // 记录行为
                adminActionLog('admin.sms_tpl_edit');

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = PSmsTplModel::where(['code' => $code])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('配置管理 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'code', 'value' => $code]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '模板名称'
            ],
            [
                'field'     => 'message_content',
                'name'      => 'message_content',
                'form_type' => 'textarea',
                'title'     => '消息内容'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 删除/批量
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();

        $where = [];

        // 批量删除
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            if (empty($data['batch_data'])) $this->error('请选择需要删除的数据');
            $ids = [];
            foreach ($data['batch_data'] as $value) {
                $ids[] = $value['code'];
            }
            $where = [['code', 'in', $ids]];
        } // 删除
        else {
            if (empty($data['code'])) $this->error('参数错误');
            $where = ['code' => $data['code']];
        }

        if (false !== PSmsTplModel::del($where)) {
            // 记录日志
            adminActionLog('admin.sms_tpl_del');

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }
}
