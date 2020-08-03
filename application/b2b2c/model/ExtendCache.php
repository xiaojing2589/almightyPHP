<?php

namespace app\b2b2c\model;

use app\b2b2c\model\B2b2cGoods as B2b2cGoodsModel;
use app\b2b2c\model\B2b2cGoodsClass as B2b2cGoodsClassModel;
use app\b2b2c\model\B2b2cGoodsClassNav as B2b2cGoodsClassNavModel;
use app\b2b2c\model\B2b2cType as B2b2cTypeModel;
use app\b2b2c\model\B2b2cGoodsClassTag as B2b2cGoodsClassTagModel;
use app\b2b2c\model\B2b2cGoodsClassStaple as B2b2cGoodsClassStapleModel;
use app\b2b2c\model\B2b2cStoreBindClass as B2b2cStoreBindClassModel;
use app\b2b2c\model\B2b2cSellerGroupBclass as B2b2cSellerGroupBclassModel;
use app\b2b2c\model\B2b2cSpec as B2b2cSpecModel;
use app\b2b2c\model\B2b2cBrand as B2b2cBrandModel;
use app\b2b2c\model\B2b2cGoodsCommon as B2b2cGoodsCommonModel;
use app\b2b2c\model\B2b2cPBundlingGoods as B2b2cPBundlingGoodsModel;
use app\b2b2c\model\B2b2cPBundling as B2b2cPBundlingModel;
use app\b2b2c\model\B2b2cPXianshiGoods as B2b2cPXianshiGoodsModel;
use app\b2b2c\model\B2b2cPComboGoods as B2b2cPComboGoodsModel;
use app\b2b2c\model\B2b2cAttribute as B2b2cAttributeModel;
use app\b2b2c\model\B2b2cAttributeValue as B2b2cAttributeValueModel;
use app\b2b2c\model\B2b2cTypeCustom as B2b2cTypeCustomModel;
use app\b2b2c\model\B2b2cMsgTpl as B2b2cMsgTplModel;
use app\b2b2c\model\B2b2cStoreMsgSetting as B2b2cStoreMsgSettingModel;
use app\b2b2c\model\B2b2cGoodsRecommend as B2b2cGoodsRecommendModel;
use app\b2b2c\model\B2b2cStoreClass as B2b2cStoreClassModel;
use app\b2b2c\model\B2b2cGoodsGift as B2b2cGoodsGiftModel;
use app\b2b2c\model\B2b2cGoodsImages as B2b2cGoodsImagesModel;
use app\b2b2c\model\B2b2cStoreGrade as B2b2cStoreGradeModel;
use app\b2b2c\model\B2b2cStore as B2b2cStoreModel;
use app\b2b2c\model\B2b2cStoreEvaluate as B2b2cStoreEvaluateModel;


use think\Model;
use util\Tree;

/**
 * 缓存扩展
 * Class ExtendCache
 * @package app\b2b2c\model
 */
class ExtendCache extends Model
{

