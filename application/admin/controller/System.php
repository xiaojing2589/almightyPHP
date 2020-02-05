<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminConfig as AdminConfigModel;
use app\common\model\AdminModule as AdminModuleModel;

/**
 * 系统模块控制器
 * @package app\admin\controller
 */
class System extends Admin
{
    /**
     * 系统设置
     * @param string $module_group 分组
     * @return mixed
     * @author 仇仇天
     */
    public function index($module_group = 'admin')
    {
        // 保存数据
        if ($this->request->isPost()) {

            // 提交过来的数据
            $data = $this->request->post();

            // 所有配置数据
            $data_config_info = AdminConfigModel::getConfigDataInfo();

            // 文件类型处理
            $files = $this->request->file();

            if (!empty($files) && is_array($files)) {

                foreach ($files as $file_key => $file) {

                    // 解析配置
                    $options = json_decode($data_config_info[$file_key]['options'], true);

                    // 检测是否有设置上传
                    if (!empty($options['storage_path'])) {

                        // 多图 / 多文件
                        if (is_array($file)) {
                            $save_data = '';
                            foreach ($file as $files_key => $files_value) {
                                // 创建图片
                                $file_info = attaAdd($files_value, $options['storage_path']);
                                if (!$file_info['status']) {
                                    $this->error($file_info['msg']);
                                }
                                $save_data .= $file_info['data']['relative_path_url'] . ',';
                            }
                            // 设置存储
                            $data[$file_key] = trim($save_data, ',');
                        } // 单图 / 单文件
                        else {

                            // 创建图片
                            $file_info = attaAdd($file, $options['storage_path']);

                            // 上传错误
                            if (!$file_info['status']) {
                                $this->error($file_info['msg']);
                            }

                            // 设置存储
                            $data[$file_key] = $file_info['data']['relative_path_url'];
                        }

                    }
                }
            }

            // 源数据
            $items = AdminConfigModel::where('module', $module_group)->where('status', 1)->column('value,type,options', 'name');

            foreach ($items as $name => $values) {

                // 多图
                if ($values['type'] == 'images') {

                    // 数据库图片
                    $values_data = explode(',', $values['value']);

                    // 不需要删除的图片是否存在
                    if (!empty($data[$name . '_images'])) {
                        $nod_images = $data[$name . '_images'];

                        // 获取 不需要删除的图片 名称
                        foreach ($nod_images as $imgskey => $imgsvalue) {
                            $pathinfo             = pathinfo($imgsvalue);
                            $nod_images[$imgskey] = $pathinfo['basename'];
                        }

                        $nodel_img = [];

                        // 对比并删除
                        foreach ($values_data as $keyt => $valuet) {
                            $valuetinfo = pathinfo($valuet);
                            if (!in_array($valuetinfo['basename'], $nod_images)) {
                                attaDel($valuet);
                            } else {
                                $nodel_img[] = $valuet;
                            }
                        }

                        // 是否有新的上传图片
                        if (!empty($data[$name])) {
                            // 新的上传图片 和原有的图片合并
                            $data[$name] = $data[$name] . ',' . implode(',', $nodel_img);
                        } else {
                            // 新的上传图片
                            $data[$name] = implode(',', $nodel_img);
                        }

                    } // 删除所有图片
                    else {

                        foreach ($values_data as $keyt => $valuet) {
                            attaDel($valuet);
                        }

                        // 是否有新的上传图片
                        if (!empty($data[$name])) {
                            // 新的上传图片 和原有的图片合并
                            $data[$name] = $data[$name];
                        } else {
                            // 新的上传图片
                            $data[$name] = '';
                        }
                    }
                } // 单图
                elseif ($values['type'] == 'image') {
                    if (isset($data[$name])) {
                        if ($values['value']) {
                            attaDel($values['value']);
                        }
                    }
                }

                // 多文件
                if ($values['type'] == 'files') {

                    // 数据库文件
                    $values_data = explode(',', $values['value']);

                    // 不需要删除的图片是否存在
                    if (!empty($data[$name . '_filse'])) {

                        // 获取 不需要删除的图片 名称
                        $nod_files = $data[$name . '_filse'];
                        foreach ($nod_files as $filesKey => $filesValue) {
                            $pathinfo             = pathinfo($filesValue);
                            $nod_files[$filesKey] = $pathinfo['basename'];
                        }
                        $nodel_files = [];

                        // 对比并删除
                        foreach ($values_data as $keyt => $valuet) {
                            $valuetinfo = pathinfo($valuet);
                            if (!in_array($valuetinfo['basename'], $nod_files)) {
                                attaDel($valuet);
                            } else {
                                $nodel_files[] = $valuet;
                            }
                        }

                        // 是否有新的上传图片
                        if (!empty($data[$name])) {
                            // 新的上传图片 和原有的图片合并
                            $data[$name] = $data[$name] . ',' . implode(',', $nodel_files);
                        } else {
                            // 新的上传图片
                            $data[$name] = implode(',', $nodel_files);
                        }
                    } // 删除所有文件
                    else {
                        foreach ($values_data as $keyt => $valuet) {
                            attaDel($valuet);
                        }

                        // 是否有新的上传图片
                        if (!empty($data[$name])) {
                            // 新的上传图片 和原有的图片合并
                            $data[$name] = $data[$name];
                        } else {
                            // 新的上传图片
                            $data[$name] = '';
                        }
                    }
                } // 单文件
                elseif ($values['type'] == 'file') {
                    if (isset($data[$name])) {
                        if ($values['value']) {
                            attaDel($values['value']);
                        }
                    }
                } // 日期时间 日期 时间
                elseif ($values['type'] == 'datetime' || $values['type'] == 'date' || $values['type'] == 'time') {
                    if (!empty($data[$name])) {
                        $data[$name] = strtotime($data[$name]);
                    }
                } // 日期范围
                elseif ($values['type'] == 'daterange') {
                    if (!empty($data[$name]) || !empty($data[$name][0]) || !empty($data[$name][1])) {
                        $data[$name] = strtotime($data[$name][0]) . ',' . strtotime($data[$name][1]);
                    }
                } // 复选/单选/下拉
                elseif ($values['type'] == 'checkbox') {
                    if (isset($data[$name])) {
                        $data[$name] = implode(',', $data[$name]);
                    }
                }

                // 修改
                if (isset($data[$name])) {
                    AdminConfigModel::where('name', $name)->update(['value' => $data[$name]]);
                }

            }

            // 删除缓存
            AdminConfigModel::delCache();

            // 记录行为
            adminActionLog('admin.system_config_update');

            // 跳转
            $this->success('更新成功', url('index', ['module_group' => $module_group]));
        }

        // 查询条件
        $where = [];

        // 模块分组
        $where['module'] = $module_group;

        // 状态
        $where['status'] = 1;

        // 未隐藏
        $where['is_hide'] = 0;

        // 数据列表
        $data_list = AdminConfigModel::where($where)->field('*')->order('sort asc,id asc')->select();

        // 设置字段
        $FormItem = [];
        foreach ($data_list as $key => $value) {

            // 前台默认模块 单独设置
            if ($value['name'] == 'home_default_module') {
                $optionsArr   = [];
                $optionsArr[] = ['title' => '默认', 'value' => 'index'];
                foreach (AdminModuleModel::getModuleDataInfo() as $module_key => $module_value) {
                    $optionsArr[] = ['title' => $module_value['title'], 'value' => $module_value['name']];
                }
                $options = $optionsArr;
            }

            $options = json_decode($value['options'], true);

            $FormItemLS = [
                // 字段名称
                'field'      => $value['name'],
                // name
                'name'       => $value['name'],
                //分组
                'group'      => $value['module'],
                //分组类型
                'type_group' => $value['group'] ? $value['group'] : 'base',
                // 表单类型
                'form_type'  => $value['type'],
                // 表单标题
                'title'      => $value['title'],
                // 表单提示
                'tips'       => $value['tips'],
                // 表单配置
                'option'     => $options
            ];

            $FormItem[] = $FormItemLS;
        }

        // 设置字段值
        $FormItemVlue = [];
        foreach ($data_list as $key => $value) {
            // 单图
            if ($value['type'] == 'image') {
                $options                      = json_decode($value['options'], true);
                $FormItemVlue[$value['name']] = attaUrl($value['value'], $options['storage_path']);
            } // 多图
            elseif ($value['type'] == 'images') {
                $options   = json_decode($value['options'], true);
                $value_arr = explode(',', $value['value']);
                $res_value = [];
                if (!empty($value_arr)) {
                    foreach ($value_arr as $key_arr => $value_arr) {
                        if (!empty($value_arr)) {
                            $res_value[$key_arr] = attaUrl($value_arr, $options['storage_path']);
                        }
                    }
                    if (!empty($res_value)) {
                        $FormItemVlue[$value['name']] = $res_value;
                    }
                }
            } // 单文件
            elseif ($value['type'] == 'file') {
                // 解析配置参数
                $options = json_decode($value['options'], true);
                // 获取文件实际地址
                $FormItemVlue[$value['name']] = attaUrl($value['value'], $options['storage_path']);
            } // 多文件
            elseif ($value['type'] == 'files') {
                $options   = json_decode($value['options'], true);
                $value_arr = explode(',', $value['value']);
                $res_value = [];
                if (!empty($value_arr)) {
                    foreach ($value_arr as $key_arr => $value_arr) {
                        if (!empty($value_arr)) {
                            $res_value[$key_arr] = attaUrl($value_arr, $options['storage_path']);
                        }
                    }
                    if (!empty($res_value)) {
                        $FormItemVlue[$value['name']] = $res_value;
                    }
                }
            } // 日期时间
            elseif ($value['type'] == 'datetime') {
                if (!empty($value['value'])) {
                    $FormItemVlue[$value['name']] = @date('Y-m-d H:i', $value['value']);
                }
            } // 日期
            elseif ($value['type'] == 'date') {
                if (!empty($value['value'])) {
                    $FormItemVlue[$value['name']] = @date('Y-m-d', $value['value']);
                }
            } // 时间
            elseif ($value['type'] == 'time') {
                if (!empty($value['value'])) {
                    $FormItemVlue[$value['name']] = @date('H:i', $value['value']);
                }
            } // 日期范围
            elseif ($value['type'] == 'daterange') {
                if (!empty($value['value'])) {
                    $daterangeValue = explode(',', $value['value']);
                    if (!empty($daterangeValue[0]) || !empty($daterangeValue[1])) {
                        $FormItemVlue[$value['name']][0] = @date('Y-m-d', $daterangeValue[0]);
                        $FormItemVlue[$value['name']][1] = @date('Y-m-d', $daterangeValue[1]);
                    }
                }
            } // 默认处理
            else {
                $FormItemVlue[$value['name']] = $value['value'];
            }

        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置标题
        $form->setPageTitle('系统设置');

        // 设置分列数
        $form->listNumber(2);

        //设置隐藏表单
        $form->setFormHiddenData([['name' => 'module_group', 'value' => $module_group]]);

        // 获取模块数据
        $module_group_arr_info = AdminModuleModel::getModuleDataInfo();
        $module_group_arr      = [];
        foreach ($module_group_arr_info as $key => $value) {

            // 检查模块下配置数量
            $data_num = AdminConfigModel::where(['module' => $value['name'], 'is_hide' => 0])->count();

            if (!empty($data_num)) {
                $module_group_arr[] = [
                    'title'   => $value['title'],
                    'field'   => $value['name'],
                    'url'     => url('index', ['module_group' => $value['name']]),
                    'default' => $module_group == $value['name'] ? true : false
                ];
            }
        }

        // 设置页面分组
        $form->setGroup($module_group_arr);

        // 获取配置分组
        $group_arr_info = rcache('module_config_group');
        $group_arr      = [];
        foreach ($group_arr_info[$module_group] as $key => $value) {
            $group_arr[] = [
                'name'  => $value['title'],
                'field' => $value['name'],
                'group' => $module_group
            ];
        }

        // 设置页面内分组
        $form->setTypeGroup($group_arr);

        // 设置表单项
        $form->addFormItems($FormItem);

        // 设置表单数据
        $form->setFormdata($FormItemVlue);

        // 渲染页面
        return $form->fetch();
    }
}
