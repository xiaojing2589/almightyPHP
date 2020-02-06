<?php
namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\PStorage as PStorageModel;
use app\common\model\AdminConfig as AdminConfigModel;
use app\common\builder\ZBuilder;
use think\facade\Env;

/**
 * 云存储控制器
 */
class Storage extends Admin
{
    /**
     * 配置首页
     * @author 仇仇天
     * @return mixed
     */
    public function index()
    {
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $Model     = new PStorageModel();
            $result    = $Model->getAll();
            $data_list = [];
            foreach ($result['storage'] as $value) {
                $data_list[] = $value;
            }
            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置行内编辑地址
        $view->editableUrl(url('edit'));

        // 设置页面标题
        $view->setPageTitle('短信管理');

        // 设置分组标签
        $view->setGroup([
            ['title' => '短信管理', 'value' => 'sms', 'url' => url('index'),'default'=>true],
            ['title' => '设置', 'value' => 'storage_setting', 'url' => url('storagesetting'),'default'=>false]
        ]);

        // 设置列
        $view->setColumn([
            [
                'field' => 'name',
                'title' => '插件名称',
                'align' => 'center'
            ],
            [
                'field' => 'mark',
                'title' => '插件标识',
                'align' => 'center'
            ],
            [
                'field' => 'ico',
                'title' => '插件图标',
                'align' => 'center',
            ],
            [
                'field' => 'doc',
                'title' => '插件描述',
                'align' => 'center',
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
                                if(row.status !== 1 || row.mark == 'local'){   
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
     * @param string $mark  模块标识
     * @param int $confirm  是否确认
     * @return mixed
     */
    public function install($mark = '',$confirm=0)
    {
        // 设置最大执行时间和内存大小
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '1024M');
        if ($mark == '') $this->error('云存储插件不存在！');

        // 插件路径
        $path = Env::get('extend_path').'/storage/';

        // 模块配置信息
        $storeage_info = [];
        if ($mark != '') {
            // 从配置文件获取
            if (is_file($path. $mark . '/config/config.php')) {
                $storeage_info = include $path. $mark . '/config/config.php';
            }
        }

        // 进入安装界面
        if ($confirm == 0) {

            // 使用ZBuilder快速创建表单
            $form = ZBuilder::make('forms');

            // 设置页面标题
            $form->setPageTitle('云存储 - 安装');

            // 设置返回地址
            $form->setReturnUrl(url('storage/index'));

            // 表单项
            $FormItem = [
                [
                    'field'=>'name',
                    'name'=>'name',
                    'value'=>$storeage_info['name'],
                    'form_type'=>'static',
                    'title'=>'插件名称'
                ],
                [
                    'field'=>'mark',
                    'name'=>'mark',
                    'value'=>$storeage_info['mark'],
                    'form_type'=>'static',
                    'title'=>'插件标识',
                ]
            ];
            $FormItem = array_merge($FormItem, $storeage_info['config']);
            $form->addFormItems($FormItem);

            // 设置隐藏表单数据
            $hiddenData = [['name'=>'mark','value'=>$mark],['name'=>'confirm','value'=>1]];
            $form->setFormHiddenData($hiddenData);

            $form->submitButtonText('安装');

            // 渲染页面
            return $form ->fetch();
        }

        $data = $this->request->post();

        // 模块配置信息
        $module_info = [];
        if (is_file($path. $mark . '/config/config.php')) {
            $module_info = include $path. $mark . '/config/config.php';
        }else{
            $this->error('插件不存在！');
        }

        foreach ($module_info['config'] as &$value){
            if(isset($data[$value['field']])){
                $value['value'] = $data[$value['field']];
            }
        }

        $allowField = [
            'name'=>$module_info['name'],
            'mark'=>$module_info['mark'],
            'ico' =>$module_info['ico'],
            'doc' =>$module_info['doc'],
            'config'=>json_encode($module_info['config'],JSON_UNESCAPED_UNICODE),
            'create_time'=>time(),
            'update_time'=>time()
        ];

        if (false !== PStorageModel::insert($allowField)) {
            // 记录行为
            adminActionLog('admin.storage_install');
            $this->refreshCache();
            $this->success('模块安装成功', 'index');
        }else{
            $this->error('安装失败！');
        }

    }

    /**
     * 卸载
     * @author 仇仇天
     * @param null $mark 标识
     */
    public function uninstall($mark = null){

        if ($mark === null) $this->error('缺少参数');

        // 获取数据
        $info = PStorageModel::where('mark', $mark)->find();

        if(empty($info))$this->error('未找到插件');

        if($info['system'] == 1)$this->error('系统插件不可卸载');

        if(false !== PStorageModel::del(['mark'=>$mark])){
            // 记录行为
            adminActionLog('admin.storage_uninstall');
            $this->refreshCache();
            $this->success('卸载成功', url('index'));
        }else{
            $this->error('卸载失败');
        }
    }

    /**
     * 设置
     * @author 仇仇天
     * @param null $mark
     * @return mixed
     */
    public function edit($mark = null){

        if ($mark === null) $this->error('缺少参数');

        // 获取数据
        $info = PStorageModel::where('mark', $mark)->find();

        if(empty($info))$this->error('未找到插件');

        if ($this->request->isPost()) {

            $data = $this->request->post();

            $info['config'] = json_decode($info['config'],true);

            $config_value = [];
            foreach ($info['config'] as $value){
                if(isset($data[$value['field']])){
                    $value['value'] = $data[$value['field']];
                }
                $config_value[] = $value;
            }

            $allowField = [
                'config'=>json_encode($config_value,JSON_UNESCAPED_UNICODE),
                'update_time'=>time()
            ];

            if (PStorageModel::where(['mark'=>$mark])->update($allowField)) {
                // 记录行为
                adminActionLog('admin.storage_edit');
                $this->refreshCache();
                $this->success('设置成功', url('index'));
            } else {
                $this->error('设置失败');
            }

        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('云存储 - 设置');

        // 设置返回地址
        $form->setReturnUrl(url('storage/index'));

        // 表单项
        $FormItem = [
            [
                'field'=>'name',
                'name'=>'name',
                'value'=>$info['name'],
                'form_type'=>'static',
                'title'=>'插件名称'
            ],
            [
                'field'=>'mark',
                'name'=>'mark',
                'value'=>$info['mark'],
                'form_type'=>'static',
                'title'=>'插件标识',
            ]
        ];

        $FormItem = array_merge($FormItem, json_decode($info['config'],true));
        $form->addFormItems($FormItem);

        // 设置隐藏表单数据
        $hiddenData = [['name'=>'mark','value'=>$info['mark']]];

        $form->setFormHiddenData($hiddenData);

        // 渲染页面
        return $form ->fetch();
    }

    /**
     * 云存储设置
     * @author 仇仇天
     */
    public function storageSetting()
    {

        if ($this->request->isPost()) {
            // 表单数据
            $data               = input();
            $save_data          = [];
            $save_data['value'] = $data['upload_driver'];
            if (false !== AdminConfigModel::where(['name' => 'upload_driver'])->update($save_data)) {
                // 记录行为
                adminActionLog('admin.storage_setting');
                // 刷新缓存
                AdminConfigModel::delCache();
                $this->success('设置成功', url('storagesetting'));
            } else {
                $this->error('设置失败');
            }
        }

        // 获取配置
        $data_config_info = rcache('system_config_info.upload_driver');

        // 所有短信驱动数据
        $smsData = PStorageModel::getStorageDataInfo();
        $smsArr = [];
        foreach ($smsData as $ka => $va) {
            $smsArr[] = ['title' => $va['name'], 'value' => $va['mark']];
        }
        $options = $smsArr;

        // 使用ZBuilder快速创建表单
        $form             = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('云存储驱动 - 设置');

        // 设置 提交地址
        $form->setFormUrl(url('storagesetting'));

        // 设置分组标签
        $form->setGroup([
            ['title' => '短信管理', 'value' => 'sms', 'url' => url('index'),'default'=>false],
            ['title' => '设置', 'value' => 'storage_setting', 'url' => url('storagesetting'),'default'=>true]
        ]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'upload_driver',
                'name'      => 'upload_driver',
                'title'     => '选择上传驱动',
                'form_type' => $data_config_info['type'],
                'value'     => $data_config_info['value'],
                'option'    => $options,
                'tips'      => $data_config_info['tips']
            ],
        ]);

        return $form->fetch();
    }

    /**
     *  刷新缓存
     * @author 仇仇天
     */
    private function refreshCache(){
        PStorageModel::delCache();
    }
}