    /**
     * 商品分类
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_goods_class($Identification = '', $extend_param = [])
    {
        $data = B2b2cGoodsClassModel::column('*', 'gc_id');
        return to_arrays($data);
    }

    /**
     * 商品分类标签
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_goods_class_tag($Identification = '', $extend_param = [])
    {
        $data = B2b2cGoodsClassTagModel::column('*', 'gc_tag_id');
        return to_arrays($data);
    }

    /**
     * 店铺常用分类
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_goods_class_staple($Identification = '', $extend_param = [])
    {
        $data = B2b2cGoodsClassStapleModel::select();
        return to_arrays($data);
    }

    /**
     * 店铺可发布商品分类
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_store_bind_class($Identification = '', $extend_param = [])
    {
        $data = B2b2cStoreBindClassModel::select();
        return to_arrays($data);
    }

    /**
     * 商家内部组商品分类
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_seller_group_bclass($Identification = '', $extend_param = [])
    {
        $data = B2b2cSellerGroupBclassModel::select();
        return to_arrays($data);
    }

    /**
     * 商品分类导航
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_goods_class_nav($Identification = '', $extend_param = [])
    {
        $data = B2b2cGoodsClassNavModel::column('*', 'gc_id');
        return to_arrays($data);
    }

    /**
     * 商品规格
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_spec($Identification = '', $extend_param = [])
    {
        $data = B2b2cSpecModel::column('*', 'sp_id');
        return to_arrays($data);
    }

    /**
     * 商品类型
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_type($Identification = '', $extend_param = [])
    {
        $data = B2b2cTypeModel::column('*', 'type_id');
        return to_arrays($data);
    }

    /**
     * 商品品牌
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_brand($Identification = '', $extend_param = [])
    {
        $data = B2b2cBrandModel::column('*', 'brand_id');
        return to_arrays($data);
    }

    /**
     * 商品公共内容
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_goods_common_data($Identification = '', $extend_param = [])
    {
        $data = [];
        if (!empty($Identification)) {
            $data = B2b2cGoodsCommonModel::where(['goods_commonid' => $Identification])->find();
            if (!empty($data)) {
                $data = to_arrays($data);
            }
        }
        return $data;
    }

    /**
     * 商品规格
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function b2b2c_goods_spec_data($Identification = '', $extend_param = [])
    {
        $data = [];
        if (!empty($Identification)) {
            $data = B2b2cGoodsModel::where(['goods_commonid' => $Identification])->field('goods_spec,goods_id,store_id,goods_image,color_id')->select();
            if (!empty($data)) {
                $data = to_arrays($data);
            }
        }
        return $data;
    }

    /**
     * 消息模板
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_msg_tpl($Identification = '', $extend_param = [])
    {
        $data = B2b2cMsgTplModel::column('*', 'code');
        return to_arrays($data);
    }

    /**
     * 商家消息设置
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_store_msg_setting($Identification = '', $extend_param = [])
    {
        $data = B2b2cStoreMsgSettingModel::column('*', 'smt_code');
        return to_arrays($data);
    }

    /**
     * 分类商品推荐
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_goods_recommend($Identification = '', $extend_param = [])
    {
        $data    = B2b2cGoodsRecommendModel::select();
        $data    = to_arrays($data);
        $resData = [];
        foreach ($data as $value) {
            if (empty($resData[$value['rec_gc_id']])) {
                $resData[$value['rec_gc_id']][0] = $value;
            } else {
                $resData[$value['rec_gc_id']][] = $value;
            }
        }
        return $resData;
    }

    /**
     * 店铺分类
     * @author 仇仇天
     * @param string $Identification
     * @param array $extend_param
     * @return array
     */
    public function b2b2c_store_class($Identification = '', $extend_param = [])
    {
        $data = B2b2cStoreClassModel::order('sc_sort ASC')->column('*', 'sc_id');
        return to_arrays($data);
    }

    /**
     * 商品属性
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_attribute($Identification = '', $extend_param = [])
    {
        $data = B2b2cAttributeModel::column('*', 'attr_id');
        return to_arrays($data);
    }

    /**
     * 商品属性值
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_attribute_value($Identification = '', $extend_param = [])
    {
        $data = B2b2cAttributeValueModel::column('*', 'attr_value_sort');
        return to_arrays($data);
    }

    /**
     * 自定义属性
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_type_custom($Identification = '', $extend_param = [])
    {
        $data = B2b2cTypeCustomModel::column('*', 'custom_id');
        return to_arrays($data);
    }

    /**
     * 组合销售活动商品
     * @param string $goodsId
     * @param array $extend_param
     * @return array
     * @author 仇仇天 SKU 商品id
     */
    public function bundling_goods($goodsId = '', $extend_param = [])
    {
        $data = [];
        if (!empty($Identification)) {
            $bundlingArray = array();
            $bGoodsArray   = array();
            // 查询组合商品
            $bundlingGoodsBlIdArr = B2b2cPBundlingGoodsModel::where(['goods_id' => $Identification, 'bl_appoint' => 1])->column('bl_id');
            if (!empty($bundlingGoodsBlIdArr)) {
                // 查询套餐列表
                $bundlingWhere = [
                    ['bl_state', '=', 1],
                    ['bl_id', 'in', $bundlingGoodsBlIdArr]
                ];

                // 组合销售活动
                $bundlingData = B2b2cPBundlingModel::where($bundlingWhere)->select();

                // 整理
                if (!empty($bundlingData)) {
                    foreach ($bundlingData as $val) {
                        // 活动id
                        $bundlingArray[$val['bl_id']]['id'] = $val['bl_id'];
                        // 活动名称
                        $bundlingArray[$val['bl_id']]['name'] = $val['bl_name'];
                        // 成本价
                        $bundlingArray[$val['bl_id']]['cost_price'] = 0;
                        // 组合价格
                        $bundlingArray[$val['bl_id']]['price'] = $val['bl_discount_price'];
                        // 运费
                        $bundlingArray[$val['bl_id']]['freight'] = $val['bl_freight'];
                    }
                    $blidArray  = array_keys($bundlingArray);
                    $bGoodsList = B2b2cPBundlingGoodsModel::where([['bl_id', 'in', $blidArray]])->select();
                    if (!empty($bGoodsList) && count($bGoodsList) > 1) {
                        $goodsidArray = array();
                        foreach ($bGoodsList as $val) {
                            $goodsidArray[] = $val['goods_id'];
                        }
                        $goodsList = B2b2cGoodsModel::where([['bl_id', 'in', $goodsidArray]])->column('goods_id,goods_name,goods_price,goods_image', 'goods_id');
                        foreach ($bGoodsList as $val) {
                            if (isset($goodsList[$val['goods_id']])) {
                                // 排序当前商品放到最前面
                                $k = (intval($val['goods_id']) == $Identification) ? 0 : $val['goods_id'];
                                // 商品id
                                $bGoodsArray[$val['bl_id']][$k]['id'] = $val['goods_id'];
                                // 商品图片
                                $bGoodsArray[$val['bl_id']][$k]['image'] = getB2b2cImg($goodsList[$val['goods_id']]['goods_image'], ['type' => 'goods']);
                                // 商品名称
                                $bGoodsArray[$val['bl_id']][$k]['name'] = $goodsList[$val['goods_id']]['goods_name'];
                                // 商品价格
                                $bGoodsArray[$val['bl_id']][$k]['shop_price'] = ncPriceFormat($goodsList[$val['goods_id']]['goods_price']);
                                // 组合商品价格
                                $bGoodsArray[$val['bl_id']][$k]['price'] = ncPriceFormat($val['bl_goods_price']);
                                // 成本价
                                $bundlingArray[$val['bl_id']]['cost_price'] += $goodsList[$val['goods_id']]['goods_price'];
                            }
                        }
                    }
                }
            }
            // bundling_array=活动 b_goods_array=活动商品
            $data = ['bundling_array' => $bundlingArray, 'b_goods_array' => $bGoodsArray];
        }
        return $data;
    }

