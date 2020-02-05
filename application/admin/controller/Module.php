<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\AdminModule as ModuleModel;
use app\common\model\AdminPlugin as PluginModel;
use app\common\model\AdminMenu as MenuModel;
use app\common\model\AdminConfigGroup as AdminConfigGroup;
use app\common\model\AdminConfig as AdminConfig;
use app\common\model\AdminAction as AdminActionModel;
use app\common\model\ExtendCache as ExtendCacheModel;
use app\common\builder\ZBuilder;
use util\Database;
use util\Sql;
use util\File;
use util\PHPZip;
use util\Tree;
use think\Db;
use think\facade\Env;

/**
 * 模块管理控制器
 * @package app\admin\controller
 */
class Module extends Admin
{
    /**
     * 模块首页
     * @param string $type 显示类型
     * @return mixed
     * @author 仇仇天
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            // 搜索关键词
            $keyword = '';

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])) {
                $keyword = $data['searchKeyword'];
            }

            $ModuleModel = new ModuleModel();
            $result      = $ModuleModel->getAll($keyword);
            $data_list   = [];
            foreach ($result['modules'] as $value) {
                $data_list[] = $value;
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('模块列表');

        // 设置搜索框
        $view->setSearch([
            ['title' => '标题', 'field' => 'title', 'condition' => 'like', 'default' => true],
            ['title' => '名称', 'field' => 'name', 'condition' => 'like', 'default' => false]
        ]);

        // 设置分组
        $view->setGroup([
            ['title' => '本地', 'field' => 'local', 'url' => url('index'), 'default' => true],
            ['title' => '线上', 'field' => 'online', 'url' => url('index'), 'default' => false]
        ]);

        $hide = <<<javascript
                if(row.system_module == 1){
                    return '<span class="kt-badge kt-badge--inline kt-badge--danger">不可操作</span>';
                }
                if(row.status == -2){
                    return '<span class="kt-badge kt-badge--inline kt-badge--danger">不可操作，模块信息缺失</span>';
                }
javascript;

        $peration_hide = <<<javascript
                $.each(perationArr,function(i,v){
                    if(v.indexOf('hide_install') > -1){
                        if(row.system_module == 1 || row.status !== '-1'){   
                            delete perationArr[i]
                        }
                    }
                    if(v.indexOf('hide_export') > -1){
                        if(row.system_module == 1 || row.status == '-1'){   
                            delete perationArr[i]
                        }
                    }  
                    if(v.indexOf('hide_uninstall') > -1){
                        if(row.system_module == 1 || row.status == '-1'){   
                            delete perationArr[i]
                        }
                    }                           
                });   
javascript;

        // 设置列
        $view->setColumn([
            [
                'field' => 'name',
                'title' => '模块标识',
                'align' => 'center'
            ],
            [
                'field' => 'title',
                'title' => '模块名称',
                'align' => 'center'
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
                'field'         => 'peration',
                'title'         => '操作',
                'align'         => 'center',
                'type'          => 'btn',
                'btn'           => [
                    [
                        'field'      => 'c',
                        'text'       => '安装',
                        'ico'        => 'fa flaticon2-download',
                        'class'      => 'btn btn-info btn-xs hide_install',
                        'url'        => url('install'),
                        'query_data' => '{"field":["name"],"extentd_field":{"confirm":"1"}}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '导出',
                        'ico'        => 'fa flaticon-reply',
                        'class'      => 'btn btn-primary btn-xs hide_export',
                        'url'        => url('export'),
                        'query_data' => '{"field":["name"]}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '卸载',
                        'ico'        => 'fa fa-trash',
                        'class'      => 'btn btn-danger btn-xs hide_uninstall',
                        'url'        => url('uninstall'),
                        'query_data' => '{"field":["name"],"extentd_field":{"confirm":"1"}}',
                        'query_jump' => 'form',
                        'query_type' => 'get'
                    ],
                ],
                'hide'          => $hide,
                'peration_hide' => $peration_hide
            ]
        ]);

        return $view->fetch();
    }

    /**
     * 安装模块
     * @param string $name 模块标识
     * @param int $confirm 是否确认
     * @author 仇仇天
     */
    public function install($name = '', $confirm = 0)
    {
        if ($name == '') $this->error('模块不存在！');

        if ($name == 'admin') $this->error('禁止操作系统核心模块！');

        // 安装界面
        if ($confirm == 1) {

            // 获取配置信息
            $module_config_info = [];
            if (is_file(config('app_path') . $name . '/config.php')) {
                $module_config_info = include config('app_path') . $name . '/config.php';
            } else {
                $this->error('模块配置不存在！');
            }

            // 检查模块依赖
            $needModule = '<div class="kt-font-brand kt-font-bold">无需依赖其他模块</div>';
            if (isset($module_config_info['info']['need_module']) && !empty($module_config_info['info']['need_module'])) {
                $needModuleInfo = $this->checkDependence('module', $module_config_info['info']['need_module']);
                $needModule     = <<<html
                            <table class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>模块</th>
                                                        <th>唯一标识</th>
                                                        <th style="width: 100px;">当前版本</th>
                                                        <th style="width: 100px;">所需版本</th>
                                                        <th class="text-center" style="width: 100px;">检查结果</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
html;
                foreach ($needModuleInfo as $needModuleValue) {
                    $needModule .= <<<html
                    <tr>
                        <td>{$needModuleValue['module']}</td>
                        <td><a href="{$needModuleValue['identifier']}" target="_blank" data-toggle="tooltip">{$needModuleValue['identifier']}</a></td>
                        <td>{$needModuleValue['version']}</td>
                        <td>{$needModuleValue['version_need']}</td>
                        <td class="text-center">
                            {$needModuleValue['result']}
                        </td>
                    </tr>
html;

                }
                $needModule .= <<<html
            </tbody>
        </table>
html;
            }

            // 检查插件依赖
            $needPlugin = '<div class="kt-font-brand kt-font-bold">无需依赖其他插件</div>';
            if (isset($module_config_info['info']['need_plugin']) && !empty($module_config_info['info']['need_plugin'])) {
                $needPluginInfo = $this->checkDependence('plugin', $module_config_info['info']['need_plugin']);
                $needPlugin     = <<<html
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>插件</th>
                                    <th>唯一标识</th>
                                    <th style="width: 100px;">当前版本</th>
                                    <th style="width: 100px;">所需版本</th>
                                    <th class="text-center" style="width: 100px;">检查结果</th>
                                </tr>
                                </thead>
                                <tbody>
html;
                foreach ($needPluginInfo as $needPluginValue) {
                    $needPlugin .= <<<html
                    <tr>
                        <td>{$needPluginValue['plugin']}</td>
                        <td><a href="{$needPluginValue['identifier']}" target="_blank" data-toggle="tooltip">{$needPluginValue['identifier']}</a></td>
                        <td>{$needPluginValue['version']}</td>
                        <td>{$needPluginValue['version_need']}</td>
                        <td class="text-center">
                            {$needPluginValue['result']}
                        </td>
                    </tr>
html;

                }
                $needPlugin .= <<<html
            </tbody>
        </table>
html;
            }

            // 检查数据表
            $tableCheck = '<div class="kt-font-brand kt-font-bold">该模块不需要数据表</div>';
            if (isset($module_config_info['info']['tables']) && !empty($module_config_info['info']['tables'])) {
                $tableCheck = <<<html
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>数据表</th>
                                    <th>检查结果</th>
                                </tr>
                                </thead>
                                <tbody>
html;
                foreach ($module_config_info['info']['tables'] as $table) {
                    $tableName   = config('database.prefix') . $table;
                    $tableStatus = Db::query("SHOW TABLES LIKE '" . $tableName . "'") ? '<span class="text-danger">存在同名</span>' : '<i class="fa fa-check text-success"></i>';
                    $tableCheck  .= <<<html
                    <tr>
                        <td>{$table}</td>
                        <td>{$tableStatus}</td>
                    </tr>
html;

                }
                $tableCheck .= <<<html
            </tbody>
        </table>
html;
            }


            // 使用ZBuilder快速创建表单
            $form = ZBuilder::make('forms');

            // 设置页面标题
            $form->setPageTitle('模块管理 - 安装');

            // 设置返回地址
            $form->setReturnUrl(url('index'));

            // 设置 提交地址
            $form->setFormUrl(url('install'));

            // 设置隐藏表单数据
            $form->setFormHiddenData([['name' => 'name', 'value' => $name]]);

            // 设置表单项
            $form->addFormItems([
                [
                    'field'     => 'check_rely',
                    'name'      => 'check_rely',
                    'form_type' => 'html',
                    'value'     => $needModule,
                    'title'     => '模块依赖检查',
                ],
                [
                    'field'     => 'plugin_rely',
                    'name'      => 'plugin_rely',
                    'form_type' => 'html',
                    'value'     => $needPlugin,
                    'title'     => '插件依赖检查'
                ],
                [
                    'field'     => 'check_data',
                    'name'      => 'check_data',
                    'form_type' => 'html',
                    'value'     => $tableCheck,
                    'title'     => '数据表检查'
                ],
                [
                    'field'     => 'clear',
                    'name'      => 'clear',
                    'form_type' => 'radio',
                    'value'     => 0,
                    'title'     => '是否清除旧数据',
                    'tips'      => '选择“是”，将删除数据库中已存在的相同数据表',
                    'option'    => [
                        ['title' => '是', 'value' => 1],
                        ['title' => '否', 'value' => 0]
                    ]
                ]
            ]);

            return $form->fetch();

        } // 执行安装
        else {

            // 设置最大执行时间
            ini_set('max_execution_time', '0');

            // 内存大小
            ini_set('memory_limit', '1024M');

            // 模块配置信息
            $module_config_info = [];
            if ($name != '') {
                // 从配置文件获取
                if (is_file(config('app_path') . $name . '/config.php')) $module_config_info = include config('app_path') . $name . '/config.php';
            }

            // 执行安装文件
            $install_file = realpath(config('app_path') . $name . '/install.php');
            if (file_exists($install_file)) {
                @include($install_file);
            }

            // 启动事务
            Db::startTrans();

            // 添加菜单
            if (!empty($module_config_info['menus'])) {
                if (false === $this->addMenus($module_config_info['menus'], $name)) {
                    Db::rollback();// 回滚事务
                    $this->error('菜单添加失败,请检查清除菜单配置，请重新安装');
                }
            }

            // 添加模块配置分组信息
            if (!empty($module_config_info['config']['group'])) {
                if (false === $this->addConfigGroup($module_config_info['config']['group'], $name)) {
                    Db::rollback();// 回滚事务
                    $this->error('请检查配置分组，请重新安装');
                }
            }

            // 添加模块配置信息
            if (!empty($module_config_info['config']['info'])) {
                if (false === $this->addConfig($module_config_info['config']['info'], $name)) {
                    Db::rollback();// 回滚事务
                    $this->error('请检查配置，请重新安装');
                }
            }

            // 添加模块行为信息
            if (!empty($module_config_info['action'])) {
                if (false === $this->addAction($module_config_info['action'], $name)) {
                    Db::rollback();// 回滚事务
                    $this->error('请检查行为，请重新安装');
                }
            }

            // 添加模块缓存信息
            if (!empty($module_config_info['cache'])) {
                if (false === $this->addCache($module_config_info['cache'], $name)) {
                    Db::rollback();// 回滚事务
                    $this->error('请检查缓存配置，请重新安装');
                }
            }


            // 将模块信息写入数据库
            $ModuleModel        = new ModuleModel($module_config_info['info']);
            $allowField         = [
                // 模块名称(唯一标识符)
                'name',
                // 模块标题
                'title',
                // 模块图标
                'icon',
                // 模块描述
                'description',
                // 作者
                'author',
                // 作者主页
                'author_url',
                // 唯一版本号
                'version',
                // 模块唯一标识符
                'identifier',
                // 状态
                'status'
            ];
            $save_module_status = $ModuleModel->allowField($allowField)->save();

            if ($save_module_status) {

                Db::commit();

                // 执行安装模块sql文件
                $sql_file = realpath(config('app_path') . $name . '/sql/install.sql');
                if (file_exists($sql_file)) {
                    // 是否设置表前缀
                    if (isset($module_config_info['info']['database_prefix']) && !empty($module_config_info['info']['database_prefix'])) {
                        // 获取替换好前缀sql语句
                        $sql_statement = Sql::getSqlFromFile($sql_file, false, [$module_config_info['info']['database_prefix'] => config('database.prefix')]);
                    } else {
                        // 直接获取sql语句
                        $sql_statement = Sql::getSqlFromFile($sql_file);
                    }
                    if (!empty($sql_statement)) {
                        foreach ($sql_statement as $value) {
                            try {
                                Db::execute($value);
                            } catch (\Exception $e) {
                                $this->error('导入SQL失败，请检查install.sql的语句是否正确');
                            }
                        }
                    }
                }

                // 复制静态资源目录
                File::copy_dir(config('app_path') . $name . '/public', Env::get('root_path') . 'public/static/assets');

                // 删除静态资源目录
                File::del_dir(config('app_path') . $name . '/public');

                $this->refreshCache();

                // 记录行为
                adminActionLog('admin.module_install');

                $this->success('模块安装成功', 'index');
            } else {
                // 回滚事务
                Db::rollback();
                $this->error('安装失败！');
            }

        }

    }

