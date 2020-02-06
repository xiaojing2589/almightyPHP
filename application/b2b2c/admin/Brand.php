<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cBrand as B2b2cBrandModel;
use app\b2b2c\model\B2b2cTypeBrand as B2b2cTypeBrandModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;

/**
 * 商品品牌
 * Class Brand
 * @package app\b2b2c\admin
 */
class Brand extends Admin
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

            // 关键词搜索字段名
            $search_field = input('param.searchField/s', '', 'trim');

            // 搜索关键词
            $keyword = input('param.searchKeyword/s', '', 'trim');

            // 筛选参数设置
            $map = [];

            // 普通搜索筛选
            if ($search_field != '' && $keyword !== '') $map[] = [$search_field, 'like', "%" . $keyword . "%"];

            //  排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'brand_apply ASC brand_sort ASC' : $order;

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = B2b2cBrandModel::where($map)->order($order)->paginate($list_rows);

            foreach ($data_list as $key => $value) {
                $data_list[$key]['brand_pic'] = getB2b2cImg($value['brand_pic'], ['type' => 'brand']);
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置搜索框
        $view->setSearch([
            ['title' => 'ID', 'field' => 'brand_id', 'default' => false],
            ['title' => '品牌名称', 'field' => 'brand_name', 'default' => true],
            ['title' => '品牌首字母', 'field' => 'brand_initial', 'default' => false]
        ]);

        // 提示信息
        $view->setExplanation([
            '当店主添加商品时可选择商品品牌，用户可根据品牌查询商品列表',
            '被推荐品牌，将在前台品牌推荐模块处显示',
            '在品牌列表页面，品牌将按类别分组，即具有相同类别的品牌为一组，品牌类别与品牌分类无联系',
        ]);

        // 设置页面标题
        $view->setPageTitle('品牌列表');

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 商品分类数据
        $goodsClassData      = B2b2cGoodsClassModel::getGoodsClassDataInfo();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['text' => '无', 'id' => 0, 'title_prefix' => ''];
        foreach ($goodsClassData AS $key => $value) {
            $goodsClassDataArr[] = ['text' => $value['gc_name'], 'id' => $value['gc_id'], 'title_prefix' => $value['title_prefix']];
        }

        // 设置展示
        $goodsClassDataSelect2 = <<<javascript
                function(repo){
                    console.log(repo);
                    return  $('<span>' + repo.title_prefix + repo.text + '</span>');
                }
javascript;
        $view->setJsFunctionArr([['name' => 'goodsClassDataSelect2', 'value' => $goodsClassDataSelect2]]);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field'    => 'brand_id',
                'title'    => 'ID',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'brand_name',
                'title'    => '品牌名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'     => 'brand_pic',
                'title'     => '品牌图片',
                'align'     => 'center',
                'width'     => 100,
                'show_type' => 'avatar_image',
            ],
            [
                'field'    => 'brand_tjstore',
                'title'    => '品牌副标题',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'brand_initial',
                'title'    => '品牌首字母',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50,
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'gc_id',
                'title'    => '所属分类',
                'align'    => 'center',
                'width'    => 100,
                'editable' => [
                    'type'    => 'select2',
                    'select2' => [
                        'templateResult'    => 'goodsClassDataSelect2',
                        'templateSelection' => 'goodsClassDataSelect2'
                    ],
                    'source'  => $goodsClassDataArr,
                ]
            ],
            [
                'field'    => 'brand_sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50,
                'editable' => [
                    'type' => 'number',
                ]
            ],
            [
                'field'    => 'brand_recommend',
                'title'    => '推荐',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 110,
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '推荐', 'on_value' => 1, 'off_text' => '不推荐', 'off_value' => 0]
                ]
            ],
            [
                'field'    => 'show_type',
                'title'    => '展示类型',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 110,
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '文字', 'on_value' => 1, 'off_text' => '图片', 'off_value' => 0]
                ]
            ],
            [
                'field'       => 'brand_apply',
                'title'       => '状态',
                'sortable'    => true,
                'align'       => 'center',
                'width'       => 50,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '未审核', 'colour' => 'label-danger'],
                    ['value' => 1, 'text' => '通过', 'colour' => 'label-info']
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
                        'query_data' => '{"field":["brand_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["brand_id"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '审核',
                        'ico'        => 'fa fa-check-square',
                        'class'      => 'btn btn-xs btn-success hide_apply',
                        'confirm'    => '您确定要通过品牌申请吗？',
                        'url'        => url('examine'),
                        'query_data' => '{"field":["brand_id"],"extentd_field":{"brand_apply":"1"}}',
                        'query_jump' => 'ajax',
                        'query_type' => 'post'
                    ]
                ],
                'peration_hide' => <<<javascript
                        $.each(perationArr,function(i,v){
                            if(v.indexOf('hide_apply') > -1){
                                if(row.brand_apply == 1){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_d') > -1){
                                if(row.brand_apply == 0){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_u') > -1){
                                if(row.brand_apply == 0){   
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
     * @param int $brand_id 品牌id
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($brand_id = 0)
    {
        if ($brand_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                // 设置需要更新的字段
                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'Brand.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            // 普通编辑
            else {

                // 需要上传的文件
                $file = $this->request->file();

                // 品牌图片
                if (!empty($file['brand_pic'])) {

                    // 创建图片
                    $brandPicFileInfo = attaAdd($file['brand_pic'], config('b2b2c.brand_path'));

                    // 上传错误
                    if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);

                    // 获取连接
                    $save_data['brand_pic'] = $brandPicFileInfo['data']['relative_path_url'];
                }


                // 品牌大图背景
                if (!empty($file['brand_bgpic'])) {

                    // 创建图片
                    $brandBgpicFileInfo = attaAdd($file['brand_bgpic'], 'b2b2c/shop/brand');

                    // 上传错误
                    if (!$brandBgpicFileInfo['status']) $this->error($brandBgpicFileInfo['status']);

                    // 获取连接
                    $save_data['brand_bgpic'] = $brandBgpicFileInfo['data']['relative_path_url'];
                }

                // 需要更新的字段
                $save_data['brand_name']         = $data['brand_name'];
                $save_data['brand_initial']      = $data['brand_initial'];
                $save_data['brand_tjstore']      = $data['brand_tjstore'];
                $save_data['brand_recommend']    = $data['brand_recommend'];
                $save_data['show_type']          = $data['show_type'];
                $save_data['brand_sort']         = $data['brand_sort'];
                $save_data['brand_introduction'] = $data['brand_introduction'];
                $save_data['gc_id']              = $data['gc_id'];

                // 验证
                $result = $this->validate($save_data, 'Brand');

                // 验证提示报错
                if (true !== $result) $this->error($result);

            }
            if (false !== B2b2cBrandModel::update($save_data, ['brand_id' => $brand_id])) {

                // 记录行为
                action_log('b2b2c.b2b2c_brand_edit');

                // 刷新缓存
                $this->refreshCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cBrandModel::where(['brand_id' => $brand_id])->find();

        // 设置品牌图片
        $info['brand_pic'] = getB2b2cImg($info['brand_pic'], ['type' => 'brand']);

        // 设置品牌大图
        $info['brand_bgpic'] = getB2b2cImg($info['brand_bgpic'], ['type' => 'brand_bgpic']);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('品牌 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 分列数
        $form->listNumber(2);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'brand_id', 'value' => $brand_id]]);

        // 商品分类数据
        $pids                = B2b2cGoodsClassModel::getGoodsClassDataInfo();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($pids AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'brand_name',
                'name'      => 'brand_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '品牌名称'
            ],
            [
                'field'     => 'brand_tjstore',
                'name'      => 'brand_tjstore',
                'form_type' => 'text',
                'title'     => '品牌副标题'
            ],
            [
                'field'     => 'brand_initial',
                'name'      => 'brand_initial',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '品牌首字母',
                'tips'      => '商家发布商品快捷搜索品牌使用'
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select2',
                'title'     => '所属分类',
                'option'    => $goodsClassDataArr,
                'tips'      => ' 选择分类，可关联大分类或更具体的下级分类。'
            ],
            [
                'field'     => 'brand_pic',
                'name'      => 'brand_pic',
                'form_type' => 'image',
                'title'     => '品牌图片标识',
                'tips'      => ' 品牌LOGO尺寸要求宽度为150像素，高度为50像素、比例为3:1的图片；支持格式gif,jpg,png'
            ],
            [
                'field'     => 'brand_bgpic',
                'name'      => 'brand_bgpic',
                'form_type' => 'image',
                'title'     => '品牌大图背景URL',
                'tips'      => ' 请填写背景图片完整URL大小为1920px*460px；支持格式gif,jpg,png'
            ],
            [
                'field'     => 'show_type',
                'name'      => 'show_type',
                'title'     => '展示方式',
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '文字',
                    'off_text'  => '图片',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '在“全部品牌”页面的展示方式，如果设置为“图片”则显示该品牌的“品牌图片标识”，如果设置为“文字”则显示该品牌的“品牌名”'
            ],
            [
                'field'     => 'brand_recommend',
                'name'      => 'brand_recommend',
                'title'     => '是否推荐',
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '是',
                    'off_text'  => '否',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '选择被推荐的图片将在所有品牌列表页“推荐品牌”位置展现。'
            ],
            [
                'field'     => 'brand_sort',
                'name'      => 'brand_sort',
                'title'     => '排序',
                'form_type' => 'number',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'brand_introduction',
                'name'      => 'brand_introduction',
                'require'   => true,
                'form_type' => 'textarea',
                'title'     => '品牌介绍',
                'tips'      => '请填写品牌介绍，建议在20~200字之间'
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
     * @throws \think\Exception
     */
    public function add()
    {
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 需要上传的文件
            $file = $this->request->file();

            // 品牌图片
            if (!empty($file['brand_pic'])) {
                $brandPicFileInfo = attaAdd($file['brand_pic'], config('b2b2c.brand_path')); // 创建图片
                if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);// 上传错误
                $save_data['brand_pic'] = $brandPicFileInfo['data']['relative_path_url'];
            }

            // 品牌大图背景
            if (!empty($file['brand_bgpic'])) {
                $brandBgpicFileInfo = attaAdd($file['brand_bgpic'], 'b2b2c/shop/brand'); // 创建图片
                if (!$brandBgpicFileInfo['status']) $this->error($brandBgpicFileInfo['status']);// 上传错误
                $save_data['brand_bgpic'] = $brandBgpicFileInfo['data']['relative_path_url'];
            }

            $save_data['brand_name']         = $data['brand_name'];
            $save_data['brand_initial']      = $data['brand_initial'];
            $save_data['brand_tjstore']      = $data['brand_tjstore'];
            $save_data['brand_recommend']    = $data['brand_recommend'];
            $save_data['show_type']          = $data['show_type'];
            $save_data['brand_sort']         = $data['brand_sort'];
            $save_data['brand_introduction'] = $data['brand_introduction'];
            $save_data['gc_id']              = $data['gc_id'];

            // 验证
            $result = $this->validate($save_data, 'Brand');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            if (false !== B2b2cBrandModel::insert($save_data)) {

                // 记录行为
                action_log('b2b2c.b2b2c_brand_add');

                // 刷新缓存
                $this->refreshCache();

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('品牌 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 分列数
        $form->listNumber(2);

        // 商品分类数据
        $pids                = B2b2cGoodsClassModel::getGoodsClassDataInfo();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($pids AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'brand_name',
                'name'      => 'brand_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '品牌名称'
            ],
            [
                'field'     => 'brand_tjstore',
                'name'      => 'brand_tjstore',
                'form_type' => 'text',
                'title'     => '品牌副标题'
            ],
            [
                'field'     => 'brand_initial',
                'name'      => 'brand_initial',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '品牌首字母',
                'tips'      => '商家发布商品快捷搜索品牌使用'
            ],
            [
                'field'     => 'gc_id',
                'name'      => 'gc_id',
                'form_type' => 'select2',
                'title'     => '所属分类',
                'option'    => $goodsClassDataArr,
                'tips'      => ' 选择分类，可关联大分类或更具体的下级分类。'
            ],
            [
                'field'     => 'brand_pic',
                'name'      => 'brand_pic',
                'form_type' => 'image',
                'title'     => '品牌图片标识',
                'tips'      => ' 品牌LOGO尺寸要求宽度为150像素，高度为50像素、比例为3:1的图片；支持格式gif,jpg,png'
            ],
            [
                'field'     => 'brand_bgpic',
                'name'      => 'brand_bgpic',
                'form_type' => 'image',
                'title'     => '品牌大图背景URL',
                'tips'      => ' 请填写背景图片完整URL大小为1920px*460px；支持格式gif,jpg,png'
            ],
            [
                'field'     => 'show_type',
                'name'      => 'show_type',
                'title'     => '展示方式',
                'value'     => 0,
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '文字',
                    'off_text'  => '图片',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '在“全部品牌”页面的展示方式，如果设置为“图片”则显示该品牌的“品牌图片标识”，如果设置为“文字”则显示该品牌的“品牌名”'
            ],
            [
                'field'     => 'brand_recommend',
                'name'      => 'brand_recommend',
                'title'     => '是否推荐',
                'value'     => 0,
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '是',
                    'off_text'  => '否',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '选择被推荐的图片将在所有品牌列表页“推荐品牌”位置展现。'
            ],
            [
                'field'     => 'brand_sort',
                'name'      => 'brand_sort',
                'title'     => '排序',
                'value'     => 0,
                'form_type' => 'number',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'brand_introduction',
                'name'      => 'brand_introduction',
                'require'   => true,
                'form_type' => 'textarea',
                'title'     => '品牌介绍',
                'tips'      => '请填写品牌介绍，建议在20~200字之间'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 审核
     * @author 仇仇天
     * @param int $brand_id 品牌id
     */
    public function examine($brand_id = 0)
    {
        if ($brand_id === 0) $this->error('参数错误');

        if (false !== B2b2cBrandModel::update(['brand_apply' => 1], ['brand_id' => $brand_id])) {

            // 记录行为
            action_log('b2b2c.b2b2c_brand_examine');

            // 刷新缓存
            $this->refreshCache();

            $this->success('审核成功', url('index'));
        } else {
            $this->error('审核失败');
        }
    }

    /**
     * 删除/批量删除
     * @author 仇仇天
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $data = $this->request->post();
        $id   = $this->request->isPost() ? input('post.brand_id/a') : input('param.brand_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue[] = $value['brand_id'];
            }
            $map = [
                ['brand_id', 'in', $inValue]
            ];
        } else {
            $inValue = $id;
            $map     = [
                ['brand_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cBrandModel::del($map)) {
            B2b2cTypeBrandModel::del($map); // 类型关联品牌
            action_log('b2b2c.b2b2c_brand_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        B2b2cBrandModel::delCache();// 删除缓存
    }
}