    /**
     * 限时折扣活动商品
     * @author 仇仇天
     * @param $goodsId SKU 商品id
     * @param array $extend_param
     * @return array|\PDOStatement|string|Model|null
     */a
    public function goods_xianshi($goodsId, $extend_param = [])
    {
        $time = time();
        $data = [];
        if (!empty($Identification)) {
            $data = B2b2cPXianshiGoodsModel::where("state=1 AND goods_id=" . $goodsId . " AND end_time>" . $time)->order('start_time ASC')->find();
            if (!empty($data) && ($data['start_time'] > $time || $data['end_time'] < $time)) {
                $data = [];
            } else {
                $data = to_arrays($data);
            }
        }
        return $data;
    }

    /**
     * 商品赠品
     * @author 仇仇天
     * @param string $goodsId SKU 商品id
     * @param array $extend_param
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function goods_gift($goodsId = '', $extend_param = [])
    {
        $data = [];
        if (!empty($goodsId)) {
            $data = B2b2cGoodsGiftModel::where(['goods_id' => $goodsId])->select();
            $data = to_arrays($data);
        }
        return $data;
    }

    /**
     * 商品推荐组合
     * @author 仇仇天
     * @param string $goodsId SKU 商品id
     * @param array $extend_param
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function goods_combo($goodsId = '', $extend_param = [])
    {
        $data = [];
        if (!empty($goodsId)) {
            $comboGoodsData = B2b2cPComboGoodsModel::where(['goods_id' => $goodsId])->select();
            if (!empty($comboGoodsData)) {
                $array        = array();
                $arr          = array();
                $comboidArray = [];
                foreach ($comboGoodsData as $val) {
                    $comboidArray[] = $val['combo_goodsid'];
                }
                $goodsData = B2b2cGoodsModel::where([['goods_id', 'in', $comboidArray]])->select();
                $goodsData = arrayUnderReset($goodsData, 'goods_id');
                foreach ($comboGoodsData as $val) {
                    if (empty($goodsData[$val['combo_goodsid']])) {
                        continue;
                    }
                    $array[$val['cg_class']][] = $comboidArray[$val['combo_goodsid']];
                }
                $i = 1;
                foreach ($array as $key => $val) {
                    $arr[$i]['name']  = $key;
                    $arr[$i]['goods'] = $val;
                    $i++;
                }
            }
            $data = $arr;
        }
        return $data;
    }

    /**
     * 商品图片
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function goods_image($Identification = '', $extend_param = [])
    {
        $data = [];
        if (!empty($Identification)) {

            $array = explode('|', $Identification);

            list($common_id, $color_id) = $array;

            $imageMore = B2b2cGoodsImagesModel::where(['goods_commonid' => $common_id, 'color_id' => $color_id])->column('goods_image');

            $data = to_arrays($imageMore);
        }
        return $data;
    }

    /**
     * 店铺等级
     * @author 仇仇天
     * @param string $Identification
     * @param array $extend_param
     * @return array
     */
    public function b2b2c_store_grade($Identification = '', $extend_param = [])
    {
        $data = B2b2cStoreGradeModel::column('*', 'sg_id');
        return to_arrays($data);
    }

