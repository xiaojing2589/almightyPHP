<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cGoodsClassTag as B2b2cGoodsClassTagModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;

/**
 * 商品分类标签
 * Class Advert
 * @package app\b2b2c\admin
 */
class GoodsClassTag extends Admin
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
    public function index($id = 0, $pid = 0)
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 关键词搜索字段名
            $search_field     = input('param.searchField/s', '', 'trim');

            // 搜索关键词
            $keyword          = input('param.searchKeyword/s', '', 'trim');

            // 筛选参数设置
            $map   = [];

            // 普通搜索筛选
            if ($search_field != '' && $keyword !== '')$map[] = [$search_field, 'like', "%".$keyword."%"];

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = B2b2cGoodsClassTagModel::where($map)->order('gc_tag_id ASC')->paginate($list_rows);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置头部按钮更新
        $view->addTopButton('custom', [
            'url' => url('update'),
            'disabled'=>true,
            'title'=>'更新标签',
            'class'=>'btn btn-success',
            'icon'=>'fa fa-refresh'
        ]);

        // 设置头部按钮更新重置
        $view->addTopButton('custom', [
            'url' => url('reset'),
            'disabled'=>true,
            'title'=>'重置标签',
            'class'=>'btn btn-info',
            'icon'=>'fa fa-refresh'
        ]);

        // 设置搜索框
        $view->setSearch([
            ['title' => '标签值', 'field' => 'gc_tag_value', 'default' => true],
            ['title' => '一级分类ID', 'field' => 'gc_id_1', 'default' => false],
            ['title' => '二级分类ID', 'field' => 'gc_id_2', 'default' => false],
            ['title' => '三级分类ID', 'field' => 'gc_id_3', 'default' => false],
        ]);

        // 提示信息
        $view->setExplanation([
            '标签值是分类搜索的关键字，请精确的填写标签值。标签值可以填写多个，每个值之间需要用逗号隔开。',
            '重置标签功能可以根据商品分类重新生成新的标签，标签值。',
            '更新标签功能可以根据商品分类重新更新标签，标签值默认为各级商品分类值。',
        ]);

        // 设置页面标题
        $view->setPageTitle('配置列表');

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置返回地址
        if ($id != 0) $view->setReturnUrl(url('index', ['id' => $pid]));

        // 设置分组标签
        $view->setGroup([
            ['title' => '管理', 'value' => 'goods_class', 'url' => url('goods_class/index', ['id' => $pid]),'default'=>false],
            ['title' => '标签管理', 'value' => 'goods_class_tag', 'url' => url('goods_class_tag/index'),'default'=>true]
        ]);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'gc_tag_id',
                'title'    => 'ID',
                'align'    => 'center',
                'width' => 50
            ],
            [
                'field'    => 'gc_tag_name',
                'title'    => '标签名称',
                'align'    => 'center'
            ],
            [
                'field'    => 'gc_tag_value',
                'title'    => '标签值',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'gc_id_1',
                'title'    => '一级分类ID',
                'align'    => 'center',
                'width'=>50
            ],
            [
                'field'    => 'gc_id_2',
                'title'    => '二级分类ID',
                'align'    => 'center',
                'width'=>50
            ],
            [
                'field'    => 'gc_id_3',
                'title'    => '三级分类ID',
                'align'    => 'center',
                'width'=>50
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 50,
                'btn'   => [
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["gc_tag_id"]}'
                    ]
                ]
            ]
        ]);

        return $view->fetch();

    }

    /**
     * 编辑
     * @param int $id
     * @return mixed
     * @throws \think\Exception
     * @author 仇仇天
     */
    public function edit($gc_tag_id = 0)
    {
        if ($gc_tag_id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
            }

            // 普通编辑
            else {
                $save_data = $data;
            }

            if (false !== B2b2cGoodsClassTagModel::update($save_data, ['gc_tag_id' => $gc_tag_id])) {
                action_log('b2b2c.b2b2c_goods_class_tag_edit');// 记录行为
                $this->refreshCache();
                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = B2b2cGoodsClassTagModel::where(['gc_tag_id'=>$gc_tag_id])->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('标签管理 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'gc_tag_id', 'value' => $gc_tag_id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'gc_tag_value',
                'name'      => 'gc_tag_value',
                'form_type' => 'textarea',
                'title'     => '标签值'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 重置标签
     * @author 仇仇天
     * @throws \Exception
     */
    public function reset(){

        // 删除全部标签
        B2b2cGoodsClassTagModel::where('1=1')->delete();

        // 获取第3级商品分类所有数据
        $levelData = B2b2cGoodsClassModel::getLevelData(3);

        // 设置分类标签
        $data = [];

        foreach ($levelData as $value){

            // 获取该分类所有父级分类
            $locatioData = B2b2cGoodsClassModel::getLocation($value['gc_id']);

            // 设置分类标签入库
            $data[] = [
                'gc_id_1'=>$locatioData[0]['gc_id'],
                'gc_id_2'=>$locatioData[1]['gc_id'],
                'gc_id_3'=>$locatioData[2]['gc_id'],
                'gc_tag_name'=> $locatioData[0]['gc_name'].'&nbsp;&gt;&nbsp;'.$locatioData[1]['gc_name'].'&nbsp;&gt;&nbsp;'.$locatioData[2]['gc_name'],
                'gc_tag_value'=>$locatioData[0]['gc_name'].','.$locatioData[1]['gc_name'].','.$locatioData[2]['gc_name'],
                'gc_id'=>$locatioData[2]['gc_id']
            ];
        }

        if(!empty($data)){
            if(false !== B2b2cGoodsClassTagModel::insertAll($data)){

                // 记录行为
                action_log('b2b2c.b2b2c_goods_class_tag_reset');

                // 刷新缓存
                $this->refreshCache();

                $this->success('重置成功', url('index'));

            }else{
                $this->error('重置失败');
            }
        }

        $this->success('重置成功', url('index'));
    }

    /**
     * 更新标签数据
     * @author 仇仇天
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update(){

        // 所有标签数据
        $b2b2cGoodsClassTagData =  B2b2cGoodsClassTagModel::getGoodsClassTagDataInfo();

        $data = [];

        foreach ($b2b2cGoodsClassTagData as $value){

            // 获取相应的商品分类信息
            $b2b2cGoodsClassInfo = B2b2cGoodsClassModel::getGoodsClassInfo($value['gc_id']);

            // 获取相应的父级信息
            $locatioData = B2b2cGoodsClassModel::getLocation($b2b2cGoodsClassInfo['gc_id']);

            $data[] = [
                'id'=>$value['gc_tag_id'],
                'gc_id_1'=>$locatioData[0]['gc_id'],
                'gc_id_2'=>$locatioData[1]['gc_id'],
                'gc_id_3'=>$locatioData[2]['gc_id'],
                'gc_tag_name'=> $locatioData[0]['gc_name'].'&nbsp;&gt;&nbsp;'.$locatioData[1]['gc_name'].'&nbsp;&gt;&nbsp;'.$locatioData[2]['gc_name'],
                'gc_tag_value'=>$locatioData[0]['gc_name'].','.$locatioData[1]['gc_name'].','.$locatioData[2]['gc_name'],
                'gc_id'=>$locatioData[2]['gc_id']
            ];
        }
        $B2b2cGoodsClassTagModel = new B2b2cGoodsClassTagModel();
        if(false !== $B2b2cGoodsClassTagModel->saveAll($data)){

            // 记录行为
            action_log('b2b2c.b2b2c_goods_class_tag_update');

            // 刷新缓存
            $this->refreshCache();

            $this->success('更新成功', url('index'));
        }else{
            $this->error('更新失败');
        }
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        B2b2cGoodsClassTagModel::delCache(); // 删除缓存
    }
}
