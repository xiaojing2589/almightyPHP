<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;

use util\PHPZip;
use util\File;

use app\admin\model\AdminConfig as AdminConfigModel;

use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;
use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use app\b2b2c\model\B2b2cGoodsCommon as B2b2cGoodsCommonModel;
use app\b2b2c\model\Product as ProductModel;


/**
 * 商品
 */
class Goods extends Admin
{
    /**
     * 列表
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 表单数据
            $data = $this->request->get();

            $where = [];

            // 筛选 spu
            if (isset($data['goods_commonid']) && $data['goods_commonid'] != '') $where[] = ['goods_commonid', '=', $data['goods_commonid']];

            // 筛选 商品名称
            if (isset($data['goods_name']) && $data['goods_name'] != '') $where[] = ['goods_name', 'like', "%" . $data['goods_name'] . "%"];

            // 筛选 店铺
            if (isset($data['store_name']) && $data['store_name'] != '') $where[] = ['store_name', 'like', "%" . $data['store_name'] . "%"];

            // 筛选 品牌名称
            if (isset($data['brand_name']) && $data['brand_name'] != '') $where[] = ['brand_name', 'like', "%" . $data['brand_name'] . "%"];

            //  排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'goods_commonid DESC' : $order;

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = B2b2cGoodsCommonModel::where($where)->order($order)->paginate($list_rows);

            foreach ($data_list as &$value) {
                // 商品主图
                $value['goods_image'] = getB2b2cImg($value['goods_image'], ['type' => 'goods']);

                // 库存
                $value['goods_storage'] = ProductModel::calculateStorage($value['goods_commonid']);
            }

            $view->setRowList($data_list);// 设置表格数据
        }

        //  导出
        $view->addTopButton('export', ['url' => url('export')]);

        // 设置高级搜索
        $view->setSeniorSearch([
            [
                'field'     => 'goods_commonid',
                'name'      => 'goods_commonid',
                'title'     => 'SKU',
                'form_type' => 'number'
            ],
            [
                'field'     => 'goods_name',
                'name'      => 'goods_name',
                'title'     => '商品名称',
                'form_type' => 'text',
                'value'     => ''
            ],
            [
                'field'     => 'store_name',
                'name'      => 'store_name',
                'title'     => '店铺名称',
                'form_type' => 'text',
                'value'     => ''
            ],
            [
                'field'     => 'brand_name',
                'name'      => 'brand_name',
                'title'     => '品牌名称',
                'form_type' => 'text',
                'value'     => ''
            ]
        ]);

        // 提示信息
        $view->setExplanation([
            '上架，当商品处于非上架状态时，前台将不能浏览该商品，店主可控制商品上架状态',
            '违规下架，当商品处于违规下架状态时，前台将不能购买该商品，只有管理员可控制商品违规下架状态，并且商品只有重新编辑后才能上架',
            '设置项中可以查看商品详细、查看商品SKU。查看商品详细，跳转到商品详细页。查看商品SKU，显示商品的SKU、图片、价格、库存信息。'
        ]);

        // 设置页面标题
        $view->setPageTitle('商品列表');

        // 设置分组标签
        $view->setGroup([
            ['title' => '商品管理', 'value' => 'goods', 'url' => url('index'), 'default' => true],
            ['title' => '商品设置', 'value' => 'goods_setting', 'url' => url('goodssetting'), 'default' => false]
        ]);

        //  违规
        $state10 = config('b2b2c.STATE10');

        // 出售中
        $state1 = config('b2b2c.STATE1');

        // 审核通过
        $verify1 = config('b2b2c.VERIFY1');

        // 设置列
        $view->setColumn([
            [
                'field'    => 'goods_commonid',
                'title'    => 'SPU',
                'width'    => 50,
                'sortable' => true,
                'align'    => 'center'
            ],
            [
                'field'     => 'goods_name',
                'title'     => '商品名称',
                'align'     => 'center',
                'formatter' => <<<javascript
                   var html = '<div class="media-list">'+
                                '<div class="media">'+
                                    '<a class="pull-left" href="javascript:;">'+
                                        '<img class="media-object" src="'+row.goods_image+'" alt="64x64" data-src="'+row.goods_image+'" style="width: 64px; height: 64px;">'+
                                    '</a>' +
                                    '<div style="display: table-cell;vertical-align: top;">'+
                                        '<dt>'+row.goods_name+'</dt>'+
                                    '</div>'+
                                '</div>'+
                            '<div/>';
                return html;
javascript
            ],
            [
                'field' => 'store_name',
                'title' => '店铺',
                'align' => 'center'
            ],
            [
                'field' => 'gc_name',
                'title' => '分类',
                'align' => 'center'
            ],
            [
                'field' => 'brand_name',
                'title' => '品牌',
                'align' => 'center'
            ],
            [
                'field'     => 'type_name',
                'title'     => '价格/运费(元)',
                'align'     => 'left',
                'valign'    => 'top',
                'width'     => '140',
                'formatter' => <<<javascript
                    var html  = '';                       
                                
                    html += '<div><span class="caption-subject bold uppercase">市场价：</span>';
                    if(row.goods_marketprice <= 0){
                        html += '<span class="caption-subject font-red-sunglo bold uppercase">'+row.goods_marketprice+'</span>';
                    }
                    if(row.goods_marketprice > 0){
                        html += '<span class="caption-subject font-blue-sharp bold uppercase">'+row.goods_marketprice+'</span>';
                    }
                    html +='</div> ';
                    
                    
                    html += '<div><span class="caption-subject bold uppercase">成本价：</span>';
                    if(row.goods_costprice <= 0){
                        html += '<span class="caption-subject font-red-sunglo bold uppercase">'+row.goods_costprice+'</span>';
                    }
                    if(row.goods_costprice > 0){
                        html += '<span class="caption-subject font-blue-sharp bold uppercase">'+row.goods_costprice+'</span>';
                    }
                    html +='</div> ';
                    
                    
                    html += '<div><span class="caption-subject bold uppercase">销售价：</span>';
                    if(row.goods_price <= 0){
                        html += '<span class="caption-subject font-red-sunglo bold uppercase">'+row.goods_price+'</span>';
                    }
                    if(row.goods_price > 0){
                        html += '<span class="caption-subject font-blue-sharp bold uppercase">'+row.goods_price+'</span>';
                    }
                    html +='</div> ';
                    

                    html += '<div><span class="caption-subject bold uppercase">运费价：</span>';
                    if(row.goods_freight <= 0){
                        html += '<span class="caption-subject font-red-sunglo bold uppercase">'+row.goods_freight+'</span>';
                    }
                    if(row.goods_freight > 0){
                        html += '<span class="caption-subject font-blue-sharp bold uppercase">'+row.goods_freight+'</span>';
                    }
                    html +='</div> ';
                    
                    
                    return html;
javascript
            ],
            [
                'field'       => 'goods_state',
                'title'       => '状态',
                'align'       => 'center',
                'sortable'    => true,
                'width'       => 100,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '仓库中', 'colour' => 'kt-font-warning'],
                    ['value' => 1, 'text' => '出售中', 'colour' => 'kt-font-success'],
                    ['value' => 10, 'text' => '违规下架', 'colour' => 'kt-font-danger kt-font-bold']
                ]
            ],
            [
                'field'       => 'goods_verify',
                'title'       => '审核',
                'align'       => 'center',
                'sortable'    => true,
                'width'       => 100,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '未通过', 'colour' => 'kt-font-danger kt-font-bold'],
                    ['value' => 1, 'text' => '出售中', 'colour' => 'kt-font-success'],
                    ['value' => 10, 'text' => '审核中', 'colour' => 'kt-font-warning']
                ]
            ],
            [
                'field'       => 'is_virtual',
                'title'       => '虚拟商品',
                'align'       => 'center',
                'sortable'    => true,
                'width'       => 50,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '否', 'colour' => 'kt-font-danger kt-font-bold'],
                    ['value' => 1, 'text' => '是', 'colour' => 'kt-font-success']
                ]
            ],
            [
                'field'       => 'is_own_shop',
                'title'       => '自营',
                'align'       => 'center',
                'sortable'    => true,
                'width'       => 30,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '否', 'colour' => 'kt-font-danger kt-font-bold'],
                    ['value' => 1, 'text' => '是', 'colour' => 'kt-font-success']
                ]
            ],
            [
                'field'     => 'level',
                'title'     => '库存',
                'align'     => 'center',
                'width'     => 70,
                'formatter' => <<<javascript
                    var html  = '';
                    if(row.goods_storage <= 0){
                        html += '<span class="kt-font-danger kt-font-bold">'+row.goods_storage+'</span>';
                    }
                    if(row.goods_storage > 0){
                        html += '<span class="kt-font-success">'+row.goods_storage+'</span>';
                    }
                    return html;
javascript
            ],
            [
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'width'         => 260,
                'btn'           => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["goods_commonid"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '审核',
                        'ico'        => 'fa fa-check',
                        'class'      => 'btn btn-xs btn-warning hide_verify',
                        'url'        => url('verify'),
                        'query_data' => '{"field":["goods_commonid"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '违规下架',
                        'ico'        => 'fa fa-ban',
                        'class'      => 'btn btn-xs btn-primary hide_state',
                        'url'        => url('state'),
                        'query_data' => '{"field":["goods_commonid"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '查看SPU',
                        'ico'        => 'fa fa-eye',
                        'class'      => 'btn btn-xs btn-success hide_seespu',
                        'url'        => url('seespu'),
                        'query_data' => '{"field":["goods_commonid"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ]
                ],
                'peration_hide' => <<<javascript
                        $.each(perationArr,function(i,v){
                            if(v.indexOf('hide_verify') > -1){
                                if(row.goods_verify == {$verify1}){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_state') > -1){
                                if(row.goods_state != {$state1}){   
                                    delete perationArr[i]
                                }
                            }                            
                            if(v.indexOf('hide_d') > -1){
                                if(row.goods_state != {$state10} && row.goods_verify != {$verify1}){   
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
     * 查看spu
     * @author 仇仇天
     * @param int $goods_commonid
     * @return mixed
     */
    public function seeSpu($goods_commonid = 0)
    {
        if (empty($goods_commonid)) $this->error('参数错误', url('index'));

        $view = ZBuilder::make('tables');  // 初始化 表格

        if ($this->request->isAjax()) {

            // 获取商品公共内容信息
//            $goodsCommonInfo = B2b2cGoodsCommonModel::getGoodsCommonInfo($goods_commonid);

//            if (empty($goodsCommonInfo)) $this->error('参数错误', url('index'));

            // 规格
//            $spec_name = array_values(json_decode($goodsCommonInfo['spec_name'], true));

            // 筛选参数设置
            $map = [];

            $map [] = ['goods_commonid', '=', $goods_commonid];

            // 排序设置
            $order = 'goods_storage DESC';

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = B2b2cGoodsModel::where($map)->order($order)->paginate($list_rows);

            foreach ($data_list as &$value) {
                // 设置图片地址
                $value['goods_image'] = getB2b2cImg($value['goods_image'], ['type' => 'goods']);
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('商品SKU列表');

        // 设置返回地址
        $view->setReturnUrl(url('index'));

        // 设置列
        $view->setColumn([
            [
                'field' => 'goods_id',
                'title' => 'SKU编号',
                'align' => 'center'
            ],
            [
                'field' => 'goods_name',
                'title' => 'SKU商品名称（+规格名称）',
                'align' => 'center'
            ],
            [
                'field'     => 'goods_image',
                'title'     => 'SKU图片',
                'align'     => 'center',
                'show_type' => 'avatar_image'
            ],
            [
                'field' => 'goods_storage',
                'title' => 'SKU库存',
                'align' => 'center'
            ],
            [
                'field' => 'goods_price',
                'title' => 'SKU价格',
                'align' => 'center'
            ]
        ]);

        return $view->fetch();

    }

    /**
     * 违规下架操作
     * @author 仇仇天
     * @param int $goods_commonid 商品公共id
     * @return mixed
     */
    public function state($goods_commonid = 0)
    {

        if (empty($goods_commonid)) $this->error('参数错误', url('index'));

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            // 需要更新的字段
            $saveData                      = [];
            $saveData['goods_stateremark'] = $data['goods_stateremark'];

            // 验证
            $result = $this->validate($saveData, 'GoodsCommon.goods_stateremark');

            // 验证提示报错
            if (true !== $result) $this->error($result);

            // 违规下架
            if (false !== ProductModel::editProducesLockUp(['goods_commonid' => $goods_commonid],$saveData)) {

                $this->success('新增成功', url('index'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('商品 - 违规下架');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('state'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'goods_commonid', 'value' => $goods_commonid]]);

        // 获取商品公共内容信息
        $info = B2b2cGoodsCommonModel::where(['goods_commonid' => $goods_commonid])->find();

        if (empty($info)) $this->error('该信息不存在', url('index'));

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'goods_commonid',
                'name'      => 'goods_commonid',
                'form_type' => 'static',
                'title'     => '违规商品货号'
            ],
            [
                'field'     => 'goods_name',
                'name'      => 'goods_name',
                'form_type' => 'static',
                'title'     => '违规商品名称'
            ],
            [
                'field'     => 'goods_stateremark',
                'name'      => 'goods_stateremark',
                'form_type' => 'textarea',
                'title'     => '违规下架理由'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        return $form->fetch();
    }

    /**
     * 审核
     * @param int $goods_commonid 公共商品id
     * @author 仇仇天
     */
    public function verify($goods_commonid = 0)
    {

        if (empty($goods_commonid)) $this->error('参数错误', url('index'));

        if ($this->request->isPost()) {

            // 表单数据
            $data                            = $this->request->post();
            $saveData                       = [];
            $saveData['goods_verify']       = $data['goods_verify'];
            $saveData['goods_verifyremark'] = ($data['goods_verify'] == config('b2b2c.VERIFY0')) ? $data['goods_verifyremark'] : '';

            // 验证提示报错
            if ($saveData['goods_verify'] == config('b2b2c.VERIFY0') && empty($saveData['goods_verifyremark'])){
                $this->error('请填写审核不通过原因');
            }

            if (false !== ProductModel::editProducesVerifyFail(['goods_commonid' => $goods_commonid],$saveData)) {
                $this->success('审核成功', url('index'));
            } else {
                $this->error('审核失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('商品 - 审核');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('verify'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'goods_commonid', 'value' => $goods_commonid]]);

        // 获取商品公共内容信息
        $info = B2b2cGoodsCommonModel::where(['goods_commonid' => $goods_commonid])->find();

        if (empty($info)) $this->error('该信息不存在', url('index'));

        $infoData = ['goods_commonid' => $info['goods_commonid'], 'goods_name' => $info['goods_name']];

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'goods_commonid',
                'name'      => 'goods_commonid',
                'form_type' => 'static',
                'title'     => '违规商品货号'
            ],
            [
                'field'     => 'goods_name',
                'name'      => 'goods_name',
                'form_type' => 'static',
                'title'     => '违规商品名称'
            ],
            [
                'field'     => 'goods_verify',
                'name'      => 'goods_verify',
                'form_type' => 'switch',
                'title'     => '是否审核通过',
                'value'     => 1,
                'option'    => [
                    'on_text'   => '通过',
                    'on_value'  => config('b2b2c.VERIFY1'),
                    'off_text'  => '不通过',
                    'off_value' => config('b2b2c.VERIFY0')
                ]
            ],
            [
                'field'     => 'goods_verifyremark',
                'name'      => 'goods_verifyremark',
                'form_type' => 'textarea',
                'title'     => '审核失败原因'
            ]
        ]);

        // 设置表单数据
        $form->setFormdata($infoData);

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
        $id   = $this->request->isPost() ? input('post.brand_id/a') : input('param.brand_id');
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            $inValue = [];
            foreach ($data['batch_data'] as $value) {
                $inValue[] = $value['brand_id'];
            }
            $where = [
                ['brand_id', 'in', $inValue]
            ];
        } else {
            $inValue = $id;
            $where     = [
                ['brand_id', 'in', $inValue]
            ];
        }
        if (false !== B2b2cGoodsCommonModel::del($where)) {
            $this->success('删除成功');
        } else {
            $this->error('操作失败，请重试');
        }
    }

    /**
     * 商品设置
     * @author 仇仇天
     */
    public function goodsSetting()
    {
        if ($this->request->isPost()) {
            // 表单数据
            $data               = input();
            $save_data          = [];
            $save_data['value'] = $data['b2b2c_goods_verify'];
            if (false !== AdminConfigModel::where(['name' => 'b2b2c_goods_verify'])->update($save_data)) {
                AdminConfigModel::delCache();
                $this->success('设置成功', url('goodssetting'));
            } else {
                $this->error('设置失败');
            }
        }

        // 获取配置
        $goodsVerifyConfig = AdminConfigModel::getByNameConfig('b2b2c_goods_verify');

        // 解析配置
        $options = json_decode($goodsVerifyConfig['options'], true);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('商品 - 设置');

        // 设置 提交地址
        $form->setFormUrl(url('goodssetting'));

        // 设置分组标签
        $form->setGroup([
            ['title' => '商品管理', 'value' => 'goods', 'url' => url('index'), 'default' => false],
            ['title' => '商品设置', 'value' => 'goods_setting', 'url' => url('goodssetting'), 'default' => true]
        ]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'b2b2c_goods_verify',
                'name'      => 'b2b2c_goods_verify',
                'form_type' => 'switch',
                'title'     => '商品是否需要审核',
                'value'     => $goodsVerifyConfig['value'],
                'option'    => $options,
                'tips'      => $goodsVerifyConfig['tips']
            ]
        ]);

        return $form->fetch();
    }

