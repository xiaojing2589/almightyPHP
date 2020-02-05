<?php

namespace app\b2b2c\model;

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
use app\b2b2c\model\B2b2cMsgTpl as B2b2cMsgTplModel;
use app\b2b2c\model\B2b2cStoreMsgSetting as B2b2cStoreMsgSettingModel;
use app\b2b2c\model\B2b2cGoodsRecommend as B2b2cGoodsRecommendModel;
use app\b2b2c\model\B2b2cStoreClass as B2b2cStoreClassModel;
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
        $data = B2b2cGoodsClassModel::select();
        $data = to_arrays($data);
        if (!empty($data)) {
            $treeConfig = ['id' => 'gc_id', 'pid' => 'gc_parent_id', 'title' => 'gc_name'];
            $Tree       = Tree::config($treeConfig);
            $data       = $Tree::toList($data);
        }
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
        $data = B2b2cGoodsClassTagModel::select();
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
    public function b2b2c_goods_common($Identification = '', $extend_param = [])
    {
        $data = B2b2cGoodsCommonModel::column('*', 'goods_commonid');
        return to_arrays($data);
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
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function b2b2c_store_class($Identification = '', $extend_param = [])
    {
        $data = B2b2cStoreClassModel::column('*', 'sc_id');
        return to_arrays($data);
    }

}
