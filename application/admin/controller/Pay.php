<?php

namespace app\admin\controller;

use app\common\controller\Admin;

use app\common\builder\ZBuilder;
use think\facade\Env;

use app\admin\model\PPay as PPayModel;

/**
 * 支付控制器
 */
class Pay extends Admin
{
    /**
     * 配置首页
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $Model     = new PPayModel();

            $result    = $Model->getAll();

            $data_list = [];
            foreach ($result['pay'] as $value) {
                $data_list[] = $value;
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置页面标题
        $view->setPageTitle('支付管理');

        // 设置列
        $view->setColumn([
            [
                'field' => 'name',
                'title' => '支付名称',
                'align' => 'center'
            ],
            [
                'field' => 'mark',
                'title' => '支付标识',
                'align' => 'center'
            ],
            [
                'field' => 'ico',
                'title' => '支付图标',
                'align' => 'center',
            ],
            [
                'field' => 'doc',
                'title' => '支付描述',
                'align' => 'center',
            ],
            [
                'field' => 'is_use',
                'title' => '状态',
                'align' => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '开启', 'on_value' => 1, 'off_text' => '关闭', 'off_value' => 0]
                ],
                'hide' => <<<javascript
                if(row.status == 4){
                    return '<span class="label label-sm label-danger">不可操作</span>';
                }
javascript
            ],
            [
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'btn'           => [
                    [
                        'field'      => 'd',
                        'text'       => '卸载',
                        'confirm'    => '确认卸载该插件',
                        'url'        => url('uninstall'),
                        'query_data' => '{"field":["mark"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'text'       => '设置',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["mark"]}'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '安装',
                        'ico'        => 'fa fa-check-square',
                        'class'      => 'btn btn-xs btn-success hide_install',
                        'url'        => url('install'),
                        'query_data' => '{"field":["mark"]}'
                    ]
                ],
                'peration_hide' => <<<javascript
                        $.each(perationArr,function(i,v){
                            if(v.indexOf('hide_install') > -1){
                                if(row.status !== 4){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_d') > -1){
                                if(row.status !== 1 || row.system == 1){   
                                    delete perationArr[i]
                                }
                            }
                            if(v.indexOf('hide_u') > -1){
                                if(row.status !== 1 || row.mark == 'local'){   
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
     * 安装模块
     * @author 仇仇天
     * @param string $mark 插件标识
     * @param int $confirm 是否确认
     * @return mixed
     */
    public function install($mark = '', $confirm = 0)
    {
        // 设置最大执行时间和内存大小
        ini_set('max_execution_time', '0');

        ini_set('memory_limit', '1024M');

        if ($mark == '') $this->error('支付不存在！');

        // 插件路径
        $path = Env::get('extend_path') . '/pay/';

        // 模块配置信息
        $sms_info = [];
        if ($mark != '') {
            // 从配置文件获取
            if (is_file($path . $mark . '/config/config.php')) {
                $sms_info = include $path . $mark . '/config/config.php';
            }
        }

        // 进入安装界面
        if ($confirm == 0) {

            // 使用ZBuilder快速创建表单
            $form = ZBuilder::make('forms');

            // 设置页面标题
            $form->setPageTitle(' 支付 - 安装');

            // 设置返回地址
            $form->setReturnUrl(url('index'));

            // 表单项
            $FormItem = [
                [
                    'field'     => 'name',
                    'name'      => 'name',
                    'value'     => $sms_info['name'],
                    'form_type' => 'static',
                    'title'     => '支付名称'
                ],
                [
                    'field'     => 'mark',
                    'name'      => 'mark',
                    'value'     => $sms_info['mark'],
                    'form_type' => 'static',
                    'title'     => '支付标识',
                ]
            ];
            $FormItem = array_merge($FormItem, $sms_info['config']);
            $form->addFormItems($FormItem);

            // 设置隐藏表单数据
            $hiddenData = [['name' => 'mark', 'value' => $mark], ['name' => 'confirm', 'value' => 1]];
            $form->setFormHiddenData($hiddenData);

            $form->submitButtonText('安装');

            // 渲染页面
            return $form->fetch();
        }

        $data = $this->request->post();

        // 模块配置信息
        $module_info = [];
        if (is_file($path . $mark . '/config/config.php')) {
            $module_info = include $path . $mark . '/config/config.php';
        } else {
            $this->error('支付不存在！');
        }

        foreach ($module_info['config'] as &$value) {
            if (isset($data[$value['field']])) {
                $value['value'] = $data[$value['field']];
            }
        }

        $allowField = [
            'name'        => $module_info['name'],
            'mark'        => $module_info['mark'],
            'ico'         => $module_info['ico'],
            'doc'         => $module_info['doc'],
            'config'      => json_encode($module_info['config'], JSON_UNESCAPED_UNICODE),
            'create_time' => time(),
            'update_time' => time()
        ];

        if (false !== PPayModel::insert($allowField)) {

            // 删除缓存
            PPayModel::delCache();

            $this->success('支付安装成功', 'index');
        } else {
            $this->error('安装失败！');
        }
    }

    /**
     * 设置
     * @author 仇仇天
     * @param null $mark 标识
     * @return mixed
     */
    public function edit($mark = null)
    {

        if ($mark === null) $this->error('缺少参数');

        // 获取数据
        $info = PPayModel::where('mark', $mark)->find();

        if (empty($info)) $this->error('未找到支付');

        if ($this->request->isPost()) {

            $data = $this->request->post();

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $allowField[$data['extend_field']] = $data[$data['extend_field']];
                if ($data[$data['extend_field']] == 1) {
                    $updateAll = [$allowField[$data['extend_field']] => 0];
                }
            }
            // 普通编辑
            else {
                $info['config'] = json_decode($info['config'], true);
                $config_value   = [];
                foreach ($info['config'] as $value) {
                    if (isset($data[$value['field']])) {
                        $value['value'] = $data[$value['field']];
                    }
                    $config_value[] = $value;
                }
                $allowField = [
                    'config'      => json_encode($config_value, JSON_UNESCAPED_UNICODE),
                    'update_time' => time()
                ];
            }

            if (false !== PPayModel::where(['mark' => $mark])->update($allowField)) {
                if (!empty($updateAll)) {
                    PPayModel::where('mark', '<>', $mark)->update($updateAll);
                }

                // 删除缓存
                PPayModel::delCache();

                $this->success('设置成功', url('index'));
            } else {
                $this->error('设置失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('支付 - 设置');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 表单项
        $FormItem = [
            [
                'field'     => 'name',
                'name'      => 'name',
                'value'     => $info['name'],
                'form_type' => 'static',
                'title'     => '支付名称'
            ],
            [
                'field'     => 'mark',
                'name'      => 'mark',
                'value'     => $info['mark'],
                'form_type' => 'static',
                'title'     => '支付标识',
            ]
        ];

        $FormItem = array_merge($FormItem, json_decode($info['config'], true));

        $form->addFormItems($FormItem);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'mark', 'value' => $info['mark']]]);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 卸载
     * @author 仇仇天
     * @param null $mark 标识
     */
    public function uninstall($mark = null)
    {
        if ($mark === null) $this->error('缺少参数');

        // 获取数据
        $info = PPayModel::where('mark', $mark)->find();

        if (empty($info)) $this->error('未找到支付');

        if (false !== PPayModel::del(['mark' => $mark])) {

            // 删除缓存
            PPayModel::delCache();

            $this->success('卸载成功', url('index'));
        } else {

            $this->error('卸载失败');
        }
    }

}