    /**
     * 卸载模块
     * @param string $name 模块名
     * @param int $confirm 是否确认
     * @return mixed
     *
     * @author 仇仇天
     */
    public function uninstall($name = '', $confirm = 0)
    {
        if ($name == '') $this->error('模块不存在！');

        if ($name == 'admin') $this->error('禁止操作系统模块！');

        // 卸载界面
        if ($confirm == 1) {

            // 使用ZBuilder快速创建表单
            $form = ZBuilder::make('forms');

            // 设置页面标题
            $form->setPageTitle('模块管理 - 卸载');

            // 设置返回地址
            $form->setReturnUrl(url('index'));

            // 设置 提交地址
            $form->setFormUrl(url('uninstall'));

            // 设置隐藏表单数据
            $form->setFormHiddenData([['name' => 'name', 'value' => $name]]);

            // 设置表单项
            $form->addFormItems([
                [
                    'field'     => 'clear',
                    'name'      => 'clear',
                    'form_type' => 'radio',
                    'value'     => 0,
                    'title'     => '是否清除旧数据',
                    'tips'      => '选择“是”，将删除数据库中的数据表',
                    'option'    => [
                        ['title' => '是', 'value' => 1],
                        ['title' => '否', 'value' => 0]
                    ]
                ]
            ]);

            return $form->fetch();

        } // 执行
        else {
            // 模块配置信息
            $module_config_info = [];

            // 获取模块信息
            if (is_file(config('app_path') . $name . '/config.php')) $module_config_info = include config('app_path') . $name . '/config.php';

            if (empty($module_config_info['info'])) $this->error('模块不存在！');

            // 卸载文件
            $uninstall_file = realpath(config('app_path') . $name . '/uninstall.php');

            // 执行卸载文件脚本
            if (file_exists($uninstall_file)) @include($uninstall_file);

            // 执行卸载模块sql文件
            $sql_file = realpath(config('app_path') . $name . '/sql/uninstall.sql');

            // 是否清除表和数据
            $clear = input('clear');

            if ($clear == 1) {
                if (file_exists($sql_file)) {
                    if (isset($module_config_infop['info']['database_prefix']) && !empty($module_config_infop['info']['database_prefix'])) {
                        $sql_statement = Sql::getSqlFromFile($sql_file, false, [$module_config_infop['info']['database_prefix'] => config('database.prefix')]);
                    } else {
                        $sql_statement = Sql::getSqlFromFile($sql_file);
                    }
                    if (!empty($sql_statement)) {
                        foreach ($sql_statement as $sql) {
                            try {
                                Db::execute($sql);
                            } catch (\Exception $e) {
                                $this->error('卸载失败，请检查uninstall.sql的语句是否正确');
                            }
                        }
                    }
                }
            }

            // 删除菜单
            if (false === MenuModel::where('module', $name)->delete()) $this->error('菜单删除失败，请重新卸载');

            // 删除配置分组
            if (false === AdminConfigGroup::where('module', $name)->delete()) $this->error('删除配置分组失败，请重新卸载');

            // 删除配置
            if (false === AdminConfig::where('module', $name)->delete()) $this->error('删除配置失败，请重新卸载');

            // 删除行为规则
            if (false === AdminActionModel::where('module', $name)->delete()) $this->error('删除行为信息失败，请重新卸载');

            // 删除模块信息
            if (ModuleModel::where('name', $name)->delete()) {

                // 复制静态资源目录
                File::copy_dir(Env::get('root_path') . 'public/static/assets/' . $name, config('app_path') . $name . '/public/' . $name);

                // 删除静态资源目录
                File::del_dir(Env::get('root_path') . 'public/static/assets/' . $name);

                // 刷新缓存
                $this->refreshCache();

                // 记录行为
                adminActionLog('admin.module_uninstall');

                $this->success('模块卸载成功', 'index');
            } else {
                $this->error('模块卸载失败');
            }

        }

    }

