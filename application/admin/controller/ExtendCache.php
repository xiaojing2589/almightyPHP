<?php

namespace app\admin\controller;

use app\common\controller\Admin;

use app\common\model\ExtendCache as ExtendCacheModel;
use app\common\builder\ZBuilder;
use think\facade\Cache;
use app\common\model\AdminModule as AdminModuleModel;

/**
 * 缓存管理控制器
 * @package app\admin\controller
 */
class ExtendCache extends Admin
{
    /**
     * 配置首页
     * @author 仇仇天
     * @param string $module_group 分组
     * @return mixed
     */
    public function index($module_group = 'admin')
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $data = $this->request->request();

            // 筛选参数设置
            $map = [];

            // 所属模块筛选
            $map[] = ['module', '=', $module_group];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])){

                if ($data['searchCondition'] == 'like'){
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                }else{
                    $where[] = [$data['searchField'], $data['searchCondition'], "%" . $data['searchKeyword'] . "%"];
                }
            }

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = ExtendCacheModel::where($map)->order('id ASC,field_name DESC')->paginate($list_rows);

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('缓存列表');

        // 设置搜索框
        $view->setSearch([
            ['title' => '标题', 'field' => 'name','condition'=>'like', 'default' => true],
            ['title' => '标识', 'field' => 'field_name','condition'=>'like', 'default' => false]
        ]);

        // 标签分组信息
        $list_group = AdminModuleModel::getModuleDataInfo();
        $tab_list   = [];
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title']   = $value['title'];
            $tab_list[$key]['value']   = $value['name'];
            $tab_list[$key]['url']     = url('index', ['module_group' => $value['name']]);
            $tab_list[$key]['default'] = ($module_group == $value['name']) ? true : false;
        }
        $view->setGroup($tab_list);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('extend_cache/add', ['module_group' => $module_group])]);

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('extend_cache/del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置行内编辑地址
        $view->editableUrl(url('extend_cache/edit'));

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field'    => 'name',
                'title'    => '标题',
                'align'    => 'center',
                'editable' => [
                    'type'     => 'text',
                    'editable' => [
                        'type'      => 'text',
                        'minlength' => 1,
                        'maxlength' => 50
                    ]
                ]
            ],
            [
                'field'    => 'field_name',
                'title'    => '标识',
                'align'    => 'center',
                'editable' => [
                    'type'      => 'text',
                    'minlength' => 1,
                    'maxlength' => 120
                ]
            ],
            [
                'field' => 'describes',
                'title' => '描述',
                'align' => 'center',
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('extend_cache/del'),
                        'query_data' => '{"field":["id"],"extentd_field":{"action":"delete"}}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('extend_cache/edit'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ]
            ]
        ]);

        // 自定义传参
        $view->setQueryParams(['module_group' => $module_group]);

        // 渲染模板
        return $view->fetch();
    }

    /**
     * 新增配置项
     * @author 仇仇天
     * @param string $module_group 分组
     * @return mixed
     */
    public function add($module_group = '')
    {
        if (empty($module_group)) $this->error('参数错误');

        // 保存数据
        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'ExtendCache');
            if (true !== $result) $this->error($result);

            // 解析配置参数
            $options = $this->optisonsConfig($data['type'], $data);

            // 入库数据加工
            $save_data                   = [];
            $save_data['field_name']     = $data['field_name'];
            $save_data['name']           = $data['name'];
            $save_data['module']         = $data['module_group'];
            $save_data['project_config'] = $options;
            $save_data['describes']      = $data['describes'];
            $save_data['type']           = $data['type'];
            if ($config = ExtendCacheModel::create($save_data)) {
                Cache::connect(config('system.extend_cache'))->pull('extend_cache'); // 删除缓存
                adminActionLog('admin.extend_cache_add');
                $this->success('新增成功', url('extend_cache/index', ['module_group' => $data['module_group']]));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('缓存管理 - 新增');

        // 设置返回地址
        $form->setReturnUrl(url('index', ['module_group' => $module_group]));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'module_group', 'value' => $module_group]]);

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // js额外代码
        $js = <<<javascript
            function storageTypeShow(v){
                var mark = ''; 
                if(typeof v == 'string'){
                    mark = v;
                }else if(typeof v == 'object'){
                  var data = v.params.data;   
                  mark = data.id;
                }                
                if(mark == 'file'){
                    $(".storage_type_server").hide();         
                    $(".storage_type_server").find(":input").attr("disabled", false);                                    
                    $(".storage_type_file").show();       
                    $(".storage_type_file").find(":input").attr("disabled", false);                               
                }else if(mark == 'lite'){
                    $(".storage_type_server").hide();         
                    $(".storage_type_server").find(":input").attr("disabled", false);                                    
                    $(".storage_type_file").show();       
                    $(".storage_type_file").find(":input").attr("disabled", false);               
                }else if(mark == 'redis'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }else if(mark == 'memcache'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }else if(mark == 'memcached'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }       
            }
            $(function(){
                storageTypeShow($(".storage_type").val());
            });            
