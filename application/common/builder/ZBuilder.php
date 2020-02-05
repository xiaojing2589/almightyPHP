<?php

namespace app\common\builder;

use app\common\controller\Common;
use think\Exception;

/**
 * 构建器
 * @package app\common\builder
 * @author 仇仇天
 */
class ZBuilder extends Common
{
    /**
     * @var array 构建器数组
     * @author 仇仇天
     */
    protected static $builder = [];

    /**
     * @var array 模板参数变量
     */
    protected static $vars = [];

    /**
     * @var string 动作
     */
    protected static $action = '';

    /**
     * 初始化
     * @author 仇仇天
     */
    public function initialize()
    {

    }

    /**
     * 创建各种builder的入口
     * @param string $type 构建器名称，'Form', 'Table', 'View' 或其他自定义构建器
     * @param string $action 动作
     * @return table\Builder|form\Builder|aside\Builder
     * @throws Exception
     * @author 仇仇天
     */
    public static function make($type = '', $action = '')
    {
        if ($type == '') {
            throw new Exception('未指定构建器名称', 8001);
        } else {
            $type = strtolower($type);
        }

        // 构造器类路径
        $class = '\\app\\common\\builder\\' . $type . '\\Builder';
        if (!class_exists($class)) {
            throw new Exception($type . '构建器不存在', 8002);
        }
        if ($action != '') {
            static::$action = $action;
        } else {
            static::$action = '';
        }

        return new $class;
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
        $vars = array_merge($vars, self::$vars);
        return parent::fetch($template, $vars, $config);
    }