    /**
     * 导出模块
     * @param string $name 模块名
     * @author 仇仇天
     */
    public function export($name = '')
    {
        if ($name == '') $this->error('缺少模块名');

        $export_data = input('export_data', '');

        if ($export_data == '') {
            $this->assign('page_title', '导出模块：' . $name);
            return $this->fetch();
        }

        // 模块导出目录
        $module_dir = Env::get('root_path') . 'export/module/' . $name;

        // 删除旧的导出数据
        if (is_dir($module_dir)) File::del_dir($module_dir);

        // 复制模块目录到导出目录
        File::copy_dir(config('app_path') . $name, $module_dir);

        // 模块本地配置信息
        $module_info = ModuleModel::getInfoFromFile($name);

        // 获取 配置分组数据
        $configGroup     = AdminConfigGroup::where(['module' => $name])->select();
        $configGroupInfo = '[]';
        if (!empty($configGroup)) {
            $configGroup     = to_arrays($configGroup);
            $configGroupInfo = $this->buildConfigGroup($configGroup);
        }

        // 获取 配置数据
        $config     = AdminConfig::where(['module' => $name])->select();
        $configInfo = '[]';
        if (!empty($config)) {
            $config     = to_arrays($config);
            $configInfo = $this->buildConfig($config);
        }

        // 获取 缓存数据
        $cache     = ExtendCacheModel::where(['module' => $name])->select();
        $cacheInfo = '[]';
        if (!empty($cacher)) {
            $cache     = to_arrays($cache);
            $cacheInfo = $this->buildCache($cache);
        }

        // 获取 模块行为数据
        $action     = Db::name('admin_action')->where('module', $name)->field('module,name,title,remark')->select();
        $actionInfo = '[]';
        if (!empty($action)) {
            $action     = to_arrays($action);
            $actionInfo = $this->buildAction($action);
        }

        // 获取 模块信息数据
        $info = '[]';
        if (!empty($module_info) && !empty($module_info['info'])) {
            $info = $this->buildInfo($module_info['info']);
        }

        // 获取 模块菜单数据
        $fields    = 'id,pid,title,icon,url_value,url_target,is_hide,sort,status';
        $menus     = MenuModel::getMenusByGroup($name, $fields);
        $menusInfo = '[]';
        if (!empty($menus)) {
            $menusInfo = $this->buildMenu($menus, $name);
        }

        // 写入配置文件
        $content = <<<INFO
<?php
// 模块信息
return [
    'infro' =>{$info},
    'config'=>[
        'group'=>{$configGroupInfo},
        'info' =>{$configInfo}
    ],
    'cache'=>{$cacheInfo},
    'action'=>{$actionInfo},
    'menus' =>{$menusInfo},
];
INFO;
        file_put_contents(Env::get('root_path') . 'export/module/' . $name . '/config.php', $content);

        // 导出数据库表
        if (isset($module_info['info']['tables']) && !empty($module_info['info']['tables'])) {
            if (!is_dir($module_dir . '/sql')) mkdir($module_dir . '/sql', 644, true);
            if (!Database::export($module_info['info']['tables'], $module_dir . '/sql/install.sql', config('database.prefix'), $export_data)) {
                $this->error('数据库文件创建失败，请重新导出');
            }
            if (!Database::exportUninstall($module_info['info']['tables'], $module_dir . '/sql/uninstall.sql', config('database.prefix'))) {
                $this->error('数据库文件创建失败，请重新导出');
            }
        }

        File::copy_dir(Env::get('root_path') . 'public/static/assets/' . $name, $module_dir . '/public/' . $name);   // 复制静态资源目录

        // 记录行为
        adminActionLog('admin.module_export');

        // 打包下载
        $archive = new PHPZip;
        return $archive->ZipAndDownload($module_dir, $name);
    }