    /**
     * 导出
     * @author 仇仇天
     */
    public function export()
    {

        // 导出参数
        $exportExcelParam = [];

        $list_rows = input('list_rows');

        $page = input('page');

        // 是否下载
        $download = input('download');

        // 导出文件存储路径
        $export_path = config('export_path');

        // 临时目录名
        $temp = input('temp');

        if (empty($temp)) {
            $temp = md5(microtime(true));
        }

        // 存储路径
        $storage_path = $export_path . '/' . $temp;

        // 要存储的文件名
        $filename = 'goods_' . $page . '.xlsx';

        // 检查目录是否存在
        if (!is_dir($storage_path)) {
            mkdir($storage_path, 0777, true);
        }
        if (!empty($download)) {
            if ($page == 1) {
                $out = File::downloadFile($storage_path . '/' . $filename);
                File::del_dir($storage_path);
                return $out;
            } else {
                // 打包下载
                $archive = new PHPZip;
                $out     = $archive->ZipAndDownload($storage_path . '/', 'goods');
                File::del_dir($storage_path);
                return $out;

            }
        }

        $exportExcelParam['export_path'] = $storage_path . '/' . $filename;

        // 获取数据
        $dataList = B2b2cGoodsCommonModel::paginate($list_rows);

        // 分页
        $percentage = round(100 * ($page / $dataList->lastPage()), 1);

        // 列头设置标题
        $exportExcelParam['cell'] = [
            ['title' => 'SPU', 'find' => 'goods_commonid', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '商品名称', 'find' => 'goods_name', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '商品价格(元)', 'find' => 'goods_price', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '市场价格(元)', 'find' => 'goods_marketprice', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '成本价格(元)', 'find' => 'goods_costprice', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '运费(元)', 'find' => 'goods_freight', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '商品状态', 'find' => 'goods_state', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '审核状态', 'find' => 'goods_verify', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '商品图片', 'find' => 'goods_image', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '广告词', 'find' => 'goods_jingle', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '分类ID', 'find' => 'gc_id', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '店铺ID', 'find' => 'store_id', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '店铺名称', 'find' => 'store_name', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '品牌ID', 'find' => 'brand_id', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '品牌名称', 'find' => 'brand_name', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '发布时间', 'find' => 'create_time', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '库存', 'find' => 'goods_storage', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '虚拟商品', 'find' => 'is_virtual', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '有效期', 'find' => 'virtual_indate', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '过期允许退款', 'find' => 'virtual_invalid_refund', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => '', 'Bold' => true, 'freeze_pane' => true],
            ['title' => '是否自营', 'find' => 'is_own_shop', 'height' => '', 'width' => '', 'size' => '', 'word_colour' => '', 'horizontal' => 'center', 'vertical' => 'center', 'Bold' => true, 'freeze_pane' => true]

        ];

        // 设置数据
        $data        = to_arrays($dataList)['data'];
        $dataListArr = [];
        foreach ($data as $value) {
            $dataListArr[] = [
                'goods_commonid'         => [
                    'value'       => $value['goods_commonid'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_name'             => [
                    'value'       => $value['goods_name'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_price'            => [
                    'value'       => ncPriceFormat($value['goods_price']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_marketprice'      => [
                    'value'       => ncPriceFormat($value['goods_marketprice']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_costprice'        => [
                    'value'       => ncPriceFormat($value['goods_costprice']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_freight'          => [
                    'value'       => $value['goods_freight'] == 0 ? '免运费' : ncPriceFormat($value['goods_freight']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_state'            => [
                    'value'       => $value['goods_state'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_verify'           => [
                    'value'       => $value['goods_verify'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_image'            => [
                    'value'       => getB2b2cImg($value['goods_image'], ['type' => 'goods']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_jingle'           => [
                    'value'       => htmlspecialchars($value['goods_jingle']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'gc_id'                  => [
                    'value'       => $value['gc_id'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'store_id'               => [
                    'value'       => $value['store_id'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'store_name'             => [
                    'value'       => $value['store_name'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'is_own_shop'            => [
                    'value'       => $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户',
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'brand_id'               => [
                    'value'       => $value['brand_id'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'brand_name'             => [
                    'value'       => $value['brand_name'],
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'create_time'            => [
                    'value'       => date('Y-m-d', $value['create_time']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'goods_storage'          => [
                    'value'       => ProductModel::calculateStorage($value['goods_commonid']),
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'is_virtual'             => [
                    'value'       => $value['is_virtual'] == '1' ? '是' : '否',
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'virtual_indate'         => [
                    'value'       => $value['is_virtual'] == '1' && $value['virtual_indate'] > 0 ? date('Y-m-d', $value['virtual_indate']) : '--',
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ],
                'virtual_invalid_refund' => [
                    'value'       => $value['is_virtual'] == '1' ? ($value['virtual_invalid_refund'] == 1 ? '是' : '否') : '--',
                    'height'      => '',
                    'width'       => '',
                    'size'        => '',
                    'word_colour' => '',
                    'horizontal'  => 'center',
                    'vertical'    => 'center',
                    'Bold'        => false,
                    'freeze_pane' => false
                ]
            ];
        }
        $exportExcelParam['data'] = $dataListArr;

        // 生成文件
        exportExcel($exportExcelParam);

        if ($page == $dataList->lastPage()) {
            $this->success('操作成功', '', ['status' => 2, 'percentage' => $percentage, 'temp' => $temp]);
        } else {
            $this->success('操作成功', '', ['status' => 1, 'percentage' => $percentage, 'temp' => $temp]);
        }

    }

    /**
     * 商品选择(测试中)
     * @author 仇仇天
     */
    public function choice()
    {
        $data = input();

        // 渲染数据
        if (!empty($data['action'])) {

            // 页面所需要js & css
            $_js_files  = ['bootstraptable_js', 'select2_js'];
            $_css_files = ['bootstraptable_css', 'select2_css'];
            $this->assign('_js_files', $_js_files);
            $this->assign('_css_files', $_css_files);

            // 商品分类
            $pids                = B2b2cGoodsClassModel::getMenuDataTree(0);
            $goodsClassDataArr   = [];
            $goodsClassDataArr[] = ['title' => '请选择商品分类', 'value' => 0];
            foreach ($pids AS $key => $value) {
                $goodsClassDataArr[] = ['title' => $value['title_display'], 'value' => $value['gc_id']];
            }
            $this->assign('goodsClassDataArr', $goodsClassDataArr);

            // 指定类型
            if (!empty($data['appoint_type'])) {
                $this->assign('appoint_type', $data['appoint_type']);
            }

            // 指定分类
            if (!empty($data['appoint_gc_id'])) {
                $this->assign('gc_id', $data['appoint_gc_id']);
            }

            // 指定分类1
            if (!empty($data['appoint_gc_id_1'])) {
                $this->assign('gc_id_1', $data['appoint_gc_id_1']);
            }

            // 指定分类2
            if (!empty($data['appoint_gc_id_2'])) {
                $this->assign('gc_id_2', $data['appoint_gc_id_2']);
            }

            // 指定分类3
            if (!empty($data['appoint_gc_id_3'])) {
                $this->assign('gc_id_3', $data['appoint_gc_id_3']);
            }

            // 已选商品
            if (!empty($data['ids']) && is_array($data['ids'])) {
                $ids = json_encode($data['ids'], JSON_NUMERIC_CHECK);
                $this->assign('goods_ids', $ids);
            }

            return $this->fetch();
        }


        $where = [];
        if (isset($data['search_goods_class']) && !empty($data['search_goods_class'])) {
            if (empty($data['appoint_gc_id']) && empty($data['appoint_gc_id_1']) && empty($data['appoint_gc_id_2']) && empty($data['appoint_gc_id_3'])) {
                $where[] = ['gc_id', '=', $data['search_goods_class']];
            }
        }
        if (isset($data['search_goods_name']) && !empty($data['search_goods_name'])) $where[] = ['goods_name', 'like', "%" . $data['search_goods_name'] . "%"];


        $data_list    = B2b2cGoodsModel::where($where)->paginate($data['list_rows']);
        $res          = [];
        $res['code']  = 200; // 状态
        $res['data']  = to_arrays($data_list->items()); // 数据
        $res['total'] = $data_list->total(); // 总条数
        foreach ($res['data'] as &$value) {
            $value['goods_image'] = getB2b2cImg($value['goods_image'], ['type' => 'goods']);
        }
        echo json_encode($res);
        exit;
    }

}
