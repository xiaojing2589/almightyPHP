<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;
use app\b2b2c\model\B2b2cType as B2b2cTypeModel;
use app\b2b2c\model\B2b2cGoodsClassTag as B2b2cGoodsClassTagModel;
use app\b2b2c\model\B2b2cGoodsClassNav as B2b2cGoodsClassNavModel;
use app\b2b2c\model\B2b2cGoodsClassStaple as B2b2cGoodsClassStapleModel;
use app\b2b2c\model\B2b2cStoreBindClass as B2b2cStoreBindClassModel;
use app\b2b2c\model\B2b2cSellerGroupBclass as B2b2cSellerGroupBclassModel;
use app\b2b2c\model\B2b2cGoodsRecommend as B2b2cGoodsRecommendModel;
use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use app\b2b2c\model\B2b2cBrand as B2b2cBrandModel;

/**
 * 商品分类
 * Class Advert
 * @package app\b2b2c\admin
 */
class GoodsClass extends Admin
{
    /**
     * 列表
     * @param int $id
     * @param int $pid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 仇仇天
     */
    public function index($gc_id = 0, $gc_parent_id = 0)
    {
        $view = ZBuilder::make('tables');  // 初始化 表格

        if ($this->request->isAjax()) {
            $list_rows = input('list_rows'); // 每页显示多少条
            $data_list = B2b2cGoodsClassModel::where(['gc_parent_id' => $gc_id])->order('gc_sort ASC,gc_id ASC')->paginate($list_rows);  // 数据列表
            foreach ($data_list as &$value) {
                $value['gc_img'] = getB2b2cImg($value['gc_img'], ['type' => 'goods_class']);
                $trrData         = B2b2cGoodsClassModel::getGoodsClassInfo($value['gc_id']);
                $value['level']  = empty($trrData['level']) ? '' : $trrData['level'];
            }
            $view->setRowList($data_list);// 设置表格数据
        }

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮导出
        $view->addTopButton('custom', [
            'url'      => url('export'),
            'disabled' => true,
            'title'    => '导出所有',
            'class'    => 'btn btn-info',
            'icon'     => 'fa fa-cloud-download',
            'jump_way' => 'form'
        ]);

        // 设置头部按钮导入
        $view->addTopButton('custom', [
            'url'      => url('import'),
            'disabled' => true,
            'title'    => '导入',
            'class'    => 'btn btn-success',
            'icon'     => 'fa fa-cloud-upload',
            'jump_way' => 'form'
        ]);

        // 提示信息
        $view->setExplanation([
            '当店主添加商品时可选择商品分类，用户可根据分类查询商品列表',
            '对分类作任何更改后，都需要到 设置 -> 清理缓存 清理商品分类，新的设置才会生效',
            '“商品展示”为在商品列表页的展示方式。',
            '“颜色”：每个SPU只展示不同颜色的SKU，同一颜色多个SKU只展示一个SKU。',
            '“SPU”：每个SPU只展示一个SKU。',
            '“编辑分类导航”功能可以设置前台左上侧商品分类导航的相关信息，可以设置分类前图标、分类别名、推荐分类、推荐品牌以及两张广告图片。',
            '分类导航信息设置完成后，需要更新“首页及频道”缓存。',
            '商家入驻时可指定此处设置店铺分类', '第3级设置分类推荐商品'
        ]);

        // 设置页面标题
        $view->setPageTitle('分类列表');

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置返回地址
        if ($gc_id != 0) $view->setReturnUrl(url('index', ['gc_id' => $gc_parent_id]));

        // 设置分组标签
        $view->setGroup([
            ['title' => '管理', 'value' => 'goods_class', 'url' => url('index', ['id' => $gc_parent_id]), 'default' => true],
            ['title' => '标签管理', 'value' => 'goods_class_tag', 'url' => url('goods_class_tag/index'), 'default' => false]
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
                'field' => 'gc_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field'    => 'gc_name',
                'title'    => '名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'     => 'gc_img',
                'title'     => '图片',
                'align'     => 'center',
                'width'     => 50,
                'show_type' => 'avatar_image',
            ],
            [
                'field' => 'type_name',
                'title' => '类型名称',
                'align' => 'center'
            ],
            [
                'field' => 'commis_rate',
                'title' => '佣金比列(%)',
                'align' => 'center',
                'width' => 50
            ],
            [
                'field' => 'level',
                'title' => '级别',
                'align' => 'center',
                'width' => 50
            ],
            [
                'field'    => 'gc_sort',
                'title'    => '排序',
                'align'    => 'center',
                'width'    => 50,
                'editable' => [
                    'type' => 'number'
                ]
            ],
            [
                'field'       => 'gc_virtual',
                'title'       => '是否允许发布虚拟商品',
                'align'       => 'center',
                'width'       => 100,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '否', 'colour' => 'label-danger'],
                    ['value' => 1, 'text' => '是', 'colour' => 'label-info']
                ]
            ],
            [
                'field'       => 'show_type',
                'title'       => '商品展示方式',
                'align'       => 'center',
                'width'       => 120,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 1, 'text' => '颜色', 'colour' => 'label-primary'],
                    ['value' => 2, 'text' => 'spu', 'colour' => 'label-info']
                ]
            ],
            [
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'width'         => 400,
                'btn'           => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["gc_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '新增子级',
                        'ico'        => 'fa fa-plus',
                        'class'      => 'btn btn-xs btn-info',
                        'url'        => url('add'),
                        'query_data' => '{"field":["gc_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["gc_id"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '编辑分类导航',
                        'ico'        => 'fa fa-sitemap',
                        'class'      => 'btn btn-xs purple hide_navedit',
                        'url'        => url('navedit'),
                        'query_data' => '{"field":["gc_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '推荐商品',
                        'ico'        => 'fa fa-fw fa-mail-reply-all',
                        'class'      => 'btn btn-xs btn-primary hide_recommend',
                        'url'        => url('recommend'),
                        'query_data' => '{"field":["gc_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '查看子级',
                        'ico'        => 'fa fa-eye',
                        'class'      => 'btn btn-xs btn-success hide_see',
                        'url'        => url('index'),
                        'query_data' => '{"field":["gc_id","gc_parent_id"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ]
                ],
                'peration_hide' => <<<javascript
                        $.each(perationArr,function(i,v){
                            if(v.indexOf('hide_recommend') > -1){
                                if(row.level == 1 || row.level == 2){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_navedit') > -1){
                                if(row.level != 1){   
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
                            if(v.indexOf('hide_see') > -1){
                                if(row.level == 3){   
                                    delete perationArr[i]
                                }
                            }
                            
                        });   
javascript
            ]
        ]);

        //返回
        return $view->fetch();

    }

    /**
     * 新增
     * @param $id 父id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function add($gc_id = 0)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post(); // 表单数据
            $file = $this->request->file(); // 需要上传的文件
            // 图片
            if (!empty($file['gc_img'])) {
                $brandPicFileInfo = attaAdd($file['gc_img'], config('b2b2c.goods_class_path')); // 创建图片
                if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);// 上传错误
                $data['gc_img'] = $brandPicFileInfo['data']['relative_path_url'];
            }
            $result = $this->validate($data, 'GoodsClass.add');// 验证
            if (true !== $result) $this->error($result);// 错误提示
            if (false !== B2b2cGoodsClassModel::create($data)) {
                action_log('b2b2c.b2b2c_goods_class_add');// 记录行为
                $this->refreshCache(); // 刷新缓存
                $this->success('新增成功', url('goods_class/index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('分类管理 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('goods_class/index'));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 分列数
        $form->listNumber(2);

        // 分类数据
        $pids      = B2b2cGoodsClassModel::getGoodsClassDataInfo();
        $pid_arr   = [];
        $pid_arr[] = ['title' => '顶级分类', 'value' => 0];
        foreach ($pids AS $key => $value) {
            $pid_arr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
        }

        // 类型数据
        $typeData      = B2b2cTypeModel::getTypeDataInfo();
        $typeDataArr   = [];
        $typeDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($typeData as $type_k => $type_v) {
            $typeDataArr[] = ['title' => $type_v['type_name'], 'value' => $type_v['type_id']];
        }

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'gc_parent_id',
                'name'      => 'gc_parent_id',
                'form_type' => 'select2',
                'value'     => $gc_id,
                'title'     => '上级分类',
                'option'    => $pid_arr
            ],
            [
                'field'     => 'gc_name',
                'name'      => 'gc_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '分类名称'
            ],
            [
                'field'     => 'gc_img',
                'name'      => 'gc_img',
                'form_type' => 'image',
                'title'     => '分类图片',
                'tips'      => '分类图片尺寸要求宽度为145像素，高度为145像素,支持格式gif,jpg,png'
            ],
            [
                'field'     => 'gc_keywords',
                'name'      => 'gc_keywords',
                'form_type' => 'text',
                'title'     => '关键词'
            ],
            [
                'field'     => 'gc_virtual',
                'name'      => 'gc_virtual',
                'form_type' => 'checkbox',
                'title'     => '发布虚拟商品',
                'form_type' => 'switch',
                'option'    => [
                    'on_text'   => '允许',
                    'off_text'  => '不允许',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'      => '勾选允许发布虚拟商品后，在发布该分类的商品时可选择交易类型为“虚拟兑换码”形式。'
            ],
            [
                'field'      => 'show_type',
                'name'       => 'show_type',
                'title'      => '商品展示方式',
                'value'      => 1,
                'form_type'  => 'radio',
                'type_group' => 'type',
                'option'     => [
                    ['title' => '按颜色', 'value' => 1],
                    ['title' => '按SPU', 'value' => 2]
                ],
                'tips'       => '在商品列表页的展示方式。“颜色”：每个SPU只展示不同颜色的SKU，同一颜色多个SKU只展示一个SKU。“SPU”：每个SPU只展示一个SKU。'
            ],
            [
                'field'     => 'commis_rate',
                'name'      => 'commis_rate',
                'form_type' => 'number',
                'title'     => '佣金比列',
                'value'     => 0,
                'tips'      => '分佣比例必须为0-100的整数。'
            ],
            [
                'field'     => 'type_id',
                'name'      => 'type_id',
                'title'     => '类型',
                'form_type' => 'select2',
                'option'    => $typeDataArr,
            ],
            [
                'field'     => 'gc_sort',
                'name'      => 'gc_sort',
                'form_type' => 'number',
                'title'     => '排序',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'gc_description',
                'name'      => 'gc_description',
                'form_type' => 'textarea',
                'title'     => '描述'
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 编辑
     * @param int $id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function edit($gc_id = 0)
    {
        if ($gc_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                // 字段
                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'GoodsClass.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);
            } // 普通编辑
            else {

                // 验证提示报错
                $result = $this->validate($data, 'GoodsClass.edit');

                // 验证提示报错
                if (true !== $result) $this->error($result);

                // 需要上传的文件
                $file = $this->request->file();

                // 图片
                if (!empty($file['gc_img'])) {
                    $brandPicFileInfo = attaAdd($file['gc_img'], config('b2b2c.goods_class_path')); // 创建图片
                    if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);// 上传错误
                    $save_data['gc_img'] = $brandPicFileInfo['data']['relative_path_url'];
                }

                // 需要存储更新字段

                $save_data['gc_name']     = $data['gc_name'];
                $save_data['gc_keywords'] = $data['gc_keywords'];
                $save_data['gc_sort']     = $data['gc_sort'];
                $save_data['commis_rate'] = $data['commis_rate'];
                if (!empty($data['type_id'])) {
                    $typeInfo               = B2b2cTypeModel::getTypeInfo($data['type_id']);
                    $save_data['type_name'] = $typeInfo['type_name'];
                    $save_data['type_id']   = $data['type_id'];
                } else {
                    $save_data['type_name'] = '';
                    $save_data['type_id']   = 0;
                }
                $save_data['show_type']  = $data['show_type'];
                $save_data['gc_virtual'] = empty($data['gc_virtual']) ? 0 : 1;
            }
            if (false !== B2b2cGoodsClassModel::update($save_data, ['gc_id' => $gc_id])) {

                // 检测是否需要关联自己操作，统一查询子分类
                if (!empty($data['relation_gc_virtual']) || !empty($data['relation_show_type']) || !empty($data['relation_commis_rate']) || !empty($data['relation_type_id'])) {

                    // 该分类所有子类
                    $childsId   = B2b2cGoodsClassModel::getChildsId($gc_id);
                    $map        = [['gc_id', 'in', $childsId]];
                    $updateData = [];

                    // 是否允许发布虚拟商品
                    if (!empty($data['relation_gc_virtual'])) $updateData['gc_virtual'] = $save_data['gc_virtual'];

                    // 商品展示方式
                    if (!empty($data['relation_show_type'])) $updateData['show_type'] = $save_data['show_type'];

                    // 佣金比例
                    if (!empty($data['relation_commis_rate'])) $updateData['commis_rate'] = $save_data['commis_rate'];

                    // 类型
                    if (!empty($data['relation_type_id'])) {
                        $updateData['type_id']   = $save_data['type_id'];
                        $updateData['type_name'] = $save_data['type_name'];
                    }

                    B2b2cGoodsClassModel::where($map)->update($updateData);
                }

                // 记录行为
                action_log('b2b2c.b2b2c_goods_class_edit');

                // 刷新缓存
                $this->refreshCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cGoodsClassModel::where(['gc_id' => $gc_id])->find();

        // 分类图片
        $info['gc_img'] = getB2b2cImg($info['gc_img'], ['type' => 'goods_class']);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('分类管理 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'gc_id', 'value' => $gc_id]]);

        // 设置类型分组
        $form->setTypeGroup([
            ['name' => '基本', 'field' => 'default'],
            ['name' => '允许发布虚拟商品', 'field' => 'virtual'],
            ['name' => '商品展示方式', 'field' => 'show'],
            ['name' => '佣金比列', 'field' => 'commis'],
            ['name' => '类型', 'field' => 'type']
        ]);

        // 分列数
        $form->listNumber(2);

        // 提示信息
        $form->setExplanation([
            '"类型"关系到商品发布时商品规格的添加，没有类型的商品分类的将不能添加规格。',
            '勾选"关联到子分类"将商品类型附加到子分类，如子分类不同于上级分类的类型，可以取消勾选并单独对子分类的特定类型进行编辑选择。',
        ]);

        // 类型数据
        $typeData      = B2b2cTypeModel::getTypeDataInfo();
        $typeDataArr   = [];
        $typeDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($typeData as $type_k => $type_v) {
            $typeDataArr[] = ['title' => $type_v['type_name'], 'value' => $type_v['type_id']];
        }

        // 设置表单
        $form->addFormItems([
            [
                'field'     => 'gc_name',
                'name'      => 'gc_name',
                'form_type' => 'text',
                'require'   => true,
                'title'     => '分类名称'
            ],
            [
                'field'     => 'gc_img',
                'name'      => 'gc_img',
                'form_type' => 'image',
                'title'     => '分类图片',
                'tips'      => '分类图片尺寸要求宽度为145像素，高度为145像素,支持格式gif,jpg,png'
            ],
            [
                'field'     => 'gc_keywords',
                'name'      => 'gc_keywords',
                'form_type' => 'text',
                'title'     => '关键词'
            ],
            [
                'field'      => 'gc_virtual',
                'name'       => 'gc_virtual',
                'type_group' => 'virtual',
                'form_type'  => 'checkbox',
                'title'      => '允许发布虚拟商品',
                'form_type'  => 'switch',
                'option'     => [
                    'on_text'   => '允许',
                    'off_text'  => '不允许',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'       => '勾选允许发布虚拟商品后，在发布该分类的商品时可选择交易类型为“虚拟兑换码”形式。'
            ],
            [
                'field'      => 'relation_gc_virtual',
                'name'       => 'relation_gc_virtual',
                'type_group' => 'virtual',
                'title'      => '关联到子分类',
                'form_type'  => 'switch',
                'option'     => [
                    'on_text'   => '关联',
                    'off_text'  => '不关联',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'       => '勾选关联到子分类后，该分类下的子分类交易类型也将被设定为“虚拟兑换码”形式。'
            ],
            [
                'field'      => 'show_type',
                'name'       => 'show_type',
                'type_group' => 'show',
                'title'      => '商品展示方式',
                'form_type'  => 'radio',
                'option'     => [
                    ['title' => '按颜色', 'value' => 1],
                    ['title' => '按SPU', 'value' => 2]
                ],
                'tips'       => '在商品列表页的展示方式。“颜色”：每个SPU只展示不同颜色的SKU，同一颜色多个SKU只展示一个SKU。“SPU”：每个SPU只展示一个SKU。'
            ],
            [
                'field'      => 'relation_show_type',
                'name'       => 'relation_show_type',
                'type_group' => 'show',
                'title'      => '关联到子分类',
                'form_type'  => 'checkbox',
                'form_type'  => 'switch',
                'option'     => [
                    'on_text'   => '关联',
                    'off_text'  => '不关联',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'       => '勾选关联到子分类后，被绑定的商品展示方式也将继承到子分类中使用。'
            ],
            [
                'field'      => 'commis_rate',
                'name'       => 'commis_rate',
                'type_group' => 'commis',
                'form_type'  => 'number',
                'title'      => '佣金比列',
                'tips'       => '分佣比例必须为0-100的整数。'
            ],
            [
                'field'      => 'relation_commis_rate',
                'name'       => 'relation_commis_rate',
                'type_group' => 'commis',
                'title'      => '佣金比列关联到子分类',
                'form_type'  => 'switch',
                'option'     => [
                    'on_text'   => '关联',
                    'off_text'  => '不关联',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'       => '勾选关联到子分类后，该分类下的子分类分佣比利也将按此继承设定。'
            ],
            [
                'field'      => 'type_id',
                'name'       => 'type_id',
                'type_group' => 'type',
                'title'      => '类型',
                'form_type'  => 'select2',
                'option'     => $typeDataArr,
            ],
            [
                'field'      => 'relation_type_id',
                'name'       => 'relation_type_id',
                'type_group' => 'type',
                'title'      => '关联到子分类',
                'form_type'  => 'switch',
                'option'     => [
                    'on_text'   => '关联',
                    'off_text'  => '不关联',
                    'on_value'  => 1,
                    'off_value' => 0
                ],
                'tips'       => '勾选关联到子分类后，该分类下的子分类分佣比利也将按此继承设定。'
            ],
            [
                'field'     => 'gc_sort',
                'name'      => 'gc_sort',
                'form_type' => 'number',
                'title'     => '排序',
                'tips'      => '数字范围为0~255，数字越小越靠前'
            ],
            [
                'field'     => 'gc_description',
                'name'      => 'gc_description',
                'form_type' => 'textarea',
                'title'     => '描述'
            ],
        ]);

        // 设置表单数据
        $form->setFormdata($info);

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
        $id   = $this->request->isPost() ? input('post.gc_id/a') : input('param.gc_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue   = array_merge($inValue, B2b2cGoodsClassModel::getChildsId($value['gc_id']));
                $inValue[] = $value['gc_id'];
            }
            $map = [
                ['gc_id', 'in', $inValue]
            ];
        } else {
            $inValue = [];
            foreach ($id as $v) {
                $inValue    = B2b2cGoodsClassModel::getChildsId($v);
                $inValue [] = $v;
            }
            $map = [
                ['gc_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cGoodsClassModel::del($map)) {
            $map = [['gc_id_1|gc_id_2|gc_id_3', 'in', $inValue]];
            B2b2cGoodsClassStapleModel::del($map);// 删除常用商品分类
            B2b2cGoodsClassTagModel::del($map);// 删除商品分类标签
            B2b2cStoreBindClassModel::del($map);// 删除店铺绑定分类
            B2b2cSellerGroupBclassModel::del($map);// 删除商家权限组绑定的分类
            action_log('b2b2c.b2b2c_goods_class_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 导出
     * @author 仇仇天
     */
    public function export()
    {
        // 所有数据
        $goodsClassData = B2b2cGoodsClassModel::getGoodsClassDataInfo();

        // 记录行为
        action_log('b2b2c.b2b2c_goods_class_export');

        // 导出
        exportCsv($goodsClassData, ['ID', '父id', '分类ID', '是否允许发布虚拟商品(1=是，0=否)', '商品展示方式(1=按颜色，2=按SPU)', '分类名称', '分类标题', '关键词', '佣金比列', '排序', '描述'], 'goodsclass', 'php://output');
    }

    /**
     * 导入
     * @author 仇仇天
     */
    public function import()
    {
        if ($this->request->isPost()) {
            $files    = $this->request->file(); // 文件类型处理
            $data     = importCsv($files['name']->getInfo('tmp_name'));
            $saveData = [];
            foreach ($data as $key => $value) {
                if ($key !== 0) {
                    $validateData = [
                        'gc_id'          => $value[0],
                        'gc_parent_id'   => $value[1],
                        'type_id'        => $value[2],
                        'gc_virtual'     => $value[3],
                        'show_type'      => $value[4],
                        'gc_name'        => $value[5],
                        'gc_title'       => $value[6],
                        'gc_keywords'    => $value[7],
                        'commis_rate'    => $value[8],
                        'gc_sort'        => $value[9],
                        'gc_description' => $value[10]
                    ];
                    $result       = $this->validate($validateData, 'GoodsClass.add');// 验证
                    if (true !== $result) $this->error($result);// 错误提示
                    $saveData[] = $validateData;
                }
            }
            if (false !== B2b2cGoodsClassModel::insertAll($saveData)) {
                action_log('b2b2c.b2b2c_goods_class_import');// 记录行为
                $this->refreshCache(); // 刷新缓存
                $this->success('导入成功', url('goods_class/index'));
            } else {
                $this->error('导入失败');
            }
        }
        $form = ZBuilder::make('forms'); // 使用ZBuilder快速创建表单
        $form->setPageTitle('导入-商品分类'); // 设置页面标题
        $form->setReturnUrl(url('index')); // 设置返回地址
        $form->setFormUrl(url('import')); // 设置 提交地址
        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'file',
                'require'   => true,
                'title'     => '导入文件',
                'tips'      => '如果导入速度较慢，建议您把文件拆分为几个小文件，然后分别导入'
            ]
        ]);
        return $form->fetch('import');
    }

    /**
     * 商品推荐
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @author 仇仇天
     */
    public function recommend($gc_id = 0)
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        // 请求表格数据
        if ($this->request->isAjax()) {
            $list_rows = input('list_rows'); // 每页显示多少条
            // 数据列表
            $data_list = B2b2cGoodsRecommendModel::alias('a')
                ->field('a.rec_id,a.rec_gc_id,b.*')
                ->where(['a.rec_gc_id' => $gc_id])
                ->join('b2b2c_goods b', 'a.rec_goods_id = b.goods_id', 'LEFT')
                ->paginate($list_rows);
            foreach ($data_list as &$value) {
                $value['goods_image'] = getB2b2cImg($value['goods_image'], ['type' => 'goods']);
                $value['goods_price'] = ncPriceFormat($value['goods_price']);

            }
            $view->setRowList($data_list);// 设置表格数据
        }

        // 商品选择地址
        $url = url('goods/choice');

        // 商品推荐添加地址
        $addurl = url('goods_class/recommendadd');

        // 新增按钮 javascript 代码
        $addFuncitons = <<<javascript
                function(){                
                
                     // 初始化选择
                    base.variableParent = {id:[],data:[]};    
                                        
                       // 筛选参数
                    var resObj = {action:'tp'};         
                                        
                    // 已选的商品数据
                    var checkGoodsData = tableObj.bootstrapTable('getData');
                    
                    // 已选的商品id
                    var checkGoodsIds = [];
                                                            
                     // 循环获取需要筛选的条件数据                    
                    $.each(checkGoodsData, function (i,v) {
                        checkGoodsIds.push(v.goods_id);
                    });        
                                             
                    resObj.ids = checkGoodsIds;
                    
                    resObj.appoint_gc_id = {$gc_id};                    
                    
                    $.ajax({
                        type: 'POST',
                        url: "{$url}",//发送请求
                        data: resObj,
                        dataType : "html",
                        success: function(result) {
                            var htmlCont = result;
                            layer.open({
                                type: 1,
                                title: '商品选择',
                                shade: false,
                                maxmin: true,
                                scrollbar: false,
                                area : ['100%' , '100%'],
                                btn: ['确定'],
                                btn1: function(index, layero){
                                
                                    // 获取选中的否商品id
                                    id = base.variableParent.id;
                                    
                                    // 关闭窗口
                                    layer.closeAll();
                                    
                                    // 新增请求
                                    layer.load(2);
                                    $.ajax({
                                        type: 'POST',
                                        url: "{$addurl}",//发送请求
                                        data: {id:id,rec_gc_id:{$gc_id}},
                                        dataType : "json",
                                        success: function(result) {
                                            layer.closeAll('loading');                                            
                                            if (result.code == 0) {
                                                layer.msg(result.msg, {icon: 5});
                                            } else {
                                                layer.msg(result.msg, {icon: 6});
                                            }
                                            refreshMethod({pageNumber:null});
                                        },
                                        error: function () {
                                            layer.closeAll('loading');
                                            layer.msg('新增失败', {icon: 5});
                                        }
                                    });
                                },
                                content:htmlCont,
                            });
                        }
                    });
                }
