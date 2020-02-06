<?php
namespace plugins\HelloWorld\controller;

use app\common\builder\ZBuilder;
use app\common\controller\Common;
use plugins\HelloWorld\model\HelloWorld;
use plugins\HelloWorld\validate\HelloWorld as HelloWorldValidate;

/**
 * 插件后台管理控制器
 * @package plugins\HelloWorld\controller
 */
class Admin extends Common
{
    /**
     * 插件管理页
     * @author 仇仇天
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        // 筛选参数
        $search_field     = input('param.searchField/s', '', 'trim'); // 关键词搜索字段名
        $keyword          = input('param.searchKeyword/s', '', 'trim'); // 搜索关键词
        $map   = [];// 筛选参数设置
        if ($search_field != '' && $keyword !== '')$map[] = [$search_field, 'like', "%".$keyword."%"];// 普通搜索筛选
        $view = ZBuilder::make('tables'); // 初始化 表格
        $view->setPageTitle('配置列表'); // 设置页面标题
        $view->setSearch([
            ['title' => '标题', 'field' => 'title', 'default' => false],
            ['title' => '名称', 'field' => 'name', 'default' => true]
        ]); // 设置搜索框
        $view->setReturnUrl(url('admin/plugin/index')); // 设置返回地址
        $view->addTopButton('add',['url'=>url('config/add')]); // 新增
        $view->addTopButton('delete',['url'=>url('config/del'),'query_data'=>'{"action":"delete_batch"}']); // 删除
        $view->addTopButton('enable',['url'=>url('config/editstatus'),'query_data'=>'{"action":"enable"}']); // 启用
        $view->addTopButton('disable',['url'=>url('config/editstatus'),'query_data'=>'{"action":"disable"}']); //  禁用
        $view->editableUrl(url('config/edit'));// 设置行内编辑地址
        $view->setColumn([
            [
                'field' => 'asdasd',
                'title' => '全选',
                'align'=>'center',
                'checkbox'=>true
            ],
            [
                'field' => 'said',
                'title' => '名言',
                'align'=>'center',
                'editable'=>[
                    'type'=>'text',
                ]
            ],
            [
                'field' => 'name',
                'title' => '出处',
                'align'=>'center',
                'editable'=>[
                    'type'=>'text'
                ]
            ],
            [
                'field' => 'status',
                'title' => '状态',
                'align'=>'center',
                'editable'=>[
                    'type'=>'switch',
                    'config'=>['on_text'=>'启用','on_value'=>1,'off_text'=>'禁用','off_value'=>2]
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type' => 'btn',
                'btn'=>[
                    [
                        'field'=>'d',
                        'confirm'=>'确认删除',
                        'query_jump'=>'ajax',
                        'url'=>url('config/del'),
                        'query_data'=>'{"field":["id"],"extentd_field":{"action":"delete"}}',
                        'query_type'=>'post'
                    ],
                    [
                        'field'=>'u',
                        'url'=>url('config/edit'),
                        'query_data'=>'{"field":["id"]}'
                    ]
                ]
            ]
        ]);// 设置列
        if ($this->request->isAjax()) {

            $requrest_data = $this->request->request();

            // 名称筛选
            if (!empty($requrest_data['name']))$map[] = ['name', 'like', "%".$requrest_data['name']."%"];

            // 标题筛选
            if (!empty($requrest_data['title']))$map[] = ['title', 'like', "%".$requrest_data['title']."%"];

            // 状态筛选
            if (!empty($requrest_data['status']))$map[] = ['status', '=', $requrest_data['status']];

            // 每页显示多少条
            $list_rows = input('list_rows');

            // 数据列表
            $data_list = HelloWorld::where($map)->order('id ASC')->paginate($list_rows);

            // 配置类型数据
            $form_item_type = json_decode(config('form_item_type'),true);


            $view->setRowList($data_list);// 设置表格数据
        }
        return $view->fetch();// 渲染模板
    }

    /**
     * 新增
     * @author 仇仇天
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            // 验证数据
            $result = $this->validate($data, [
                'name|出处' => 'require',
                'said|名言' => 'require',
            ]);
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }

            // 插入数据
            if (HelloWorld::create($data)) {
                $this->success('新增成功', cookie('__forward__'));
            } else {
                $this->error('新增失败');
            }
        }

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('新增')
            ->addFormItem('text', 'name', '出处')
            ->addFormItem('text', 'said', '名言')
            ->fetch();
    }

    /**
     * 编辑
     * @author 仇仇天
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 使用自定义的验证器验证数据
            $validate = new HelloWorldValidate();
            if (!$validate->check($data)) {
                // 验证失败 输出错误信息
                $this->error($validate->getError());
            }

            // 更新数据
            if (HelloWorld::update($data)) {
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }

        $id = input('param.id');

        // 获取数据
        $info = HelloWorld::get($id);

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('编辑')
            ->addFormItem('hidden', 'id')
            ->addFormItem('text', 'name', '出处')
            ->addFormItem('text', 'said', '名言')
            ->setFormData($info)
            ->fetch();
    }

    /**
     * 插件自定义方法
     * @author 仇仇天
     * @return mixed
     * @throws \think\Exception
     */
    public function testTable()
    {
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('table')
            ->setPageTitle('插件自定义方法(列表)')
            ->setSearch(['said' => '名言', 'name' => '出处'])
            ->addColumn('id', 'ID')
            ->addColumn('said', '名言')
            ->addColumn('name', '出处')
            ->addColumn('status', '状态', 'switch')
            ->addColumn('right_button', '操作', 'btn')
            ->setTableName('plugin_hello')
            ->fetch();
    }

    /**
     * 插件自定义方法
     * 这里的参数是根据插件定义的按钮链接按顺序设置
     * @param string $id
     * @param string $table
     * @param string $name
     * @param string $age
     * @author 仇仇天
     * @return mixed
     * @throws \think\Exception
     */
    public function testForm($id = '', $table = '', $name = '', $age = '')
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            halt($data);
        }

        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('插件自定义方法(表单)')
            ->addFormItem('text', 'name', '出处')
            ->addFormItem('text', 'said', '名言')
            ->fetch();
    }

    /**
     * 自定义页面
     * @author 仇仇天
     * @return mixed
     */
    public function testPage()
    {
        // 1.使用默认的方法渲染模板，必须指定完整的模板文件名（包括模板后缀）
//        return $this->fetch(config('plugin_path'). 'HelloWorld/view/index.html');

        // 2.使用已封装好的快捷方法，该方法只用于加载插件模板
        // 如果不指定模板名称，则自动加载插件view目录下与当前方法名一致的模板
        return $this->pluginView();
//         return $this->pluginView('index'); // 指定模板名称
//         return $this->pluginView('', 'tpl'); // 指定模板后缀
    }
}