    /**
     * 店铺
     * @author 仇仇天
     * @param $storeId 店铺id
     * @param array $extend_param 扩展参数
     * @return array
     */
    public function b2b2c_store($storeId, $extend_param = [])
    {
        $data = [];

        $dataInfo = B2b2cStoreModel::where(['store_id'=>$storeId])->find();

        if(!empty($dataInfo)){

            $dataInfo = to_arrays($dataInfo);

            // 售前客服
            if(!empty($dataInfo['store_presales'])){
                $dataInfo['store_presales'] = json_decode($dataInfo['store_presales'],true);
            }

            // 售后客服
            if(!empty($dataInfo['store_aftersales'])){
                $dataInfo['store_aftersales'] = json_decode($dataInfo['store_aftersales'],true);
            }

            // 商品数量
            $store_info['goods_count'] = B2b2cGoodsCommonModel::getGoodsCommonOnlineCount(['store_id'=>$dataInfo['store_id']]);

            // 店铺评价
            $storeEvaluate = B2b2cStoreEvaluateModel::getStoreEvaluateDataInfo($dataInfo['store_id'],$extend_param['sc_id']);
            $storeEvaluate = to_arrays($storeEvaluate);

            $data = array_merge($dataInfo, $storeEvaluate);
        }
        return $data;
    }

    /**
     * 店铺评价
     * @author 仇仇天
     * @param $storeId 店铺id
     * @param array $extend_param
     * @return array
     */
    public function b2b2c_store_evaluate($storeId, $extend_param = [])
    {
        $data = [];
        if(!empty($storeId)){

            $info = [];

            // 获取店铺评分数据
            $info['store_credit'] = B2b2cStoreEvaluateModel::getEvaluateStore(array('seval_storeid' => $storeId));
            // 获取所有评分相加后平均分
            $info['store_credit_average'] = number_format(round((($info['store_credit']['store_desccredit']['credit'] + $info['store_credit']['store_servicecredit']['credit'] + $info['store_credit']['store_deliverycredit']['credit']) / 3), 1), 1);
            // 获取所有评分相加后平均分百分比
            $info['store_credit_percent'] = intval($info['store_credit_average'] / 5 * 100);

            if(!empty($extend_param['sc_id'])){

                $sc_id = $extend_param['sc_id'];

                // 根据店铺分类id获取评分
                $scData = B2b2cStoreEvaluateModel::getEvaluateStoreInfoByScID($sc_id);

                foreach ($info['store_credit'] as $key => $value) {
                    if($scData[$key]['credit'] > 0) {
                        $info['store_credit'][$key]['percent'] = intval(($info['store_credit'][$key]['credit'] - $scData[$key]['credit']) / $scData[$key]['credit'] * 100);
                    } else {
                        $info['store_credit'][$key]['percent'] = 0;
                    }
                    if($info['store_credit'][$key]['percent'] > 0) {
                        $info['store_credit'][$key]['percent_class'] = 'high';
                        $info['store_credit'][$key]['percent_text'] = '高于';
                        $info['store_credit'][$key]['percent'] .= '%';
                    } elseif ($info['store_credit'][$key]['percent'] == 0) {
                        $info['store_credit'][$key]['percent_class'] = 'equal';
                        $info['store_credit'][$key]['percent_text'] = '持平';
                        $info['store_credit'][$key]['percent'] = '--';
                    } else {
                        $info['store_credit'][$key]['percent_class'] = 'low';
                        $info['store_credit'][$key]['percent_text'] = '低于';
                        $info['store_credit'][$key]['percent'] = abs($info['store_credit'][$key]['percent']);
                        $info['store_credit'][$key]['percent'] .= '%';
                    }
                }

                $data = $info;
            }
        }

        return $data;
    }

}
