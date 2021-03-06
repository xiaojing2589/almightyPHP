<?php

/**
 * 获取图片
 * @author 仇仇天
 * @param string $file_path  文件路径 goods/xxx.jpg
 * @param int $id 商品id
 * @param array $param 参数
 */
function getB2b2cImg($file_path='',$param=[])
{
    $upload_dir = config('upload_dir'); // 文件上传根目录

    if(!empty($param['type'])){
        switch ($param['type'])
        {
            // 商品图片
            case 'goods':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_goods_image.gif';
                break;
            // 商品分类图片
            case 'goods_class':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_goods_class.gif';
                break;
            // 商品分类分组图片
            case 'goods_category':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_goods_class.gif';
                break;
            // 商品分类广告图片
            case 'goods_brand':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_goods_class.gif';
                break;
            // 相册封面
            case 'aclass_cover':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_aclass_cover.gif';
                break;
            // 相册图片
            case 'aclass':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_aclass.gif';
                break;
            // 视频
            case 'video':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_video.gif';
                break;
            // 品牌图片
            case 'brand':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/default_brand_image.gif';
                break;
            // 品牌大图
            case 'brand_bgpic':
                $param['default'] = $upload_dir.'/'.config('b2b2c.common_path').'/brand_default_max.gif';
                break;
            // 店铺头像
            case 'store_avatar':
                $param['default'] = $upload_dir.'/'.config('b2b2c.store_avatar_path').'/store_avatar_default.gif';
                break;
            // 店铺log
            case 'store_logo':
                $param['default'] = $upload_dir.'/'.config('b2b2c.store_avatar_path').'/store_logo_default.gif';
                break;
            // 店铺等级图标
            case 'store_ioc':
                $param['default'] = '';
                break;

        }
    }
    if(empty($file_path)){
        if(empty($param['default'])){
            return '';
        }else{
            $file_path = $param['default'];
        }
    }
    return attaUrl($file_path,$param);

}

/**
 * 发送短信
 * @author 仇仇天
 * @param $mobile 手机号
 * @param $message 发送内容
 */
function b2b2cSendSms($mobile, $code, $param = [])
{
    $res_arr = ['status' => true, 'msg' => '删除成功', 'data' => []];// 返回信息

    // 检测手机号
    if (!$mobile || !\think\Validate::regex($mobile, "^1\d{10}$")) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '手机号不正确';
        return $res_arr;
    }

    $last = app\common\model\Sms::where(['mobile' => $mobile, 'event' => $code])->order('id', 'DESC')->find(); // 查询最后一次短信

    if ($last && time() - $last['createtime'] < 60) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '发送频繁';
        return $res_arr;
    }

    $ipSendTotal = app\common\model\Sms::where(['ip' => $this->request->ip()])->whereTime('createtime', '-1 hours')->count();

    if ($ipSendTotal >= 5) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '发送频繁';
        return $res_arr;
    }

    $msgTpInfo = app\b2b2c\model\B2b2cMsgTpl::getMsgTpInfo($code); // 获取消息模板配置

    $message = ncReplaceText($msgTpInfo['short_content'], $param); // 内容

    $pSms = app\common\model\PSms::getDefault(); // 获取设置的短信平台

    $sms_config = app\common\model\PSms::getDefaultConfig();

    if(!empty($pSms) && !empty($sms_config)){

        $config_driver = $pSms['mark']; // 配置存储驱动

        $obj = DIRECTORY_SEPARATOR . 'sms' . DIRECTORY_SEPARATOR . $config_driver . DIRECTORY_SEPARATOR . 'Sms'; // 插件类文件路径

        if(!class_exists($obj)){
            $res_arr['status'] = false;
            $res_arr['msg']    = '平台短信 未设置!';
            return $res_arr;
        }

        $sms_obj = new $obj($sms_config);

        if(false !== $sms_obj->send($mobile,$message)){
            return $res_arr;
        }else{
            $res_arr['status'] = false;
            $res_arr['msg']    = $sms_obj->error;
            return $res_arr;
        }
    }else{
        $res_arr['status'] = false;
        $res_arr['msg']    = '平台短信 未设置!';
        return $res_arr;
    }
}