javascript;

        // 设置javascript方法
        $view->setJsFunctionArr([['name' => 'addFuncitons', 'value' => $addFuncitons]]);

        //设置头部按钮
        $view->addTopButton('add', ['url' => url('recommendadd'), 'functions' => 'addFuncitons', 'functionsend' => true]); // 新增

        // 提示信息
        $view->setExplanation(['推荐的商品将显示在按商品分类搜索时的商品列表页面，显示位置位于主商品列表的上方。']);

        // 设置页面标题
        $view->setPageTitle('商品推荐列表');

        // 设置返回地址
        $gcinfo  = B2b2cGoodsClassModel::getLocationInfo($gc_id);
        $pgcinfo = B2b2cGoodsClassModel::getLocationInfo($gcinfo['gc_id']);
        $view->setReturnUrl(url('index', ['gc_id' => $gcinfo['gc_id'], 'gc_parent_id' => $pgcinfo['gc_id']]));

        // 设置列
        $view->setColumn([
            [
                'field' => 'goods_id',
                'title' => 'ID',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field' => 'goods_commonid',
                'title' => 'SKU',
                'width' => 50,
                'align' => 'center'
            ],
            [
                'field' => 'goods_name',
                'title' => '商品名称',
                'align' => 'center',
            ],
            [
                'field'     => 'goods_image',
                'title'     => '商品图片',
                'align'     => 'center',
                'width'     => 50,
                'show_type' => 'avatar_image',
            ],
            [
                'field' => 'goods_price',
                'title' => '商品价格',
                'align' => 'center',
                'width' => 50
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 400,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('recommenddel'),
                        'query_data' => '{"field":["rec_id","rec_gc_id"]}',
                        'query_type' => 'post'
                    ]
                ]
            ]
        ]);

        // 返回页面
        return $view->fetch();
    }

    /**
     * 商品推荐新增
     * @author 仇仇天
     */
    public function recommendAdd()
    {

        $data = input();

        if (empty($data['id'])) $this->error('新增失败');

        if (empty($data['rec_gc_id'])) $this->error('新增失败');

        // 分类信息
        $goodsClassInfo = B2b2cGoodsClassModel::getGoodsClassInfo($data['rec_gc_id']);

        if (empty($goodsClassInfo)) $this->error('新增失败');

        // 筛选条件
        $where = [];

        $where[] = ['goods_id', 'in', $data['id']];

        $where[] = ['gc_id', '=', $data['rec_gc_id']];

        $goodsData = B2b2cGoodsModel::where($where)->select();

        $saveDat = [];

        foreach ($goodsData as $value) {
            $saveDat[] = ['rec_gc_id' => $data['rec_gc_id'], 'rec_gc_name' => $goodsClassInfo['gc_name'], 'rec_goods_id' => $value['goods_id']];
        }

        if (!empty($saveDat)) {
            B2b2cGoodsRecommendModel::del(['rec_gc_id' => $data['rec_gc_id']]);
            if (false !== B2b2cGoodsRecommendModel::insertAll($saveDat)) {
                // 记录行为
                action_log('b2b2c.b2b2c_goods_recommend_add');
                $this->success('新增成功', url('recommend', ['gc_id' => $data['rec_gc_id'], 'gc_parent_id' => $goodsClassInfo['gc_parent_id']]));
            } else {
                $this->error('新增失败');
            }
        }
    }

    /**
     * 商品推荐删除
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     *
     * @author 仇仇天
     */
    public function recommendDel()
    {
        $data = input();
        if (false !== B2b2cGoodsRecommendModel::del(['rec_id' => $data['rec_id'], 'rec_gc_id' => $data['rec_gc_id']])) {
            // 记录行为
            action_log('b2b2c.b2b2c_goods_recommend_del');
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 编辑分类导航
     * @param int $gc_id 分类id
     * @return mixed
     * @author 仇仇天
     */
    public function navEdit($gc_id = 0)
    {
        if ($gc_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 需要上传的文件
            $file = $this->request->file();

            // 分类图片
            if (!empty($file['cn_pic'])) {

                // 创建图片
                $brandPicFileInfo = attaAdd($file['cn_pic'], config('b2b2c.goods_class_category_path'));

                // 上传错误
                if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);

                // 赋值
                $save_data['cn_pic'] = $brandPicFileInfo['data']['relative_path_url'];

            }

            // 分类广告图片1
            if (!empty($file['cn_adv1'])) {

                // 创建图片
                $brandPicFileInfo = attaAdd($file['cn_adv1'], config('b2b2c.goods_class_brand_path'));

                // 上传错误
                if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);

                // 赋值
                $save_data['cn_adv1'] = $brandPicFileInfo['data']['relative_path_url'];

            }

            // 分类广告图片2
            if (!empty($file['cn_adv2'])) {

                // 创建图片
                $brandPicFileInfo = attaAdd($file['cn_adv2'], config('b2b2c.goods_class_brand_path'));

                // 上传错误
                if (!$brandPicFileInfo['status']) $this->error($brandPicFileInfo['status']);

                // 赋值
                $save_data['cn_adv2'] = $brandPicFileInfo['data']['relative_path_url'];

            }

            $cn_classidsArr = [];
            if (!empty($data['cn_classids'])) {
                $classidsL = 1;
                foreach ($data['cn_classids'] as $value) {
                    if ($classidsL <= 8) {
                        $cn_classidsArr[] = $value;
                        $classidsL++;
                    }
                }
            }

            $cn_brandidsArr = [];
            if (!empty($data['cn_brandids'])) {
                $brandidsL = 1;
                foreach ($data['cn_brandids'] as $valueS) {
                    if ($brandidsL <= 8) {
                        $cn_brandidsArr[] = $valueS;
                        $brandidsL++;
                    }
                }
            }

            // 需要存储更新字段
            $save_data['cn_adv2_link'] = $data['cn_adv2_link'];
            $save_data['cn_adv1_link'] = $data['cn_adv1_link'];
            $save_data['cn_alias']     = $data['cn_alias'];
            $save_data['cn_classids']  = implode(',', $cn_classidsArr);
            $save_data['cn_brandids']  = implode(',', $cn_brandidsArr);

            if (false !== B2b2cGoodsClassNavModel::update($save_data, ['gc_id' => $gc_id])) {

                // 记录行为
                action_log('b2b2c.b2b2c_goods_goods_class_nav_edit');

                // 刷新缓存
                B2b2cGoodsClassNavModel::delCache();

                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cGoodsClassNavModel::where(['gc_id' => $gc_id])->find();

        // 分类图片
        $info['cn_pic'] = getB2b2cImg($info['cn_pic'], ['type' => 'goods_category']);

        // 广告图片1
        $info['cn_adv1'] = getB2b2cImg($info['cn_adv1'], ['type' => 'goods_brand']);

        // 广告图片2
        $info['cn_adv2'] = getB2b2cImg($info['cn_adv2'], ['type' => 'goods_brand']);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('分类导航 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('navedit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'gc_id', 'value' => $gc_id]]);

        // 设置类型分组
        $form->setTypeGroup([
            ['name' => '基本', 'field' => 'default'],
            ['name' => '广告1', 'field' => 'cn1'],
            ['name' => '广告2', 'field' => 'cn2']
        ]);

        // 分列数
        $form->listNumber(2);

        // 类型数据
        $typeData      = B2b2cTypeModel::getTypeDataInfo();
        $typeDataArr   = [];
        $typeDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($typeData as $type_k => $type_v) {
            $typeDataArr[] = ['title' => $type_v['type_name'], 'value' => $type_v['type_id']];
        }

        // 所有子分类
        $goodsClassArr    = [];
        $goodsClassChilds = B2b2cGoodsClassModel::getChildsData($gc_id);
        foreach ($goodsClassChilds as $child_k => $child_v) {
            $Ldata       = ['title' => $child_v['gc_name'], 'option' => []];
            $LdataChilds = $goodsClassChilds = B2b2cGoodsClassModel::getChildsData($child_v['gc_id']);
            foreach ($LdataChilds as $LdataChildsValue) {
                $Ldata['option'][] = ['title' => str_replace('┝', '', $LdataChildsValue['title_display']), 'value' => $LdataChildsValue['gc_id']];
            }
            $goodsClassArr[] = $Ldata;
        }

        // 所有品牌
        $BrandArr  = [];
        $BrandData = B2b2cBrandModel::getBrandDataInfo();
        foreach ($BrandData as $brand_k => $brand_v) {
            $BrandArr[] = ['title' => $brand_v['brand_name'], 'value' => $brand_v['brand_id']];
        }

        // 设置表单
        $form->addFormItems([
            [
                'field'     => 'cn_alias',
                'name'      => 'cn_alias',
                'form_type' => 'text',
                'title'     => '分类别名',
                'tips'      => '可选项。设置别名后，别名将会替代原分类名称展示在分类导航菜单列表中。'
            ],
            [
                'field'     => 'cn_pic',
                'name'      => 'cn_pic',
                'form_type' => 'image',
                'title'     => '分类图片',
                'tips'      => '建议使用16*16像素PNG透明背景图片'
            ],
            [
                'field'       => 'cn_classids',
                'name'        => 'cn_classids',
                'form_type'   => 'select2',
                'title'       => '推荐分类',
                'option'      => $goodsClassArr,
                'multiple'    => true,
                'optgroup'    => true,
                'allow_clear' => true,
                'tips'        => '推荐分类将在展开后的二、三级导航列表上方突出显示，建议根据分类名称长度控制选择数量不超过8个以确保展示效果。<code>按住Ctrl点击选择可选多个</code>'
            ],
            [
                'field'       => 'cn_brandids',
                'name'        => 'cn_brandids',
                'form_type'   => 'select2',
                'title'       => '推荐品牌',
                'option'      => $BrandArr,
                'multiple'    => true,
                'allow_clear' => true,
                'tips'        => '推荐品牌将在展开后的二、三级导航列表右侧突出显示，建议选择数量为8个具有图片的品牌，超过将被隐藏。<code>按住Ctrl点击选择可选多个</code>'
            ],
            [
                'field'      => 'cn_adv1',
                'name'       => 'cn_adv1',
                'type_group' => 'cn1',
                'form_type'  => 'image',
                'title'      => '广告图片1',
                'tips'       => '广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传'
            ],
            [
                'field'      => 'cn_adv1_link',
                'name'       => 'cn_adv1_link',
                'type_group' => 'cn1',
                'form_type'  => 'text',
                'title'      => '广告1链接',
                'tips'       => '如需跳转请在后方添加以http://开头的链接地址'
            ],
            [
                'field'      => 'cn_adv2',
                'name'       => 'cn_adv2',
                'type_group' => 'cn2',
                'form_type'  => 'image',
                'title'      => '广告图片2',
                'tips'       => '广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传'
            ],
            [
                'field'      => 'cn_adv2_link',
                'name'       => 'cn_adv2_link',
                'type_group' => 'cn2',
                'form_type'  => 'text',
                'title'      => '广告2链接',
                'tips'       => '如需跳转请在后方添加以http://开头的链接地址'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        B2b2cGoodsClassModel::delCache();
    }
}
