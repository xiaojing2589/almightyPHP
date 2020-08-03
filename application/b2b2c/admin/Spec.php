<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cSpec as B2b2cSpecModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;

/**
 * 商品规格
 * Class Brand
 * @package app\b2b2c\admin
 */
class Spec extends Admin
{
    /**
     * 列表
     * @author 仇仇天
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
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

            // 排序字段
            $orderSort = input('sort/s', '', 'trim');
            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            $order     = $orderSort . ' ' . $orderMode;

            $order     = empty($orderSort) ? 'a.sp_sort ASC' : $order;

            // 数据列表
            $dataList = B2b2cSpecModel::alias('a')
                        ->field('a.*,b.gc_name')
                        ->join('b2b2c_goods_class b', 'a.gc_id = b.gc_id','LEFT')
                        ->where($where)
                        ->order($order)
                        ->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($dataList);
        }

        //设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        //设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置搜索框
        $view->setSearch([
            ['title' => '编号', 'field' => 'a.sp_id','condition'=>'=', 'default' => false],
            ['title' => '规格名称', 'field' => 'a.sp_name','condition'=>'like', 'default' => true]
        ]);

        // 提示信息
        $view->setExplanation([
            '规格将会对应到商品发布的规格，规格值由店铺自己添加。',
            '默认安装中会添加一个默认颜色规格，请不要删除，只有这个颜色规格才能在商品详细页显示为图片。'
        ]);

        // 设置页面标题
        $view->setPageTitle('规格列表');

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
                'field'    => 'sp_id',
                'title'    => '编号',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'sp_name',
                'title'    => '规格名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'gc_name',
                'title'    => '快捷定位',
                'align'    => 'center'
            ],
            [
                'field'    => 'sp_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50,
                'editable' => [
                    'type' => 'number',
                ]
            ],
            [
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'width'         => 180,
                'btn'           => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["sp_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["sp_id"]}'
                    ],
                ],
                'peration_hide' => <<<javascript
                        $.each(perationArr,function(i,v){
                            if(v.indexOf('hide_d') > -1){
                                if(row.sp_id == 1){   
                                    delete perationArr[i]
                                }
                            }
                        });   
javascript
            ]
        ]);

        return $view->fetch();

    }

    /**
     * 编辑
     * @author 仇仇天
     * @param int $sp_id 规格id
     * @return mixed
     */
    public function edit($sp_id = 0)
    {
        if ($sp_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                $result                           = $this->validate($data, 'Spec.' . $data['extend_field']); // 验证
                if (true !== $result) $this->error($result);// 验证提示报错
            }

            // 普通编辑
            else {

                $save_data['sp_name'] = $data['sp_name'];
                $save_data['sp_sort'] = $data['sp_sort'];
                $save_data['gc_id']   = $data['gc_id'];

                // 验证
                $result               = $this->validate($save_data, 'Spec');

                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            if (false !== B2b2cSpecModel::update($save_data, ['sp_id' => $sp_id])) {

                // 删除缓存
                B2b2cSpecModel::delCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cSpecModel::where(['sp_id' => $sp_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('规格 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'sp_id', 'value' => $sp_id]]);

        // 商品分类数据
        $goodsClassTopData              = B2b2cGoodsClassModel::getGoodsClassTreeDataAll();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($goodsClassTopData AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'sp_name',
                'name'      => 'sp_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '规格名称',
                'tips'      => '请填写常用的商品规格的名称；例如：颜色；尺寸等。'
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select',
                'title'     => '快捷定位名',
                'option'    => $goodsClassDataArr,
                'tips'      => ' 选择分类，可关联到任意级分类。（只在后台快捷定位中起作用）'
            ],
            [
                'field'     => 'sp_sort',
                'name'      => 'sp_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 新增
     * @author 仇仇天
     * @return mixed
     */
    public function add()
    {
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            $save_data['sp_name'] = $data['sp_name'];
            $save_data['sp_sort'] = $data['sp_sort'];
            $save_data['gc_id']   = $data['gc_id'];
            $result               = $this->validate($save_data, 'Spec'); // 验证
            if (true !== $result) $this->error($result);// 验证提示报错

            if (false !== B2b2cSpecModel::insert($save_data)) {
                // 删除缓存
                B2b2cSpecModel::delCache();

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('规格 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址        //
        $form->setFormUrl(url('add'));

        // 商品分类数据
        $goodsClassTopData              = B2b2cGoodsClassModel::getGoodsClassTreeDataAll();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($goodsClassTopData AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'sp_name',
                'name'      => 'sp_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '规格名称',
                'tips'      => '请填写常用的商品规格的名称；例如：颜色；尺寸等。'
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select',
                'title'     => '快捷定位名',
                'option'    => $goodsClassDataArr,
                'tips'      => ' 选择分类，可关联到任意级分类。（只在后台快捷定位中起作用）'
            ],
            [
                'field'     => 'sp_sort',
                'name'      => 'sp_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'value'     => 0,
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 删除/批量删除
     * @author 仇仇天
     * @throws \Exception
     */
    public function del()
    {
        $data = $this->request->post();
        $id   = $this->request->isPost() ? input('post.sp_id/a') : input('param.sp_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue[] = $value['sp_id'];
            }
            $map = [
                ['sp_id', 'in', $inValue]
            ];
        }
        else {
            $inValue = $id;
            $map     = [
                ['sp_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cSpecModel::del($map)) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
