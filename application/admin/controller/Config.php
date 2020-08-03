<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\admin\model\AdminConfig as AdminConfigModel;
use app\admin\model\AdminModule as AdminModuleModel;
use app\common\builder\ZBuilder;


/**
 * 系统配置控制器
 * Class Config
 * @package app\admin\controller
 */
class Config extends Admin
{
    /**
     * 配置首页
     * @author 仇仇天
     * @param string $module_group 模块分组
     * @return mixed
     */
    public function index($module_group = 'admin')
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        // 加载表格数据
        if ($this->request->isAjax()) {

            // 传输数据
            $data = input();

            // 筛选参数设置
            $where = [];

            // 所属模块筛选
            $where[] = ['module', '=', $module_group];

            // 快捷筛选 关键词
            if ((!empty($data['searchKeyword']) && $data['searchKeyword'] !== '') && !empty($data['searchField']) && !empty($data['searchCondition'])){
                if ($data['searchCondition'] == 'like'){
                    $where[] = [$data['searchField'], 'like', "%" . $data['searchKeyword'] . "%"];
                }else{
                    $where[] = [$data['searchField'], $data['searchCondition'], "%" . $data['searchKeyword'] . "%"];
                }
            }

            // 排序字段
            $orderSort = input('sort/s', '', 'trim');

            // 排序方式
            $orderMode = input('order/s', '', 'trim');

            // 拼接排序语句
            $order = $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'sort ASC,id ASC' : $order;

            // 数据列表
            $data_list = AdminConfigModel::where($where)->order($order)->paginate($data['list_rows']);

            // 配置类型数据
            $form_item_type = json_decode(config('form_item_type'), true);

            // 重新格式化数据
            foreach ($data_list as $key => $value) {
                foreach ($form_item_type as $keys => $values) {
                    if ($value['type'] == $values['value']) {
                        $data_list[$key]['type'] = $values['title'];
                    }
                }
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('配置列表');

        // 设置搜索框
        $view->setSearch([
            ['title' => '标题', 'field' => 'title','condition'=>'like', 'default' => true],
            ['title' => '标识', 'field' => 'name','condition'=>'like', 'default' => false]
        ]);

        // 标签分组信息
        $moduleData = AdminModuleModel::getOpenModuleAll();
        $moduleTagData   = [];
        foreach ($moduleData as $key => $value) {
            $moduleTagData[$key]['title']   = $value['title'];
            $moduleTagData[$key]['field']   = $value['name'];
            $moduleTagData[$key]['ico']     = $value['icon'];
            $moduleTagData[$key]['url']     = url('index', ['module_group' => $value['name']]);
            $moduleTagData[$key]['default'] = $module_group == $value['name'] ? true : false;
        }

        // 设置分组
        $view->setGroup($moduleTagData);

        // 设置头部按钮 新增
        $view->addTopButton('add', ['url' => url('add', ['module_group' => $module_group])]);

        // 设置头部按钮 删除
        $view->addTopButton('delete', ['url' => url('del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮 启用
        $view->addTopButton('enable', ['url' => url('editstatus'), 'query_data' => '{"status":1}']);

        //设置头部按钮  禁用
        $view->addTopButton('disable', ['url' => url('editstatus'), 'query_data' => '{"status":0}']);

        // 设置行内编辑地址
        $view->editableUrl(url('config/edit'));

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true,
                'width'    => 50
            ],
            [
                'field'    => 'id',
                'title'    => 'ID',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50
            ],
            [
                'field'    => 'name',
                'title'    => '标识',
                'align'    => 'center',
                'editable' => [
                    'type' => 'text',
                ]
            ],
            [
                'field'    => 'title',
                'title'    => '标题',
                'align'    => 'center',
                'editable' => [
                    'type'      => 'text',
                    'minlength' => 1,
                    'maxlength' => 64
                ]
            ],
            [
                'field' => 'type',
                'title' => '类型',
                'align' => 'center',
            ],
            [
                'field'    => 'status',
                'title'    => '状态',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 90,
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '启用', 'on_value' => 1, 'off_text' => '禁用', 'off_value' => 0]
                ]
            ],
            [
                'field'    => 'is_hide',
                'title'    => '是否隐藏',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 90,
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '是', 'on_value' => 1, 'off_text' => '否', 'off_value' => 0]
                ]
            ],
            [
                'field'    => 'sort',
                'title'    => '排序',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 60,
                'editable' => [
                    'type' => 'number'
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'width' => 150,
                'btn'   => [
                    [
                        'field'      => 'd',
                        'confirm'    => '确认删除',
                        'query_jump' => 'ajax',
                        'url'        => url('del'),
                        'query_data' => '{"field":["id"]}',
                        'query_type' => 'post'
                    ],
                    [
                        'field'      => 'u',
                        'url'        => url('edit'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ]
            ]
        ]);

        // 自定义传参
        $view->setQueryParams(['module_group' => $module_group]);

        // 设置页面
        return $view->fetch();
    }

