<?php
namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminPlugin as PluginModel;
use app\common\model\AdminHookPlugin as HookPluginModel;
use util\Sql;
use think\Db;

/**
 * 插件管理控制器
 * @package app\admin\controller
 */
class Plugin extends Admin
{
    /**
     * 首页
     * @author 仇仇天
     * @param string $group 分组
     * @param string $type 显示类型
     * @return mixed|string
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 搜索关键词
            $keyword = input('param.searchKeyword/s', '', 'trim');

            $PluginModel = new PluginModel;

            $result = $PluginModel->getAll($keyword);

            $data_list = [];

            foreach ($result['plugins'] as $value){
                $data_list[] = $value;
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('模块列表');

        // 设置搜索框
        $view->setSearch([
            ['title' => '标识/名称/作者', 'field' => 'title','condition'=>'like', 'default' => true]
        ]);

        // 设置分组
        $view->setGroup([
            ['title'=>'本地','field'=>'local','url'=>url('index'),'default'=>true],
            ['title'=>'线上','field'=>'online','url'=>url('index'),'default'=>false]
        ]);

        $hide = <<<javascript
                if(row.system_module == 1){
                    return '<span class="label label-sm label-danger">不可操作</span>';
                }
                if(row.status == -2){
                    return '<span class="label label-sm label-danger">不可操作，模块信息缺失</span>';
                }
javascript;

        $peration_hide = <<<javascript
                $.each(perationArr,function(i,v){
                    if(v.indexOf('hide_install') > -1){
                        if(row.status !== '-1'){   
                            delete perationArr[i]
                        }
                    }
                    if(v.indexOf('hide_open') > -1){
                        if(row.status !== 2){   
                            delete perationArr[i]
                        }
                    } 
                    if(v.indexOf('hide_prohibit') > -1){
                        if(row.status !== 1){   
                            delete perationArr[i]
                        }
                    }  
                    
                    if(v.indexOf('hide_manage') > -1){
                        if(row.admin == 2|| row.status == '-1'){   
                            delete perationArr[i]
                        }
                    }  
                    if(v.indexOf('hide_config') > -1){
                        if(!row.config || row.status == '-1'){   
                            delete perationArr[i]
                        }
                    } 
                     if(v.indexOf('hide_uninstall') > -1){
                        if(row.status == '-1'){   
                            delete perationArr[i]
                        }
                    }                           
                });   
javascript;

        // 设置列
        $view->setColumn([
            [
                'field'    => 'name',
                'title'    => '标识',
                'align'    => 'center'
            ],
            [
                'field'    => 'title',
                'title'    => '名称',
                'align'    => 'center'
            ],
            [
                'field' => 'icon',
                'title' => '图标',
                'align' => 'center',
            ],
            [
                'field' => 'author',
                'title' => '作者',
                'align' => 'center',
            ],
            [
                'field' => 'version',
                'title' => '版本',
                'align' => 'center',
            ],
            [
                'field' => 'description',
                'title' => '简介',
                'align' => 'center',
                'width'=>100,
                'mecism'=>true
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'width'=>300,
                'type'  => 'btn',
                'btn'   => [
                    [
                        'field'      => 'c',
                        'text'       => '安装',
                        'ico'        => 'fa flaticon2-download',
                        'class'      => 'btn btn-xs btn-info hide_install',
                        'url'        => url('install'),
                        'query_data' => '{"field":["name"]}',
                        'query_jump' => 'ajax',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '启用',
                        'ico'        => 'fa fa-check',
                        'class'      => 'btn btn-xs btn-success hide_open',
                        'url'        => url('editstatus'),
                        'query_data' => '{"field":["name"],"extentd_field":{"status":1}}',
                        'query_jump' => 'ajax',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '禁用',
                        'ico'        => 'fa fa-close',
                        'class'      => 'btn btn-xs btn-warning hide_prohibit',
                        'url'        => url('editstatus'),
                        'query_data' => '{"field":["name"],"extentd_field":{"status":2}}',
                        'query_jump' => 'ajax',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '管理',
                        'ico'        => 'fa fa-cogs',
                        'class'      => 'btn btn-xs btn-info hide_manage',
                        'url'        => url('manage'),
                        'query_data' => '{"field":["name"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '设置',
                        'ico'        => 'fa fa-cog',
                        'class'      => 'btn btn-xs btn-brand hide_config',
                        'url'        => url('config'),
                        'query_data' => '{"field":["name"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '卸载',
                        'ico'        => 'fa fa-trash',
                        'class'      => 'btn btn-xs btn-danger hide_uninstall',
                        'url'        => url('uninstall'),
                        'query_data' => '{"field":["name"]}',
                        'query_jump' => 'ajax',
                        'confirm'    => '确认卸载该插件？',
                        'query_type' => 'get'
                    ]
                ],
                'hide' =>$hide,
                'peration_hide' =>$peration_hide
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 安装插件
     * @author 仇仇天
     * @param string $name 插件标识
     */
    public function install($name = '')
    {
        // 设置最大执行时间和内存大小
        ini_set('max_execution_time', '0');

        ini_set('memory_limit', '1024M');

        $plug_name = trim($name);

        if ($plug_name == '') $this->error('插件不存在！');

        // 获取插件类
        $plugin_class = get_plugin_class($plug_name);

        // 判断插件是否存在
        if (!class_exists($plugin_class))$this->error('插件不存在！');

        // 实例化插件
        $plugin = new $plugin_class;

        // 插件预安装
        if(!$plugin->install()) $this->error('插件预安装失败!原因：'. $plugin->getError());

        // 插件配置信息
        $plugin_config = [];

        // 从配置文件获取
        if (is_file(config('plugin_path'). $name . '/config.php')) {
            $plugin_config = include config('plugin_path'). $name . '/config.php';
        }

        if (empty($plugin_config))$this->error('插件不存在！');

        // 添加钩子
        if (isset($plugin_config['hook']) && !empty($plugin_config['hook'])) {
            if (!HookPluginModel::addHooks($plugin_config['hook'], $name)){
                $this->error('安装插件钩子时出现错误，请重新安装');
            }
        }

        // 执行安装插件sql文件
        $sql_file = realpath(config('plugin_path').$name.'/install.sql');
        if (file_exists($sql_file)) {
            if (isset($plugin_info['database_prefix']) && $plugin_config['database_prefix'] != '') {
                $sql_statement = Sql::getSqlFromFile($sql_file, false, [$plugin_config['database_prefix'] => config('database.prefix')]);
            } else {
                $sql_statement = Sql::getSqlFromFile($sql_file);
            }
            if (!empty($sql_statement)) {
                foreach ($sql_statement as $value) {
                    Db::execute($value);
                }
            }
        }

        // 验证插件信息
        $result = $this->validate($plugin_config['info'], 'Plugin');

        // 验证失败 输出错误信息
        if(true !== $result) $this->error($result);

        // 插件基础信息
        $pluginDataInfo = $plugin_config['info'];

        // 插件设置信息
        if(!empty($plugin_config['config'])){
            $pluginDataInfo['config'] = json_encode($plugin_config['config'],JSON_UNESCAPED_UNICODE);
        }

        // 将插件信息写入数据库
        if (PluginModel::insert($pluginDataInfo)) {

            // 刷新缓存
            $this->refreshCache();

            // 记录行为
            adminActionLog('admin.plugin_install');

            $this->success('插件安装成功');
        } else {
            $this->error('插件安装失败');
        }
    }

