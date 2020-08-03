<?php

namespace app\b2b2c\admin;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\b2b2c\model\B2b2cStore as B2b2cStoreModel;
use app\b2b2c\model\B2b2cStoreClass as B2b2cStoreClassModel;
use app\b2b2c\model\B2b2cStoreJoinin as B2b2cStoreJoininModel;

use app\b2b2c\model\B2b2cTypeBrand as B2b2cTypeBrandModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;
use app\b2b2c\model\B2b2cBrand as B2b2cBrandModel;
use app\b2b2c\model\B2b2cStoreGrade as B2b2cStoreGradeModel;
use app\common\model\Area as AreaModel;

/**
 * 店铺管理
 */
class Store extends Admin
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

            $where[] = ['is_own_shop', '=', '0'];

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
            $order = empty($orderSort) ? 'store_id ASC' : $order;

            // 数据列表
            $dataList = B2b2cStoreModel::where($where)->order($order)->paginate($data['list_rows']);

            foreach ($dataList as $key => &$value) {
//                $value['store_avatar']   = getB2b2cImg($value['store_avatar'], ['type' => 'store_avatar']);
//                $value['store_logo']     = getB2b2cImg($value['store_logo'], ['type' => 'store_logo']);
                $value['store_time']     = format_time($value['store_time']);
                $value['store_end_time'] = format_time($value['store_end_time']);
            }

            // 设置表格数据
            $view->setRowList($dataList);
        }

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('add')]);

        // 设置搜索框
        $view->setSearch([
            ['title' => '店铺名称', 'field' => 'store_name', 'condition' => 'like', 'default' => false],
            ['title' => '店主账号', 'field' => 'member_name', 'condition' => 'like', 'default' => true],
            ['title' => '商家账号', 'field' => 'seller_name', 'condition' => 'like', 'default' => false]
        ]);

        // 提示信息
        $view->setExplanation([
            '如果当前时间超过店铺有效期或店铺处于关闭状态，前台将不能继续浏览该店铺，但是店主仍然可以编辑该店铺'
        ]);

        // 设置页面标题
        $view->setPageTitle('店铺列表');

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 商品分类数据
        $goodsClassData      = B2b2cStoreClassModel::getStoreClassDataAll();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['text' => '无', 'id' => 0];
        foreach ($goodsClassData AS $key => $value) {
            $goodsClassDataArr[] = ['text' => $value['sc_name'] . '【保证金数额：' . $value['sc_bail'] . '元】', 'id' => $value['sc_id']];
        }

        // 店铺等级
        $storeGradeData      = B2b2cStoreGradeModel::getStoreGradeData();
        $storeGradeDataArr   = [];
        $storeGradeDataArr[] = ['text' => '无', 'id' => 0];
        foreach ($storeGradeData AS $key => $value) {
            $storeGradeDataArr[] = ['text' => $value['sg_name'] . '【开店费用：' . $value['sg_price'] . '元/年】', 'id' => $value['sg_id']];
        }


        // 设置列
        $view->setColumn([
            [
                'field'    => 'store_id',
                'title'    => 'ID',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'store_name',
                'title'    => '店铺名称',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'     => 'member_name',
                'title'     => '店主账号',
                'align'     => 'left',
                'width'     => 130,
                'formatter' => <<<javascript
                    var html  = '';                       
                                
                    html += '<div><span class="caption-subject bold uppercase">店主账号：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.member_name+'</span>';
                    html += '</div> ';
                    
                    
                    html += '<div><span class="caption-subject bold uppercase">商家账号：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.seller_name+'</span>';
                    html += '</div> ';                   
                    
                    return html;
javascript
            ],
            [
                'field'    => 'grade_id',
                'title'    => '店铺等级',
                'align'    => 'center',
                'editable' => [
                    'type'              => 'select2',
                    'dropdownAutoWidth' => true,
                    'source'            => $storeGradeDataArr
                ]
            ],
            [
                'field'    => 'sc_id',
                'title'    => '店铺分类',
                'align'    => 'center',
                'editable' => [
                    'type'              => 'select2',
                    'dropdownAutoWidth' => true,
                    'source'            => $goodsClassDataArr

                ]
            ],
            [
                'field'     => 'store_avatar',
                'title'     => '店铺头像',
                'align'     => 'center',
                'width'     => 100,
                'show_type' => 'avatar_image',
            ],
            [
                'field'     => 'store_logo',
                'title'     => '店铺logo',
                'align'     => 'center',
                'width'     => 100,
                'show_type' => 'avatar_image',
            ],
            [
                'field'     => 'store_time',
                'title'     => '时间',
                'align'     => 'left',
                'width'     => 180,
                'formatter' => <<<javascript
                    var html  = '';                       
                                
                    html += '<div><span class="caption-subject bold uppercase">开店时间：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_time+'</span>';
                    html += '</div> ';
                    
                    
                    html += '<div><span class="caption-subject bold uppercase">到期时间：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_end_time+'</span>';
                    html += '</div> ';                 
                    
                    return html;
javascript
            ],
            [
                'field'     => 'store_qq',
                'title'     => '信息',
                'align'     => 'left',
                'valign'    => 'top',
                'width'     => 180,
                'formatter' => <<<javascript
                    var html  = '';         
                                
                    html += '<div><span class="caption-subject bold uppercase">QQ：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_qq+'</span>';
                    html += '</div> ';
                    
                    
                    html += '<div><span class="caption-subject bold uppercase">旺旺：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_ww+'</span>';
                    html += '</div> ';     
                    
                    html += '<div><span class="caption-subject bold uppercase">电话：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_phone+'</span>';
                    html += '</div> ';             
                    
                    return html;
javascript
            ],
            [
                'field'       => 'store_state',
                'title'       => '状态',
                'align'       => 'center',
                'sortable'    => true,
                'width'       => 50,
                'show_type'   => 'status',
                'show_config' => [
                    ['value' => 0, 'text' => '未审核', 'colour' => 'kt-font-warning'],
                    ['value' => 1, 'text' => '通过', 'colour' => 'kt-font-success']
                ]
            ],
            [
                'field'     => 'area',
                'title'     => '所在地区',
                'align'     => 'left',
                'valign'    => 'top',
                'formatter' => <<<javascript
                    var html  = '';      
                                     
                    html += '<div><span class="caption-subject bold uppercase">地区：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.area_info+'</span>';
                    html += '</div> ';       
                                
                    html += '<div><span class="caption-subject bold uppercase">详细地址：</span>';
                    html += '<span class="caption-subject bold uppercase">'+row.store_address+'</span>';
                    html += '</div> ';           
                    
                    return html;
javascript
            ],
            [
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'width'         => 230,
                'btn'           => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["store_id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["store_id"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '修改经营类目',
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
     * @param int $store_id 店铺id
     * @return mixed
     * @author 仇仇天
     */
    public function edit($tag_type = 'store_info')
    {
        $store_id = input('store_id');

        if (empty($store_id)) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $saveData = [];

            if ($tag_type == 'store_info') {
                // 是否行内修改
                if (!empty($data['extend_field'])) {

                    // 设置需要更新的字段
                    $saveData[$data['extend_field']] = $data[$data['extend_field']];

                    // 验证
                    $result = $this->validate($data, 'Store.' . $data['extend_field']);

                    // 验证提示报错
                    if (true !== $result) $this->error($result);
                } // 普通编辑
                else {
                    // 地区数据
                    $areaData = AreaModel::getAreaData();

                    // 地区冗余
                    $areaInfo = '';
                    if (!empty($data['province_id'])) {
                        $province = $areaData[$data['province_id']];
                        if (!empty($province)) {
                            $saveData['province_id'] = $data['province_id'];
                            $areaInfo                .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['city_id'])) {
                        $city = $areaData[$data['city_id']];
                        if (!empty($city)) {
                            $saveData['city_id'] = $data['city_id'];
                            $areaInfo            .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['area_id'])) {
                        $area = $areaData[$data['area_id']];
                        if (!empty($area)) {
                            $saveData['area_id'] = $data['area_id'];
                            $areaInfo            .= '-' . $area['area_name'];
                        }
                    }

                    // 需要更新的字段
                    $saveData['store_name']         = $data['store_name'];
                    $saveData['store_company_name'] = $data['store_company_name'];
                    $saveData['province_id']        = $data['province_id'];
                    $saveData['city_id']            = $data['city_id'];
                    $saveData['area_id']            = $data['area_id'];
                    $saveData['area_info']          = $areaInfo;
                    $saveData['store_address']      = $data['store_address'];
                    $saveData['sc_id']              = $data['sc_id'];
                    $saveData['grade_id']           = $data['grade_id'];
                    $saveData['store_end_time']     = empty($data['store_end_time']) ? '' : strtotime('2020-07-25');
                    $saveData['store_state']        = $data['store_state'];

                    // 验证
                    $result = $this->validate($saveData, 'Store');
                    // 验证提示报错
                    if (true !== $result) $this->error($result);

                }
                if (false !== B2b2cStoreModel::edit(['store_id' => $store_id], $saveData)) {

                    $this->success('编辑成功', url('index'));
                } else {
                    $this->error('编辑失败');
                }
            } else {

                // 地区数据
                $areaData = AreaModel::getAreaData();

                // 需要上传的文件
                $file = $this->request->file();

                if ($data['is_person']) {

                    // 身份证正面
                    if (!empty($file['organization_code_electronic'])) {

                        // 创建图片
                        $organizationCodeElectronicFileInfo = attaAdd($file['organization_code_electronic'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$organizationCodeElectronicFileInfo['status']) $this->error($organizationCodeElectronicFileInfo['status']);

                        // 获取连接
                        $saveData['organization_code_electronic'] = $organizationCodeElectronicFileInfo['data']['relative_path_url'];
                    }

                    // 身份证反面
                    if (!empty($file['general_taxpayer'])) {

                        // 创建图片
                        $generalTaxpayerFileInfo = attaAdd($file['general_taxpayer'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$generalTaxpayerFileInfo['status']) $this->error($generalTaxpayerFileInfo['status']);

                        // 获取连接
                        $saveData['general_taxpayer'] = $generalTaxpayerFileInfo['data']['relative_path_url'];
                    }

                    // 手持身份证
                    if (!empty($file['business_licence_number_elc'])) {

                        // 创建图片
                        $businessLicenceNumberElcFileInfo = attaAdd($file['business_licence_number_elc'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$businessLicenceNumberElcFileInfo['status']) $this->error($businessLicenceNumberElcFileInfo['status']);

                        // 获取连接
                        $saveData['business_licence_number_elc'] = $businessLicenceNumberElcFileInfo['data']['relative_path_url'];
                    }

                    // 家庭所在地区冗余
                    $companyAreaInfo = '';
                    if (!empty($data['company_province_id'])) {
                        $province = $areaData[$data['company_province_id']];
                        if (!empty($province)) {
                            $saveData['company_province_id'] = $data['company_province_id'];
                            $companyAreaInfo                 .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['company_city_id'])) {
                        $city = $areaData[$data['company_city_id']];
                        if (!empty($city)) {
                            $saveData['company_city_id'] = $data['company_city_id'];
                            $companyAreaInfo             .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['company_area_id'])) {
                        $area = $areaData[$data['company_area_id']];
                        if (!empty($area)) {
                            $saveData['company_area_id'] = $data['company_area_id'];
                            $companyAreaInfo             .= '-' . $area['area_name'];
                        }
                    }

                    // 需要更新的字段
                    $saveData['company_name']                   = $data['company_name'];
                    $saveData['company_address']                = $companyAreaInfo;
                    $saveData['company_address_detail']         = $data['company_address_detail'];
                    $saveData['contacts_name']                  = $data['contacts_name'];
                    $saveData['contacts_phone']                 = $data['contacts_phone'];
                    $saveData['contacts_email']                 = $data['contacts_email'];
                    $saveData['settlement_bank_account_name']   = $data['settlement_bank_account_name'];
                    $saveData['settlement_bank_account_number'] = $data['settlement_bank_account_number'];

                    // 验证
                    $result = $this->validate($saveData, 'StoreJoininPersonal');

                    // 验证提示报错
                    if (true !== $result) $this->error($result);

                } else {

                    // 营业执照有效期
                    if(!empty($data['business_licence_time'])){
                        $saveData['business_licence_start'] = $data['business_licence_time'][0];
                        $saveData['business_licence_end'] = $data['business_licence_time'][1];
                    }

                    // 组织机构代码证
                    if (!empty($file['organization_code_electronic'])) {

                        // 创建图片
                        $organizationCodeElectronicFileInfo = attaAdd($file['organization_code_electronic'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$organizationCodeElectronicFileInfo['status']) $this->error($organizationCodeElectronicFileInfo['status']);

                        // 获取连接
                        $saveData['organization_code_electronic'] = $organizationCodeElectronicFileInfo['data']['relative_path_url'];
                    }

                    // 一般纳税人证明
                    if (!empty($file['general_taxpayer'])) {

                        // 创建图片
                        $generalTaxpayerFileInfo = attaAdd($file['general_taxpayer'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$generalTaxpayerFileInfo['status']) $this->error($generalTaxpayerFileInfo['status']);

                        // 获取连接
                        $saveData['general_taxpayer'] = $generalTaxpayerFileInfo['data']['relative_path_url'];
                    }

                    // 营业执照
                    if (!empty($file['business_licence_number_elc'])) {

                        // 创建图片
                        $businessLicenceNumberElcFileInfo = attaAdd($file['business_licence_number_elc'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$businessLicenceNumberElcFileInfo['status']) $this->error($businessLicenceNumberElcFileInfo['status']);

                        // 获取连接
                        $saveData['business_licence_number_elc'] = $businessLicenceNumberElcFileInfo['data']['relative_path_url'];
                    }

                    // 开户银行许可证
                    if (!empty($file['bank_licence_electronic'])) {

                        // 创建图片
                        $bankLicenceElectronicFileInfo = attaAdd($file['bank_licence_electronic'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$bankLicenceElectronicFileInfo['status']) $this->error($bankLicenceElectronicFileInfo['status']);

                        // 获取连接
                        $saveData['bank_licence_electronic'] = $bankLicenceElectronicFileInfo['data']['relative_path_url'];
                    }

                    // 税务登记证号
                    if (!empty($file['tax_registration_certif_elc'])) {

                        // 创建图片
                        $taxRegistrationCertifElcFileInfo = attaAdd($file['tax_registration_certif_elc'], config('b2b2c.store_joinin_path'));

                        // 上传错误
                        if (!$taxRegistrationCertifElcFileInfo['status']) $this->error($taxRegistrationCertifElcFileInfo['status']);

                        // 获取连接
                        $saveData['tax_registration_certif_elc'] = $taxRegistrationCertifElcFileInfo['data']['relative_path_url'];
                    }

                    // 公司所在地区冗余
                    $companyAreaInfo = '';
                    if (!empty($data['company_province_id'])) {
                        $province = $areaData[$data['company_province_id']];
                        if (!empty($province)) {
                            $saveData['company_province_id'] = $data['company_province_id'];
                            $companyAreaInfo                 .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['company_city_id'])) {
                        $city = $areaData[$data['company_city_id']];
                        if (!empty($city)) {
                            $saveData['company_city_id'] = $data['company_city_id'];
                            $companyAreaInfo             .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['company_area_id'])) {
                        $area = $areaData[$data['company_area_id']];
                        if (!empty($area)) {
                            $saveData['company_area_id'] = $data['company_area_id'];
                            $companyAreaInfo             .= '-' . $area['area_name'];
                        }
                    }

                    // 营业执照所在地区冗余
                    $businessLicenceAreaInfo = '';
                    if (!empty($data['business_licence_province_id'])) {
                        $province = $areaData[$data['business_licence_province_id']];
                        if (!empty($province)) {
                            $saveData['business_licence_province_id'] = $data['business_licence_province_id'];
                            $businessLicenceAreaInfo                  .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['business_licence_city_id'])) {
                        $city = $areaData[$data['business_licence_city_id']];
                        if (!empty($city)) {
                            $saveData['business_licence_city_id'] = $data['business_licence_city_id'];
                            $businessLicenceAreaInfo              .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['business_licence_area_id'])) {
                        $area = $areaData[$data['business_licence_area_id']];
                        if (!empty($area)) {
                            $saveData['business_licence_area_id'] = $data['business_licence_area_id'];
                            $businessLicenceAreaInfo              .= '-' . $area['area_name'];
                        }
                    }

                    // 开户银行所在地区冗余
                    $bankAreaInfo = '';
                    if (!empty($data['bank_province_id'])) {
                        $province = $areaData[$data['bank_province_id']];
                        if (!empty($province)) {
                            $saveData['bank_province_id'] = $data['bank_province_id'];
                            $bankAreaInfo                 .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['bank_city_id'])) {
                        $city = $areaData[$data['bank_city_id']];
                        if (!empty($city)) {
                            $saveData['bank_city_id'] = $data['bank_city_id'];
                            $bankAreaInfo             .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['bank_area_id'])) {
                        $area = $areaData[$data['bank_area_id']];
                        if (!empty($area)) {
                            $saveData['bank_area_id'] = $data['bank_area_id'];
                            $bankAreaInfo             .= '-' . $area['area_name'];
                        }
                    }

                    // 结算开户银行所在地区冗余
                    $settlementBankAreaInfo = '';
                    if (!empty($data['settlement_bank_province_id'])) {
                        $province = $areaData[$data['settlement_bank_province_id']];
                        if (!empty($province)) {
                            $saveData['settlement_bank_province_id'] = $data['settlement_bank_province_id'];
                            $settlementBankAreaInfo                  .= '-' . $province['area_name'];
                        }
                    }
                    if (!empty($data['settlement_bank_city_id'])) {
                        $city = $areaData[$data['settlement_bank_city_id']];
                        if (!empty($city)) {
                            $saveData['settlement_bank_city_id'] = $data['settlement_bank_city_id'];
                            $settlementBankAreaInfo              .= '-' . $city['area_name'];
                        }
                    }
                    if (!empty($data['settlement_bank_area_id'])) {
                        $area = $areaData[$data['settlement_bank_area_id']];
                        if (!empty($area)) {
                            $saveData['settlement_bank_area_id'] = $data['settlement_bank_area_id'];
                            $settlementBankAreaInfo              .= '-' . $area['area_name'];
                        }
                    }


                    // 需要更新的字段
                    $saveData['company_name']                   = $data['company_name'];
                    $saveData['company_address']                = $companyAreaInfo;
                    $saveData['company_address_detail']         = $data['company_address_detail'];
                    $saveData['company_phone']                  = $data['company_phone'];
                    $saveData['company_employee_count']         = $data['company_employee_count'];
                    $saveData['company_registered_capital']     = $data['company_registered_capital'];
                    $saveData['contacts_name']                  = $data['contacts_name'];
                    $saveData['contacts_phone']                 = $data['contacts_phone'];
                    $saveData['contacts_email']                 = $data['contacts_email'];
                    $saveData['business_licence_number']        = $data['business_licence_number'];
                    $saveData['business_licence_address']       = $businessLicenceAreaInfo;
                    $saveData['business_sphere']                = $data['business_sphere'];
                    $saveData['organization_code']              = $data['organization_code'];
                    $saveData['bank_account_name']              = $data['bank_account_name'];
                    $saveData['bank_account_number']            = $data['bank_account_number'];
                    $saveData['bank_name']                      = $data['bank_name'];
                    $saveData['bank_code']                      = $data['bank_code'];
                    $saveData['bank_address']                   = $bankAreaInfo;
                    $saveData['settlement_bank_account_name']   = $data['settlement_bank_account_name'];
                    $saveData['settlement_bank_account_number'] = $data['settlement_bank_account_number'];
                    $saveData['settlement_bank_name']           = $data['settlement_bank_name'];
                    $saveData['settlement_bank_code']           = $data['settlement_bank_code'];
                    $saveData['settlement_bank_address']        = $settlementBankAreaInfo;
                    $saveData['tax_registration_certificate']   = $data['tax_registration_certificate'];

                    // 验证
                    $result = $this->validate($saveData, 'StoreJoinin');

                    // 验证提示报错
                    if (true !== $result) $this->error($result);
                }

                if (false !== B2b2cStoreJoininModel::update($saveData, ['member_id' => $data['member_id']])) {

                    $this->success('编辑成功', url('edit',['tag_type'=>'store_register','store_id'=>$store_id]));
                } else {
                    $this->error('编辑失败');
                }
            }
        }

        // 获取数据
        $info = B2b2cStoreModel::where(['store_id' => $store_id])->find();

        if (!empty($info['store_time'])) {
            $info['store_time'] = date('Y-m-d', $info['store_time']);
        }

        if (!empty($info['store_end_time'])) {
            $info['store_end_time'] = date('Y-m-d', $info['store_end_time']);
        }

        if (empty($info)) $this->error('没有该数据');

        // 店铺入住信息
        $joininDetail = B2b2cStoreJoininModel::where(['member_id' => $info['member_id']])->find();

        // 营业执照有效期
        if(!empty($joininDetail['business_licence_start']) && !empty($joininDetail['business_licence_end'])){
            $joininDetail['business_licence_time'] = [$joininDetail['business_licence_start'],$joininDetail['business_licence_end']];
        }

        if (!empty($info)) {

            $joininDetail['business_licence_number_elc'] = getB2b2cImg($joininDetail['business_licence_number_elc']);

            $joininDetail['organization_code_electronic'] = getB2b2cImg($joininDetail['organization_code_electronic']);

            $joininDetail['general_taxpayer'] = getB2b2cImg($joininDetail['general_taxpayer']);

            $joininDetail['bank_licence_electronic'] = getB2b2cImg($joininDetail['bank_licence_electronic']);

            $joininDetail['tax_registration_certif_elc'] = getB2b2cImg($joininDetail['tax_registration_certif_elc']);
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('店铺 - 编辑');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([
            ['name' => 'store_id', 'value' => $store_id],
            ['name' => 'tag_type', 'value' => $tag_type],
            ['name' => 'is_person', 'value' => $info['is_person']],
            ['name' => 'member_id', 'value' => $info['member_id']]
        ]);

        // 商品分类数据
        $goodsClassData      = B2b2cStoreClassModel::getStoreClassDataAll();
        $goodsClassDataArr   = [];
        $goodsClassDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($goodsClassData AS $key => $value) {
            $goodsClassDataArr[] = ['title' => $value['sc_name'] . '【保证金数额：' . $value['sc_bail'] . '元】', 'value' => $value['sc_id']];
        }

        // 店铺等级
        $storeGradeData      = B2b2cStoreGradeModel::getStoreGradeData();
        $storeGradeDataArr   = [];
        $storeGradeDataArr[] = ['title' => '无', 'value' => 0];
        foreach ($storeGradeData AS $key => $value) {
            $storeGradeDataArr[] = ['title' => $value['sg_name'] . '【开店费用：' . $value['sg_price'] . '元/年】', 'value' => $value['sg_id']];
        }

        // 所有地区
        $areaData = AreaModel::getAreaData();
        $areaData = json_encode(array_values($areaData), JSON_UNESCAPED_UNICODE);

        // 设置页面分组
        $form->setGroup([
            ['title' => '店铺信息', 'field' => 'store_info', 'url' => url('edit', ['tag_type' => 'store_info', 'store_id' => $store_id]), 'default' => $tag_type == 'store_info' ? true : false],
            ['title' => '注册信息', 'field' => 'store_register', 'url' => url('edit', ['tag_type' => 'store_register', 'store_id' => $store_id]), 'default' => $tag_type == 'store_register' ? true : false],
        ]);

        // 表单分组
        if ($tag_type == 'store_register') {
            $formGroupItems = [];
            if (empty($info['is_person'])) {
                $formGroupItems = [
                    ['name' => '公司及联系人信息', 'field' => 'company'],
                    ['name' => '营业执照信息(副本)', 'field' => 'business'],
                    ['name' => '组织机构代码证', 'field' => 'organization'],
                    ['name' => '一般纳税人证明', 'field' => 'general'],
                    ['name' => '开户银行信息', 'field' => 'bank'],
                    ['name' => '结算账号信息', 'field' => 'settlement'],
                    ['name' => '税务登记证', 'field' => 'tax'],
                ];
            } else {
                $formGroupItems = [
                    ['name' => '联系人信息', 'field' => 'company'],
                    ['name' => '结算账号信息', 'field' => 'settlement'],
                ];
            }
            $form->setTypeGroup($formGroupItems);
        }

        // 表单
        $formItems = [];
        if ($tag_type == 'store_info') {
            $formItems = [
                [
                    'field'     => 'member_name',
                    'name'      => 'member_name',
                    'form_type' => 'static',
                    'title'     => '店主账号',
                ],
                [
                    'field'     => 'store_name',
                    'name'      => 'store_name',
                    'form_type' => 'text',
                    'require'   => true,
                    'title'     => '店铺名称',
                ],
                [
                    'field'     => 'store_time',
                    'name'      => 'store_time',
                    'form_type' => 'static',
                    'title'     => '开店时间',
                ],
                [
                    'field'     => 'store_company_name',
                    'name'      => 'store_company_name',
                    'form_type' => 'text',
                    'title'     => '公司名称',
                ],
                [
                    'field'            => 'area',
                    'name'             => 'area',
                    'form_type'        => 'linkage',
                    'title'            => '所在地区',
                    'optionconfig'     => [
                        ['name' => 'province_id'],
                        ['name' => 'city_id'],
                        ['name' => 'area_id'],
                    ],
                    'datasourceconfig' => [
                        'name' => 'areaSourceData',
                        'data' => $areaData
                    ],
                    'defaultconfig'    => [
                        'name' => 'areaUpdateData',
                        'data' => [$info['province_id'], $info['city_id'], $info['area_id']]
                    ],
                    'set_id'           => 'area_id',
                    'set_text'         => 'area_name',
                    'set_pid'          => 'area_parent_id'
                ],
                [
                    'field'     => 'store_address',
                    'name'      => 'store_address',
                    'form_type' => 'textarea',
                    'title'     => '店铺地址',
                ],

                [
                    'field'     => 'sc_id',
                    'name'      => 'sc_id',
                    'form_type' => 'select2',
                    'title'     => '店铺分类',
                    'option'    => $goodsClassDataArr,
                ],
                [
                    'field'     => 'grade_id',
                    'name'      => 'grade_id',
                    'form_type' => 'select2',
                    'title'     => '店铺等级',
                    'option'    => $storeGradeDataArr,
                ],
                [
                    'field'     => 'store_end_time',
                    'name'      => 'store_end_time',
                    'form_type' => 'date',
                    'title'     => '有效期至',
                ],
                [
                    'field'     => 'store_state',
                    'name'      => 'store_state',
                    'form_type' => 'switch',
                    'title'     => '状态',
                    'option'    => [
                        'on_text'   => '开启',
                        'off_text'  => '关闭',
                        'on_value'  => 1,
                        'off_value' => 0,
                    ]
                ],
            ];
        } else {
            if (empty($info['is_person'])) {
                $formItems = [
                    [
                        'field'      => 'company_name',
                        'name'       => 'company_name',
                        'form_type'  => 'text',
                        'title'      => '公司名称',
                        'type_group' => 'company',
                    ],
                    [
                        'field'            => 'company_area',
                        'name'             => 'company_area',
                        'form_type'        => 'linkage',
                        'title'            => '公司所在地',
                        'type_group'       => 'company',
                        'optionconfig'     => [
                            ['name' => 'company_province_id'],
                            ['name' => 'company_city_id'],
                            ['name' => 'company_area_id'],
                        ],
                        'datasourceconfig' => [
                            'name' => 'companySourceData',
                            'data' => $areaData
                        ],
                        'defaultconfig'    => [
                            'name' => 'companyUpdateData',
                            'data' => [$joininDetail['company_province_id'], $joininDetail['company_city_id'], $joininDetail['company_area_id']]
                        ],
                        'set_id'           => 'area_id',
                        'set_text'         => 'area_name',
                        'set_pid'          => 'area_parent_id'
                    ],
                    [
                        'field'      => 'company_address_detail',
                        'name'       => 'company_address_detail',
                        'form_type'  => 'textarea',
                        'title'      => '公司详细地址',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'company_phone',
                        'name'       => 'company_phone',
                        'form_type'  => 'text',
                        'title'      => '公司电话',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'company_employee_count',
                        'name'       => 'company_employee_count',
                        'form_type'  => 'number',
                        'title'      => '员工总数',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'company_registered_capital',
                        'name'       => 'company_registered_capital',
                        'form_type'  => 'text',
                        'title'      => '注册资金',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_name',
                        'name'       => 'contacts_name',
                        'form_type'  => 'text',
                        'title'      => '联系人姓名',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_phone',
                        'name'       => 'contacts_phone',
                        'form_type'  => 'text',
                        'title'      => '联系人电话',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_email',
                        'name'       => 'contacts_email',
                        'form_type'  => 'text',
                        'title'      => '电子邮箱',
                        'type_group' => 'company',
                    ],

                    [
                        'field'      => 'business_licence_number',
                        'name'       => 'business_licence_number',
                        'form_type'  => 'text',
                        'title'      => '营业执照号',
                        'type_group' => 'business',
                    ],
                    [
                        'field'            => 'business_licence_area',
                        'name'             => 'business_licence_area',
                        'form_type'        => 'linkage',
                        'title'            => '营业执照所在地',
                        'type_group'       => 'business',
                        'optionconfig'     => [
                            ['name' => 'business_licence_province_id'],
                            ['name' => 'business_licence_city_id'],
                            ['name' => 'business_licence_area_id'],
                        ],
                        'datasourceconfig' => [
                            'name' => 'businessLicenceSourceData',
                            'data' => $areaData
                        ],
                        'defaultconfig'    => [
                            'name' => 'businessLicenceUpdateData',
                            'data' => [$joininDetail['business_licence_province_id'], $joininDetail['business_licence_city_id'], $joininDetail['business_licence_area_id']]
                        ],
                        'set_id'           => 'area_id',
                        'set_text'         => 'area_name',
                        'set_pid'          => 'area_parent_id'
                    ],
                    [
                        'field'      => 'business_licence_time',
                        'name'       => 'business_licence_time',
                        'form_type'  => 'daterange',
                        'title'      => '营业执照有效期',
                        'type_group' => 'business',
                    ],
                    [
                        'field'      => 'business_sphere',
                        'name'       => 'business_sphere',
                        'form_type'  => 'text',
                        'title'      => '法定经营范围',
                        'type_group' => 'business',
                    ],
                    [
                        'field'      => 'business_licence_number_elc',
                        'name'       => 'business_licence_number_elc',
                        'form_type'  => 'image',
                        'title'      => '营业执照(电子版)',
                        'type_group' => 'business',
                    ],

                    [
                        'field'      => 'organization_code',
                        'name'       => 'organization_code',
                        'form_type'  => 'text',
                        'title'      => '组织机构代码',
                        'type_group' => 'organization',
                    ],
                    [
                        'field'      => 'organization_code_electronic',
                        'name'       => 'organization_code_electronic',
                        'form_type'  => 'image',
                        'title'      => '组织机构代码证(电子版)',
                        'type_group' => 'organization',
                    ],

                    [
                        'field'      => 'general_taxpayer',
                        'name'       => 'general_taxpayer',
                        'form_type'  => 'image',
                        'title'      => '一般纳税人证明',
                        'type_group' => 'general',
                    ],

                    [
                        'field'      => 'bank_account_name',
                        'name'       => 'bank_account_name',
                        'form_type'  => 'text',
                        'title'      => '银行开户名',
                        'type_group' => 'bank',
                    ],
                    [
                        'field'      => 'bank_account_number',
                        'name'       => 'bank_account_number',
                        'form_type'  => 'text',
                        'title'      => '公司银行账号',
                        'type_group' => 'bank',
                    ],
                    [
                        'field'      => 'bank_name',
                        'name'       => 'bank_name',
                        'form_type'  => 'text',
                        'title'      => '开户银行支行名称',
                        'type_group' => 'bank',
                    ],
                    [
                        'field'      => 'bank_code',
                        'name'       => 'bank_code',
                        'form_type'  => 'text',
                        'title'      => '支行联行号',
                        'type_group' => 'bank',
                    ],
                    [
                        'field'            => 'bank_area',
                        'name'             => 'bank_area',
                        'form_type'        => 'linkage',
                        'title'            => '开户银行所在地',
                        'type_group' => 'bank',
                        'optionconfig'     => [
                            ['name' => 'bank_province_id'],
                            ['name' => 'bank_city_id'],
                            ['name' => 'bank_area_id'],
                        ],
                        'datasourceconfig' => [
                            'name' => 'bankSourceData',
                            'data' => $areaData
                        ],
                        'defaultconfig'    => [
                            'name' => 'bankUpdateData',
                            'data' => [$joininDetail['bank_province_id'], $joininDetail['bank_city_id'], $joininDetail['bank_area_id']]
                        ],
                        'set_id'           => 'area_id',
                        'set_text'         => 'area_name',
                        'set_pid'          => 'area_parent_id'
                    ],
                    [
                        'field'      => 'bank_licence_electronic',
                        'name'       => 'bank_licence_electronic',
                        'form_type'  => 'image',
                        'title'      => '开户银行许可证(电子版)',
                        'type_group' => 'bank',
                    ],

                    [
                        'field'      => 'settlement_bank_account_name',
                        'name'       => 'settlement_bank_account_name',
                        'form_type'  => 'text',
                        'title'      => '银行开户名',
                        'type_group' => 'settlement',
                    ],
                    [
                        'field'      => 'settlement_bank_account_number',
                        'name'       => 'settlement_bank_account_number',
                        'form_type'  => 'text',
                        'title'      => '公司银行账号',
                        'type_group' => 'settlement',
                    ],
                    [
                        'field'      => 'settlement_bank_name',
                        'name'       => 'settlement_bank_name',
                        'form_type'  => 'text',
                        'title'      => '开户银行支行名称',
                        'type_group' => 'settlement',
                    ],
                    [
                        'field'      => 'settlement_bank_code',
                        'name'       => 'settlement_bank_code',
                        'form_type'  => 'text',
                        'title'      => '支行联行号',
                        'type_group' => 'settlement',
                    ],
                    [
                        'field'            => 'settlement_bank_area',
                        'name'             => 'settlement_bank_area',
                        'form_type'        => 'linkage',
                        'title'            => '开户银行所在地',
                        'type_group' => 'settlement',
                        'optionconfig'     => [
                            ['name' => 'settlement_bank_province_id'],
                            ['name' => 'settlement_bank_city_id'],
                            ['name' => 'settlement_bank_area_id'],
                        ],
                        'datasourceconfig' => [
                            'name' => 'settlementBankSourceData',
                            'data' => $areaData
                        ],
                        'defaultconfig'    => [
                            'name' => 'settlementBankUpdateData',
                            'data' => [$joininDetail['settlement_bank_province_id'], $joininDetail['settlement_bank_city_id'], $joininDetail['settlement_bank_area_id']]
                        ],
                        'set_id'           => 'area_id',
                        'set_text'         => 'area_name',
                        'set_pid'          => 'area_parent_id'
                    ],

                    [
                        'field'      => 'tax_registration_certificate',
                        'name'       => 'tax_registration_certificate',
                        'form_type'  => 'text',
                        'title'      => '税务登记证号',
                        'type_group' => 'tax',
                    ],
                    [
                        'field'      => 'tax_registration_certif_elc',
                        'name'       => 'tax_registration_certif_elc',
                        'form_type'  => 'image',
                        'title'      => '税务登记证号(电子版)',
                        'type_group' => 'tax',
                    ]
                ];
            } else {
                $formItems = [
                    [
                        'field'      => 'company_name',
                        'name'       => 'company_name',
                        'form_type'  => 'text',
                        'title'      => '个人姓名',
                        'type_group' => 'company',
                    ],
                    [
                        'field'            => 'company_area',
                        'name'             => 'company_area',
                        'form_type'        => 'linkage',
                        'title'            => '家庭所在地',
                        'optionconfig'     => [
                            ['name' => 'company_province_id'],
                            ['name' => 'company_city_id'],
                            ['name' => 'company_area_id'],
                        ],
                        'datasourceconfig' => [
                            'name' => 'companySourceData',
                            'data' => $areaData
                        ],
                        'defaultconfig'    => [
                            'name' => 'companyUpdateData',
                            'data' => [$joininDetail['province_id'], $joininDetail['city_id'], $joininDetail['area_id']]
                        ],
                        'set_id'           => 'area_id',
                        'set_text'         => 'area_name',
                        'set_pid'          => 'area_parent_id'
                    ],
                    [
                        'field'      => 'company_address_detail',
                        'name'       => 'company_address_detail',
                        'form_type'  => 'textarea',
                        'title'      => '家庭详细地址',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_name',
                        'name'       => 'contacts_name',
                        'form_type'  => 'text',
                        'title'      => '联系人姓名',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_phone',
                        'name'       => 'contacts_phone',
                        'form_type'  => 'text',
                        'title'      => '联系人电话',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'contacts_email',
                        'name'       => 'contacts_email',
                        'form_type'  => 'text',
                        'title'      => '电子邮箱',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'organization_code_electronic',
                        'name'       => 'organization_code_electronic',
                        'form_type'  => 'image',
                        'title'      => '身份证正面(电子版)',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'general_taxpayer',
                        'name'       => 'general_taxpayer',
                        'form_type'  => 'image',
                        'title'      => '身份证反面(电子版)',
                        'type_group' => 'company',
                    ],
                    [
                        'field'      => 'business_licence_number_elc',
                        'name'       => 'business_licence_number_elc',
                        'form_type'  => 'image',
                        'title'      => '手持身份证(五官照片)',
                        'type_group' => 'company',
                    ],

                    [
                        'field'      => 'settlement_bank_account_name',
                        'name'       => 'settlement_bank_account_name',
                        'form_type'  => 'text',
                        'title'      => '支付宝账号信息',
                        'type_group' => 'settlement',
                    ],
                    [
                        'field'      => 'settlement_bank_account_number',
                        'name'       => 'settlement_bank_account_number',
                        'form_type'  => 'text',
                        'title'      => '支付宝认证姓名',
                        'type_group' => 'settlement',
                    ]
                ];
            }

        }

        $form->addFormItems($formItems);

        // 设置表单数据
        if ($tag_type == 'store_info') {
            $form->setFormdata($info);
        } else {
            $form->setFormdata($joininDetail);
        }

        return $form->fetch();
    }

    /**
     * 审核
     * @param int $brand_id
     * @author 仇仇天
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
