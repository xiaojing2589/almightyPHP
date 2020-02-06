<?php

namespace storage\oss;

use OSS\OssClient;
use OSS\Core\OssException;
use think\Image;

class Storage
{
    protected $config = [ // 配置参数
        'key'      => '',                                           // 您的Access Key ID
        'secret'   => 'asdasda',                                 // 您的Access Key Secret
        'Endpoint' => 'http://oss-cn-shanghai.aliyuncs.com ',  // 阿里云oss 外网地址endpoint
        'bucket'   => 'dasdasd'                                  // Bucket名称
    ];

    protected $ossClient; // oss对象

    public $error; // 错误信息

    /**
     * Storage constructor.
     * @param array $config 是插件配置
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        try {
            $this->ossClient = new OssClient($this->config['key'], $this->config['secret'], $this->config['Endpoint']);
        } catch (OssException $e) {
            $this->error = $e->getMessage();
        }
        return false;
    }

    /**
     * @describe  设置配置
     * @param array $config 配置信息
     * @return Storage
     */
    public function setConfig($config = [])
    {
        $this->config = $config;
    }

    /**
     * @describe 文件上传
     * @param $source_file 要上传的文件
     * @param $upload_file_Path 上传的路径
     */
    public function uploadFile($source_file, $upload_file_path, $param = [])
    {
        //返回信息
        $file_info = [
            'source_file_name'  => '', // 源文件名
            'save_file_name'    => '', // 存储的文件名称
            'ext'               => '', // 文件后缀
            'size'              => 0,  // 文件大小
            'thumb'             => [], // 缩略图路径
            'relative_path_url' => '', // 文件相对url路径
            'url'               => '', // 可访问url地址
        ];

        $upload_thumb_water = config('upload_thumb_water');     // 系统是否开启水印

        $upload_thumb_water_pic = config('upload_thumb_water_pic'); // 系统水印图片路径

        $upload_path_temp_thumb = config('upload_temp_path') . 'thumb/';     // 缩略图文件上传临时目录

        $upload_path_temp_water = config('upload_temp_path') . 'water/';     // 水印文件上传临时目录

        $upload_dir = config('upload_dir'); // 文件上传根目录

        $source_upload_file_path_info = pathinfo($upload_file_path); // 上传文件名称 信息

        if (empty($source_upload_file_path_info['extension'])) {
            if (empty($upload_file_path)) {
                $upload_relative = '';
            } else {
                $upload_relative = $upload_file_path;
            }
        } else {
            if ($source_upload_file_path_info['dirname'] == '.') {
                $upload_relative = '';
            } else {
                $upload_relative = $source_upload_file_path_info['dirname'];
            }
        }

        $relative_upload_file_path = empty($upload_file_path) ? $upload_dir : $upload_dir . '/' . $upload_file_path; // 相对上传路径名/路径文件名

        $upload_file_path_info = pathinfo($relative_upload_file_path);  // 存储路径/文件名称 信息

        // 设置存储路径文件，路径形式 例如：upload/test
        if (empty($upload_file_path_info['extension'])) {
            if (is_object($source_file)) {
                $ext         = pathinfo($source_file->getInfo('name'))['extension']; // 要上传的文件后缀
                $upload_path = $relative_upload_file_path;// 上传路径
                $upload_file = md5(microtime(true)) . '.' . $ext; // 上传文件名称
            } else {
                $source_file_info = pathinfo($source_file); // 要上传的文件信息
                $ext              = $source_file_info['extension']; // 要上传的文件后缀
                $upload_path      = $relative_upload_file_path; // 上传路径
                $upload_file      = md5(microtime(true)) . '.' . $ext; // 上传文件名称
            }
        } // 上传路径文件 upload/test.text
        else {
            $ext         = $upload_file_path_info['extension']; // 要上传的文件后缀
            $upload_path = $upload_file_path_info['dirname']; // 上传路径
            $upload_file = $upload_file_path_info['basename']; // 上传文件名称
        }


        // 添加水印
        if (is_img($source_file) && $upload_thumb_water == 1 && !empty($upload_thumb_water_pic)) {
            $water = $this->create_water($source_file, $upload_path_temp_water, $upload_file, $upload_thumb_water_pic);
            if (!empty($water)) {
                $source_file = $water;
            }
        }

        // 对象的形式上传
        if (is_object($source_file)) {

            // 是否剪裁图片缩放
            if (!empty($param['tailoring']) && is_array($param['tailoring'])) {
                $tailoringFile = $this->create_thumb($source_file, $upload_path_temp_thumb, $upload_file, $param['tailoring']['width'], $param['tailoring']['height'], 6, true);
                $data          = $this->ossClient->uploadFile($this->config['bucket'], $upload_path . '/' . $upload_file, $tailoringFile);
                @unlink($tailoringFile);
            }

            // 正常上传
            else{
                try {
                    $data                           = $this->ossClient->uploadFile($this->config['bucket'], $upload_path . '/' . $upload_file, $source_file->getInfo('tmp_name'));
                    $file_info['source_file_name']  = $source_file->getInfo('name');
                } catch (OssException $e) {
                    $this->error = $e->getMessage();
                    return false;
                }
            }

            $file_info['save_file_name']    = $upload_file;
            $file_info['relative_path_url'] = $upload_path . '/' . $upload_file;
            $file_info['ext']               = $ext;
            $file_info['size']              = $data['info']['size_upload'];
            $file_info['url']               = $data['info']['url'];
        }


        // 路径形式上传
        elseif (is_string($source_file)) {

            // 是否剪裁图片缩放
            if (!empty($param['tailoring']) && is_array($param['tailoring'])) {
                $tailoringFile = $this->create_thumb($source_file, $upload_path_temp_thumb, $upload_file, $param['tailoring']['width'], $param['tailoring']['height'], 6, true);
                $data          = $this->ossClient->uploadFile($this->config['bucket'], $upload_path . '/' . $upload_file, $tailoringFile);
                @unlink($tailoringFile);
            }

            // 正常上传
            else {
                try {
                    $data                          = $this->ossClient->uploadFile($this->config['bucket'], $upload_path . '/' . $upload_file, $source_file);
                    $source_file_info              = pathinfo($source_file);
                    $file_info['source_file_name'] = $source_file_info['basename'];
                } catch (OssException $e) {
                    $this->error = $e->getMessage();
                    return false;
                }
            }

            $file_info['save_file_name']    = $upload_file;
            $file_info['relative_path_url'] = $upload_path . '/' . $upload_file;
            $file_info['ext']               = $ext;
            $file_info['size']              = $data['info']['size_upload'];
            $file_info['url']               = $data['info']['url'];
        }

        // 生成缩略图
        $config_thumbs = empty($param['thumbs']) ? '' : $param['thumbs'];
        if (is_img($source_file) && !empty($config_thumbs)) {
            if (is_array($config_thumbs)) {
                foreach ($config_thumbs as $value) {
                    $thumb_size               = $this->create_thumb($source_file, $upload_path_temp_thumb, $upload_file, $value['width'], $value['height']);
                    $thumb_size_file_info     = pathinfo($thumb_size);
                    $thumb_size_file_res_info = attaAdd($thumb_size, empty($upload_relative) ? $thumb_size_file_info['basename'] : $upload_relative . '/' . $thumb_size_file_info['basename']);
                    if (unlink($thumb_size)) {
                        $file_info['thumb'][] = $thumb_size_file_res_info['data']['url'];
                    } else {
                        $this->error = '缩略图生成失败';
                        return false;
                    }
                }
            }
        }

        // 删除水印
        if (!empty($water)) {
            unlink($water);
        }

        return $file_info;
    }

