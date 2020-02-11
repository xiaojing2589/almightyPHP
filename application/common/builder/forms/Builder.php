<?php

namespace app\common\builder\forms;

use app\common\builder\ZBuilder;
use think\Exception;
use think\facade\Env;

/**
 * 表单构建器
 * @package app\common\builder\type
 * @author 仇仇天
 */
class Builder extends ZBuilder
{
    /**
     * @var string 模板路径
     */
    private $_template = '';

    /**
     * @var array 模板变量
     */
    public $_vars = [
        '_module'                          => '',            // 当前访问的模块
        '_controller'                      => '',            // 当前访问的控制器
        '_action'                          => '',            // 当前访问的方法
        'page_title'                       => '',            // 页面标题
        'return_url'                       => [],            // 返回地址
        'explanation'                      => [],            // 页面提示信息
        'group'                            => [],            // 分组
        'type_group'                       => [],            // 分组类型
        'list_number'                      => 1,             // 表单分列数
        'list_numberL'                     => 1,             // 表单分列数量（bootstrap专用）
        'form_items'                       => [],            // 表单项目
        'form_validate'                    => [],            // 表单验证
        'form_url'                         => '',            // 表单提交地址
        'form_data'                        => [],            // 表单数据
        'form_hidden_data'                 => [],            // 表单隐藏数据
        'form_method'                      => 'POST',        // 表单请求方式
        'form_is_ajax'                     => true,          // 是否ajax方式提交
        '_js_init'                         => [],            // 初始化的js（合并输出）
        '_js_files'                        => [],            // 需要加载的js（合并输出）
        '_css_files'                       => [],            // 需要加载的css（合并输出）
        '_css_code'                        => '',            // 额外自定义css代码
        'extra_js_code'                    => '',            // 额外自定义js代码
        'extra_js_file'                    => [],            // 额外自定义js文件
        'extra_css_code'                   => '',            // 额外自定义css代码
        'extra_css_file'                   => [],            // 额外自定义css文件
        'extra_html_code'                  => '',            // 额外自定义html代码
        'extra_html_content_code'          => '',            // 额外自定义内容html代码
        'extra_html_content_form_code'     => '',            // 额外自定义表单内容html代码
        'extra_prepose_block_js_code'      => '',            // 额外前置自定义js代码块
        'extra_postposition_block_js_code' => '',            // 额外后置自定义js代码块
        'btn_extra'                        => [],            // 额外按钮
        'token_name'                       => '__token__',   // 表单令牌名称
        'token_value'                      => '',            // 表单令牌值
        '_submit_button_show'              => 1,             // 是否显示提交按钮
        '_button_button_show'              => 1,             // 是否显示重置按钮
        '_submit_button_text'              => '保存',        // 提交按钮文本
        '_button_button_text'              => '重置',        // 重置按钮文本
        'ispopup'                          => 0,             // 弹窗模式
        'popup_form'                       => '',            // 弹窗模式form名称
        'popupisjump'                      => true,          // 弹窗模式成功后是否跳转
    ];

    /**
     * 初始化
     * @author 仇仇天
     */
    public function initialize()
    {
        // 获取当前访问模块
        $this->_vars['_model'] = $this->request->module();

        // 获取当前访问控制器
        $this->_vars['_controller'] = $this->request->controller();

        // 获取当前访问控方法
        $this->_vars['_action'] = $this->request->action();

        // 设置模板文件
        $this->_template = config('app_path') . 'common/builder/forms/layout.html';

        // 当前url地址
        $this->_vars['form_url'] = $this->request->url(true);

        // 表单令牌名称
        $this->_vars['token_name'] = config('zbuilder.form_token_name');

        // 生成请求令牌
        $this->_vars['token_value'] = $this->request->token($this->_vars['token_name']);
    }

    /**
     * 设置页面标题
     * @param string $title 页面标题
     * @return $this
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
     * js合并
     * @author 仇仇天
     * @param $js_files js文件数组
     */
    public function setJsFiles($js_files)
    {
        if (!empty($js_files)) {
            $this->_vars['_js_files'] = array_merge($this->_vars['_js_files'], $js_files);
        }
    }

