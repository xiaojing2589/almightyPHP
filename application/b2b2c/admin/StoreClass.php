<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cStoreClass as B2b2cStoreClassModel;

/**
 * 店铺分类
 */
class StoreClass extends Admin
{
    /**
     * 列表
     * @return mixed
     * @author 仇仇天
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])) {
                if ($data['searchCondition'] == 'like') {
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                } else {
                    $where[] = [$data['searchField'], $data['searchCondition'], $data['searchKeyword']];
                }
            }

            //  排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'sc_sort ASC' : $order;

            // 数据列表
            $dataList = B2b2cStoreClassModel::where($where)->order($order)->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($dataList);
        }

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置搜索框
        $view->setSearch([
            ['title' => '编号', 'field' => 'sc_id', 'condition' => '=', 'default' => false],
            ['title' => '分类名称', 'field' => 'sc_name', 'condition' => 'like', 'default' => true]
        ]);

        // 提示信息
        $view->setExplanation([
            '商家入驻时可指定此处设置店铺分类'
        ]);

        // 设置页面标题
        $view->setPageTitle('店铺分类列表');

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
                'field'    => 'sc_id',
                'title'    => '编号',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'sc_name',
                'title'    => '分类名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'sc_bail',
                'title'    => '保证金',
                'align'    => 'center',
                'sortable' => true,
                'editable' => [
                    'type' => 'number',
                ]
            ],
            [
                'field'    => 'sc_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'editable' => [
                    'type' => 'number',
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 180,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["sc_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["sc_id"]}'
                    ],
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 编辑
     * @param $sg_id
     * @return mixed
     * @author 仇仇天
     */
    public function edit($sc_id)
    {
        if (empty($sc_id)) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $saveData = [];


            // 是否行内修改
            if (!empty($data['extend_field'])) {

                // 设置需要更新的字段
                $saveData[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'StoreClass.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);
            } // 普通编辑
            else {
                // 需要更新的字段
                $saveData['sc_name'] = $data['sc_name'];
                $saveData['sc_sort'] = $data['sc_sort'];
                $saveData['sc_bail'] = $data['sc_bail'];

                // 验证
                $result = $this->validate($saveData, 'StoreClass');

                // 验证提示报错
                if (true !== $result) $this->error($result);

            }

            if (false !== B2b2cStoreClassModel::update($saveData, ['sc_id' => $sc_id])) {

                // 删除缓存
                B2b2cStoreClassModel::delCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cStoreClassModel::where(['sc_id' => $sc_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('店铺分类 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'sc_id', 'value' => $sc_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'sc_name',
                'name'      => 'sc_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '分类名称'
            ],
            [
                'field'     => 'sc_sort',
                'name'      => 'sc_sort',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '排序',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'sc_bail',
                'name'      => 'sc_bail',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '保证金数额',
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 新增
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function add()
    {
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $saveData = [];

            $saveData['sc_name'] = $data['sc_name'];
            $saveData['sc_sort'] = $data['sc_sort'];
            $saveData['sc_bail'] = $data['sc_bail'];

            // 验证
            $result = $this->validate($saveData, 'StoreClass');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== B2b2cStoreClassModel::insert($saveData)) {

                // 删除缓存
                B2b2cStoreClassModel::delCache();

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('店铺分类 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'sc_name',
                'name'      => 'sc_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '分类名称'
            ],
            [
                'field'     => 'sc_sort',
                'name'      => 'sc_sort',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '排序',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'sc_bail',
                'name'      => 'sc_bail',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '保证金数额',
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 删除/批量删除
     * @throws \Exception
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();
        $id   = $this->request->isPost() ? input('post.sc_id/a') : input('param.sc_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue[] = $value['sc_id'];
            }
            $map = [
                ['sc_id', 'in', $inValue]
            ];
        } else {
            $inValue = $id;
            $map     = [
                ['sc_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cStoreClassModel::del($map)) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