    /**
     * @describe 获取文url地址
     * @param $file 路径文件 例如 uploads/adminavatar/test.jpg
     * @param array $param
     *                   thumbs     缩略图
     *                       width  宽
     *                       height 高
     *                   default    默认图例如 uploads/adminavatar/default.jpg
     * @return string
     * @author 仇仇天
     */
    public function getUrl($file, $param = [])
    {
        try {
            $file_info = pathinfo($file); // 文件信息

            $file_exist = true; // 文件是否存在

            // 缩略图
            if (!empty($param['thumbs'])) {
                // 拼接附件url地址
                $path_file  = $file_info['dirname'] . '/' . $file_info['filename'] . '_' . $param['thumbs']['width'] . 'x' . $param['thumbs']['height'] . '.' . $file_info['extension'];
                $url        = 'http://' . $this->config['bucket'] . '.oss-cn-shanghai.aliyuncs.com/' . $path_file;
                $file_exist = $this->exist($path_file);
            } // 取主图
            else {
                $url        = 'http://' . $this->config['bucket'] . '.oss-cn-shanghai.aliyuncs.com/' . $file;
                $file_exist = $this->exist($file);
            }

            // 如果图不存在 这取默认图片
            if (!empty($param['default']) && $file_exist == false) {
                $url = 'http://' . $this->config['bucket'] . '.oss-cn-shanghai.aliyuncs.com/' . $param['default'];
            }

            return $url;
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @describe 删除文件
     * @author 仇仇天
     */
    public function del($file, $param = [])
    {
        try {
            $objects   = array();
            $objects[] = $file; // 首图
            $file_info = pathinfo($file);
            // 缩略图
            if (!empty($param['thumbs'])) {
                foreach ($param['thumbs'] as $value) {
                    $objects[] = $file_info['dirname'] . '/' . $file_info['filename'] . '_' . $value['width'] . 'x' . $value['height'] . '.' . $file_info['extension'];
                }
            }
            $this->ossClient->deleteObjects($this->config['bucket'], $objects);
            return true;
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @describe 判断文件是否存在
     * @author 仇仇天
     */
    public function exist($file, $param = [])
    {
        try {
            $exist = $this->ossClient->doesObjectExist($this->config['bucket'], $file);
            if ($exist) {
                return true;
            } else {
                return false;
            }
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }

    }

    /**
     * @describe 创建缩略图
     * @param string $file 目标文件，可以是文件对象或文件路径
     * @param string $dir 保存目录，即目标文件所在的目录名
     * @param string $save_name 缩略图名
     * @param string $thumb_max_width 宽度
     * @param string $thumb_max_height 高度
     * @param string $thumb_type 裁剪类型 1=等比例缩放 2=缩放后填充 3=居中裁剪 4=左上角裁剪 5=右下角裁剪 6=固定尺寸缩放
     * @return string 缩略图路径
     * @author 仇仇天
     */
    private function create_thumb($file, $dir, $save_name, $thumb_max_width, $thumb_max_height, $thumb_type = 1, $custom = false)
    {

        // 读取源图片
        $image = Image::open($file);

        // 设置缩放参数
        $image->thumb($thumb_max_width, $thumb_max_height, $thumb_type);

        // 解析文件信息
        $fileInfo = pathinfo($save_name);

        if (!$custom) {
            $thumb_path_name = $dir . $fileInfo['filename'] . '_' . $thumb_max_width . 'x' . $thumb_max_height . '.' . $fileInfo['extension'];
        } else {
            $thumb_path_name = $dir . $fileInfo['filename'] . '.' . $fileInfo['extension'];
        }


        // 拼接图片路径
//        if (is_object($file)) { // 对象形式
//            $thumb_path_name = $dir . pathinfo($file->getSaveName())['filename'] . '_' . $thumb_max_width . 'x' . $thumb_max_height . '.' . pathinfo($file->getSaveName())['extension'];
//        } else { // 字符形式
//
//            // 解析文件信息
//            $fileInfo = pathinfo($save_name);
//
//            $thumb_path_name = $dir . $fileInfo['filename'] . '_' . $thumb_max_width . 'x' . $thumb_max_height . '.' . $fileInfo['extension'];
//        }


        // 保存缩略图
        $image->save($thumb_path_name);

        return $thumb_path_name;
    }

    /**
     * @describe 添加水印
     * @param string $file 要添加水印的文件路径
     * @param string $dir 保存目录，即目标文件所在的目录名
     * @param string $save_name 图片名
     * @param string $thumb_water_pic 水印图片
     * @param string $watermark_pos 水印位置
     * @param string $watermark_alpha 水印透明度
     * @author 仇仇天
     */
    private function create_water($file = '', $dir, $save_name, $thumb_water_pic = '', $watermark_pos = '', $watermark_alpha = '')
    {
        // 系统水印位置
        $upload_thumb_water_position = config('upload_thumb_water_position');

        // 系统透明度
        $upload_thumb_water_alpha = config('upload_thumb_water_alpha');

        if (is_file($thumb_water_pic)) {

            // 读取图片
            $image = Image::open($file);

            // 添加水印
            $watermark_pos   = $watermark_pos == '' ? $upload_thumb_water_position : $watermark_pos;
            $watermark_alpha = $watermark_alpha == '' ? $upload_thumb_water_alpha : $watermark_alpha;
            $image->water($thumb_water_pic, $watermark_pos, $watermark_alpha);
            $thumb_path_name = $dir . $save_name;

            // 保存水印图片，覆盖原图
            $image->save($thumb_path_name);

            return $thumb_path_name;
        }
    }

}