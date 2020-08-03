<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cType as B2b2cTypeModel;
use app\b2b2c\model\B2b2cSpec as B2b2cSpecModel;
use app\b2b2c\model\B2b2cTypeSpec as B2b2cTypeSpecModel;
use app\b2b2c\model\B2b2cBrand as B2b2cBrandModel;
use app\b2b2c\model\B2b2cTypeBrand as B2b2cTypeBrandModel;
use app\b2b2c\model\B2b2cTypeCustom as B2b2cTypeCustomModel;
use app\b2b2c\model\B2b2cAttribute as B2b2cAttributeModel;
use app\b2b2c\model\B2b2cAttributeValue as B2b2cAttributeValueModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;

/**
 * 商品类型
 * Class Brand
 * @package app\b2b2c\admin
 */
class Type extends Admin
{
    /**
     * 列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
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
            $order     = $orderSort . ' ' . $orderMode;
            $order     = empty($orderSort) ? 'a.type_sort ASC' : $order;

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = B2b2cTypeModel::alias('a')
                ->field('a.*,b.gc_name')
                ->join('b2b2c_goods_class b', 'a.gc_id = b.gc_id', 'LEFT')
                ->where($where)
                ->order($order)
                ->paginate($list_rows);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'a.type_id', 'condition' => '=', 'default' => false],
            ['title' => '类型名称', 'field' => 'a.type_name', 'condition' => 'like', 'default' => true]
        ]);

        // 提示信息
        $view->setExplanation([
            '当管理员添加商品分类时需选择类型。前台分类下商品列表页通过类型生成商品检索，方便用户搜索需要的商品。',
        ]);

        // 设置页面标题
        $view->setPageTitle('类型列表');

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

//        // 商品分类数据
//        $goodsClassData      = B2b2cGoodsClassModel::getGoodsClassTreeDataAll();
//        $goodsClassDataArr   = [];
//        $goodsClassDataArr[] = ['text' => '无', 'id' => 0, 'title_prefix' => ''];
//        foreach ($goodsClassData AS $key => $value) {
//            $goodsClassDataArr[] = ['text' => $value['gc_name'], 'id' => $value['gc_id'], 'title_prefix' => $value['title_prefix']];
//        }
//
//        // 设置展示
//        $goodsClassDataSelect2 = <<<javascript
//                function(repo){
//                    console.log(repo);
//                    return  $('<span>' + repo.title_prefix + repo.text + '</span>');
//                }
//javascript;
//        $view->setJsFunctionArr([['name' => 'goodsClassDataSelect2', 'value' => $goodsClassDataSelect2]]);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field'    => 'type_id',
                'title'    => 'ID',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'type_name',
                'title'    => '类型名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field' => 'gc_name',
                'title' => '快捷定位',
                'align' => 'center',
            ],

//            [
//                'field'    => 'gc_id',
//                'title'    => '快捷定位',
//                'align'    => 'center',
//                'width'    => 100,
//                'editable' => [
//                    'type'    => 'select2',
//                    'select2' => [
//                        'templateResult'    => 'goodsClassDataSelect2',
//                        'templateSelection' => 'goodsClassDataSelect2'
//                    ],
//                    'source'  => $goodsClassDataArr,
//                ]
//            ],
            [
                'field'    => 'type_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50,
                'editable' => [
                    'type' => 'number',
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 300,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["type_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["type_id"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '设置属性',
                        'class'      => 'btn btn-primary',
                        'url'        => url('attribute'),
                        'query_data' => '{"field":["type_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '自定义属性',
                        'class'      => 'btn btn-primary',
                        'url'        => url('custom'),
                        'query_data' => '{"field":["type_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ]
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 编辑
     * @param int $type_id 类型id
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 仇仇天
     */
    public function edit($type_id = 0)
    {
        if ($type_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'Type.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);
            } // 普通编辑
            else {

                // 需要更新的字段
                $save_data['type_name'] = $data['type_name'];
                $save_data['type_sort'] = $data['type_sort'];
                $save_data['gc_id']     = $data['gc_id'];

                // 验证
                $result = $this->validate($save_data, 'Type');

                // 验证提示报错
                if (true !== $result) $this->error($result);

            }

            if (false !== B2b2cTypeModel::update($save_data, ['type_id' => $type_id])) {

                // 设置关联品牌
                if (!empty($data['brand'])) {
                    B2b2cTypeBrandModel::del(['type_id' => $type_id]);
                    $BrandArr = [];
                    foreach ($data['brand'] as $bv) {
                        $BrandArr[] = ['type_id' => $type_id, 'brand_id' => $bv];
                    }
                    B2b2cTypeBrandModel::insertAll($BrandArr);
                    B2b2cTypeBrandModel::delCache();
                }

                // 设置关联规格
                if (!empty($data['spec'])) {
                    B2b2cTypeSpecModel::del(['type_id' => $type_id]);
                    $SpecArr = [];
                    foreach ($data['spec'] as $sv) {
                        $SpecArr[] = ['type_id' => $type_id, 'sp_id' => $sv];
                    }
                    B2b2cTypeSpecModel::insertAll($SpecArr);
                    B2b2cTypeSpecModel::delCache();
                }

                // 删除缓存
                B2b2cTypeModel::delCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cTypeModel::where(['type_id' => $type_id])->find();

        // 设置 关联规格
        $typeSpecData = B2b2cTypeSpecModel::where(['type_id' => $type_id])->column('sp_id');
        $typeSpecData = to_arrays($typeSpecData);
        $info['spec'] = empty($typeSpecData) ? '' : implode(',', $typeSpecData);

        // 设置 关联品牌
        $typeBrandData = B2b2cTypeBrandModel::where(['type_id' => $type_id])->column('brand_id');
        $typeBrandData = to_arrays($typeBrandData);
        $info['brand'] = empty($typeBrandData) ? '' : implode(',', $typeBrandData);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('类型 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'type_id', 'value' => $type_id]]);

        // 提示信息
        $form->setExplanation([
            '关联规格不是必选项，它会影响商品发布时的规格及价格的录入。不选为没有规格。',
            '关联品牌不是必选项，它会影响商品发布时的品牌选择。'
        ]);

        // 商品分类数据
        $goodsClassTopData   = B2b2cGoodsClassModel::getGoodsClassTreeDataAll();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($goodsClassTopData AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 所有品牌
        $BrandArr  = [];
        $BrandData = B2b2cBrandModel::getBrandDataAll();
        foreach ($BrandData as $brand_k => $brand_v) {
            $BrandArr[] = ['title' => $brand_v['brand_name'], 'value' => $brand_v['brand_id']];
        }

        // 所有规格
        $SpecArr  = [];
        $SpecData = B2b2cSpecModel::getSpecDataAll();
        foreach ($SpecData as $spec_k => $spec_v) {
            $SpecArr[] = ['title' => $spec_v['sp_name'], 'value' => $spec_v['sp_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'type_name',
                'name'      => 'type_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '规格名称',
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select2',
                'title'     => '快捷定位',
                'option'    => $goodsClassDataArr,
                'tips'      => '选择分类，可关联到任意级分类。（只在后台快捷定位中起作用）'
            ],
            [
                'field'     => 'type_sort',
                'name'      => 'type_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'       => 'spec',
                'name'        => 'spec',
                'form_type'   => 'select2',
                'title'       => '选择关联规格',
                'option'      => $SpecArr,
                'multiple'    => true,
                'allow_clear' => true,
                'tips'        => '<code>按住Ctrl点击选择可选多个</code>'
            ],
            [
                'field'       => 'brand',
                'name'        => 'brand',
                'form_type'   => 'select2',
                'title'       => '选择关联品牌',
                'option'      => $BrandArr,
                'multiple'    => true,
                'allow_clear' => true,
                'tips'        => '<code>按住Ctrl点击选择可选多个</code>'
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

            $save_data              = [];
            $save_data['type_name'] = $data['type_name'];
            $save_data['type_sort'] = $data['type_sort'];
            $save_data['gc_id']     = $data['gc_id'];

            // 验证
            $result = $this->validate($save_data, 'Type');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== $type_id = B2b2cTypeModel::insertGetId($save_data)) {

                // 设置关联品牌
                if (!empty($data['brand'])) {
                    B2b2cTypeBrandModel::del(['type_id' => $type_id]);
                    $BrandArr = [];
                    foreach ($data['brand'] as $bv) {
                        $BrandArr[] = ['type_id' => $type_id, 'brand_id' => $bv];
                    }
                    B2b2cTypeBrandModel::insertAll($BrandArr);
                    B2b2cTypeBrandModel::delCache();
                }

                // 设置关联规格
                if (!empty($data['spec'])) {
                    B2b2cTypeSpecModel::del(['type_id' => $type_id]);
                    $SpecArr = [];
                    foreach ($data['spec'] as $sv) {
                        $SpecArr[] = ['type_id' => $type_id, 'sp_id' => $sv];
                    }
                    B2b2cTypeSpecModel::insertAll($SpecArr);
                    B2b2cTypeSpecModel::delCache();
                }

                // 记录行为
                action_log('b2b2c.b2b2c_type_add');

                // 删除缓存
                B2b2cTypeModel::delCache();

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('类型 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 商品分类数据
        $pids                = B2b2cGoodsClassModel::getGoodsClassDataInfo();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($pids AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 所有品牌
        $BrandArr  = [];
        $BrandData = B2b2cBrandModel::getBrandDataInfo();
        foreach ($BrandData as $brand_k => $brand_v) {
            $BrandArr[] = ['title' => $brand_v['brand_name'], 'value' => $brand_v['brand_id']];
        }

        // 所有规格
        $SpecArr  = [];
        $SpecData = B2b2cSpecModel::getSpecDataInfo();
        foreach ($SpecData as $spec_k => $spec_v) {
            $SpecArr[] = ['title' => $spec_v['sp_name'], 'value' => $spec_v['sp_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'type_name',
                'name'      => 'type_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '规格名称',
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select2',
                'title'     => '快捷定位',
                'option'    => $goodsClassDataArr,
                'tips'      => '选择分类，可关联到任意级分类。（只在后台快捷定位中起作用）'
            ],
            [
                'field'     => 'type_sort',
                'name'      => 'type_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'value'     => 0,
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'       => 'spec',
                'name'        => 'spec',
                'form_type'   => 'select2',
                'title'       => '选择关联规格',
                'option'      => $SpecArr,
                'multiple'    => true,
                'allow_clear' => true,
                'tips'        => '<code>按住Ctrl点击选择可选多个</code>'
            ],
            [
                'field'       => 'brand',
                'name'        => 'brand',
                'form_type'   => 'select2',
                'title'       => '选择关联品牌',
                'option'      => $BrandArr,
                'multiple'    => true,
                'allow_clear' => true,
                'tips'        => '<code>按住Ctrl点击选择可选多个</code>'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 删除/批量删除
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();
        $id   = $this->request->isPost() ? input('post.type_id/a') : input('param.type_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue[] = $value['type_id'];
            }
            $map = [
                ['type_id', 'in', $inValue]
            ];
        } else {
            $inValue = $id;
            $map     = [
                ['type_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cTypeModel::del($map)) {
            B2b2cTypeSpecModel::del($map); // 关联规格
            B2b2cTypeBrandModel::del($map); // 关联品牌
            B2b2cTypeCustomModel::del($map); // 关联自定义属性
            B2b2cAttributeModel::del($map);// 属性
            B2b2cAttributeValueModel::del($map); // 属性值
            action_log('b2b2c.b2b2c_type_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }


    /**
     * 属性列表
     * @param int $type_id 类型id
     * @author 仇仇天
     */
    public function attribute($type_id = 0)
    {

        if ($type_id === 0) $this->error('参数错误');

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            $where[] = ['a.type_id', '=', $type_id];

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
            $order     = $orderSort . ' ' . $orderMode;
            $order     = empty($orderSort) ? 'a.attr_sort ASC' : $order;

            // 数据列表
            $dataList = B2b2cAttributeModel::alias('a')
                ->field('a.*,b.type_name')
                ->join('b2b2c_type b', 'a.type_id = b.type_id', 'LEFT')
                ->where($where)
                ->order($order)
                ->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($dataList);
        }

        //设置头部按钮新增
        $view->addTopButton('add', ['url' => url('attributeadd', ['type_id' => $type_id])]);

        // 设置搜索框
        $view->setSearch([
            ['title' => '属性名称', 'field' => 'a.attr_name', 'condition' => 'like', 'default' => true]
        ]);

        // 提示信息
        $view->setExplanation([
            '属性的“显示”选项，该属性将会在商品列表页显示',
        ]);

        // 设置返回地址
        $view->setReturnUrl(url('index'));

        // 设置行内编辑地址
        $view->editableUrl(url('attributeedit'));

        // 设置页面标题
        $view->setPageTitle('属性列表');

        // 设置列
        $view->setColumn([
            [
                'field' => 'type_name',
                'title' => '类型名称',
                'align' => 'center'
            ],
            [
                'field'    => 'attr_name',
                'title'    => '属性名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field'    => 'attr_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'editable' => [
                    'type' => 'number'
                ]
            ],
            [
                'field'    => 'attr_show',
                'title'    => '是否显示',
                'align'    => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '显示', 'on_value' => 1, 'off_text' => '不显示', 'off_value' => 0]
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
                        'confirm'    => '请确定要删除该属性吗？',
                        'query_jump' => 'ajax',
                        'url'        => url('attributedel'),
                        'query_data' => '{"field":["attr_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('attributeedit'),
                        'query_data' => '{"field":["attr_id","type_id"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '设置属性值',
                        'class'      => 'btn btn-primary',
                        'url'        => url('attributevalue'),
                        'query_data' => '{"field":["attr_id","type_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 属性编辑
     * @param int $id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function attributeEdit($attr_id = 0, $type_id = 0)
    {
        if ($attr_id == 0 || $type_id == 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'Attribute.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);

            } // 普通编辑
            else {
                $save_data['attr_name'] = $data['attr_name'];
                $save_data['attr_show'] = $data['attr_show'];
                $save_data['attr_sort'] = $data['attr_sort'];
                $save_data['type_id']   = $type_id;

                // 验证
                $result = $this->validate($save_data, 'Attribute');

                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            if (false !== B2b2cAttributeModel::update($save_data, ['attr_id' => $attr_id])) {

                // 删除缓存
                B2b2cAttributeModel::delCache();

                $this->success('编辑成功', url('attribute', ['type_id' => $type_id]));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cAttributeModel::where(['attr_id' => $attr_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('属性 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('attribute', ['type_id' => $type_id]));

        // 设置 提交地址
        $form->setFormUrl(url('attributeedit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'type_id', 'value' => $type_id], ['name' => 'attr_id', 'value' => $attr_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'attr_name',
                'name'      => 'attr_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '属性名称',
            ],
            [
                'field'     => 'attr_show',
                'name'      => 'attr_show',
                'title'     => '是否显示',
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '显示',
                    'off_text'  => '不显示',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '该选项将影响商品列表页显示'
            ],
            [
                'field'     => 'attr_sort',
                'name'      => 'attr_sort',
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
     * 属性新增
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function attributeAdd($type_id = 0)
    {
        if ($type_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {
            // 表单数据
            $data                   = $this->request->post();
            $save_data              = [];
            $save_data['attr_name'] = $data['attr_name'];
            $save_data['attr_show'] = $data['attr_show'];
            $save_data['attr_sort'] = $data['attr_sort'];
            $save_data['type_id']   = $data['type_id'];

            // 验证
            $result = $this->validate($save_data, 'Attribute');
            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== B2b2cAttributeModel::insert($save_data)) {

                // 删除缓存
                B2b2cAttributeModel::delCache();

                $this->success('新增成功', url('attribute', ['type_id' => $type_id]));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('属性 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('attribute', ['type_id' => $type_id]));


        // 设置 提交地址
        $form->setFormUrl(url('attributeadd'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'type_id', 'value' => $type_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'attr_name',
                'name'      => 'attr_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '属性名称',
            ],
            [
                'field'     => 'attr_show',
                'name'      => 'attr_show',
                'title'     => '是否显示',
                'form_type' => 'switch',
                'value'     => 1,
                'option'    => [
                    'on_text'   => '显示',
                    'off_text'  => '不显示',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '该选项将影响商品列表页显示'
            ],
            [
                'field'     => 'attr_sort',
                'name'      => 'attr_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'value'     => 0,
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 属性 删除
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function attributeDel()
    {
        $data = $this->request->post();
        $map  = [
            ['attr_id', '=', $data['attr_id']]
        ];
        if (false !== B2b2cAttributeModel::del($map)) {
            // 删除缓存
            B2b2cAttributeModel::delCache();

            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }


    /**
     * 属性值列表
     * @param int $type_id
     * @author 仇仇天
     */
    public function attributeValue($attr_id = 0, $type_id = 0)
    {

        if ($type_id == 0 || $type_id == 0) $this->error('参数错误');

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            $where[] = ['a.type_id', '=', $type_id];
            $where[] = ['a.attr_id', '=', $attr_id];

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
            $order     = $orderSort . ' ' . $orderMode;
            $order     = empty($orderSort) ? 'a.attr_value_sort ASC' : $order;

            // 数据列表
            $dataList = B2b2cAttributeValueModel::alias('a')
                ->field('a.*,b.type_name')
                ->join('b2b2c_type b', 'a.type_id = b.type_id','LEFT')
                ->join('b2b2c_attribute c', 'a.attr_id = c.attr_id','LEFT')
                ->where($where)
                ->order($order)
                ->paginate($data['list_rows']);
            // 设置表格数据
            $view->setRowList($dataList);
        }

        //设置头部按钮新增
        $view->addTopButton('add', ['url' => url('attributevalueadd', ['attr_id' => $attr_id, 'type_id' => $type_id])]);

        // 设置搜索框
        $view->setSearch([
            ['title' => '属性值', 'field' => 'a.attr_value', 'condition' => 'like', 'default' => true]
        ]);

        // 设置返回地址
        $view->setReturnUrl(url('attribute', ['attr_id' => $attr_id, 'type_id' => $type_id]));

        // 设置行内编辑地址
        $view->editableUrl(url('attributevalueedit'));

        // 设置页面标题
        $view->setPageTitle('属性列表');

        // 设置列
        $view->setColumn([
            [
                'field' => 'type_name',
                'title' => '类型名称',
                'align' => 'center'
            ],
            [
                'field'    => 'attr_value',
                'title'    => '属性值',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field'    => 'attr_value_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'editable' => [
                    'type' => 'number'
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
                        'confirm'    => '请确定要删除该属性值吗？',
                        'query_jump' => 'ajax',
                        'url'        => url('attributevaluedel'),
                        'query_data' => '{"field":["attr_value_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('attributevalueedit'),
                        'query_data' => '{"field":["attr_value_id","attr_id","type_id"]}'
                    ],
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 属性值编辑
     * @param int $id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function attributeValueEdit($attr_value_id = 0, $attr_id = 0, $type_id = 0)
    {
        if ($attr_id == 0 || $type_id == 0 || $attr_value_id == 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result                           = $this->validate($data, 'AttributeValue.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);

            } // 普通编辑
            else {
                $save_data['attr_value']      = $data['attr_value'];
                $save_data['attr_value_sort'] = $data['attr_value_sort'];
                $save_data['type_id']         = $type_id;
                $save_data['attr_id']         = $attr_id;

                // 验证
                $result = $this->validate($save_data, 'AttributeValue');

                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            if (false !== B2b2cAttributeValueModel::update($save_data, ['attr_value_id' => $attr_value_id])) {

                // 删除缓存
                B2b2cAttributeValueModel::delCache();

                $this->success('编辑成功', url('attributevalue', ['type_id' => $type_id, 'attr_id' => $attr_id]));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cAttributeValueModel::where(['attr_value_id' => $attr_value_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('属性值 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('attributevalue', ['type_id' => $type_id, 'attr_id' => $attr_id]));

        // 设置 提交地址
        $form->setFormUrl(url('attributevalueedit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([
            ['name' => 'type_id', 'value' => $type_id],
            ['name' => 'attr_id', 'value' => $attr_id],
            ['name' => 'attr_value_id', 'value' => $attr_value_id]
        ]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'attr_value',
                'name'      => 'attr_value',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '属性值',
            ],
            [
                'field'     => 'attr_value_sort',
                'name'      => 'attr_value_sort',
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
     * 属性值新增
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function attributeValueAdd($attr_id = 0, $type_id = 0)
    {
        if ($type_id == 0 || $attr_id == 0) $this->error('参数错误');

        if ($this->request->isPost()) {
            // 表单数据
            $data                         = $this->request->post();
            $save_data                    = [];
            $save_data['attr_value']      = $data['attr_value'];
            $save_data['attr_value_sort'] = $data['attr_value_sort'];
            $save_data['type_id']         = $type_id;
            $save_data['attr_id']         = $attr_id;

            // 验证
            $result                       = $this->validate($save_data, 'AttributeValue');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== B2b2cAttributeValueModel::insert($save_data)) {

                // 删除缓存
                B2b2cAttributeValueModel::delCache();

                $this->success('新增成功', url('attributevalue', ['type_id' => $type_id, 'attr_id' => $attr_id]));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('属性 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('attributevalue', ['type_id' => $type_id, 'attr_id' => $attr_id]));

        // 设置 提交地址
        $form->setFormUrl(url('attributevalueadd'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([
            ['name' => 'type_id', 'value' => $type_id],
            ['name' => 'attr_id', 'value' => $attr_id]
        ]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'attr_value',
                'name'      => 'attr_value',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '属性值',
            ],
            [
                'field'     => 'attr_value_sort',
                'name'      => 'attr_value_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 属性值删除
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function attributeValueDel()
    {
        $data = $this->request->post();
        $map  = [
            ['attr_value_id', '=', $data['attr_value_id']]
        ];
        if (false !== B2b2cAttributeValueModel::del($map)) {
            // 删除缓存
            B2b2cAttributeValueModel::delCache();
            $this->success('删除成功');
        } else {
            $this->error('删除失败，请重试');
        }
    }


    /**
     * 自定义属性列表
     * @param int $type_id
     * @author 仇仇天
     */
    public function custom($type_id = 0)
    {

        if ($type_id === 0) $this->error('参数错误');

        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 传递数据
            $data = input();

            // 筛选参数设置
            $where = [];

            $where[] = ['a.type_id', '=', $type_id];

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
            $order     = $orderSort . ' ' . $orderMode;
            $order     = empty($orderSort) ? 'a.custom_id ASC' : $order;

            // 数据列表
            $data_list = B2b2cTypeCustomModel::alias('a')
                ->field('a.*,b.type_name')
                ->join('b2b2c_type b', 'a.type_id = b.type_id','LEFT')
                ->where($where)
                ->order($order)
                ->paginate($data['list_rows']);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        //设置头部按钮新增
        $view->addTopButton('add', ['url' => url('customadd', ['type_id' => $type_id])]);

        // 设置搜索框
        $view->setSearch([
            ['title' => '自定义属性名称', 'field' => 'custom_name', 'condition' => 'like', 'default' => true]
        ]);

        // 提示信息
        $view->setExplanation([
            '自定义属性的属性值由商家自行填写。注意：自定义属性不作为商品检索项使用',
        ]);

        // 设置返回地址
        $view->setReturnUrl(url('index'));

        // 设置行内编辑地址
        $view->editableUrl(url('customedit'));

        // 设置页面标题
        $view->setPageTitle('自定义属性列表');

        // 设置列
        $view->setColumn([
            [
                'field' => 'type_name',
                'title' => '类型名称',
                'align' => 'center'
            ],
            [
                'field'    => 'custom_name',
                'title'    => '自定义属性名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text'
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
                        'confirm'    => '请确定要删除该自定义属性吗？',
                        'query_jump' => 'ajax',
                        'url'        => url('customdel'),
                        'query_data' => '{"field":["custom_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('customedit'),
                        'query_data' => '{"field":["custom_id","type_id"]}'
                    ],
                ]
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 自定义属性编辑
     * @param int $id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function customEdit($custom_id = 0, $type_id = 0)
    {
        if ($custom_id == 0 || $type_id == 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                $result                           = $this->validate($data, 'Custom.' . $data['extend_field']); // 验证
                if (true !== $result) $this->error($result);// 验证提示报错
            } // 普通编辑
            else {
                $save_data['custom_name'] = $data['custom_name'];
                $save_data['type_id']     = $type_id;
                $result                   = $this->validate($save_data, 'Custom'); // 验证
                if (true !== $result) $this->error($result);// 验证提示报错
            }

            if (false !== B2b2cTypeCustomModel::update($save_data, ['custom_id' => $custom_id])) {
                B2b2cTypeCustomModel::delCache();
                $this->success('编辑成功', url('custom', ['type_id' => $type_id]));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cTypeCustomModel::where(['custom_id' => $custom_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('自定义属性 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('custom', ['type_id' => $type_id]));

        // 设置 提交地址
        $form->setFormUrl(url('customedit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'type_id', 'value' => $type_id], ['name' => 'custom_id', 'value' => $custom_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'custom_name',
                'name'      => 'custom_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '自定义属性名称',
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 自定义属性新增
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function customAdd($type_id = 0)
    {
        if ($type_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {
            // 表单数据
            $data                     = $this->request->post();
            $save_data                = [];
            $save_data['custom_name'] = $data['custom_name'];
            $save_data['type_id']     = $type_id;

            // 验证
            $result                   = $this->validate($save_data, 'Custom');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== B2b2cTypeCustomModel::insert($save_data)) {

                // 删除缓存
                B2b2cTypeCustomModel::delCache();

                $this->success('新增成功', url('custom', ['type_id' => $type_id]));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('属性 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('custom', ['type_id' => $type_id]));

        // 设置 提交地址
        $form->setFormUrl(url('customadd'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'type_id', 'value' => $type_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'custom_name',
                'name'      => 'custom_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '自定义属性名称',
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 自定义属性 删除
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author 仇仇天
     */
    public function customDel()
    {
        $data = $this->request->post();
        $map  = [
            ['custom_id', '=', $data['custom_id']]
        ];
        if (false !== B2b2cTypeCustomModel::del($map)) {
            // 删除缓存
            B2b2cTypeCustomModel::delCache();
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }
}