    /**
     * css合并
     * @author 仇仇天
     * @param $css_files css文件数组
     */
    public function setCaaFiles($css_files)
    {
        if (!empty($css_files)) {
            $this->_vars['_css_files'] = array_merge($this->_vars['_css_files'], $css_files);
        }
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
     * 设置提示信息
     * @param string $title 页面标题
     * @return $this
     * @author 仇仇天
     */
    public function setExplanation($msg = [])
    {
        $this->_vars['explanation'] = $msg;
        return $this;
    }

    /**
     * 设置提交地址
     * @param $url string 设置请求地址
     * @author 仇仇天
     */
    public function setFormUrl($url)
    {
        if (!empty($url)) {
            $this->_vars['form_url'] = $url;
        }
        return $this;
    }

    /**
     * 是否ajax提交
     * @param $is_ajax  true=是 false=否
     * @author 仇仇天
     */
    public function setFormIsAjax($is_ajax = true)
    {
        $this->_vars['form_is_ajax'] = $is_ajax;
        return $this;
    }

    /**
     * 设置请求方式
     * @param $method string POST GET
     * @author 仇仇天
     */
    public function setFormMethod($method = 'POST')
    {
        $this->_vars['form_method'] = $method;
        return $this;
    }

    /**
     * 提交按钮是否显示
     * @author 仇仇天
     */
    public function submitButtonShow($show = true)
    {
        $this->_vars['_submit_button_show'] = $show;
        return $this;
    }

    /**
     * 设置按钮是否显示
     * @author 仇仇天
     */
    public function buttonButtonShow($show = true)
    {
        $this->_vars['_button_button_show'] = $show;
        return $this;
    }

    /**
     * 提交按钮文本
     * @author 仇仇天
     */
    public function submitButtonText($text = '保存')
    {
        $this->_vars['_submit_button_text'] = $text;
        return $this;
    }

    /**
     * 设置按钮文本
     * @author 仇仇天
     */
    public function buttonButtonText($text = '重置')
    {
        $this->_vars['_button_button_text'] = $text;
        return $this;
    }

    /**
     * 添加表单项
     * @param string $type 表单项类型
     * @param string $param 表单项的一些参数具体更具表单项类型而定
     *    field           String  表单项字段名
     *    form_type       String  表单项类型
     *    class           String  表单项class值
     *    title           String  表单项标题
     *    tips            String  表单项提示
     *    placeholder     String  表单项提示项
     *    default         String  表单项默认值
     *    type_group      String  类型分组
     *    group           String  分组
     * @param $is_alone 是否独立 意思 单独返回数据 true = 是，false=否（返回对象）
     * @return $this
     * @author 仇仇天
     */
    public function addFormItem($param, $is_alone = false)
    {
        $replace                     = [];
        $replace                     = $param;
        $replace['field']            = isset($param['field']) ? $param['field'] : '';                        // 表单项字段名
        $replace['form_type']        = isset($param['form_type']) ? $param['form_type'] : '';                // 表单项类型
        $replace['name']             = isset($param['name']) ? $param['name'] : '';                          // 表单项name值
        $replace['class']            = isset($param['class']) ? $param['class'] : '';                        // 表单项class值
        $replace['title']            = isset($param['title']) ? $param['title'] : '';                        // 表单项标题
        $replace['tips']             = isset($param['tips']) ? $param['tips'] : '';                          // 表单项提示
        $replace['placeholder']      = isset($param['placeholder']) ? $param['placeholder'] : '';            // 表单项提示项
        $replace['value']            = isset($param['value']) ? $param['value'] : '';                        // 表单项默认值
        $replace['group']            = isset($param['group']) ? $param['group'] : 'default';                 // 分组
        $replace['type_group']       = isset($param['type_group']) ? $param['type_group'] : 'default';       // 类型分组
        $replace['require']          = isset($param['require']) ? $param['require'] : '';                    // 是否必填
        $replace['option']           = isset($param['option']) ? $param['option'] : [];                      // 扩展配置
        $replace['form_group_class'] = isset($param['form_group_class']) ? $param['form_group_class'] : '';  // 分组类名
        $replace['form_group_hide']  = empty($param['form_group_hide']) ? '' : 'hidden';                     // 分组隐藏

        $alone_arr = [];

        if ($replace['form_type'] != '' || $replace['field'] != '') {
            $item = [];
            switch ($replace['form_type']) {

                /************************************文本输入框*************************************/
                // disabled true=不可填写 ，空=可填写
                case 'text':
                    $item['disabled'] = !empty($replace['disabled']) ? true : false;
                    break;
                /************************************密码输入框*************************************/
                case 'password':
                    break;
                /************************************静态文本*************************************/
                case 'static':
                    break;
                /************************************多行文本输入框*************************************/
                case 'textarea':
                    $item['rows'] = !empty($replace['rows']) ? $replace['rows'] : 3; // 多少行
                    $item['cols'] = !empty($replace['cols']) ? $replace['cols'] : ''; // 多宽
                    break;
                /************************************复选框*************************************/
                // 'option'=>[['title'=>'复选框1s','filed'=>'ss','value'=>1],['title'=>'复选框2','value'=>2],['title'=>'复选框3','value'=>3]]
                case 'checkbox':
                    break;
                /************************************单选框*************************************/
                /**
                 * @参数:
                 *   option Array 必须 复选框，选项配置
                 *      title String 必须 标题
                 *      filed String 非必须 name名称
                 *      value blend 必须 值
                 * @参数格式:
                 * 'option'=>[
                 * ['title'=>'单选框1s','filed'=>'ss','value'=>1],
                 * ['title'=>'单选框2','value'=>2],
                 * ['title'=>'单选框3','value'=>3]
                 * ]
                 */
                case 'radio':
                    break;
                /************************************日期选择*************************************/
                case 'date':
                    // 设置日期格式
                    $replace['option']['format'] = !empty($replace['option']['format']) ? $replace['option']['format'] : 'yyyy-mm-dd';
                    break;
                /************************************时间选择*************************************/
                case 'time':
                    break;
                /************************************日期时间选择*************************************/
                case 'datetime':
                    // 时间格式
                    $replace['option']['format'] = !empty($replace['option']['format']) ? $replace['option']['format'] : 'yyyy-mm-dd hh:ii';
                    break;
                /************************************时间范围*************************************/
                case 'daterange':
                    // 时间格式
                    $item['format'] = !empty($replace['format']) ? $replace['format'] : 'yyyy-mm-dd';
                    break;
                /************************************开关*************************************/
                case 'switch':
                    // 启用文本
                    $replace['option']['on_text'] = !empty($replace['option']['on_text']) ? $replace['option']['on_text'] : '开';
                    // 禁用文本
                    $replace['option']['off_text'] = !empty($replace['option']['off_text']) ? $replace['option']['off_text'] : '关';
                    // 启用值
                    $replace['option']['on_value'] = !empty($replace['option']['on_value']) ? $replace['option']['on_value'] : 1;
                    // 禁用值
                    $replace['option']['off_value'] = !empty($replace['option']['off_value']) ? $replace['option']['off_value'] : 0;
                    // 大小  btn-group-lg=大 btn-group-sm=小 btn-group-xs=超小
                    $replace['option']['size'] = !empty($replace['option']['size']) ? $replace['option']['size'] : '';
                    break;
                /************************************标签*************************************/
                case 'tags':
                    // 数据格式 default=[['title'=>'标签1','value'=>'标签1值'],['title'=>'标签2','value'=>'标签2值']]
                    break;
                /************************************下拉选择*************************************/
                case 'select':
                    // 数据格式 option=[['title'=>'选项1','value'=>'选项1值'],['title'=>'选项2','value'=>'选项2值']]
                    // 是否多选
                    $item['multiple'] = empty($replace['multiple']) ? false : true;

                    // 是否有关闭按钮
                    $item['allow_clear'] = empty($replace['allow_clear']) ? true : false;

                    // 是否有分组
                    $item['optgroup'] = empty($replace['optgroup']) ? false : true;

                    // 宽度
                    $item['width'] = empty($replace['width']) ? 'resolve' : $replace['width'];

                    // 宽度
                    $item['on_select'] = empty($replace['on_select']) ? '' : $replace['on_select'];

                    break;
                /************************************下拉选择2*************************************/
                case 'select2':
                    // 数据格式 option=[['title'=>'选项1','value'=>'选项1值'],['title'=>'选项2','value'=>'选项2值']]

                    // 是否多选
                    $item['multiple'] = empty($replace['multiple']) ? false : true;

                    // 是否有关闭按钮
                    $item['allow_clear'] = empty($replace['allow_clear']) ? true : false;

                    // 是否有分组
                    $item['optgroup'] = empty($replace['optgroup']) ? false : true;

                    // 宽度
                    $item['width'] = empty($replace['width']) ? 'resolve' : $replace['width'];

                    // 宽度
                    $item['on_select'] = empty($replace['on_select']) ? '' : $replace['on_select'];
                    break;
                /************************************范围*************************************/
                case 'range':
                    // 设置滑块类型 设置为`type:”double”`时为双滑块，默认为`”single”`单滑块
                    $item['range_type'] = !empty($replace['option']['range_type']) ? $replace['option']['range_type'] : 'single';
                    // 滑动条最小值
                    $item['range_min'] = empty($replace['option']['range_min']) ? $replace['option']['range_min'] : 0;
                    // 滑动条最大值
                    $item['range_max'] = !empty($replace['option']['range_max']) ? $replace['option']['range_max'] : 100;
                    // 初始值
                    $item['range_fronm'] = !empty($replace['option']['range_fronm']) ? $replace['option']['range_fronm'] : 0;
                    // 终点值
                    $item['range_to'] = !empty($replace['option']['range_to']) ? $replace['option']['range_to'] : 0;

                    break;
                /************************************数字输入*************************************/
                case 'number':
                    // 规定允许的最小值
                    $item['min'] = !empty($replace['min']) ? $replace['min'] : false;
                    // 规定允许的最大值
                    $item['max'] = !empty($replace['max']) ? $replace['max'] : false;
                    // 规定合法的数字间隔（如果 step="3"，则合法的数是 -3,0,3,6 等）
                    $item['step'] = !empty($replace['step']) ? $replace['step'] : false;
                    break;
                /************************************图片上传*************************************/
                case 'image':
                    // 默认图片地址
                    $item['url'] = !empty($replace['url']) ? $replace['url'] : '';
                    break;
                /************************************多图片上传*************************************/
                case 'images':
                    // 默认图片地址
                    $item['url'] = !empty($replace['url']) ? $replace['url'] : '';
                    break;
                /************************************文件上传*************************************/
                case 'file':
                    // 默认文件地址
                    $item['url'] = !empty($replace['url']) ? $replace['url'] : '';
                    break;
                /************************************多文件上传*************************************/
                case 'files':
                    // 默认文件地址
                    $item['url'] = !empty($replace['url']) ? $replace['url'] : '';
                    break;
                /************************************隐藏域*************************************/
                case 'hidden':
                    break;
                /************************************百度编辑器*************************************/
                case 'ueditor':
                    break;
                /************************************取色器*************************************/
                case 'colorpicker':
                    break;
                /************************************格式化文本*************************************/
                case 'masked':
                    // 默认格式
                    $replace['option']['masked'] = !empty($replace['option']['masked']) ? $replace['option']['masked'] : '';
                    break;
                /************************************图标*************************************/
                case 'icon':
                    break;
                /************************************排序*************************************/
                case 'sort':
                    break;
                /************************************下拉联动*************************************/
                case 'linkage':
                    $replace['option']['url'] = $replace['option']['url'] ? url($replace['option']['url']) : '';
                    $replace['option']        = str_replace('"', '”', json_encode($replace['option'], JSON_UNESCAPED_UNICODE));
                    break;
                /************************************排序*************************************/
                case 'array':
                    break;
                /************************************树*************************************/
                case 'tree':

                    // 数据参数配置
                    $replace['option']['data'] = !empty($replace['option']['data']) ? $replace['option']['data'] : '{}';

                    // 展示配置
                    $replace['option']['view'] = !empty($replace['option']['view']) ? $replace['option']['view'] : '{}';

                    // 异步请求配置
                    $replace['option']['async'] = !empty($replace['option']['async']) ? $replace['option']['async'] : '{}';

                    // 复选/单选配置
                    $replace['option']['check'] = !empty($replace['option']['check']) ? $replace['option']['check'] : '{}';

                    // 事件方法
                    $replace['option']['callback'] = !empty($replace['option']['callback']) ? $replace['option']['callback'] : '{}';

                    // 唯一标识
                    $replace['option']['markname'] = !empty($replace['option']['markname']) ? $replace['option']['markname'] : '';

                    break;
                /************************************html*************************************/
                case 'html':
                    break;

                default:
                    return $this;
            }

//            $item = !empty($item) ? array_merge($item, $replace) : $replace;

            $item = !empty($item) ? array_merge($replace, $item) : $replace;

            $alone_arr = $item;

            $this->_vars['form_items'][$replace['field']] = $item;

        }
        return $is_alone ? $alone_arr : $this;
    }

    /**
     * 一次性添加多个表单项
     * @param array $items 表单项
     * @return \app\common\builder\form\Builder
     * @author 仇仇天
     */
    public function addFormItems($items = [])
    {
        if (!empty($items)) {
            foreach ($items as $item) {
                call_user_func([$this, 'addFormItem'], $item);
            }
        }
        return $this;
    }

    /**
     * 外部设置表单项调用
     * @author 仇仇天
     * @param array $items     表单数据
     * @param int $columnNumber 列数
     * @return $this
     */
    public function setFormItemsInfo($items = [],$columnNumber = 0)
    {
        $form_data = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $form_data[] = call_user_func([$this, 'addFormItem'], $item, true);
            }
        }