    /**
     * 新增
     * @author 仇仇天
     * @param string $module_group 模块分组
     * @return mixed
     */
    public function add($module_group = '')
    {
        if (empty($module_group)) $this->error('参数错误');

        // 传递值
        $vars = [];

        // 操作标识
        $vars['actions'] = 'add';

        // 保存数据
        if ($this->request->isPost()) {

            // 表单数据
            $data = input();

            // 验证
            $result = $this->validate($data, 'Config.add');
            if (true !== $result) $this->error($result);

            // 解析配置参数
            $options = $this->type_optisons($data['type'], $data);

            // 入库数据加工
            $save_data            = [];
            $save_data['module']  = $data['module_group'];
            $save_data['type']    = $data['type'];
            $save_data['title']   = $data['title'];
            $save_data['name']    = $data['name'];
            $save_data['tips']    = empty($data['tips']) ? "代码调用方式：<code>config('".$data['name']."')</code>" : $data['tips']."，代码调用方式：<code>config('".$data['name']."')</code>";
            $save_data['value']   = $data['value'];
            $save_data['options'] = $options;

            if ($config = AdminConfigModel::create($save_data)) {

                // 删除缓存
                AdminConfigModel::delCache();

                $this->success('新增成功', url('index', ['module_group' => $data['module_group']]));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('配置管理 - 新增');

        // 设置页面需要加载的js
        $form->setJsFiles(['jsrender_js', 'colorpicker_js', 'jsoneditor_js','tags_js']);

        // 设置页面需要加载的css
        $form->setCaaFiles(['colorpicker_css', 'jsoneditor_css','tags_css']);

        // 设置返回地址
        $form->setReturnUrl(url('config/index', ['module_group' => $module_group]));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'module_group', 'value' => $module_group]]);

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 分列
        $form->listNumber(2);

