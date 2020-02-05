<?php

namespace app\common\builder\tables;

use app\common\builder\ZBuilder;
use app\common\builder\forms\Builder as formsBuilder;
use app\user\model\Role;
use think\facade\Cache;
use think\facade\Env;

/**
 * 表格构建器
 * @package app\common\builder\table
 * @author 仇仇天
 */
class Builder extends ZBuilder
{

    public $_template = '';

    public $_table_name = '';

    public $_table_config = [];

    /**
     * @var array 模板变量
     */
    private $_vars = [
        'page_title'               => '', // 页面标题
        '_module'                  => '', // 获取当前访问模块
        '_controller'              => '', // 获取当前访问控制器
        '_action'                  => '', // 获取当前访问控方法
        '_param'                   => '', // 传递参数
        'return_url'               => [], // 返回地址
        '_pag_id'                  => '', // 页面标识
        '_table_id'                => '', // 表格标识
        '_template'                => '', // 表格名称
        '_columns'                 => [], // 列设置数据
        'explanation'              => [],// 页面提示信息
        '_url'                     => '', // 数据获取地址
        '_queryParams'             => [],// 表格请求数据参数
        '_editable_url'            => '',// 行内编辑保存地址
        '_editable_data'           => '',// 行内编辑保存参数
        '_top_buttons'             => [],// 顶部按钮
        '_search_setting'          => '', // 快捷搜索项目
        '_advanced_search_setting' => '', // 高级搜索项目
        '_advanced_list_number'    => '', // 高级表单分列数量
        'group'                    => [], //分组
        '_tab_nav'                 => [], // 分组标签
        '_formatter'               => [], // 格式化
        '_events'                  => [], // 事件
        '_hide'                    => [],// 隐藏逻辑
        '_js_files'                => [], // 需要合并js文件
        '_css_files'               => [], // 需要合并的css文件
        '_css_code'                => '', // 额外自定义css代码
        '_js_code'                 => '', // 额外自定义js代码
        '_js_list'                 => [], // 额外自定义js文件名
        '_css_list'                => [], // 额外自定义css文件名
        '_js_function_arr'         => [], // 额外自定义js方法代码
        '_isTreeTable'             => false, // 是否树表格
        '_treeTableConfig'         => '', // 树表格配置
        '_tableConfig'             => '', // 表格配置

    ];

    /**
     * 初始化
     * @author 仇仇天
     */
    public function initialize()
    {
        // 获取当前访问模块
        $this->_vars['_module'] = $this->request->module();

        // 获取当前访问控制器
        $this->_vars['_controller'] = $this->request->controller();

        // 获取当前访问控方法
        $this->_vars['_action'] = $this->request->action();

        // 获取当前访问传递参数
        $this->_vars['_param'] = $this->request->param();

        // 设置表格数据加载地址
        $this->_vars['_url'] = url('/' . $this->_vars['_module'] . '/' . $this->_vars['_controller'] . '/' . $this->_vars['_action']);

        // 设置表格数据加载参数
        $this->_vars['_queryParams'] = json_encode($this->_vars['_param'], true);

        // 设置表格数据加载地址
        $this->_vars['_editable_url'] = url('/' . $this->_vars['_module'] . '/' . $this->_vars['_controller'] . '/editable');

        // 设置页面标识
        $this->_vars['_pag_id'] = 'table_pag_id' . time();

        // 设置表格标识
        $this->_vars['_table_id'] = 'table_id' . time();

        // 设置模板文件
        $this->_template = Env::get('app_path') . 'common/builder/tables/layout.html';

        // 默认加载所需jscss
        $this->_vars['_js_files'] = ['select2_js', 'bootstraptable_js', 'moment_js'];

        // 默认加载所需css
        $this->_vars['_css_files'] = ['select2_css', 'bootstraptable_css'];

    }

    /**
     * 设置页面标题
     * @param string $title 页面标题
     * @return \app\common\builder\forms\Builder
     * @author 仇仇天
     */
    public function setPageTitle($title = '')
    {
        if ($title != '') {
            $this->_vars['page_title'] = trim($title);
        }
        return $this;
    }

    /**
     * 设置提示信息
     * @param string $title 页面标题
     * @return \app\common\builder\forms\Builder
     * @author 仇仇天
     */
    public function setExplanation($msg = [])
    {
        $this->_vars['explanation'] = $msg;
        return $this;
    }

    /**
     * js合并
     * @param $js_files js文件数组
     * @author 仇仇天
     */
    public function setJsFiles($js_files)
    {
        if (!empty($js_files)) {
            $this->_vars['_js_files'] = array_merge($this->_vars['_js_files'], $js_files);
        }
    }

    /**
     * css合并
     * @param $css_files css文件数组
     * @author 仇仇天
     */
    public function setCaaFiles($css_files)
    {
        if (!empty($css_files)) {
            $this->_vars['_css_files'] = array_merge($this->_vars['_css_files'], $css_files);
        }
    }

    /**
     * 设置快捷搜索参数
     * @param array $param 参与搜索的字段
     * [
     * ['title' => '标题', 'field' => 'title','condition'=>'like', 'default' => true],
     * ['title' => '标识', 'field' => 'name','condition'=>'like', 'default' => false]
     * ]
     * @author 仇仇天
     */
    public function setSearch($param = [])
    {
        if (!empty($param)) {

            $this->_vars['_search_setting'] = $param;
        }
        return $this;
    }

    /**
     * 设置搜高级索参数
     * @param $param            表单数据
     * @param int $columnNumber 列数
     * @return $this
     * @author 仇仇天
     */
    public function setSeniorSearch($param, $columnNumber = 4)
    {
        $forms = new formsBuilder();

        // 设置表单以及分列
        $forms->setFormItemsInfo($param, $columnNumber);

        // 获取数据
        $this->_vars['_advanced_search_setting'] = $forms->_vars['form_items'];

        // 获取表单相关js
        $this->setJsFiles($forms->_vars['_js_files']);

        // 获取表单相关css
        $this->setCaaFiles($forms->_vars['_css_files']);

        // 设置高级搜索表单列数
        $this->_vars['_advanced_list_number'] = $columnNumber;

        return $this;
    }