    /**
     * @describe 创建表单项
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
     * @return $this
     * @author 仇仇天
     */
    public function establishFormItem($param)
    {
        $replace                = [];
        $replace                = $param;
        $replace['field']       = isset($param['field']) ? $param['field'] : '';             // 表单项字段名
        $replace['form_type']   = isset($param['form_type']) ? $param['form_type'] : '';     // 表当项类型
        $replace['name']        = isset($param['name']) ? $param['name'] : '';               // 表当项name值
        $replace['class']       = isset($param['class']) ? $param['class'] : '';             // 表当项class值
        $replace['title']       = isset($param['title']) ? $param['title'] : '';             // 表当项标题
        $replace['tips']        = isset($param['tips']) ? $param['tips'] : '';               // 表当项提示
        $replace['placeholder'] = isset($param['placeholder']) ? $param['placeholder'] : ''; // 表当项提示项
        $replace['default']     = isset($param['default']) ? $param['default'] : '';         // 表当项默认值
        $replace['type_group']  = isset($param['type_group']) ? $param['type_group'] : '';   //  类型分组
        $replace['group']       = isset($param['group']) ? $param['group'] : '';             //  分组
        $replace['value']       = isset($param['value']) ? $param['value'] : '';             //  默认值
        if ($replace['form_type'] != '' || $replace['field'] != '') {
            $item = [];
            switch ($replace['form_type']) {
                /************************************文本输入框*************************************/
                case 'text':
                    break;
                /************************************多行文本输入框*************************************/
                case 'textarea':
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
                 * ['title'=>'复选框1s','filed'=>'ss','value'=>1],
                 * ['title'=>'复选框2','value'=>2],
                 * ['title'=>'复选框3','value'=>3]
                 * ]
                 */
                case 'radio':
                    break;
                /************************************日期选择*************************************/
                case 'date':
                    // 设置日期格式
                    $item['format'] = !empty($replace['group']) ? $replace['group'] : 'yyyy-mm-dd';
                    break;
                /************************************时间选择*************************************/
                case 'time':
                    //
                    $item['meridian'] = !empty($replace['meridian']) ? $replace['meridian'] : 'false';
                    // 是否显示秒
                    $item['seconds'] = $replace['seconds'] == false ? 'false' : 'true';
                    // 秒数进步数
                    $item['second_step'] = !empty($replace['second_step']) ? $replace['second_step'] : 1;
                    // 分钟进步数
                    $item['minute_step'] = !empty($replace['minute_step']) ? $replace['minute_step'] : 1;

                    break;
                /************************************日期时间选择*************************************/
                case 'datetime':
                    // 时间格式
                    $item['format'] = !empty($replace['format']) ? $replace['format'] : 'yyyy-mm-dd hh:ii';
                    // 语言
                    $item['locale'] = !empty($replace['locale']) ? $replace['locale'] : 'zh-cn';
                    break;
                /************************************时间范围*************************************/
                case 'daterange':
                    // 时间格式
                    $item['format'] = !empty($replace['format']) ? $replace['format'] : 'YYYY-MM-DD HH:MM:SS';
                    break;
                /************************************开关*************************************/
                case 'switch':
                    // 启用文本
                    $item['on_text'] = !empty($replace['on_text']) ? $replace['on_text'] : '开';
                    // 禁用文本
                    $item['off_text'] = !empty($replace['off_text']) ? $replace['off_text'] : '关';
                    // 启用值
                    $item['on_vale'] = !empty($replace['on_vale']) ? $replace['on_vale'] : 1;
                    // 禁用值
                    $item['off_value'] = !empty($replace['off_value']) ? $replace['off_value'] : 0;
                    break;
                /************************************标签*************************************/
                case 'tags':
                    // 数据格式 default=[['title'=>'标签1','value'=>'标签1值'],['title'=>'标签2','value'=>'标签2值']]
                    break;
                /************************************下拉选择*************************************/
                case 'select':
                    // 数据格式 option=[['title'=>'选项1','value'=>'选项1值'],['title'=>'选项2','value'=>'选项2值']]
                    break;
                /************************************范围*************************************/
                case 'range':
                    // 设置滑块类型 设置为`type:”double”`时为双滑块，默认为`”single”`单滑块
                    $item['range_type'] = !empty($replace['range_type']) ? $replace['range_type'] : 'single';
                    // 滑动条最小值
                    $item['range_min'] = !empty($replace['range_min']) ? $replace['range_min'] : 0;
                    // 滑动条最大值
                    $item['range_max'] = !empty($replace['range_max']) ? $replace['range_max'] : 100;
                    // 初始值
                    $item['range_fronm'] = !empty($replace['range_fronm']) ? $replace['range_fronm'] : 0;
                    // 终点值
                    $item['range_to'] = !empty($replace['range_to']) ? $replace['range_to'] : 0;
                    break;
                /************************************数字输入*************************************/
                case 'number':
                    // 规定允许的最小值
                    $item['number_min'] = !empty($replace['number_min']) ? $replace['number_min'] : 0;
                    // 规定允许的最大值
                    $item['number_max'] = !empty($replace['number_max']) ? $replace['number_max'] : 0;
                    // 规定合法的数字间隔（如果 step="3"，则合法的数是 -3,0,3,6 等）
                    $item['number_step'] = !empty($replace['number_step']) ? $replace['number_step'] : 0;
                    break;
                /************************************图片上传*************************************/
                case 'image':
                    // 默认图片地址
                    $item['url'] = !empty($replace['url']) ? $replace['url'] : '';
                    break;
                /************************************文件上传*************************************/
                case 'file':
                    break;
                /************************************隐藏域*************************************/
                case 'hidden':
                    break;
                /************************************百度编辑器*************************************/
                case 'ueditor':
                    break;
                default:
                    return $this;
            }
            $item                                        = !empty($item) ? array_merge($item, $replace) : $replace;
            self::$vars['form_items'][$replace['field']] = $item;
        }
    }

    /**
     * @describe 一次性添加多个表单项
     * @param array $items 表单项
     * @return $this
     * @author 仇仇天
     */
    public function establishFormItems($items = [])
    {
        if (!empty($items)) {
            foreach ($items as $item) {
                call_user_func([$this, 'establishFormItem'], $item);
            }
        }
        return $this;
    }

    /**
     * @describe 设置表单项的值
     * @author 仇仇天
     */
    public function setFormValue()
    {
        // 是默认值 赋值
        foreach ($this->_vars['form_items'] as &$item) {
            switch ($item['form_type']) {
                /************************************开关*************************************/
                case 'switch':
                    $item['is_checked'] = $item['on_vale'] == $this->_vars['form_data'][$item['field']] ? 'checked' : '';
                    break;
            }
            $item['value'] = $this->_vars['form_data'][$item['field']] ? $this->_vars['form_data'][$item['field']] : $item['default'];
        }

        return $this;
    }

}