    /**
     * 卸载插件
     * @author 仇仇天
     * @param string $name 插件标识
     */
    public function uninstall($name = '')
    {
        $plug_name = trim($name);

        if ($plug_name == '') $this->error('插件不存在！');

        $class = get_plugin_class($plug_name);

        if (!class_exists($class))$this->error('插件不存在！');

        // 实例化插件
        $plugin = new $class;

        // 插件预卸
        if(!$plugin->uninstall()) $this->error('插件预卸载失败!原因：'. $plugin->getError());

        // 卸载插件自带钩子
        HookPluginModel::deleteHooks($plug_name);

        // 执行卸载插件sql文件
        $sql_file = realpath(config('plugin_path').$plug_name.'/uninstall.sql');
        if (file_exists($sql_file)) {
            if (isset($plugin->database_prefix) && $plugin->database_prefix != '') {
                $sql_statement = Sql::getSqlFromFile($sql_file, true, [$plugin->database_prefix => config('database.prefix')]);
            } else {
                $sql_statement = Sql::getSqlFromFile($sql_file, true);
            }

            if (!empty($sql_statement)) {
                Db::execute($sql_statement);
            }
        }

        // 删除插件信息
        if (PluginModel::where('name', $plug_name)->delete()) {

            // 刷新缓存
            $this->refreshCache();

            // 记录行为
            adminActionLog('admin.plugin_uninstall');

            $this->success('插件卸载成功');
        } else {
            $this->error('插件卸载失败');
        }
    }

    /**
     *  插件管理
     * @author 仇仇天
     * @param string $name 插件名
     * @return mixed
     */
    public function manage($name = '')
    {
        // 加载自定义后台页面
        if (plugin_action_exists($name, 'Admin', 'index')) {
            return plugin_action($name, 'Admin', 'index');
        }
    }

    /**
     * 插件参数设置
     * @param string $name 插件名称
     * @author 仇仇天
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function config($name = '')
    {
        if ($name === null) $this->error('缺少参数');

        // 插件配置值
        $info      = PluginModel::where('name', $name)->field('id,name,config')->find();

        // 配置参数
        $db_config = json_decode($info['config'], true);

        if ($this->request->isPost()) {

            // 提交参数
            $data = input();

            // 设置入库
            $config_value = [];

            // 设置入库
            foreach ($db_config as $value){
                if(isset($data[$value['field']])){
                    $value['value'] = $data[$value['field']];
                }
                $config_value[] = $value;
            }

            $allowField = [
                'config'=>json_encode($config_value,JSON_UNESCAPED_UNICODE),
                'update_time'=>time()
            ];

            if (false !== PluginModel::where('name', $name)->update($allowField)) {

                // 记录行为
                adminActionLog('admin.plugin_config');

                // 刷新缓存
                $this->refreshCache();

                $this->success('更新成功', 'index');
            } else {
                $this->error('更新失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('插件 - 设置');

        // 设置返回地址
        $form->setReturnUrl(url('plugin/index',['name'=>$name]));

        // 设置表单项
        $form->addFormItems($db_config);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name'=>'name','value'=>$name]]);

        return $form ->fetch();

    }

    /**
     * 禁用/启用
     * @author 仇仇天
     */
    public function editStatus()
    {
        // 表单数据
        $data = input();

        // 需要修改的数据
        $where= [['name', 'in', $data['name']]];

        $result = PluginModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {

            adminActionLog('admin.config_edit_status');

            $this->refreshCache();

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     * @throws \Exceptionsa
     */
    private function refreshCache(){

        // 删除插件钩子缓存
        HookPluginModel::delCache();

        // 删除插件信息
        PluginModel::delCache();
    }
}