javascript;
        $form->extraJsCode($js);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '名称'
            ],
            [
                'field'     => 'field_name',
                'name'      => 'field_name',
                'form_type' => 'text',
                'title'     => '字段标识',
                'tips'      => "由英文字母和下划线组成，如： <code>web_site_title</code>，调用方法：<code>rcache('web_site_title')</code>"
            ],
            [
                'field'     => 'expire',
                'name'      => 'expire',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '缓存有效期',
                'tips'      => '默认为0 表示永久缓存，单位秒'
            ],
            [
                'field'     => 'prefix',
                'name'      => 'prefix',
                'form_type' => 'text',
                'title'     => '缓存前缀',
                'tips'      => '默认为空'
            ],
            [
                'field'       => 'type',
                'name'        => 'type',
                'form_type'   => 'select2',
                'title'       => '存储类型',
                'class'       => 'storage_type',
                'width'       => '100%',
                'allow_clear' => true,
                'on_select'   => 'storageTypeShow',
                'option'      => [
                    ['title' => '文件', 'value' => 'file'],
                    ['title' => 'Lite', 'value' => 'lite'],
                    ['title' => 'Redis', 'value' => 'redis'],
                    ['title' => 'Memcache', 'value' => 'memcache'],
                    ['title' => 'Memcached', 'value' => 'memcached']
                ]
            ],
            [
                'field'     => 'describes',
                'name'      => 'describes',
                'form_type' => 'textarea',
                'title'     => '描述'
            ],
            [
                'field'            => 'path',
                'name'             => 'path',
                'form_type'        => 'text',
                'title'            => '指定缓存目录',
                'tips'             => '格式：<code>../runtime/cache/</code>',
                'form_group_class' => 'storage_type_file',
                'form_group_hide'  => true
            ],
            [
                'field'            => 'host',
                'name'             => 'host',
                'form_type'        => 'text',
                'title'            => '服务器地址',
                'tips'             => '默认<code>127.0.0.1</code>',
                'form_group_class' => 'storage_type_server',
                'form_group_hide'  => true
            ],
            [
                'field'            => 'port',
                'name'             => 'port',
                'form_type'        => 'number',
                'title'            => '端口号',
                'form_group_class' => 'storage_type_server',
                'form_group_hide'  => true
            ],
        ]);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     * @param int $id 缓存id
     * @return mixed
     */
    public function edit($id = 0)
    {
        if ($id === 0) $this->error('参数错误');

        if ($this->request->isPost()) {

            // 表单数据
            $data = $this->request->post();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                // 验证
                $result = $this->validate($data, 'ExtendCache.' . $data['extend_field']);
                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            // 普通修改
            else {

                // 验证
                $result = $this->validate($data, 'ExtendCache');

                // 验证提示报错
                if (true !== $result) $this->error($result);

                // 解析配置参数
                $options = $this->optisonsConfig($data['type'], $data);

                $save_data['field_name']     = $data['field_name'];
                $save_data['name']           = $data['name'];
                $save_data['project_config'] = $options;
                $save_data['describes']      = $data['describes'];
                $save_data['type']           = $data['type'];
            }

            // 原配置内容
            $config_info = ExtendCacheModel::where('id', $id)->find();

            if ($config = ExtendCacheModel::update($save_data, ['id' => $id])) {
                Cache::connect(config('system.extend_cache'))->pull('extend_cache'); // 删除缓存
                adminActionLog('admin.extend_cache_edit');
                $this->success('编辑成功', url('extend_cache/index', ['module_group' => $config_info['module']]));
            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = ExtendCacheModel::get($id);
        $info = to_arrays($info);

        // 获取配置信息
        $config_info = json_decode($info['project_config'], true);

        // 合并配置信息
        $info = array_merge($config_info,$info);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('缓存管理 - 编辑');

        // 设置页面需要加载的js
        $form->setJsFiles(['validate_js']);

        // 设置返回地址
        $form->setReturnUrl(url('extend_cache/index', ['module_group' => $info['module']]));

        // 设置 提交地址
        $form->setFormUrl(url('extend_cache/edit'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // js额外代码
        $js = <<<javascript
            function storageTypeShow(v){
                var mark = ''; 
                if(typeof v == 'string'){
                    mark = v;
                }else if(typeof v == 'object'){
                  var data = v.params.data;   
                  mark = data.id;
                }                
                if(mark == 'file'){
                    $(".storage_type_server").hide();         
                    $(".storage_type_server").find(":input").attr("disabled", false);                                    
                    $(".storage_type_file").show();       
                    $(".storage_type_file").find(":input").attr("disabled", false);                               
                }else if(mark == 'lite'){
                    $(".storage_type_server").hide();         
                    $(".storage_type_server").find(":input").attr("disabled", false);                                    
                    $(".storage_type_file").show();       
                    $(".storage_type_file").find(":input").attr("disabled", false);               
                }else if(mark == 'redis'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }else if(mark == 'memcache'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }else if(mark == 'memcached'){
                    $(".storage_type_server").show();
                    $(".storage_type_server").find(":input").attr("disabled", false);                    
                    $(".storage_type_file").hide();
                    $(".storage_type_file").find(":input").attr("disabled", true);       
                }       
            }
            $(function(){
                storageTypeShow($(".storage_type").val());
            });            
javascript;
        $form->extraJsCode($js);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '名称'
            ],
            [
                'field'     => 'field_name',
                'name'      => 'field_name',
                'form_type' => 'text',
                'title'     => '字段标识',
                'tips'      => "由英文字母和下划线组成，如： <code>web_site_title</code>，调用方法：<code>rcache('web_site_title')</code>"
            ],
            [
                'field'     => 'expire',
                'name'      => 'expire',
                'form_type' => 'number',
                'value'     => 0,
                'title'     => '缓存有效期',
                'tips'      => '默认为0 表示永久缓存，单位秒'
            ],
            [
                'field'     => 'prefix',
                'name'      => 'prefix',
                'form_type' => 'text',
                'title'     => '缓存前缀',
                'tips'      => '默认为空'
            ],
            [
                'field'       => 'type',
                'name'        => 'type',
                'form_type'   => 'select2',
                'title'       => '存储类型',
                'class'       => 'storage_type',
                'width'       => '100%',
                'allow_clear' => true,
                'on_select'   => 'storageTypeShow',
                'option'      => [
                    ['title' => '文件', 'value' => 'file'],
                    ['title' => 'Lite', 'value' => 'lite'],
                    ['title' => 'Redis', 'value' => 'redis'],
                    ['title' => 'Memcache', 'value' => 'memcache'],
                    ['title' => 'Memcached', 'value' => 'memcached']
                ]
            ],
            [
                'field'     => 'describes',
                'name'      => 'describes',
                'form_type' => 'textarea',
                'title'     => '描述'
            ],
            [
                'field'            => 'path',
                'name'             => 'path',
                'form_type'        => 'text',
                'title'            => '指定缓存目录',
                'tips'             => '格式：<code>../runtime/cache/</code>',
                'form_group_class' => 'storage_type_file',
                'form_group_hide'  => true
            ],
            [
                'field'            => 'host',
                'name'             => 'host',
                'form_type'        => 'text',
                'title'            => '服务器地址',
                'tips'             => '默认<code>127.0.0.1</code>',
                'form_group_class' => 'storage_type_server',
                'form_group_hide'  => true
            ],
            [
                'field'            => 'port',
                'name'             => 'port',
                'form_type'        => 'number',
                'title'            => '端口号',
                'form_group_class' => 'storage_type_server',
                'form_group_hide'  => true
            ],
        ]);

        // 设置表单数据
        $form->setFormdata($info);

        // 渲染页面
        return $form->fetch();
    }

    /**
     *  删除/批量
     * @author 仇仇天
     */
    public function del()
    {
        $data = $this->request->post();

        $where = [];

        // 批量删除
        if (!empty($data['action']) && $data['action'] == 'delete_batch') {
            if (empty($data['batch_data'])) $this->error('请选择需要删除的数据');
            $ids = [];
            foreach ($data['batch_data'] as $value) {
                $ids[] = $value['id'];
            }
            $where = [['id', 'in', $ids]];
        }
        // 删除
        else {
            if (empty($data['id'])) $this->error('参数错误');
            $where = ['id' => $data['id']];
        }

        if (false !== ExtendCacheModel::where($where)->delete()) {
            // 记录日志
            adminActionLog('admin.extend_cache_del');
            // 删除缓存
            Cache::connect(config('system.extend_cache'))->pull('extend_cache');
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 解析
     * @author 仇仇天
     * @param $type String 类型
     * @param $config_param Array 配置参数
     */
    private function optisonsConfig($type, $value = [])
    {
        $res           = [];
        $res['type']   = $type;
        $res['expire'] = $value['expire'] ? $value['expire'] : 0;
        $res['prefix'] = $value['prefix'] ? $value['prefix'] : '';
        switch ($type) {
            case 'file':
                $res['path'] = $value['path'] ? $value['path'] : '../runtime/cache/';
                break;
            case 'lite':
                $res['path'] = $value['path'] ? $value['path'] : '../runtime/cache/';
                break;
            case 'xcache':
                break;
            case 'redis':
                $res['host'] = $value['host'] ? $value['host'] : '127.0.0.1';
                $res['port'] = $value['port'] ? $value['port'] : 6379;
                break;
            case 'memcache':
                $res['host'] = $value['host'] ? $value['host'] : '127.0.0.1';
                $res['port'] = $value['port'] ? $value['port'] : 11211;
                break;
            case 'memcached':
                $res['host'] = $value['host'] ? $value['host'] : '127.0.0.1';
                $res['port'] = $value['port'] ? $value['port'] : 11211;
                break;
            case 'wincache':
                break;
            case 'sqlite':
                break;
        }
        return json_encode($res, JSON_UNESCAPED_SLASHES);
    }
}