        $this->setFormValue($form_data);

        $this->loadMinify();

        if($columnNumber > 0){

            // 分成两列
            $this->listNumber($columnNumber);

            // 分列
            $this->setFormList();
        }

        return $this;
    }

    /**
     * 设置表单数据
     * @param array $form_data 表单数据
     * @return $this
     * @author 仇仇天
     */
    public function setFormData($form_data = [])
    {
        if (!empty($form_data)) {
            $this->_vars['form_data'] = $form_data;
        }
        return $this;
    }

    /**
     * 设置隐藏表单数据
     * @param array $form_hidden_data 隐藏表单数据 [['name'=>'name1','value'=>value1],['name'=>'name2','value'=>value2]]
     * @author 仇仇天
     */
    public function setFormHiddenData($form_hidden_data = [])
    {
        if (!empty($form_hidden_data)) {
            $this->_vars['form_hidden_data'] = $form_hidden_data;
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
     * 设置分组类型
     * @param array $param
     * [
     * [
     * 'name'=>'标题',   [必填]
     * 'field'=>字段,    [必填]
     * 'group'=>'分组值' [非必填]
     * ],
     * [
     * 'name'=>'标题',
     * 'field'=>字段,
     * 'group'=>'分组值'
     * ],
     * ]
     * @author 仇仇天
     */
    public function setTypeGroup($param = [])
    {
        foreach ($param as $value) {
            $this->_vars['type_group'][$value['field']] = $value;
        }
        return $this;
    }

    /**
     * 设置表单项的值
     * @author 仇仇天
     */
    public function setFormValue($data = [])
    {
        // 是默认值 赋值
        if (!empty($data)) {
            foreach ($data as &$item) {
                switch ($item['form_type']) {
                    /************************************开关*************************************/
                    case 'switch':
                        $item['is_checked'] = $item['on_value'] == $item['value'] ? 'checked' : '';
                        break;
                }
                return $data;
            }
        } else {

            foreach ($this->_vars['form_items'] as &$item) {
                if (isset($this->_vars['form_data'][$item['field']])) {
                    $item['value'] = $this->_vars['form_data'][$item['field']];
                }
            }
            return $this;
        }
    }

    /**
     * 设置额外自定义js代码
     * @param string $code javascript 代码
     * @return $this
     * @author 仇仇天
     */
    public function extraJsCode($code = '')
    {
        $this->_vars['extra_js_code'] = $this->_vars['extra_js_code'] . $code;
        return $this;
    }

    /**
     * 设置额外自定义js 文件
     * @param string $file css 文件
     * @return $thiss
     * @author 仇仇天
     */
    public function extraJsFile($file = [])
    {
        array_push($this->_vars['extra_js_file'], $file);
        return $this;
    }

    /**
     * 设置前置额外自定义js代码块
     * @param string $code javascript 代码
     * @return $this
     * @author 仇仇天
     */
    public function extraPreposeBlockJsCode($code = '')
    {
        $this->_vars['extra_prepose_block_js_code'] = $this->_vars['extra_prepose_block_js_code'] . $code;
        return $this;
    }

    /**
     * 设置后置额外自定义js代码块
     * @param string $code javascript 代码
     * @return $this
     * @author 仇仇天
     */
    public function extraPostpositionBlockJsCode($code = '')
    {
        $this->_vars['extra_postposition_block_js_code'] = $this->_vars['extra_postposition_block_js_code'] . $code;
        return $this;
    }

    /**
     * 设置额外自定义css代码
     * @param string $code css 代码
     * @return $this
     * @author 仇仇天
     */
    public function extraCssCode($code = '')
    {
        $this->_vars['extra_css_code'] = $this->_vars['extra_css_code'] . $code;
        return $this;
    }

    /**
     * 设置额外自定义css 文件
     * @param string $file css 文件
     * @return $thiss
     * @author 仇仇天
     */
    public function extraCssFile($file = [])
    {
        array_push($this->_vars['extra_css_file'], $file);
        return $this;
    }

    /**
     * 设置额外自定义html代码
     * @author 仇仇天
     * @param string $code html 代码
     * @return $this
     */
    public function extraHtmlCode($code = '')
    {
        $this->_vars['extra_html_code'] = $this->_vars['extra_html_code'] . $code;
        return $this;
    }

    /**
     * 设置额外自定义html内容代码
     * @author 仇仇天
     * @param string $file html 代码
     * @return $this
     */
    public function extraHtmlContentCode($code = '')
    {
        $this->_vars['extra_html_content_code'] = $this->_vars['extra_html_content_code'] . $code;
        return $this;
    }

    /**
     * 设置额外自定义html表单内容代码
     * @author 仇仇天
     * @param string $file html 代码
     * @return $this
     */
    public function extraHtmlContentFormCode($code = '')
    {
        $this->_vars['extra_html_content_form_code'] = $this->_vars['extra_html_content_form_code'] . $code;
        return $this;
    }

    /**
     * 设置表单项验证
     * @author 仇仇天
     */
    private function setFormValidate()
    {
        foreach ($this->_vars['form_items'] as $item) {
            if (!empty($item['validate'])) {
                if (!empty($item['validate']['rules']) && is_array($item['validate']['rules'])) {
                    foreach ($item['validate']['rules'] as $key => $value) {
                        $this->_vars['form_validate']['rules'][$item['field']] [$key] = $value;
                    }
                }
                if (!empty($item['validate']['messages']) && is_array($item['validate']['messages'])) {
                    foreach ($item['validate']['messages'] as $key => $value) {
                        $this->_vars['form_validate']['messages'][$item['field']] [$key] = $value;
                    }
                }
            }
        }
    }

    /**
     * 设置表单列数量
     * @author 仇仇天
     * @param int $list_number 数量
     */
    public function listNumber($list_number = 1)
    {
        $this->_vars['list_number'] = $list_number;
        return $this;
    }

    /**
     * 设置表单分列
     * @author 仇仇天
     */
    public function setFormList()
    {
        // 将表单项分割成多维数组
        $type_group = [];
        foreach ($this->_vars['form_items'] as $value){
            $type_group[$value['type_group']][] = $value;
        }

        foreach ($type_group as $key=>$type_group_value){
            $type_group[$key] = array_chunk($type_group_value, $this->_vars['list_number']);
        }

        $this->_vars['form_items'] = $type_group;

        return $this;
    }

    /**
     * 根据表单项类型，加载不同js和css文件，并合并
     * @author 仇仇天
     * @param string $type 表单项类型
     */
    public function loadMinify($type = '')
    {
        if ($type != '') {
            switch ($type) {
                /************************************取色器*************************************/
                case 'colorpicker':
                    $this->_vars['_js_files'][]  = 'colorpicker_js';
                    $this->_vars['_css_files'][] = 'colorpicker_css';
                    break;
                /************************************日期*************************************/
                case 'date':
                    $this->_vars['_js_files'][]  = 'datepicker_js';
                    $this->_vars['_css_files'][] = 'datepicker_css';
                    break;
                /************************************时间范围*************************************/
                case 'daterange':
                    $this->_vars['_js_files'][]  = 'datepicker_js';
                    $this->_vars['_css_files'][] = 'datepicker_css';
                    break;
                /************************************日期时间*************************************/
                case 'datetime':
                    $this->_vars['_js_files'][]  = 'datetimepicker_js';
                    $this->_vars['_css_files'][] = 'datetimepicker_css';
                    break;
                /************************************时间*************************************/
                case 'time':
                    $this->_vars['_js_files'][]  = 'timepicker_js';
                    $this->_vars['_css_files'][] = 'timepicker_css';
                    break;
                /************************************图片*************************************/
                case 'image':
                    /************************************多图片*************************************/
                case 'images':
                    /************************************附件*************************************/
                case 'file':
                    /************************************多附件*************************************/
                case 'files':
                    /************************************图标*************************************/
                case 'icon':
                    $this->_vars['_icon'] = '1';
                    break;
                /************************************下拉联动*************************************/
                case 'linkage':
                    $this->_vars['_js_files'][] = 'cxselect_js';
                    break;
                /************************************下拉框*************************************/
                case 'select':
                    $this->_vars['_js_files'][]  = 'select2_js';
                    $this->_vars['_css_files'][] = 'select2_css';
                    break;
                /************************************下拉框2*************************************/
                case 'select2':
                    $this->_vars['_js_files'][]  = 'select2_js';
                    $this->_vars['_css_files'][] = 'select2_css';
                    break;
                /************************************格式化*************************************/
                case 'masked':
                    $this->_vars['_js_files'][] = 'masked_inputs_js';
                    break;
                /************************************范围选择器*************************************/
                case 'range':
                    $this->_vars['_js_files'][]  = 'rangeslider_js';
                    $this->_vars['_css_files'][] = 'rangeslider_css';
                    break;
                /************************************排序*************************************/
                case 'sort':
                    $this->_vars['_js_files'][]  = 'nestable_js';
                    $this->_vars['_css_files'][] = 'nestable_css';
                    break;
                /************************************标签*************************************/
                case 'tags':
                    $this->_vars['_js_files'][]  = 'tags_js';
                    $this->_vars['_css_files'][] = 'tags_css';
                    break;
                /************************************百度编辑器*************************************/
                case 'ueditor':
                    $this->_vars['_js_files'][]  = 'ueditor_js';
                    break;
                /************************************数组*************************************/
                case 'array':
                    $this->_vars['_js_files'][]  = 'jsoneditor_js';
                    $this->_vars['_css_files'][] = 'jsoneditor_css';
                    break;
                /************************************树*************************************/
                case 'tree':
                    $this->_vars['_js_files'][]  = 'tree_js';
                    $this->_vars['_css_files'][] = 'tree_css';
                    break;
            }
        } else {
            if ($this->_vars['form_items']) {
                foreach ($this->_vars['form_items'] as &$item) {
                    // 判断是否为分组
                    if ($item['form_type'] != '') {
                        $this->loadMinify($item['form_type']);
                    }
                }
            }
        }
    }

    /**
     * 加载模板输出
     * @author 仇仇天
     * @param string $template 模板文件名
     * @param array $vars 模板输出变量
     * @param array $config 模板参数
     * @return mixed
     */
    public function fetch($template = '', $vars = [], $config = [])
    {
        // 合并传入的参数
        if (!empty($vars)) {
            $this->_vars = array_merge($this->_vars, $vars);
        }

        // 处理不同表单类型加载不同js和css
        $this->loadMinify();

        // 设置表单值
        $this->setFormValue();

        // 设置验证
        $this->setFormValidate();

        // 处理页面标题
        if ($this->_vars['page_title'] == '' && defined('ENTRANCE') && ENTRANCE == 'admin') {
            $location = get_location('', false, false);
            if ($location) {
                $curr_location             = end($location);
                $this->_vars['page_title'] = $curr_location['title'];
            }
        }

        // 另外设置模板
        if ($template != '') {
            $this->_template = $template;
        }

        // 处理js合并的参数 去重
        if (!empty($this->_vars['_js_files'])) {
            $this->_vars['_js_files'] = array_unique($this->_vars['_js_files']);
            sort($this->_vars['_js_files']);
        }

        // 处理css合并的参数 去重
        if (!empty($this->_vars['_css_files'])) {
            $this->_vars['_css_files'] = array_unique($this->_vars['_css_files']);
            sort($this->_vars['_css_files']);
        }

        // 处理初始化 去重
        if (!empty($this->_vars['_js_init'])) {
            $this->_vars['_js_init'] = array_unique($this->_vars['_js_init']);
            sort($this->_vars['_js_init']);
            $this->_vars['_js_init'] = json_encode($this->_vars['_js_init']);
        }

        // 表单分列
        $this->setFormList();

        // 表单分列数量
        $this->_vars['list_numberL'] = 12 / $this->_vars['list_number'];

        // 处理额外按钮
        $this->_vars['btn_extra'] = implode(' ', $this->_vars['btn_extra']);

        // 弹窗模式展示
        if(!empty(input('ispopup'))){

            $this->_vars['ispopup'] = 1;

            $this->_vars['popup_form'] = input('popup_form');

            $this->_vars['popupisjump'] = input('popupisjump');

            $this->assign('_admin_base_layout', Env::get('app_path').'admin/view/ajaxformlayout.html');
        }

//        predBUG($this->_vars['_js_files']);

        // 实例化视图并渲染
        return parent::fetch($this->_template, $this->_vars, $config);
    }
}