    /**
     * 更新模块配置
     * @param string $name 模块名
     * @author 仇仇天
     */
    public function update($name = '')
    {
        $name == '' && $this->error('缺少模块名！');

        $Module = ModuleModel::get(['name' => $name]);

        !$Module && $this->error('模块不存在，或未安装');

        // 模块配置信息
        $module_config_info = [];
        if ($name != '') {
            // 从配置文件获取
            if (is_file(config('app_path') . $name . '/config.php')) {
                $module_config_info = include config('app_path') . $name . '/config.php';
            }
        }
        unset($module_config_info['info']['name']);

        // 检查是否有模块配置分组信息
        if (!empty($module_config_info['config']['group'])) {
            $config_group = $module_config_info['config']['group'];

            // 查询是否有的数据
            $in              = [];
            $array_keys_data = [];
            foreach ($config_group as $key_config_group => $value_config_group) {
                $in[]                                         = $value_config_group['name'];
                $array_keys_data[$value_config_group['name']] = $value_config_group;
            }
            $where_config_group = [['name', 'in', $in], ['module', '=', $name]];
            $data_config_group  = AdminConfigGroup::where($where_config_group)->column('module,name,title', 'name');
            $data_config_group  = to_arrays($data_config_group);

            // 处理表里没有的数据并加入
            $save_data_config_group = [];
            if (!empty($data_config_group) && !empty($array_keys_data)) {
                foreach ($array_keys_data as $kg => $vg) {
                    if (!array_key_exists($vg['name'], $data_config_group)) {
                        $save_data_config_group[] = $vg;
                    }
                }
            } else if (!empty($config_group)) {
                AdminConfigGroup::insertAll($config_group, false);
            }

            if (!empty($save_data_config_group)) {
                AdminConfigGroup::insertAll($save_data_config_group, false);
            }
        }

        // 检查是否有模块配置信息
        $config = realpath(Env::get('app_path') . $name . '/system.config.php');
        if (!empty($config)) {
            $config = include $config;
            if (!empty($config)) {
                // 查询是否有的数据
                $in              = [];
                $array_keys_data = [];
                foreach ($config as $key_config => $value_config) {
                    $in[]                                   = $value_config['name'];
                    $array_keys_data[$value_config['name']] = $value_config;
                    $config[$key_config]['options']         = !empty($value_config['options']) ? json_encode($value_config['options'], JSON_UNESCAPED_UNICODE) : '';
                }
                $where_config = [
                    ['name', 'in', $in],
                    ['module', '=', $name],
                ];
                $data_config  = AdminConfig::where($where_config)->column('*', 'name');
                $data_config  = to_arrays($data_config);

                // 处理表里没有的数据并加入
                $save_data_config = [];
                if (!empty($data_config) && !empty($array_keys_data)) {
                    foreach ($array_keys_data as $kg => $vg) {
                        if (!array_key_exists($vg['name'], $data_config_group)) {
                            $vg['options']      = !empty($vg['options']) ? json_encode($vg['options'], JSON_UNESCAPED_UNICODE) : '';
                            $save_data_config[] = $vg;
                        }
                    }
                } else if (!empty($config)) {
                    AdminConfig::insertAll($config, false);
                }
                if (!empty($save_data_config)) {
                    AdminConfig::insertAll($save_data_config, false);
                }

            }
        }

        // 更新模块信息
        $this->success('模块配置更新成功');
    }