    /**
     * 设置Tab按钮列表
     * @param array $tab_list Tab列表  ['title' => '标题', 'href' => 'http://www.dolphinphp.com']
     * @param string $curr_tab 当前tab
     * @return $this
     * @author 仇仇天
     */
    public function setTabNav($tab_list = [], $curr_tab = '')
    {
        if (!empty($tab_list)) {
            $this->_vars['_tab_nav'] = ['tab_list' => $tab_list, 'curr_tab' => $curr_tab];
        }
        return $this;
    }

    /**
     * 设置分组
     * @param array $param
     * [
     * [
     * 'title'=>'标题', [必填]
     * 'value'=>'值',   [必填]
     * 'url'=>'地址',   [必填]
     * 'default'=>'是否选中' true=是，false=否 [必填]
     * ],
     * [
     * 'title'=>'标题',
     * 'value'=>'值',
     * 'url'=>'地址',
     * 'default'=>'是否选中' true=是，false=否
     * ]
     * ]
     * @author 仇仇天
     */
    public function setGroup($param = [])
    {
        $this->_vars['group'] = $param;
        return $this;
    }

    /**
     * 添加一个顶部按钮
     * @param string $type 按钮类型：add/enable/disable/back/delete/custom
     *                          add = 新增
     *                          edit = 编辑
     *                          delete = 删除
     *                          enable = 启用
     *                          disable = 禁用
     *                          custom = 自定义按钮
     * @param array $attribute 按钮属性
     * @param bool $pop 是否使用弹出框形式
     * @return $this
     * @author 仇仇天
     */
    public function addTopButton($type = '', $parameter_config = [])
    {
        switch ($type) {
            // 新增按钮
            case 'add':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '新增' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-plus-circle' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn-info btn-elevate' : 'btn btn-info btn-elevate ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => isset($parameter_config['batch']) ? 'top-batch' : '',
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? false : true,
                    // ajax：ajax请求，form：跳转请求
                    'jump_way'      => 'form',
                    // 请求地址
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/add') : $parameter_config['url'], // 跳转url
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'get') ? 'get' : 'post',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? '' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
            // 启用按钮
            case 'enable':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '启用' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-check-circle-o' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn-success btn-elevate' : 'btn btn-success btn-elevate ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => true,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? true : false,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchEnable') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'post') ? 'post' : 'get',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? '' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];

                break;
            // 禁用按钮
            case 'disable':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '禁用' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-ban' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn-warning btn-elevate' : 'btn btn-warning btn-elevate ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => true,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? true : false,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchDisable') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'post') ? 'post' : 'get',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? '' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
            // 删除按钮
            case 'delete':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '删除' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-trash' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn-danger btn-elevate' : 'btn btn-danger btn-elevate ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => true,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? true : false,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchDelete') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'post') ? 'post' : 'get',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? '' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '确定要删除该数据吗？' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
            // 导出按钮
            case 'export':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '导出' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-cloud-download' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn-info' : 'btn btn-info ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => false,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? false : true,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchDisable') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'post') ? 'post' : 'get',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? 'export' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
            // 导入按钮
            case 'import':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '导入' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-cloud-upload' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn blue-hoki' : 'btn blue-hoki ' . $parameter_config['class'],
                    // 是否批量
                    'batch'         => false,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? false : true,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchDisable') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'post') ? 'post' : 'get',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? 'import' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
            // 自定义按钮
            case 'custom':
                $config = [
                    // 标题
                    'title'         => empty($parameter_config['title']) ? '自定义按钮' : $parameter_config['title'],
                    // 图标
                    'icon'          => empty($parameter_config['icon']) ? 'fa fa-trash' : $parameter_config['icon'],
                    // 类名
                    'class'         => empty($parameter_config['class']) ? 'btn btn blue-hoki' : $parameter_config['class'],
                    // 是否批量
                    'batch'         => empty($parameter_config['batch']) ? '' : true,
                    // 是否不可点击
                    'disabled'      => empty($parameter_config['disabled']) ? true : false,
                    // ajax：ajax请求，jump：跳转请求
                    'jump_way'      => !empty($parameter_config['jump_way']) ? $parameter_config['jump_way'] : 'ajax',
                    // 跳转url
                    'href'          => empty($parameter_config['url']) ? url('/' . $this->_module . '/' . $this->_controller . '/batchCustom') : $parameter_config['url'],
                    // 传输数据
                    'query_data'    => !empty($parameter_config['query_data']) ? $parameter_config['query_data'] : '',
                    // 请求类型
                    'query_type'    => (empty($parameter_config['query_type']) || $parameter_config['query_type'] == 'get') ? 'get' : 'post',
                    // 类型
                    'type'          => empty($parameter_config['query_type']) ? '' : $parameter_config['query_type'],
                    // 方法
                    'functions'     => empty($parameter_config['functions']) ? '' : $parameter_config['functions'],
                    // 是否终止后续代码
                    'functionsend'  => empty($parameter_config['functionsend']) ? '' : $parameter_config['functionsend'],
                    // 警告
                    'confirm'       => empty($parameter_config['confirm']) ? '' : $parameter_config['confirm'],
                    // 是否弹窗展示
                    'is_popup'      => empty($parameter_config['is_popup']) ? false : $parameter_config['is_popup'],
                    // 是否弹窗设置表单类名
                    'popup_form'    => empty($parameter_config['popup_form']) ? 'popup-form-' . time() : 'popup-form-' . $parameter_config['popup_form'],
                    // 是否弹窗设置表单成功后是否跳转
                    'popup_is_jump' => empty($parameter_config['popup_is_jump']) ? false : $parameter_config['popup_is_jump']
                ];
                break;
        }
        if ($config) {
            $this->_vars['_top_buttons'][] = $config;
        }
        return $this;
    }

    /**
     * 设置返回地址
     * @param string $title 页面标题
     * @return $this
     * @author 仇仇天
     */
    public function setReturnUrl($url = '')
    {
        if (!empty($url)) {
            $this->_vars['return_url'] = trim($url);
        }
        return $this;
    }

    /**
     * 设置表格数据请求地址
     * @param string $url
     * @author 仇仇天
     */
    public function setUrl($url = '')
    {
        $this->_vars['_url'] = $url ? $url : $this->_vars['_url'];
        return $this;
    }

    /**
     * 设置表格数据请求参数
     * @param array $params 参数
     * @return $this
     * @author 仇仇天
     */
    public function setQueryParams($params = [])
    {
        // 设置表格传递参数
        $this->_vars['_queryParams'] = !empty($params) ? json_encode($params, true) : $this->_vars['_queryParams'];
        return $this;
    }

    /**
     * 设置表格需要使用到的方法
     * @param array $param 方法数据
     * @return $this
     * @author 仇仇天
     */
    public function setJsFunctionArr($param = [])
    {
        if (!empty($param) && is_array($param)) {
            foreach ($param as $value) {
                array_push($this->_vars['_js_function_arr'], $value);
            }
        }
        return $this;
    }

    /**
     * 设置table配置
     * @param array $param
     * @author 仇仇天
     */
    public function setTableConfig($param = [])
    {
        $this->_table_config = $param;
    }

    /**
     * 设置表格参数
     * @param array $param
     * @author 仇仇天
     */
    private function setConfig($param = [])
    {

        $responseHandler = <<<javascript
            function(res) {
            return res;
        }
javascript;

        $customSearch = <<<javascript
            function(data,text){
            return data.filter(function (row) {
                    return row.field.indexOf(text) > -1
                    })
                }
javascript;

        $footerStyle = <<<javascript
            function(column){
                return {
                    css: { 'font-weight': 'normal' },
                    classes: 'my-class'
                }
            }
javascript;

        $detailFormatter = <<<javascript
            function(index,row,element){             
                return '';
            }
javascript;

        $detailFilter = <<<javascript
            function(index,row){ 
                return true;
            }
javascript;

        $onLoadSuccess = <<<javascript
            function (data) {            
            
             // 内容过长省略设置
               $('.table_mecism').each(function() {
                    var _this = $(this);
                    var nodType = _this.prop('nodeName');
                    var content = _this.html();
                    if(nodType == 'TD'){
                        _this.popover({
                            html:true,
                            content:content,
                            trigger:'hover'
                        });
                    }                    
                })
            
               // 表格图初始化设置
               $('.table_avatar_image').each(function() {
                    var _this = $(this);
                    var src = _this.attr('src'); // 图片源地址
                    var width= _this.data('width') ? _this.data('width') : 50; // 设置宽度
                    var height= _this.data('height') ? _this.data('height') : 50; // 设置高度
                    Base.funDrawImg(_this,width,height); // 设置等比例缩放        
                    // 设置预览      
                    _this.popover({
                        html:true,
                        content:'<img  src="'+src+'" style="max-width: 500px;max-height: 500px">',
                        trigger:'hover'
                    });
                })
                
                // 视频展示
                // http://vjs.zencdn.net/v/oceans.mp4
               $('.js-video').each(function() {
                    var _this = $(this);
                    var url = _this.data('url');
                    _this.click(function(){
                        layer.open({
                            title:'视频',
                            type: 1,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['413px', '500px'], //宽高
                            content: '<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="400" height="400">' +
                                 '<source src="'+url+'">' +
                                '</video>'
                        });
                    });                   
                });  
            }
javascript;

        $onLoadError = <<<javascript
            function (status,jqXHR) {                   
            }
javascript;

        // 默认参数
        $config = [
            'method'                     => 'post', // 请求远程数据的方法类型 get,post
            'contentType'                => 'application/json', // 发送到服务器的数据编码类型s
            'dataType'                   => 'json', // 服务器返回的数据类型
            'ajax'                       => 'undefined', // 自定义 AJAX 方法,须实现 jQuery AJAX API
            'cache'                      => 'false', // 是否使用缓存
            'ajaxOptions'                => '{}', // 提交ajax请求时的附加参数，可用参数列请查看 http://api.jquery.com/jQuery.ajax.
            /**
             * 请求服务器数据时，你可以通过重写参数的方式添加一些额外的参数，例如 toolbar 中的参数 如果 queryParamsType = ‘limit’ ,返回参数必须包含
             * limit, offset, search, sort, order 否则, 需要包含:
             * pageSize, pageNumber, searchText, sortName, sortOrder.
             * 返回false将会终止请求　　
             */
            'queryParams'                => 'queryParams',
            'queryParamsType'            => 'limit', // 设置为 ‘limit’ 则会发送符合 RESTFul 格式的参数.
            // 设置为 ‘limit’ 则会发送符合 RESTFul 格式的参数.
            'responseHandler'            => $responseHandler,
            'totalField'                 => 'total',// 总条数字段。
            'dataField'                  => 'data',// 数据字段
            'data'                       => '[]', // 要加载的数据 [] or {}
            /*****************************************************分页相关设置*****************************************************************/
            'pagination'                 => 'true', // 设置为 true 会在表格底部显示分页条
            'onlyInfoPagination'         => 'false',// 设置为 true 只显示总数据数，而不显示分页按钮。需要 pagination=’True’
            'paginationLoop'             => 'true', // 设置为 true 启用分页条无限循环的功能。
            'sidePagination'             => 'server', // 设置在哪里进行分页，可选值为 ‘client’ 或者 ‘server’。设置 ‘server’时，必须设置 服务器数据地址（url）或者重写ajax方法s
            'totalRows'                  => 0, // 该属性主要由分页服务器传递，易于使用
            'pageNumber'                 => 1, // 初始化加载第一页，默认第一页
            'pageSize'                   => 20, // 每页的记录行数
            'pageList'                   => '[5,10,15 ,20,25,30,35,40,45 ,50, 100]', // 可供选择的每页的行数
            'paginationHAlign'           => 'right', // 分页条水平方向的位置，默认right（最右），可选left
            'paginationVAlign'           => 'bottom', // ：分页条垂直方向的位置，默认bottom（底部），可选top、both（顶部和底部均有分页条）
            'paginationDetailHAlign'     => 'left', // 如果解译的话太长，举个例子，paginationDetail就是“显示第 1 到第 8 条记录，总共 15 条记录 每页显示 8 条记录”，默认left（最左），可选right
            'paginationPreText'          => '<', // 上一页的按钮符号
            'paginationNextText'         => '>', // 下一页的按钮符号
            'paginationSuccessivelySize' => 5, // 连续的最大连续页数,分页时会有<12345...80>这种格式而5则表示显示...左边的的页数
            'paginationPagesBySide'      => 1, // 当前页面每侧（右侧，左侧）的页数,右边的最大连续页数如改为2则 <1 2 3 4....79 80>
            'paginationUseIntermediate'  => 'false', // 计算并显示中间页面以便快速访问,计算并显示中间页面以便快速访问 true 会将...替换为计算的中间页数42
            /*****************************************************搜索相关设置*****************************************************************/
            'search'                     => 'false', // 是否显示表格搜索input，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
            'searchOnEnterKey'           => 'false', // 搜索方法将一直执行，直到按下Enter键,true时搜索方法将一直执行，直到按下Enter键（即按下回车键才进行搜索）
            'strictSearch'               => 'true', // 启用严格搜索
            'trimOnSearch'               => 'true', // 自动忽略空格,默认true，自动忽略空格
            'searchAlign'                => 'right', // 指定搜索输入框的方向,指定搜索输入框的方向。可以使用'left'，'right'。
            'searchTimeOut'              => 500, // 设置搜索触发超时
            'searchText'                 => '', // 设置搜索文本框的默认搜索值
            'customSearch'               => $customSearch, // 执行自定义搜索功能替换内置搜索功能
            /*****************************************************页脚相关设置*****************************************************************/
            'showFooter'                 => 'false', // 显示摘要页脚行,设置为true以显示摘要页脚行(固定也交 比如显示总数什么的最合适)
            'footerStyle'                => $footerStyle, // 页脚样式格式化,column：列对象 支持类或css 页脚样式格式化程序函数，只需一个参数 m默认｛｝
            /*****************************************************工具按钮相关设置*****************************************************************/
            'toolbar'                    => 'undefined',// 自定义工具栏，工具栏按钮用哪个容器 一个jQuery 选择器，指明自定义的 buttons toolbar。例如:#buttons-toolbar, .buttons-toolbar 或 DOM 节点
            'toolbarAlign'               => 'left', // 子定义工具栏位置,指示如何对齐自定义工具栏。可以使用'left'，'right'
            'buttonsToolbar'             => 'undefined', // 自定义按钮工具,一个jQuery选择器，指示按钮工具栏，例如：＃buttons-toolbar，.buttons-toolbar或DOM节点
            'buttonsAlign'               => 'right', // 自定义按钮工具位置,指示如何对齐工具栏按钮。可以使用'left'，'right'
            'buttonsClass'               => 'secondary', // 定义按钮工具类,定义表按钮的Bootstrap类（在'btn-'之后添加）
            'showColumns'                => 'false', // 显示列下拉列表,是否显示所有的列 设置为true以显示列下拉列表（一个可以设置显示想要的列的下拉f按钮）
            'minimumCountColumns'        => 1, // 要从列下拉列表中隐藏的最小列数,最少允许的列数  要从列下拉列表中隐藏的最小列数
            'showPaginationSwitch'       => 'false', // 设置true为显示分页切换按钮,设置为true以显示分页组件的切换按钮
            'showRefresh'                => 'false', // 显示刷新按钮,设置true为显示刷新按钮
            'showToggle'                 => 'false', // 显示切换按钮以切换表/卡视图
            'showFullscreen'             => 'false', // 显示全屏按钮
            /*****************************************************设置相关*****************************************************************/
            // stickyHeader: true, // 全屏固定头部
            // stickyHeaderOffsetY:51, // 设置窗口顶部的Y偏移以固定粘性标头。如果有一个高度为60px的固定导航栏，则该值为60。
            'classes'                    => 'table table-bordered table-hover table-sm', // 设置表类,表的类名。可以使用'table'，'table-bordered'，'table-hover'，'table-striped'，'table-dark'，'table-sm'和'table-borderless'。默认情况下，表格是有界的。

            'striped'          => 'false', // 是否显示行间隔色
            'undefinedText'    => 'undefined', // 定义默认的未定义文本
            'locale'           => 'zh-CN', // 语言设置
            'height'           => 'undefined', // 固定表格的高度,行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
            'showHeader'       => 'true',// 隐藏表头,设置为false以隐藏表头
            'smartDisplay'     => 'true',// 智能显示分页或卡片视图,设置true为智能显示分页或卡片视图
            'escape'           => 'false', // 是否转义字符串,转义字符串以插入HTML，替换 &, <, >, “, `, 和 ‘字符  跳过插入HTML中的字符串，替换掉特殊字符
            'idField'          => 'undefined', // 指示哪个字段是标识字段
            'selectItemName'   => 'btSelectItem', // 设置radio 或者 checkbox的字段名称
            'clickToSelect'    => 'false', // 是否启用点击选中行，设置为true时 在点击列时可以选择checkbox或radio
            'singleSelect'     => 'false',// 允许复选框仅选择一行,默认false，设为true则允许复选框仅选择一行(不能多选了？)
            'checkboxHeader'   => 'true',// 标题行中的check-all复选框 即隐藏全选框,设置为false以隐藏标题行中的check-all复选框 即隐藏全选框
            'maintainSelected' => 'false', // 设置true为在更改页面上维护选定的行并进行搜索,true 时点击分页按钮或搜索按钮时，记住checkbox的选择项    设为true则保持被选的那一行的状态
            'uniqueId'         => 'undefined', // 指示每行的唯一标识符,每一行的唯一标识字段，一般为主键列
            'cardView'         => 'false',// 显示卡片视图表,是否显示详细视图  设置为true以显示卡片视图表，例如mobile视图（卡片视图）
            'theadClasses'     => '', // 表thead的类名，thead的类名。Bootstrap v4，使用修饰符类.thead-light或.thead-dark使theads显示为浅灰色或深灰色
            /*****************************************************详细设置相关*****************************************************************/
            'detailView'       => 'false', // 显示详细视图表,设置为true以显示detail 视图表（细节视图）
            /**
             * 当detailView设置为true时格式化您的详细信息视图。返回一个String，它将被附加到详细视图单元格中，可选地使用第三个参数直接渲染元素，该参数是目标单元格的jQuery元素。
             * 前提：detailView设为true，启用了显示detail view。- 用于格式化细节视图- 返回一个字符串，通过第三个参数element直接添加到细节视图的cell（某一格）中，其中，element为目标cell的jQuery element
             * 格式化详细信息视图
             */
            'detailFormatter'  => $detailFormatter,
            /**
             * 每行启用扩展
             * 当detailView设置为true时，每行启用扩展。返回true并且将启用该行以进行扩展，返回false并禁用该行的扩展。默认函数返回true以启用所有行的扩展。
             */
            'detailFilter'     => $detailFilter,
            /*****************************************************图标设置相关*****************************************************************/
            'iconSize'         => 'undefined', // 定义图标的大小,定义icon图表的尺寸大小html对应为data-icon-undefined （默认btn）、data-icon-lg 大按钮的尺寸（btn-lg）...;  这里的值依次为undefined => btnxs => btn-xssm => btn-smlg => btn-lg
            /*****************************************************排序设置相关*****************************************************************/
            'sortable'         => 'true', // 排序,是否启用排序 列中也有此变量
            'sortClass'        => 'undefined', // 排序的元素的类名称,td已排序的元素的类名称
            'silentSort'       => 'true', // 使用加载消息对数据进行排序,设置为false以便对加载的消息数据进行排序。当sidePagination选项设置为“server”时，此选项有效。
            'sortName'         => 'undefined', // 定义要排序的列，定义要排序的列   没定义默认都不排列，同sortOrder结合使用，sortOrder没写的话列默认递增（asc）
            'sortOrder'        => 'asc', // 定义列排序顺序,定义列排序顺序，只能是'asc'或'desc'
            'sortStable'       => 'false', // 设置为 true 将获得稳定的排序，我们会添加_position属性到 row 数据中。
            'rememberOrder'    => 'false', // 记住每列的顺序,设置为true以记住每列的顺序
            'customSort'       => 'undefined',// 自定义排序功能，sortName：排序名称 sortOrder：排序顺序 data：行数据 自定义排序功能（用来代替自带的排序功能），需要两个参数（可以参考前面）
            /*****************************************************扩展插件相关设置*****************************************************************/
            /**
             * 描述：列是否可调整大小
             *  需要引入： jquery.resizableColumns.min.js
             *             bootstrap-table-resizable.min.js
             *             jquery.resizableColumns.css
             *  实列：https://examples.bootstrap-table.com/#extensions/resizable.html
             * 类型：Boolean
             * 详情：true=可调整，false=不可调整
             */
            'resizable'        => 'true',

            'fixedColumns'  => 'false', // 是否启用固定列
            'fixedNumber'   => 1,// 固定几列
            /*****************************************************字段属性设置相关*****************************************************************/
            'columns'       => 'columns',
            // 在成功加载远程数据时触发
            'onLoadSuccess' => $onLoadSuccess,
            // 在加载远程数据时发生某些错误时触发.
            'onLoadError'   => $onLoadError,
        ];

        $this->_vars['_tableConfig'] = array_merge($config, $param); // 合并配置
    }

    /**
     * 设置表格数据列表
     * @param array|object $row_list 表格数据
     * @return $this
     * @author 仇仇天
     */
    public function setRowList($row_list = null)
    {
        if (is_object($row_list)) {

            $this->_vars['row_list']['data'] = to_arrays($row_list->items()); // 数据

            $this->_vars['row_list']['pages'] = $row_list->getCurrentPage(); // 当前页码

            $this->_vars['row_list']['lastPage'] = $row_list->lastPage(); // 总页数

            $this->_vars['row_list']['total'] = $row_list->total(); // 总条数

            $this->_vars['row_list']['limit'] = $row_list->listRows(); // 每页显示数量

            $this->_vars['row_list']['hasPages'] = $row_list->hasPages(); // 是否足够分页

        } else {

            $this->_vars['row_list']['data'] = $row_list; // 数据

            $this->_vars['row_list']['pages'] = ''; // 当前页码

            $this->_vars['row_list']['lastPage'] = ''; // 总页数

            $this->_vars['row_list']['total'] = ''; // 总条数

            $this->_vars['row_list']['limit'] = ''; // 每页显示数量

            $this->_vars['row_list']['hasPages'] = ''; // 是否足够分页
        }
        return $this;
    }

    /**
     * 设置列选项
     * @param Array $data 列设置数据
     * @return $this
     * @author 仇仇天
     */
    public function setColumn($data = [])
    {
        // 默认字段 初始化
        $columns = [
            // String 字段名
            'field'         => '',
            // String 标题
            'title'         => '',
            // 标题提示文 此选项还支持标题HTML属性。
            'titleTooltip'  => null,
            // Boolean 是否有复选框
            'checkbox'      => false,
            // Boolean 是否有单选框
            'radio'         => false,
            // String 类名
            'class'         => null,
            // Number 跨度
            'rowspan'       => null,
            // String 水平对其方式 'left'，'right'，'center'
            'align'         => 'left',
            // String 垂直对其方式
            'valign'        => 'middle',
            // Number | String 列的宽度。如果未定义，宽度将自动扩展以适合其内容。虽然如果表格保持响应并且大小太小，则'width'可能会忽略该表格（通过类等使用min / max-width）。您也可以添加'%'您的号码，Bootstrap表将使用百分比单位，否则，保留为数字（或添加'px'）以使其使用像素。
            'width'         => null,
            // Boolean 设置true为允许列可以排序。
            'sortable'      => false,
            // String 默认排序顺序，只能是'asc'或'desc'。
            'order'         => 'asc',
            // Boolean 设置false为隐藏列项。
            'visible'       => true,
            // Boolean 设置false为隐藏卡视图状态中的列项。// Boolean 设置false为禁用可切换的列项。
            'cardVisible'   => true,
            // Boolean 设置false为禁用可切换的列项。
            'switchable'    => true,
            // Boolean 设置true为在单击列时选择复选框或单选按钮。
            'clickToSelect' => true,
            // 设置样式
            'cellStyle'     => [],
            // 行内编辑
            'editable'      => null,
            // 值隐藏该值
            'hide'          => '',
            // 事件
            'events'        => '',
            // 格式化内容
            'formatter'     => '',
            // 是否省略
            'mecism'        => false,
            // 省略配置
            'mecism_config' => [],
            // 设置字段展示类型
            'show_type'     => 'text',
            // 设置字段展示配置
            'show_config'   => []
        ];
        $resdata = [];
        $url     = url("platform/storage/edits");
//        $events = <<<javascript
//                {
//                    // 编辑回调
//                    "click .TableEditor":function(ev,value,row,index){
//                        $.post("{$url}", {}, function(str){
//                            layer.open({
//                                type: 1,
//                                maxmin:true,
//                                area:'60%',
//                                btn: ['提交', '按钮二', '按钮三'],
//                                yes: function(index, layero){
//                                    form_validate();
//                                },
//                                btn2: function(index, layero){
//                                },
//                                btn3: function(index, layero){
//                                },
//                                cancel: function(){
//                                },
//                                content: str //注意，如果str是object，那么需要字符拼接。
//                            });
//                        });
//                    },
//                    // 删除回调
//                    "click .TableDelete":function(ev,value,row,index){
//                         $.post("{$url}", {}, function(str){
//                            layer.open({
//                                type: 1,
//                                maxmin:true,
//                                area:'80%',
//                                btn: ['按钮一', '按钮二', '按钮三'],
//                                yes: function(index, layero){
//                                },
//                                btn2: function(index, layero){
//                                },
//                                btn3: function(index, layero){
//                                },
//                                cancel: function(){
//                                },
//                                content: str //注意，如果str是object，那么需要字符拼接。
//                            });
//                        });
//                    },
//                    // 查看回调
//                    "click .TableSse":function(ev,value,row,index){
//                         $.post("{$url}", {}, function(str){
//                            layer.open({
//                                type: 1,
//                                maxmin:true,
//                                area:'80%',
//                                btn: ['按钮一', '按钮二', '按钮三'],
//                                yes: function(index, layero){
//                                },
//                                btn2: function(index, layero){
//                                },
//                                btn3: function(index, layero){
//                                },
//                                cancel: function(){
//                                },
//                                content: str //注意，如果str是object，那么需要字符拼接。
//                            });
//                        });
//                    }
//                }
//javascript;

//        $peration_formatter = <<<javascript
//                function(value,row,index){
//                    return [
//                        '<button type="button" class = "btn blue btn-xs TableEditor"><i class="fa fa-edit"></i>编辑</button>',
//                        '<button type="button" class = "btn red btn-xs TableDelete"><i class="fa fa-trash"></i>删除</button>',
//                        '<button type="button" class="btn green btn-xs TableSse"><i class="fa fa-eye"></i>查看</button>',
//                    ].join('');
//                }
//javascript;

        if (is_array($data) && !empty($data)) {
            foreach ($data as $key => $value) {

                // 设置操作按钮
                if ($value['field'] == 'peration') {
                    // 设置按钮 属性
                    if (!empty($value['btn']) && is_array($value['btn'])) {
                        $value["peration_hide"] = !empty($value["peration_hide"]) ? $value["peration_hide"] : '';
                        $perationHtml           = '';
                        foreach ($value['btn'] as $btn_value) {
                            if ($btn_value['field'] == 'd') { // 删除
                                // 类名
                                $btn_class = !empty($btn_value['class']) ? 'btn-danger btn-elevate ' . $btn_value['class'] : 'btn-danger btn-elevate hide_d';
                                // 图标
                                $btn_ico = !empty($btn_value['ico']) ? $btn_value['ico'] : '<i class="fa fa-trash"></i>';
                                // 文字
                                $btn_text = !empty($btn_value['text']) ? $btn_value['text'] : '删除';
                            } elseif ($btn_value['field'] == 'u') {  // 编辑
                                // 类名
                                $btn_class = !empty($btn_value['class']) ? 'btn-info btn-elevate hide_u ' . $btn_value['class'] : 'btn-info btn-elevate hide_u';
                                // 图标
                                $btn_ico = !empty($btn_value['ico']) ? $btn_value['ico'] : '<i class="fa fa-edit"></i>';
                                // 文字
                                $btn_text = !empty($btn_value['text']) ? $btn_value['text'] : '编辑';
                            } elseif ($btn_value['field'] == 's') { // 查看
                                // 类名
                                $btn_class = !empty($btn_value['class']) ? 'btn-success hide_s ' . $btn_value['class'] : 'btn-success hide_s';
                                // 图标
                                $btn_ico = !empty($btn_value['ico']) ? $btn_value['ico'] : '<i class="fa fa-eye"></i>';
                                // 文字
                                $btn_text = !empty($btn_value['text']) ? $btn_value['text'] : '查看';
                            } elseif ($btn_value['field'] == 'c') { // 自定义
                                // 类名
                                $btn_class = !empty($btn_value['class']) ? 'btn-success' . $btn_value['class'] : '';
                                // 图标
                                $btn_ico = !empty($btn_value['ico']) ? '<i class="' . $btn_value['ico'] . '"></i>' : '<i class="fa fa-eye"></i>';
                                // 文字
                                $btn_text = !empty($btn_value['text']) ? $btn_value['text'] : '';
                            }

                            // 请求类型
                            $query_type = (empty($btn_value['query_type']) || $btn_value['query_type'] == 'get') ? 'get' : 'post';

                            // 请求方式
                            $query_jump = (empty($btn_value['query_jump']) || $btn_value['query_jump'] == 'form') ? 'form' : 'ajax';

                            // 警告
                            $confirm = !empty($btn_value['confirm']) ? $btn_value['confirm'] : '';

                            // 跳转地址
                            $btn_url = !empty($btn_value['url']) ? $btn_value['url'] : '';

                            // 传递参数
                            $query_data = '';
                            if (!empty($btn_value['query_data'])) {
                                $query_data = str_replace('"', '”', $btn_value['query_data']);
                            }

                            // 是否弹窗展示
                            $is_popup = !empty($btn_value['is_popup']) ? $btn_value['is_popup'] : false;

                            // 是否弹窗设置表单类名
                            $popup_form = !empty($btn_value['popup_form']) ? 'popup-form-' . $btn_value['popup_form'] : 'popup-form-' . time();

                            // 是否弹窗设置表单成功后是否跳转
                            $popup_is_jump = !empty($btn_value['popup_is_jump ']) ? $btn_value['popup_is_jump'] : false;

                            // 构建按钮
                            $perationHtml .= '\'<button type="button" data-url="' . $btn_url . '" data-query_data="' . $query_data . '" data-confirm="' . $confirm . '" data-query_jump="' . $query_jump . '" data-query_type="' . $query_type . '" data-ispopup="' . $is_popup . '" data-popupform="' . $popup_form . '" data-popupisjump="' . $popup_is_jump . '" class="btn btn-xs table-peration ' . $btn_class . '">' . $btn_ico . $btn_text . '</button>\',';
                        }
                    }
                    // 设置渲html
                    if (!empty($perationHtml)) {
                        // 设置按钮
                        $peration_formatter = <<<javascript
                        var perationArr = [$perationHtml];
                        {$value["peration_hide"]}                        
                        return perationArr.join("");
javascript;
                        $value['formatter'] = $peration_formatter;

                        // 设置事件
                        $peration_events = <<<javascript
                        {
                            "click .table-peration":function(ev,value,row,index){
                                  perationFun(ev,value,row,index);
                            }
                        };
javascript;
                        $value['events'] = $peration_events;
                    }
                }

                // 内容过长省略
                if (!empty($value['mecism'])) {
                    $mecism_config = [
                        // 最小宽度
                        'min-width'     => '100px',
                        // 最大宽度
                        'max-width'     => '200px',
                        // 超出内容隐藏
                        'overflow'      => 'hidden',
                        // 出现省略号
                        'text-overflow' => 'ellipsis',
                        //不换行
                        'white-space'   => 'nowrap'
                    ];
                    if (!empty($value['mecism_config'])) {
                        $mecism_config = array_merge($mecism_config, $value['mecism_config']);
                    }
                    if (!empty($value['cellStyle']['css'])) {
                        $value['cellStyle']['css'] = array_merge($value['cellStyle']['css'], $mecism_config);
                    } else {
                        $value['cellStyle']['css'] = $mecism_config;
                    }
                    // 设置类
                    $value['class'] = empty($value['class']) ? 'table_mecism' : $value['class'] . ' table_mecism';
                }

                // 展示类型
                if (!empty($value['show_type'])) {
                    switch ($value['show_type']) {
                        // 文本输入框
                        case 'text':
                            break;
                        // 按钮
                        case 'button':
                            break;
                        // 时间日期
                        case 'datetime':
                            $value['show_config']['format'] = empty($value['show_config']['format']) ? 'YYYY-MM-DD HH:mm:ss' : $value['show_config']['format'];
                            // 设置展示
                            $formatter          = <<<javascript
                            if(value){
                                if(value.toString().length == 10){
                                    value = parseInt(value) * 1000
                                }
                                return moment(value).format('{$value['show_config']['format']}');
                            }
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 字节计算
                        case 'byte':
                            // 设置展示
                            $formatter          = <<<javascript
                              return Base.funDyteConvert(value);
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 图片头像
                        case 'avatar_image':
                            // 设置展示
                            $formatter          = <<<javascript
                            return (value) ? '<img src="'+value+'" class="table_avatar_image">' : '';
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 是/否
                        case 'yesno':
                            // 是值
                            $value['show_config']['y_value'] = empty($value['show_config']['y_value']) ? 1 : $value['show_config']['y_value'];
                            // 是格式
                            $value['show_config']['y_text'] = empty($value['show_config']['y_text']) ? '<i class="fa fa-check text-success"></i>' : $value['show_config']['y_text'];
                            // 否值
                            $value['show_config']['n_value'] = empty($value['show_config']['n_value']) ? 0 : $value['show_config']['n_value'];
                            // 否格式
                            $value['show_config']['n_text'] = empty($value['show_config']['n_text']) ? '<i class="fa fa-ban text-danger"></i>' : $value['show_config']['n_text'];

                            // 设置展示
                            $formatter          = <<<javascript
                            if(value == {$value['show_config']['y_value']}){
                                    return '{$value['show_config']['y_text']}';
                                } else if(value == {$value['show_config']['n_value']}){
                                    return '{$value['show_config']['n_text']}';
                                }
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 图标
                        case 'ico':
                            // 设置展示
                            $formatter          = <<<javascript
                            return (value) ? '<i class="'+value+'"></i>' : '';
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 视频
                        case 'video':
                            // 设置展示
                            $formatter          = <<<javascript
                            return (value) ? '<img src="/uploads/b2b2c/common/default_video.gif" data-url="'+value+'" class="js-video table_avatar_image">' : '';
javascript;
                            $value['formatter'] = $formatter;
                            break;
                        // 状态
                        case 'status':
                            $jsonData = json_encode($value['show_config'], JSON_UNESCAPED_UNICODE);
                            // 设置展示
                            $formatter          = <<<javascript
                            var html = ''
                            var _jsonData = {$jsonData};
                            $.each(_jsonData,function(i,v){
                                if(value == v.value){        
                                 html =  '<span class="label '+v.colour+'"> '+v.text+' </span>';
                                }                           
                            });          
                            return html;                
javascript;
                            $value['formatter'] = $formatter;
                            break;

                    }
                }

                // 行内编辑
                if (!empty($value['editable'])) {
                    if ($value['editable']['type'] == 'switch') {

                        // 开值
                        $value['editable']['config']['on_value'] = empty($value['editable']['config']['on_value']) ? 1 : $value['editable']['config']['on_value'];
                        // 开格式
                        $value['editable']['config']['on_text'] = empty($value['editable']['config']['on_text']) ? '<i class="fa fa-check text-success"></i>' : $value['editable']['config']['on_text'];

                        // 关值
                        $value['editable']['config']['off_value'] = empty($value['editable']['config']['off_value']) ? 0 : $value['editable']['config']['off_value'];
                        // 关格式
                        $value['editable']['config']['off_text'] = empty($value['editable']['config']['off_text']) ? '<i class="fa fa-ban text-danger"></i>' : $value['editable']['config']['off_text'];

                        // 大小 btn-group-lg=大 btn-group-sm=小 btn-group-xs=超小  $this->_vars['_editable_data']
                        $value['editable']['config']['size'] = empty($value['editable']['config']['size']) ? 'btn-group-xs' : $value['editable']['config']['size'];

                        // 设置展示
                        $formatter          = <<<javascript
                                var html = '<div class="btn-group table-btn-make-switch {$value['editable']['config']['size']}" role="group" aria-label="Small button group" data-toggle="buttons" data-index="'+index+'">';
                                    
                                    var on_active = value == '{$value['editable']['config']['on_value']}' ? 'active' : '';
                                    
                                    var on_checked = value == '{$value['editable']['config']['on_value']}' ? 'checked' : '';
                                    
                                    html += '<button class="btn btn-outline-info on-button '+on_active+'" data-name="{$value['field']}" data-value="{$value['editable']['config']['on_value']}" style="margin-right: 0px"><input type="radio" name="{$value['field']}_'+index+'" class="toggle" value="{$value['editable']['config']['on_value']}" '+on_checked+'>{$value['editable']['config']['on_text']}</button>';
                                                
                                                
                                    var off_active = value == '{$value['editable']['config']['off_value']}' ? 'active' : '';
                                    var off_checked= value == '{$value['editable']['config']['off_value']}' ? 'checked' : '';
                                    
                                    html += '<button class="btn btn-outline-info off-button '+off_active+'" data-name="{$value['field']}" data-value="{$value['editable']['config']['off_value']}" style="margin-right: 0px"><input type="radio" name="{$value['field']}_'+index+'" class="toggle" value="{$value['editable']['config']['off_value']}" '+off_active+'>{$value['editable']['config']['off_text']}</button>';
    
                                    html += '</div>';
    
                                    return html;
javascript;
                        $value['formatter'] = $formatter;

                        // 设置事件
                        $peration_events = <<<javascript
                        {
                            "click .on-button":function(ev,value,row,index){
                                  tableBtnMakeSwitch(ev,value,row,index);
                            },
                            "click .off-button":function(ev,value,row,index){
                                  tableBtnMakeSwitch(ev,value,row,index);
                            }
                        };
javascript;
                        $value['events'] = $peration_events;

                        $value['editable'] = null;
                    }
                    // 数据为空时默认怎么显示
//                    $value['editable']['emptytext'] = !empty($value['editable']['emptytext']) ? $value['editable']['emptytext'] : '';
                    // 验证
//                    $value['editable']['validate'] = !empty($value['editable']['validate']) ? $value['editable']['validate'] : null;
                }

                // 隐藏值
                if (!empty($value['hide'])) {
                    $value['formatter'] = !empty($value['events']) ? $value['hide'] . $value['formatter'] : $value['hide'];
                    $value['editable']  = null;
                }

                // 设置事件
                if (!empty($value['events'])) {
                    $this->_vars['_events'][$value['field'] . '_events'] = $value['events'];
                    $value['events']                                     = $value['field'] . '_events';
                }

                // 格式化显示
                if (!empty($value['formatter'])) {
                    $this->_vars['_formatter'][$value['field'] . '_formatter'] = <<<javascript
                        function(value,row,index){
                            {$value['formatter']}
                        };
javascript;
//                    $this->_vars['_formatter'][$value['field'] . '_formatter'] = addslashes($this->_vars['_formatter'][$value['field'] . '_formatter']);
                    $value['formatter'] = $value['field'] . '_formatter';
                }

                $resdata[] = array_merge($columns, $value);
            }
            $this->_vars['_columns'] = json_encode($resdata, JSON_UNESCAPED_UNICODE);
        }
        return $this;
    }

    /**
     * 设置行内编辑地址
     * @param $url 提交地址
     * @param array $data 附带参数
     * @author 仇仇天
     */
    public function editableUrl($url, $data = [])
    {
        if (!empty($url)) {
            $this->_vars['_editable_url']  = $url;
            $this->_vars['_editable_data'] = json_encode($data, true);
        }
    }

    /**
     * 设置表格树
     * @param array $param 参数
     * @author 仇仇天
     */
    public function setTreeTable($param = [])
    {

        // 默认参数
        $configd = [
            'treeShowField'          => null,
            'idField'                => 'id',
            'parentIdField'          => 'pid',
            'rootParentId'           => null,
            'treeColumn'             => 0, // 指明第几列数据改为树形
            'initialState'           => 'expanded', // 默认展开折叠状态 collapsed=折叠  expanded=展开
            'expanderExpandedClass'  => 'kt-font-brand fa fa-angle-double-down', // 展开时用于扩展元素的类
            'expanderCollapsedClass' => 'kt-font-brand fa fa-angle-double-right', // 折叠时用于扩展器元素的类
        ];

        $configd = array_merge($configd, $param); // 合并配置

        $this->_vars['_isTreeTable'] = true; // 设置树表格

        // 色湖之树表格相关配置
        $this->_vars['_treeTableConfig'] = <<<javascript
        
        tableinfo.treeShowField = '{$configd['treeShowField']}';
        tableinfo.idField = '{$configd['idField']}';
        tableinfo.onPostBody = function () {
            tableObj.treegrid({
                 initialState: '{$configd['initialState']}',
                 treeColumn: {$configd['treeColumn']},
                 expanderExpandedClass: '{$configd['expanderExpandedClass']}',
                 expanderCollapsedClass: '{$configd['expanderCollapsedClass']}',
                 onChange: function() {
                     tableObj.bootstrapTable('resetWidth');
                 }
            });
        };
javascript;
    }

    /**
     * 加载模板输出
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $config 模板参数
     * @return mixed
     * @author 仇仇天
     */
    public function fetch($template = '', $vars = [], $config = [])
    {
        // 设置显示模板
        if ($template != '') {
            $this->_template = $template;
        }

        // 设置传递数据
        if (!empty($vars)) {
            $this->_vars = array_merge($this->_vars, $vars);
        }

        //初始化表格设置参数
        $this->setConfig($this->_table_config);

        if (request()->isAjax()) {
            $extendDaa['total'] = $this->_vars['row_list']['total'];
            msgSuccess($msg = '', $this->_vars['row_list']['data'], $type = 'json', $header = [], $extendDaa);
        } else {
            // 实例化视图并渲染
            return parent::fetch($this->_template, $this->_vars, $config);
        }
    }
}
