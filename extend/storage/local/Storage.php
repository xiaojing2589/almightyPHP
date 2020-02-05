<?php

namespace storage\local;

use think\facade\Request;

class Storage
{
    /**
     * 配置参数
     * @var array
     */

    protected $config = [];

    protected $ossClient; // oss对象

    public $error; // 错误信息

    /**
     * Storage constructor.
     * @param array $config 是插件配置
     */
    public function __construct($config = [])
    {
        $this->config = $config;
    }

    /**
     * 文件上传
     * @param $source_file 要上传的 文件(d:/sa/test.text)/对象(thinkphp5 request->file())
     * @param $upload_file_Path 上传的路径
     */
    public function uploadFile($source_file, $upload_file_path, $param = [])
    {

        //返回信息
        $file_info = [
            'source_file_name'  => '',  // 源文件名
            'save_file_name'    => '',    // 上传文件名称
            'ext'               => '',               // 文件后缀
            'size'              => 0,               // 文件大小
            'thumb'             => [],             // 缩略图路径
            'relative_path_url' => '', // 文件相对url路径
            'url'               => '',               // 可访问url地址
        ];

        $upload_thumb_water = config('upload_thumb_water');     // 系统是否开启水印

        $upload_thumb_water_pic = config('upload_thumb_water_pic'); // 系统水印图片路径

        $upload_dir = config('upload_dir'); // 文件上传根目录

        $upload_path = config('upload_path'); // 文件上传路径

        $absolutely_upload_file_path = empty($upload_file_path) ? $upload_path : $upload_path . '/' . $upload_file_path; // 绝对上传路径名/路径文件名

        $relative_upload_file_path = empty($upload_file_path) ? $upload_dir : $upload_dir . '/' . $upload_file_path; // 相对上传路径名/路径文件名

        $upload_file_path_info = pathinfo($absolutely_upload_file_path); // 存储路径/文件名称 信息


        // 设置存储路径文件，路径形式 例如：upload/test
        if (empty($upload_file_path_info['extension'])) {
            if (is_object($source_file)) {
                $ext         = pathinfo($source_file->getInfo('name'))['extension']; // 要上传的文件后缀
                $upload_path = $absolutely_upload_file_path;// 上传路径
                $upload_file = md5(microtime(true)) . '.' . $ext; // 上传文件名称
            } else {
                $source_file_info = pathinfo($source_file); // 要上传的文件信息
                $upload_path      = $absolutely_upload_file_path; // 上传路径
                $upload_file      = md5(microtime(true)) . '.' . $source_file_info['extension']; // 上传文件名称
            }
            $save_upload_path_file = $relative_upload_file_path . '/' . $upload_file;
            $save_upload_path      = $relative_upload_file_path;
        } // 上传路径文件 upload/test.text
        else {
            $upload_path           = $upload_file_path_info['dirname']; // 上传路径
            $upload_file           = $upload_file_path_info['basename']; // 上传文件名称
            $save_upload_path_file = $relative_upload_file_path;
            $save_upload_path      = pathinfo($relative_upload_file_path)['dirname'];
        }

        // 检查目录是否存在
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // 对象形式上传
        if (is_object($source_file)) {

            // 是否剪裁图片缩放
            if (!empty($param['tailoring']) && is_array($param['tailoring'])) {
                $thumb_file                     = create_thumb($source_file, $upload_path, $upload_file, $param['tailoring']['width'], $param['tailoring']['height'], 6, true);
                $save_file_info                 = pathinfo($thumb_file); // 上传路径文件信息
                $file_info['save_file_name']    = $save_file_info['basename'];
                $file_info['ext']               = $save_file_info['extension'];
                $file_info['size']              = filesize($upload_path . '/' . $upload_file);
                $file_info['relative_path_url'] = $save_upload_path_file;
                $file_info['url']               = $this->getUrl($save_upload_path_file);
            } // 普通上传
            else {
                $info = $source_file->rule('uniqid')->move($upload_path . '/', $upload_file); // 上传
//                predBUG($info);
                if ($info) {
                    // 获取附件信息
                    $file_info['source_file_name']  = $info->getInfo('name');
                    $file_info['save_file_name']    = $info->getFilename();
                    $file_info['ext']               = $info->getExtension();
                    $file_info['size']              = $info->getSize();
                    $file_info['relative_path_url'] = $save_upload_path_file;
                    $file_info['url']               = $this->getUrl($save_upload_path_file);
                } else {
                    $this->error = "上传失败！";
                    return false;
                }
            }
        } // 路径形式上传
        elseif (is_string($source_file)) {

            // 是否剪裁图片缩放
            if (!empty($param['tailoring']) && is_array($param['tailoring'])) {
                $thumb_file     = create_thumb($source_file, $upload_path, $upload_file, $param['tailoring']['width'], $param['tailoring']['height'], 6, true);
                $save_file_info = pathinfo($thumb_file); // 上传路径文件信息
            } // 普通上传
            else {
                // 拷贝
                if (copy($source_file, $upload_path . '/' . $upload_file)) {
                    $save_file_info                = pathinfo($upload_path . '/' . $upload_file); // 上传路径文件信息
                    $source_file_info              = pathinfo($source_file); // 源文件信息
                    $file_info['source_file_name'] = $source_file_info['basename'];
                } else {
                    $this->error = "上传失败！";
                    return false;
                }
            }

            $file_info['save_file_name']    = $save_file_info['basename'];
            $file_info['ext']               = $save_file_info['extension'];
            $file_info['size']              = filesize($upload_path . '/' . $upload_file);
            $file_info['relative_path_url'] = $save_upload_path_file;
            $file_info['url']               = $this->getUrl($save_upload_path_file);
        }

        // 添加水印
        if (is_img($source_file) && $upload_thumb_water == 1 && $upload_thumb_water_pic > 0) {
            create_water($upload_path . '/' . $upload_file, $upload_thumb_water_pic);
        }

        // 生成缩略图
        $config_thumbs = empty($param['thumbs']) ? '' : $param['thumbs']; // 配置缩略图配置
        if (is_img($source_file) && !empty($config_thumbs)) {
            if (is_array($config_thumbs)) {
                foreach ($config_thumbs as $value) {
                    $thumb_file = create_thumb($upload_path . '/' . $upload_file, $upload_path, $upload_file, $value['width'], $value['height']);
                    if ($thumb_file) {
                        $file_info['thumb'][] = $this->getUrl($save_upload_path . '/' . pathinfo($thumb_file)['basename']);
                    } else {
                        $this->error = '缩略图生成失败';
                        return false;
                    }
                }
            }
        }
        return $file_info;
    }