    /**
     * 创建模块菜单文件
     * @param array $menus 菜单
     * @return array|bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildMenu($menus = [])
    {
        $menus = Tree::toLayer($menus);
        // 美化数组格式
        $menus = var_export($menus, true);
        $menus = preg_replace("/(\d+|'id'|'pid') =>(.*)/", '', $menus);
        $menus = preg_replace("/'child' => (.*)(\r\n|\r|\n)\s*array/", "'child' => $1array", $menus);
        $menus = str_replace(['array (', ')'], ['[', ']'], $menus);
        $menus = preg_replace("/(\s*?\r?\n\s*?)+/", "\n", $menus);
        return $menus;
    }

    /**
     * 解析模块信息(存储文件格式)
     * @param array $info 模块配置信息
     * @return array|bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildInfo($info = [])
    {
        // 美化数组格式
        $info = var_export($info, true);
        $info = preg_replace("/'(.*)' => (.*)(\r\n|\r|\n)\s*array/", "'$1' => array", $info);
        $info = preg_replace("/(\d+) => (\s*)(\r\n|\r|\n)\s*array/", "array", $info);
        $info = preg_replace("/(\d+ => )/", "", $info);
        $info = preg_replace("/array \((\r\n|\r|\n)\s*\)/", "[)", $info);
        $info = preg_replace("/array \(/", "[", $info);
        $info = preg_replace("/\)/", "]", $info);
        return $info;
    }

    /**
     * 解析配置分组(存储文件格式)
     * @param array $config_group 配置分组数组
     * @return bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildConfigGroup($config_group = [])
    {
        // 美化数组格式
        $info = var_export($config_group, true);
        $info = preg_replace("/'(.*)' => (.*)(\r\n|\r|\n)\s*array/", "'$1' => array", $info);
        $info = preg_replace("/(\d+) => (\s*)(\r\n|\r|\n)\s*array/", "array", $info);
        $info = preg_replace("/(\d+ => )/", "", $info);
        $info = preg_replace("/array \((\r\n|\r|\n)\s*\)/", "[)", $info);
        $info = preg_replace("/array \(/", "[", $info);
        $info = preg_replace("/\)/", "]", $info);
        return $info;
    }

    /**
     * 解析配置(存储文件格式)
     * @param array $config 配置数组
     * @return bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildConfig($config = [])
    {
        // 美化数组格式
        $info = var_export($config, true);
        $info = preg_replace("/'(.*)' => (.*)(\r\n|\r|\n)\s*array/", "'$1' => array", $info);
        $info = preg_replace("/(\d+) => (\s*)(\r\n|\r|\n)\s*array/", "array", $info);
        $info = preg_replace("/(\d+ => )/", "", $info);
        $info = preg_replace("/array \((\r\n|\r|\n)\s*\)/", "[)", $info);
        $info = preg_replace("/array \(/", "[", $info);
        $info = preg_replace("/\)/", "]", $info);
        return $info;
    }

    /**
     * 解析缓存(存储文件格式)
     * @param array $cache 缓存数组
     * @return bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildCache($cache = [])
    {
        // 美化数组格式
        $info = var_export($cache, true);
        $info = preg_replace("/'(.*)' => (.*)(\r\n|\r|\n)\s*array/", "'$1' => array", $info);
        $info = preg_replace("/(\d+) => (\s*)(\r\n|\r|\n)\s*array/", "array", $info);
        $info = preg_replace("/(\d+ => )/", "", $info);
        $info = preg_replace("/array \((\r\n|\r|\n)\s*\)/", "[)", $info);
        $info = preg_replace("/array \(/", "[", $info);
        $info = preg_replace("/\)/", "]", $info);
        return $info;
    }

    /**
     * 解析行为(存储文件格式)
     * @param array $action 行为数组
     * @return bool|int|mixed|string|string[]|null
     * @author 仇仇天
     */
    private function buildAction($action = [])
    {
        // 美化数组格式
        $info = var_export($action, true);
        $info = preg_replace("/'(.*)' => (.*)(\r\n|\r|\n)\s*array/", "'$1' => array", $info);
        $info = preg_replace("/(\d+) => (\s*)(\r\n|\r|\n)\s*array/", "array", $info);
        $info = preg_replace("/(\d+ => )/", "", $info);
        $info = preg_replace("/array \((\r\n|\r|\n)\s*\)/", "[)", $info);
        $info = preg_replace("/array \(/", "[", $info);
        $info = preg_replace("/\)/", "]", $info);
        return $info;
    }