        // 设置配置
        $this->setConfig($form);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'title',
                'name'      => 'title',
                'form_type' => 'text',
                'title'     => '配置标题',
                'tips'      => '一般由中文组成，仅用于显示'
            ],
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '标识',
                'tips'      => "由英文字母和下划线组成，如 <code>web_site_title</code>，调用方法：<code>config('web_site_title')</code>"
            ],
            [
                'field'     => 'tips',
                'name'      => 'tips',
                'form_type' => 'textarea',
                'title'     => '配置说明',
                'tips'      => '该配置的具体说明'
            ],
            [
                'field'     => 'sort',
                'name'      => 'sort',
                'form_type' => 'number',
                'value'     => 100,
                'title'     => '排序',
                'tips'      => '数字越低排序越靠前'
            ],
            [
                'field'     => 'type',
                'name'      => 'type',
                'form_type' => 'select',
                'title'     => '配置类型',
                'option'    =>  json_decode(config('form_item_type'), true)
            ],
            [
                'field'            => 'value',
                'name'             => 'value',
                'form_group_class' => 'config_value',
                'form_type'        => 'textarea',
                'title'            => '默认值',
                'tips'             => '该配置的具体内容'
            ]
        ]);

        // 设置页面
        return $form->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     * @param int $id 配置id
     * @return mixed
     */
    public function edit($id = 0)
    {
        if ($id === 0) $this->error('参数错误');

        // 传递值
        $vars = [];

        // 操作标识
        $vars['actions'] = 'edit';

        // 保存数据
        if ($this->request->isPost()) {

            // 表单数据
            $data = input();

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {
                $save_data[$data['extend_field']] = $data[$data['extend_field']];
                // 验证
                $result = $this->validate($data, 'Config.' . $data['extend_field']);
                // 验证提示报错
                if (true !== $result) $this->error($result);
            }

            // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'Config.edit');

                // 验证提示报错
                if (true !== $result) $this->error($result);

                // 解析配置参数
                $options = $this->type_optisons($data['type'], $data);

                // 解析默认值
                $value = $this->type_value($data['type'], $data);

                $save_data['type']    = $data['type'];
                $save_data['title']   = $data['title'];
                $save_data['name']    = $data['name'];
                $save_data['tips']    = $data['tips'];
                $save_data['value']   = $value;
                $save_data['options'] = $options;
            }

            // 原配置内容
            $config_info = AdminConfigModel::where('id', $id)->find();

            if ($config = AdminConfigModel::update($save_data, ['id' => $id])) {

                // 删除缓存
                AdminConfigModel::delCache();

                $this->success('编辑成功', url('index', ['module_group' => $config_info['module']]));

            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = AdminConfigModel::get($id);

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('配置管理 - 编辑');

        // 设置页面需要加载的js
        $form->setJsFiles(['jsrender_js', 'colorpicker_js', 'jsoneditor_js','tags_js']);

        // 设置页面需要加载的css
        $form->setCaaFiles(['colorpicker_css', 'jsoneditor_css','tags_css']);

        // 设置返回地址
        $form->setReturnUrl(url('index', ['module_group' => $info['module']]));

        // 设置 提交地址
        $form->setFormUrl(url('edit'));

        // 分列
        $form->listNumber(2);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // 设置配置
        $info['actions'] = 'edit';
        $this->setConfig($form,$info);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'title',
                'name'      => 'title',
                'form_type' => 'text',
                'title'     => '配置标题',
                'tips'      => '一般由中文组成，仅用于显示'
            ],
            [
                'field'     => 'name',
                'name'      => 'name',
                'form_type' => 'text',
                'title'     => '标识',
                'tips'      => "由英文字母和下划线组成，如 <code>web_site_title</code>，调用方法：<code>config('web_site_title')</code>"
            ],
            [
                'field'     => 'tips',
                'name'      => 'tips',
                'form_type' => 'textarea',
                'title'     => '配置说明',
                'tips'      => '该配置的具体说明'
            ],
            [
                'field'     => 'sort',
                'name'      => 'sort',
                'form_type' => 'number',
                'title'     => '排序',
                'tips'      => '数字越低排序越靠前'
            ],
            [
                'field'     => 'type',
                'name'      => 'type',
                'form_type' => 'select',
                'title'     => '配置类型',
                'option'    => json_decode(config('form_item_type'), true)
            ],
            [
                'field'            => 'value',
                'name'             => 'value',
                'form_group_class' => 'config_value',
                'form_type'        => 'textarea',
                'title'            => '默认值',
                'tips'             => '该配置的具体内容'
            ]
        ]);

        // 解析值
        $info['value'] = $this->typeValueAnalysis($info['type'], $info);

        // 设置表单数据
        $form->setFormdata($info);

        // 设置页面
        return $form->fetch();
    }

    /**
     * 删除/批量
     * @author 仇仇天
     */
    public function del()
    {
        $data = input();

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

        if (false !== AdminConfigModel::del($where)) {

            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 状态编辑
     * @author 仇仇天
     */
    public function editStatus()
    {
        // 表单数据
        $data = $this->request->post();

        // 需要修改的数据id
        $ids = [];
        foreach ($data['batch_data'] as $value) {
            $ids[] = $value['id'];
        }
        $where= [['id', 'in', $ids]];

        $result = AdminConfigModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }

    }

    /**
     * 解析默认值入
     * @param $type  类型
     * @param array $value 配置参数
     * @return mixed|string
     * @author 仇仇天
     */
    private function typeValueAnalysis($type, $value = [])
    {
        $res = '';
        switch ($type) {
            case 'tags':
                $res = $value['value'];
                break;
            default:
                $res = $value['value'];
        }
        return $res;
    }

    /**
     * 解析默认值入库数据库
     * @param $type  类型
     * @param array $config_value 配置参数
     * @return false|mixed|string
     * @author 仇仇天
     */
    private function type_value($type, $config_value = [])
    {
        $res = '';
        switch ($type) {
            case 'tags':
                $res = $value['value'];
                break;
            default:
                $res = $config_value['value'];
        }
        return $res;
    }

    /**
     * 解析配置参数入库数据库
     * @param $type 类型
     * @param array $config_param 配置参数
     * @return false|string
     * @author 仇仇天
     */
    private function type_optisons($type, $config_param = [])
    {

        $res = [];

        // 复选/单选/下拉
        if($type == 'checkbox' || $type == 'radio' || $type == 'select'){
            if ($config_param['config_title'] !== '') {
                foreach ($config_param['config_title'] as $key => $value) {
                    $res[] = ['title' => $value, 'value' => $config_param['config_value'][$key]];
                }
            }
        }

        // 开关
        else if($type == 'switch'){

            // 启用文本
            $res['on_text']   = empty($config_param['on_text']) ? '是' : $config_param['on_text'];

            // 禁用文本
            $res['off_text']  = empty($config_param['off_text']) ? '否' : $config_param['off_text'];

            // 启用值
            $res['on_value']  = empty($config_param['on_value']) ? 1 : $config_param['on_value'];

            // 禁用值
            $res['off_value'] = empty($config_param['off_value']) ? 0 : $config_param['off_value'];

        }

        // 数字
        else if($type == 'number'){

            // 允许的最小值
            $res['min'] = $config_param['min'] == '' ? '' : $config_param['min'];

            // 允许的最大值
            $res['max'] = $config_param['max'] == '' ? '' : $config_param['max'];

            // 数字间隔
            $res['step'] = $config_param['step'] == '' ? '' : $config_param['step'];
        }

        // 范围滑块
        else if($type == 'range'){

            // 滑动条最小值
            $res['range_min']   = $config_param['range_min'] == '' ?  0 : $config_param['range_min'];

            // 滑动条最大值
            $res['range_max']   = $config_param['range_max'] == '' ?  100 : $config_param['range_max'];

        }

        // 格式化文本
        else if($type == 'masked'){
            // 格式化文本
            if ($config_param['masked'] !== '') {
                $res['masked'] = $config_param['masked'];
            }
        }

        // 单图/多图
        else if($type == 'images' || $type == 'image' || $type == 'file' || $type == 'files'){
            if (!empty($config_param['storage_path'])) {
                // 存储路径
                $res['storage_path'] = $config_param['storage_path'];
            }
        }

        // 下拉联动
        else if($type == 'linkage'){
            $config = [];

            $linkage_value = [];

            $linkage_default_url = '';

            $linkage_type = 1;

            $linkage_data_find = '';

            $linkage_json_name = '';

            $linkage_json_value = '';

            $linkage_json_sub = '';

            $linkage_empty_style = '';

            $linkage_manner = 1;

            $linkage_first_title = '';

            $linkage_first_value = '';

            if ($config_param['linkage_title'] !== '') {
                foreach ($config_param['linkage_title'] as $key => $value) {
                    $config[] = ['title' => $value, 'name' => $config_param['linkage_name'][$key], 'url' => $config_param['linkage_url'][$key]];
                }
            }

            if ($config_param['linkage_value'] !== '') {
                $linkage_value = json_decode($config_param['linkage_value'], true);
            }

            if ($config_param['linkage_default_url'] !== '') {
                $linkage_default_url = $config_param['linkage_default_url'];
            }

            if ($config_param['linkage_type'] == 2) {
                $linkage_type = 2;
            }

            if ($config_param['linkage_manner'] == 0) {
                $linkage_manner = 0;
            }


            if ($config_param['linkage_data_find']) {
                $linkage_data_find = $config_param['linkage_data_find'];
            }

            if (!empty($config_param['linkage_empty_style'])) {
                $linkage_empty_style = $config_param['linkage_empty_style'];
            }

            if ($config_param['linkage_json_name']) {
                $linkage_json_name = $config_param['linkage_json_name'];
            }

            if ($config_param['linkage_json_value']) {
                $linkage_json_value = $config_param['linkage_json_value'];
            }

            if ($config_param['linkage_json_sub']) {
                $linkage_json_sub = $config_param['linkage_json_sub'];
            }

            if ($config_param['linkage_first_title']) {
                $linkage_first_title = $config_param['linkage_first_title'];
            }

            if ($config_param['linkage_first_value']) {
                $linkage_first_value = $config_param['linkage_first_value'];
            }

            $res['url'] = $linkage_default_url;

            $res['config'] = $config;

            $res['value'] = $linkage_value;

            $res['type'] = $linkage_type;

            $res['data_find'] = $linkage_data_find;

            $res['json_name'] = $linkage_json_name;

            $res['json_value'] = $linkage_json_value;

            $res['json_sub'] = $linkage_json_sub;

            $res['empty_style'] = $linkage_empty_style;

            $res['first_title'] = $linkage_first_title;

            $res['first_value'] = $linkage_first_value;

            $res['manner'] = $linkage_manner;
        }

        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 设置配置
     * @param $form 表单对象
     */
    private function setConfig($form,$info=[]){

        $info['type'] = isset($info['type']) ? $info['type'] : '';

        $info['options'] = isset($info['options']) ? $info['options'] : '{}';

        $info['value'] = isset($info['value']) ? $info['value'] : '';

        $info['actions'] = isset($info['actions']) ? $info['actions'] : 'add';


        // 设置额外表单代码
        $formHtml = <<<html
        <div class="result"></div>
html;
        $form->extraHtmlContentFormCode($formHtml);

        // 设置额外代码
        $html = <<<html
<!--*******************************************值配置******************************************************-->

<!--值单行文本-->
<script id="config_value_text" type="text/x-jsrender">
 <input name="{{:name}}" class="form-control" type="text" value="{{:value}}">
</script>

<!--值多行文本-->
<script id="config_value_textarea" type="text/x-jsrender">
<textarea class="form-control" name="{{:name}}">{{:value}}</textarea>
</script>

<!--值数字文本-->
<script id="config_value_number" type="text/x-jsrender">    
<input name="{{:name}}" class="form-control" type="number" value="{{:value}}">   
</script>

<!--值取色文本-->
<script id="config_value_colorpicker" type="text/x-jsrender">     
 <input name="{{:name}}" class="form-control js-colorpicker" data-position="bottom right" type="text" value="{{:value}}"> 
</script>

<!--值标签-->
<script id="config_value_tags" type="text/x-jsrender">    
    <input name="{{:name}}" class="js-tags form-control" id="tags" />
</script>

<!--值数组-->
<script id="config_value_array" type="text/x-jsrender">    
   <div id="jsoneditor" style="width: 100%; height: 400px;"></div>
    <input type="hidden" name="{{:name}}" class="linkage_value" value="{{:value}}">
</script>
            
<!--*******************************************字段配置******************************************************-->

<!--配置数字-->
<script id="config_number" type="text/x-jsrender">

<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>
     
<h3 class="kt-section__title">配置参数:</h3>    

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title"><span class="kt-font-boldest">最小值</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="min" class="form-control" type="number" value="{{:min}}">
    </div>
</div>

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title"><span class="kt-font-boldest">最大值</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="max" class="form-control" type="number" value="{{:max}}">
    </div>
</div>

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title"><span class="kt-font-boldest">复数</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="step" class="form-control" type="number" value="{{:step}}">
    </div>
</div>

</script>

<!--配置开关-->
<script id="config_switch" type="text/x-jsrender">

    <div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>
     
    <h3 class="kt-section__title">配置参数:</h3>    
    
    <div class="form-group row">
        <label class="col-form-label col-lg-2 result-title"><span class="kt-font-danger kt-font-bold">*</span><span class="kt-font-boldest">启用文本</span>： </label>
        <div class="col-lg-10 result-form">
            <input name="on_text" class="form-control" type="text" value="{{:config.on_text}}">
            <div class="form-text text-muted">默认<code>开</code></div>
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-form-label col-lg-2 result-title"><span class="kt-font-danger kt-font-bold">*</span><span class="kt-font-boldest">禁用文本</span>： </label>
        <div class="col-lg-10 result-form">
            <input name="off_text" class="form-control" type="text" value="{{:config.off_text}}">
            <div class="form-text text-muted">默认<code>关</code></div>
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-form-label col-lg-2 result-title"><span class="kt-font-danger kt-font-bold">*</span><span class="kt-font-boldest">启用值</span>： </label>
        <div class="col-lg-10 result-form">
            <input name="on_value" class="form-control" type="text" value="{{:config.on_value}}">
            <div class="form-text text-muted">默认<code>1</code></div>
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-form-label col-lg-2 result-title"><span class="kt-font-danger kt-font-bold">*</span><span class="kt-font-boldest">禁用值</span>： </label>
        <div class="col-lg-10 result-form">
            <input name="off_value" class="form-control" type="text" value="{{:config.off_value}}">
            <div class="form-text text-muted">默认<code>0</code></div>
        </div>
    </div>
</script>

<!--配置 复选框/单选框/下拉框-->
<script id="config_title_value" type="text/x-jsrender">

<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>     

<div class="form-horizontal">   
    <div class="form-group row">
        <label class="col-lg-1 col-form-label">配置参数:</label>
        <div class="col-lg-11 config_dome">
            {{for config}}
            <div class="row kt-margin-b-10">
                <div class="col-lg-5">
                    <input class="form-control" placeholder="标题" type="text" name="config_title[]" value="{{:title}}">
                </div>
                <div class="col-lg-5">
                     <input class="form-control" placeholder="值" type="text" name="config_value[]" value="{{:value}}">
                </div>
                <div class="col-lg-2">
                    <a href="javascript:;" class="btn btn-danger btn-icon config_del">
                        <i class="la la-remove"></i>
                    </a>
                </div>
            </div>
            {{/for}}
        </div>            
    </div>       
            
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col">
            <div class="btn btn btn-primary config_add">
                <span>
                    <i class="la la-plus"></i>
                    <span>新增</span>
                </span>
            </div>
        </div>
    </div>
</div>
    
</script>

<!--配置 日期/日期时间/日期范围/格式化输入-->
<script id="config_text" type="text/x-jsrender">

<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>
     
<h3 class="kt-section__title">配置参数:</h3>    

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title">格式： </label>
    <div class="col-lg-10 result-form">
        <input name="format" class="form-control" type="text" value="{{:format}}">
    </div>
</div>
</script>

<!--配置图片-->
<script id="config_image" type="text/x-jsrender">

<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>
     
<h3 class="kt-section__title">配置参数:</h3>    

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title"><span class="kt-font-danger kt-font-bold">*</span><span class="kt-font-boldest">存储路径</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="storage_path" class="form-control" type="text" value="{{:config.storage_path}}">      
    </div>
</div>
</script>

<!--配置下拉联动框-->
<script id="config_linkage" type="text/x-jsrender">
<h3 class="form-section">配置参数</h3>

    <div class="form-group">
        <label class="control-label col-md-2">异步默认地址：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <div class="btn-group btn-make-switch" data-toggle="buttons">
                    <button class="btn blue btn-outline on-button {{if type == 1}}active{{/if}}">
                        <input type="radio" name="linkage_type" class="toggle" value="1" {{if type == 1}}checked{{/if}}>异步
                    </button>
                    <button class="btn blue btn-outline off-button {{if type == 2}}active{{/if}}">
                        <input type="radio" name="linkage_type" class="toggle" value="2" {{if type == 2}}checked{{/if}}> 数据
                    </button>
                </div>
                <span class="help-block">异步=异步请求，数据=设置静态数据(<code>json</code>)</span>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-md-2">显示状态：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <div class="btn-group btn-make-switch" data-toggle="buttons">
                    <button class="btn blue btn-outline on-buttons {{if empty_style == 1}}active{{/if}}">
                        <input type="radio" name="linkage_empty_style" class="toggle" value="1" {{if empty_style == 1}}checked{{/if}}>隐藏
                    </button>
                    <button class="btn blue btn-outline off-buttons {{if empty_style == 0}}active{{/if}}">
                        <input type="radio" name="linkage_empty_style" class="toggle" value="0" {{if empty_style == 0}}checked{{/if}}> 显示
                    </button>
                </div>
                <span class="help-block">子集无数据时 select 的显示状态</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2">选项标题展示方式：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <div class="btn-group btn-make-switch" data-toggle="buttons">
                    <button class="btn blue btn-outline on-buttons {{if manner == 1}}active{{/if}}">
                        <input type="radio" name="linkage_manner" class="toggle" value="1" {{if manner == 1}}checked{{/if}}>展示
                    </button>
                    <button class="btn blue btn-outline off-buttons {{if manner == 0}}active{{/if}}">
                        <input type="radio" name="linkage_manner" class="toggle" value="0" {{if manner == 0}}checked{{/if}}> 隐藏
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group linkage_value_style">
        <label class="control-label col-md-2">下拉联动数据：</label>
        <div class="col-md-10">
            <div id="jsoneditor" style="width: 100%; height: 400px;"></div>
            <input type="hidden" name="linkage_value" class="linkage_value" value="{{:value}}">
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2"><span class="font-red-sunglo"> * </span>异步默认地址：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_default_url" class="form-control" value="{{:url}}">
                <span class="help-block">如请求的地址是 <code>url('ajax/getCity')</code>，那么只需填写 <code>ajax/getCity</code>，或者直接填写以 <code>http</code>开头的url地址</span>
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">异步数据字段：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_data_find" class="form-control" value="{{:data_find}}">
                <span class="help-block">异步请求回调时数据字段</span>
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">数据标题字段名称：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_json_name" class="form-control" value="{{:json_name}}">
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">数据值字段名称：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_json_value" class="form-control" value="{{:json_value}}">
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">子集数据字段名称：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_json_sub" class="form-control" value="{{:json_sub}}">
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">选框第一个项目的标题：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_first_title" class="form-control" value="{{:first_title}}">
                 <span class="help-block">默认"请选择"</span>
            </div>
        </div>
    </div>

    <div class="form-group linkage_default_url">
        <label class="control-label col-md-2">选框第一个项目的值：</label>
        <div class="col-md-10">
            <div class="input-icon right">
                <input type="text" name="linkage_first_value" class="form-control" value="{{:first_value}}">
                 <span class="help-block">默认"空"</span>
            </div>
        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-md-2">下拉联动框值：</label>
        <div class="col-md-10 linkage_config_dome">
                <button class="btn blue btn-block margin-bottom-5 linkage_config_add" type="button">新增
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                {{for config}}
                    <div class="input-group linkage_config margin-bottom-5">
                        <label class="input-group-addon">标题</label>
                        <input class="form-control" type="text" name="linkage_title[]" value="{{:title}}">
                        <label class="input-group-addon">名称</label>
                        <input class="form-control" type="text" name="linkage_name[]" value="{{:name}}">
                        <label class="input-group-addon">请求地址</label>
                        <input class="form-control" type="text" name="linkage_url[]" value="{{:url}}">
                        <span class="input-group-btn">
                            <button class="btn btn-info linkage_config_del" type="button" title="删除">
                                <span class="fa fa-trash-o"></span>
                            </button>
                        </span>
                    </div>
                {{/for}}
            </div>
        </div>
    </div>
</script>

<!--范围滑块-->
<script id="config_range" type="text/x-jsrender">

<div class="kt-separator kt-separator--border-dashed kt-separator--portlet-fit"></div>
     
<h3 class="kt-section__title">配置参数:</h3>    

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title">最小值</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="range_min" class="form-control" type="text" value="{{:config.range_min}}">
        <div class="form-text text-muted">默认<code>0</code></div>
    </div>
</div>

<div class="form-group row">
    <label class="col-form-label col-lg-2 result-title">最大值</span>： </label>
    <div class="col-lg-10 result-form">
        <input name="range_max" class="form-control" type="text" value="{{:config.range_max}}">
        <div class="form-text text-muted">默认<code>100</code></div>
    </div>
</div>

</script>

html;
        $form->extraHtmlCode($html);

        // 额外前置js代码
        $preposeJs = <<<javascript
        
        var edit_config_type = "{$info['type']}";
        
         // 操作标识
        var actions = '{$info['actions']}';

        // 编辑时需要用到类型
        var edit_config_type = "{$info['type']}"; 

        // 配置选项
        var edit_config_options = '{$info["options"]}';      
           
        try {
            edit_config_options = $.parseJSON(edit_config_options);
        }catch(err) {
            edit_config_options = {};
        }

        // 配置值(表单name)
        var edit_config_name = "value"; 

        // 配置值(表单值)
        var edit_config_value = "{$info['value']}";
        
         if(actions == 'edit'){
            createConfig(edit_config_type);
        }else{            
            createConfig('text');
        }
        
        // 类型选择
        $('select[name="type"]').on('change',function(){
            var _this = $(this);
            var value = _this.val();
            createConfig(value);
         });
        
javascript;
        $form->extraPreposeBlockJsCode($preposeJs);

        // 额外后置js代码
        $postpositionJs = <<<javascript
        
         /**
         * @describe 创建配置
         * @author 仇仇天
         */
        function createConfig(type_value,option_value){ 
        
/***************************************************配置值*****************************************************************/     
          
            // 值模板渲染
            var value_template = '';
            
            // 文本类型
            if(type_value == 'text' || type_value == 'static' || type_value == 'password' || type_value == 'date' || type_value == 'time' || type_value == 'datetime' || type_value == 'daterange' || type_value == 'hidden' || type_value == 'image' || type_value == 'images' || type_value == 'file' || type_value == 'files' || type_value == 'ueditor' || type_value == 'masked' || type_value == 'range' || type_value == 'switch' || type_value == 'checkbox' || type_value == 'radio' || type_value == 'select'  || type_value == 'linkage'){
        
                var config_data_config_value = {};
                    
                value_template = $.templates("#config_value_text");
                
                config_data_config_value.name = edit_config_name;
                
                if(actions == 'edit' && edit_config_type == 'text'){
                    config_data_config_value.value = edit_config_value;
                }
                
                var value_html_output = value_template.render(config_data_config_value);
                
                $(".config_value-form").html(value_html_output);
                
            }
            
            // 多行文本
            else if(type_value == 'textarea'){ 
                    var config_data_config_value = {};
                    
                    value_template = $.templates("#config_value_textarea");
                    
                    config_data_config_value.name = edit_config_name;
                    
                    if(actions == 'edit' && edit_config_type == 'textarea'){
                        config_data_config_value.value = edit_config_value;
                    }
                    
                    var value_html_output = value_template.render(config_data_config_value);
                    
                    $(".config_value-form").html(value_html_output);
            }
            
             // 多行文本
            else if(type_value == 'array'){
            
                    var config_data_config_value = {};
                    
                    value_template = $.templates("#config_value_array");
                    
                    config_data_config_value.name = edit_config_name;
                    
                    if(actions == 'edit' && edit_config_type == 'linkage'){
                    
                        config_data_config_value.value = edit_config_value;
                        
                    }

                    var value_html_output = value_template.render(config_data_config_value);
                    
                    $(".config_value-form").html(value_html_output);

                    // 创建json编辑器
                    var container = document.getElementById("jsoneditor");
                    
                    var options = {
                        // 编辑触发
                        onChangeText:function(staingJson){ 
                            $('.linkage_value').val(staingJson);
                        },
                        // 默认展示类型
                        mode: 'code', 
                        // 设置可展示类型 'text', 'code','tree','view','form','preview'
                        modes: ['code','tree'], 
                    };
                    var add = new JSONEditor(container, options);

                    // 设置 json
                    $('.linkage_value').val(edit_config_value ? edit_config_value : '[]' );
                    
                    add.set(edit_config_value ? JSON.parse(edit_config_value) : []);
                
            }
            // 数字
            else if(type_value == 'array'){ 
                    var config_data_config_value = {};
                    
                    value_template = $.templates("#config_value_number");
                    
                    config_data_config_value.name = edit_config_name;
                    
                    if(actions == 'edit' && edit_config_type == 'number')config_data_config_value.value = edit_config_value;
                    
                    var value_html_output = value_template.render(config_data_config_value);
                    
                    $(".config_value-form").html(value_html_output);
            }
            // 取色器
            else if(type_value == 'colorpicker'){ 
                    var config_data_config_value = {};
                    
                    value_template = $.templates("#config_value_colorpicker");
                    
                    config_data_config_value.name = edit_config_name;
                    
                    if(actions == 'edit' && edit_config_type == 'colorpicker')config_data_config_value.value = edit_config_value;
                    
                    var value_html_output = value_template.render(config_data_config_value);
                    
                    $(".config_value-form").html(value_html_output);
            }
            
            // 标签
            else if(type_value == 'tags'){ 
                var config_data_config_value = {};
                    value_template = $.templates("#config_value_tags");
                    config_data_config_value.name = edit_config_name;
                    if(actions == 'edit' && edit_config_type == 'tags'){
                        try {
                            edit_config_value = $.parseJSON(edit_config_value);
                        }catch(err) {
                            edit_config_value = {};
                        }
                        config_data_config_value.config = edit_config_value;
                    }

                    // 禁止默认值编辑
                    $('textarea[name="value"]').attr("disabled",true);

                    // 下拉选框配置新增
                    $('.form-horizontal').on('click','.config_value_tags_add',function(){
                        var htmls =  '<div class="input-group config_value_tags_config margin-bottom-5">' +
                            '<label class="input-group-addon">标题</label>' +
                            '<input class="form-control" type="text" name="tags_title[]" />' +
                            '<label class="input-group-addon">值</label>' +
                            '<input class="form-control" type="text" name="tags_value[]" />' +
                            '<span class="input-group-btn">' +
                            '<button class="btn btn-info config_value_tags_del" type="button" title="删除">' +
                            '<span class="fa fa-trash-o"></span>' +
                            '</button>' +
                            '</span>' +
                            '</div>';
                        $('.config_value_tags_dome').append(htmls);
                    });

                    // 下拉选框配置删除
                    $('.form-horizontal').on('click','.config_value_tags_del',function(){
                        $(this).parent('.input-group-btn').parent('.config_value_tags_config').remove();
                    });

                    var value_html_output = value_template.render(config_data_config_value);
                    $(".config_value-form").html(value_html_output);
            }           
            
            
/***************************************************配置选项*****************************************************************/

            // 配置选项渲染
            var options_data = {};
            
            // 编辑配置值   
            options_data.config = edit_config_options;
             
             // 数字       
            if(type_value == 'number'){    
                 // 获取模板
                var template = $.templates("#config_number");
            }
            
            // 开关
            else if(type_value == 'switch'){
                 // 获取模板
                var template = $.templates("#config_switch");
            }
            
            // 复选框/单选/下拉
            else if(type_value == 'checkbox' || type_value == 'radio' || type_value == 'select'){               
                 // 获取模板
                var template = $.templates("#config_title_value");
            }
            
            // 日期/日期时间/日期范围/格式化输入
            else if(type_value == 'date' || type_value == 'datetime' || type_value == 'daterange' || type_value == 'masked'){
                 // 获取模板
                var template = $.templates("#config_text");
            }
            
            // 单图/多图
            else if(type_value == 'image' || type_value == 'images' || type_value == 'file' || type_value == 'files'){
                 // 获取模板
                var template = $.templates("#config_image");
            }
            
            
            // 范围滑块
            else if(type_value == 'range'){
            
                options_data.range_min = edit_config_options.range_min;
                
                options_data.range_max = edit_config_options.range_max;
                
                options_data.range_fronm = edit_config_options.range_fronm;
                
                options_data.range_to = edit_config_options.range_to;
                    
                 // 获取模板
                var template = $.templates("#config_range");
            }         
            // 联动下拉
            else if(type_value == 'linkage'){
            
                    // 配置信息
                    options_data.config = edit_config_options.config; 
                    
                    // 默认地址
                    options_data.url = edit_config_options.url; 
                    
                    // 数据类型方式
                    options_data.type = edit_config_options.type ? edit_config_options.type : 1 ; 

                    // 显示 隐藏
                    options_data.empty_style = (edit_config_options.empty_style == 0) ? 0 : 1 ;

                    // 标题 展示 方式 显示 隐藏
                    options_data.manner = (edit_config_options.manner == 0) ? 0 : 1 ; 

                    // 	选框第一个项目的标题
                    options_data.first_title = edit_config_options.first_title; 
                    
                    // 选框第一个项目的值
                    options_data.first_value = edit_config_options.first_value; 
                    
                    // 	异步数据字段
                    options_data.data_find = edit_config_options.data_find; 
                    
                    // 	数据标题字段名称
                    options_data.json_name = edit_config_options.json_name; 
                    
                    // 数据值字段名称
                    options_data.json_value = edit_config_options.json_value; 
                    
                    // 子集数据字段名称
                    options_data.json_sub = edit_config_options.json_sub; 
                    
                    // 自定义数据
                    options_data.value = edit_config_options.value; 

                    // 获取模板
                    var template = $.templates("#config_linkage");
            }                       
               
            
            // 加载渲染数据
            var htmlOutput = template ? template.render(options_data) : '';
      
            // 渲染模板
            $(".result").html(htmlOutput);          
            
            
            
            
            // 开关       
            if(type_value == 'number'){            
                // 添加表单验证
                setTimeout(function() {   
                     $('input[name="on_text"]').rules("add",{
                        required: true,
                        messages:{
                            required: '请输入启用文本'
                        }
                    });
                    
                    $('input[name="off_text"]').rules("add",{
                        required: true,
                        messages:{
                            required: '请输入禁用文本'
                        }
                    });
                    
                    $('input[name="on_value"]').rules("add",{
                        required:true,
                        messages:{
                            required: '请输入启用值'
                        }
                    });
                    
                    $('input[name="off_value"]').rules("add",{
                        required:true,
                        messages:{
                            required: '请输入禁用值'
                        }
                    });                    
                }, 1000); 
            }
            
             // 单图/多图/单文件/多文件
            else if(type_value == 'image' || type_value == 'images' || type_value == 'file' || type_value == 'files'){   
            setTimeout(function() {   
                     $('input[name="storage_path"]').rules("add",{
                        required: true,
                        messages:{
                            required: '请输入存储路径'
                        }
                    });                   
                }, 1000); 
            }
            
            // 复选框/单选/下拉 
            else if(type_value == 'checkbox' || type_value == 'radio' || type_value == 'select'){            
                // 配置新增
                $('.form-horizontal').on('click','.config_add',function(){
                    var htmls =  '<div class="row kt-margin-b-10">'+
                                    '<div class="col-lg-5">'+
                                        '<input class="form-control" placeholder="标题" type="text" name="config_title[]">'+
                                    '</div>'+
                                    '<div class="col-lg-5">'+
                                        '<input class="form-control" placeholder="值" type="text" name="config_value[]">'+    
                                    '</div>'+
                                    '<div class="col-lg-2">'+
                                        '<a href="javascript:;" class="btn btn-danger btn-icon config_del">'+
                                            '<i class="la la-remove"></i>'+
                                        '</a>'+
                                    '</div>'+
                                '</div>';                            
                    $('.config_dome').append(htmls);    
                });
                
                 // 置删除
                $('.form-horizontal').on('click','.config_del',function(){
                    $(this).parent('div').parent('.kt-margin-b-10').remove();
                });   
            }
            
            // 联动下拉
            else if(type_value == 'linkage'){
            
                    // 下拉选框配置新增
                    $('.form-horizontal').on('click','.linkage_config_add',function(){
                        var htmls =  '<div class="input-group linkage_config margin-bottom-5">' +
                            '<label class="input-group-addon">标题</label>' +
                            '<input class="form-control" type="text" name="linkage_title[]" />' +
                            '<label class="input-group-addon">名称</label>' +
                            '<input class="form-control" type="text" name="linkage_name[]" />' +
                            '<label class="input-group-addon">请求地址</label>' +
                            '<input class="form-control" type="text" name="linkage_url[]" />' +
                            '<span class="input-group-btn">' +
                            '<button class="btn btn-info linkage_config_del" type="button" title="删除">' +
                            '<span class="fa fa-trash-o"></span>' +
                            '</button>' +
                            '</span>' +
                            '</div>';
                        $('.linkage_config_dome').append(htmls);
                    });

                    // 下拉选框配置删除
                    $('.form-horizontal').on('click','.linkage_config_del',function(){
                        $(this).parent('.input-group-btn').parent('.linkage_config').remove();
                    });

                    // 创建json编辑器
                    var container = document.getElementById("jsoneditor");

                    var options = {
                        onChangeText:function(staingJson){ // 编辑触发
                            $('.linkage_value').val(staingJson);
                        },
                        mode: 'code', // 默认展示类型
                        modes: ['code','tree'], // 设置可展示类型 'text', 'code','tree','view','form','preview'
                    };

                    var editor = new JSONEditor(container, options);

                    // 设置 json
                    $('.linkage_value').val(options_data.value ? options_data.value : '[]');
                    editor.set(options_data.value ? options_data.value : []);
                    // // 获取 json
                    // var updatedJson = editor.get();

                    if(options_data.type == 1){
                        $('.linkage_value_style').css({display:'none'});
                        $('.linkage_default_url').css({display:''});
                    }else{
                        $('.linkage_value_style').css({display:''});
                        $('.linkage_default_url').css({display:'none'});
                    }

                    $('.form-horizontal').on('click','.on-button',function(){
                        $('.linkage_value_style').css({display:'none'});
                        $('.linkage_default_url').css({display:''});
                    });

                    $('.form-horizontal').on('click','.off-button',function(){
                        $('.linkage_value_style').css({display:''});
                        $('.linkage_default_url').css({display:'none'});
                    });

                    // 添加表单验证
                    $('input[name="linkage_default_url"]').rules("add",{
                        required: true,
                        messages:{
                            required: '请输入默认一步地址'
                        }
                    });
            }        
        }
        
javascript;
        $form->extraPostpositionBlockJsCode($postpositionJs);

        return $form;
    }
}