    /**
     * 获取文url地址
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
        $domain = Request::domain(); // 协议加域名

        $file_info = pathinfo($file); // 文件信息

        $file_exist = true; // 文件是否存在

        // 缩略图
        if (!empty($param['thumbs'])) {
            // 拼接附件url地址
            $path_file  = $file_info['dirname'] . '/' . $file_info['filename'] . '_' . $param['thumbs']['width'] . 'x' . $param['thumbs']['height'] . '.' . $file_info['extension'];
            $url        = $domain . '/' . $path_file;
            $file_exist = $this->exist($path_file);
        } // 取主图
        else {
            $url        = $domain . '/' . $file;// 拼接附件url地址
            $file_exist = $this->exist($file);
        }

        // 如果图不存在 这取默认图片
        if (!empty($param['default']) && empty($file_exist)) {
            $url = $this->getUrl($param['default'], $param);
        }
        $url = strtr($url, '\\', '/');
        return $url;
    }

    /**
     * 删除文件
     * @param $file 需要删除的路径文件  例如 uploads/adminavatar/test.jpg
     * @param array $param 参数
     * @return bool
     * @author 仇仇天
     */
    public function del($file, $param = [])
    {
        $upload_path      = config('public_path');
        $file_info        = pathinfo($file);
        $upload_path_file = $upload_path . $file;
        try {
            if (!empty($file_info['extension'])) {
                @unlink($upload_path_file); // 删除主图
                // 删除缩略图
                if (!empty($param['thumbs'])) {
                    foreach ($param['thumbs'] as $value) {
                        @unlink($upload_path . $file_info['dirname'] . '/' . $file_info['filename'] . '_' . $value['width'] . 'x' . $value['height'] . '.' . $file_info['extension']);
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->error = '删除失败';
            return false;
        }
    }

    /**
     * 判断文件收存在
     * @author 仇仇天
     */
    public function exist($file, $param = [])
    {
        $upload_path      = config('public_path');
        $upload_path_file = $upload_path . $file;
        if (is_file($upload_path_file)) {
            return true;
        } else {
            return false;
        }
    }
}