    /**
     * 检查依赖
     * @param string $type 类型：module/plugin
     * @param array $data 检查数据
     * @return array
     * @author 仇仇天
     */
    private function checkDependence($type = '', $data = [])
    {
        $need = [];
        foreach ($data as $key => $value) {
            if (!isset($value[3])) {
                $value[3] = '=';
            }
            // 当前版本
            if ($type == 'module') {
                $curr_version = ModuleModel::where('identifier', $value[1])->value('version');
            } else {
                $curr_version = PluginModel::where('identifier', $value[1])->value('version');
            }

            // 比对版本
            $result     = version_compare($curr_version, $value[2], $value[3]);
            $need[$key] = [
                $type          => $value[0],
                'identifier'   => $value[1],
                'version'      => $curr_version ? $curr_version : '未安装',
                'version_need' => $value[3] . $value[2],
                'result'       => $result ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>'
            ];
        }

        return $need;
    }

    /**
     * 添加模型菜单
     * @param $menus 菜单
     * @param $module 模型名称
     * @param int $pid 父级ID
     * @return bool
     * @author 仇仇天
     */
    private function addMenus($menus, $module, $pid = 0)
    {
        foreach ($menus as $menu) {

            // 定义存储结构
            $data = [
                // 父id
                'pid'        => $pid,
                // 所属模块
                'module'     => $module,
                // 标题
                'title'      => $menu['title'],
                // 图标
                'icon'       => isset($menu['icon']) ? $menu['icon'] : 'fa fa-fw fa-puzzle-piece',
                // 标识
                'mark'       => isset($menu['mark']) ? $menu['mark'] : md5(microtime(true)),
                // 节点连接地址
                'url_value'  => isset($menu['url_value']) ? $menu['url_value'] : '',
                // 节点连接类型
                'url_target' => isset($menu['url_target']) ? $menu['url_target'] : '_self',
                // 是否隐藏节点
                'is_hide'    => isset($menu['is_hide']) ? $menu['is_hide'] : 0
            ];

            // 存储 菜单
            $result = MenuModel::create($data);

            if (!$result) return false;

            // 是否有子节点
            if (isset($menu['child'])) $this->addMenus($menu['child'], $module, $result['id']);
        }
        return true;
    }

    /**
     * 添加配置分组
     * @param $config_group 配置分组数据
     * @param $module       所属模块
     * @return bool
     * @author 仇仇天
     */
    private function addConfigGroup($config_group, $module)
    {

        $in              = [];
        $array_keys_data = [];
        foreach ($config_group as $key => $value) {
            $config_group[$key]['module']    = $module;
            $in[]                            = $value['name'];
            $array_keys_data[$value['name']] = $value;
        }
        $where_config_group = [['name', 'in', $in], ['module', '=', $module]];
        $data_config_group  = AdminConfigGroup::where($where_config_group)->column('module,name,title', 'name');
        $data_config_group  = to_arrays($data_config_group);

        // 处理表里没有的数据并加入
        $save_data_config_group = [];
        if (!empty($data_config_group) && !empty($array_keys_data)) {
            foreach ($array_keys_data as $kg => $vg) {
                if (!array_key_exists($vg['name'], $data_config_group)) {
                    $save_data_config_group[] = $vg;
                }
            }
        } else if (!empty($config_group)) {
            if (!AdminConfigGroup::insertAll($config_group, false)) {
                return false;
            }
        }
        if (!empty($save_data_config_group)) {
            if (!AdminConfigGroup::insertAll($save_data_config_group, false)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 添加配置
     * @param $config  配置数据
     * @param $module  所属模块
     * @author 仇仇天
     */
    private function addConfig($config, $module)
    {
        // 查询是否有的数据
        $in              = [];
        $array_keys_data = [];
        foreach ($config as $key_config => $value_config) {
            $config[$key_config]['module']          = $module;
            $in[]                                   = $value_config['name'];
            $array_keys_data[$value_config['name']] = $value_config;
            $config[$key_config]['options']         = !empty($value_config['options']) ? json_encode($value_config['options'], JSON_UNESCAPED_UNICODE) : '';
        }
        $where_config = [['name', 'in', $in], ['module', '=', $module]];
        $data_config  = AdminConfig::where($where_config)->column('*', 'name');
        $data_config  = to_arrays($data_config);

        // 处理表里没有的数据并加入
        $save_data_config = [];
        if (!empty($data_config) && !empty($array_keys_data)) {
            foreach ($array_keys_data as $kg => $vg) {
                if (!array_key_exists($vg['name'], $data_config)) {
                    $vg['options']      = !empty($vg['options']) ? json_encode($vg['options'], JSON_UNESCAPED_UNICODE) : '';
                    $save_data_config[] = $vg;
                }
            }
        } else if (!empty($config)) {
            if (!AdminConfig::insertAll($config, false)) {
                return false;
            }
        }

        if (!empty($save_data_config)) {
            if (!AdminConfig::insertAll($save_data_config, false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 添加缓存
     * @param $cache  缓存数据
     * @param $module 所属模块
     * @author 仇仇天
     */
    private function addCache($cache, $module)
    {
        // 查询是否有的数据
        $in              = [];
        $array_keys_data = [];
        foreach ($cache as $key_config => $value_config) {
            $cache[$key_config]['module']                 = $module;
            $in[]                                         = $value_config['field_name'];
            $array_keys_data[$value_config['field_name']] = $value_config;
            $cache[$key_config]['project_config']         = !empty($value_config['project_config']) ? json_encode($value_config['project_config'], JSON_UNESCAPED_UNICODE) : '';
        }
        $where_config = [['field_name', 'in', $in], ['module', '=', $module]];
        $data_config  = ExtendCacheModel::where($where_config)->column('*', 'field_name');
        $data_config  = to_arrays($data_config);

        // 处理表里没有的数据并加入
        $save_data_config = [];
        if (!empty($data_config) && !empty($array_keys_data)) {
            foreach ($array_keys_data as $kg => $vg) {
                if (!array_key_exists($vg['field_name'], $data_config)) {
                    $vg['project_config'] = !empty($vg['project_config']) ? json_encode($vg['project_config'], JSON_UNESCAPED_UNICODE) : '';
                    $save_data_config[]   = $vg;
                }
            }
        } else if (!empty($cache)) {
            if (!AdminConfig::insertAll($cache, false)) {
                return false;
            }
        }

        if (!empty($save_data_config)) {
            if (!AdminConfig::insertAll($save_data_config, false)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 行为添加
     * @param $action 行为数据
     * @param $module 所属模块
     * @author 仇仇天
     */
    private function addAction($action, $module)
    {
        foreach ($action as $key => $value) {
            $data   = [
                'module'      => $module,
                'name'        => $value['name'],
                'title'       => $value['title'],
                'remark'      => isset($value['remark']) ? $value['remark'] : '',
                'create_time' => time(),
                'update_time' => time()
            ];
            $result = AdminActionModel::create($data);
            if (!$result) return false;
        }
        return true;
    }


    /**
     * 清除缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        dkcache('system_config');
        dkcache('system_config_info');
        dkcache('system_module');
        dkcache('action_config');
        dkcache('admin_menu');
    }
}
