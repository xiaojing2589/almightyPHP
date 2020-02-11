<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminModule as AdminModuleModel;
use app\common\model\AdminMenu as AdminMenuModel;
use app\common\model\AdminRole as AdminRoleModel;

/**
 * 节点管理
 */
class Menu extends Admin
{
    /**
     * 节点首页
     * @author 仇仇天
     * @param string $group 分组
     * @return mixed
     */
    public function index($group = 'admin')
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        // 获取数据
        if ($this->request->isAjax()) {
            $data_list  = AdminMenuModel::getMenusByGroup($group);
            $data_listT = [];
            foreach ($data_list as $value) {
                $data_listT[] = $value;
            }
            $view->setRowList($data_listT); // 设置表格数据
        }

        // 设置页面标题
        $view->setPageTitle('角色管理');

        // 设置树表格
        $view->setTreeTable(['treeShowField' => 'title', 'treeColumn' => 1]);

        // 设置行内编辑地址
        $view->editableUrl(url('menu/edit', ['group' => $group]));

        // 设置标签
        $tab_list   = [];
        $list_group = AdminModuleModel::getModuleDataInfo();
        foreach ($list_group as $key => $value) {
            $tab_list[] = [
                'title' => $value['title'],
                'value' => $value['name'],
                'ico'   => $value['icon'],
                'url' => url('index', ['group' => $value['name']]),
                'default' => ($group == $value['name']) ? true : false
            ];
        }
        $tab_list[] = ['title' => '模块排序', 'value' => 'module_sort','ico'=> 'fab fa-gitter', 'url' => url('modulesort'), 'default' => false];

        // 设置分组标签
        $view->setGroup($tab_list);

        // 设置头部按钮新增
        $view->addTopButton('add', ['url' => url('menu/add', ['module' => $group])]);

        // 设置头部按钮删除
        $view->addTopButton('delete', ['url' => url('menu/del'), 'query_data' => '{"action":"delete_batch"}']);

        // 设置头部按钮启用
        $view->addTopButton('enable', ['url' => url('menu/editstatus'), 'query_data' => '{"status":1}']);

        // 设置头部按钮禁用
        $view->addTopButton('disable', ['url' => url('menu/editstatus'), 'query_data' => '{"status":0}']);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'asdasd',
                'title'    => '全选',
                'align'    => 'center',
                'checkbox' => true
            ],
            [
                'field'    => 'title',
                'title'    => '名称',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field'    => 'mark',
                'title'    => '标识',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field'    => 'url_value',
                'title'    => '链接地址',
                'editable' => [
                    'type' => 'text'
                ]
            ],
            [
                'field'     => 'icon',
                'title'     => '图标',
                'width'     => 50,
                'align'     => 'center',
                'show_type' => 'ico'
            ],
            [
                'field'    => 'url_target',
                'title'    => '打开方式',
                'align'    => 'center',
                'width'    => 80,
                'editable' => [
                    'type'   => 'select',
                    'source' => [['text' => '当前窗口', 'value' => '_self'], ['text' => '新窗口', 'value' => '_blank']],
                ]
            ],
            [
                'field'    => 'sort',
                'title'    => '排序',
                'width'    => 50,
                'align'    => 'center',
                'editable' => [
                    'type' => 'number'
                ]
            ],
            [
                'field'    => 'is_hide',
                'title'    => '是否隐藏',
                'width'    => 80,
                'align'    => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '是', 'on_value' => 1, 'off_text' => '否', 'off_value' => 0]
                ]
            ],
            [
                'field'    => 'system_menu',
                'title'    => '是否系统节点',
                'width'    => 100,
                'align'    => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '是', 'on_value' => 1, 'off_text' => '否', 'off_value' => 0]
                ]
            ],
            [
                'field'    => 'status',
                'title'    => '状态',
                'width'    => 100,
                'align'    => 'center',
                'editable' => [
                    'type'   => 'switch',
                    'config' => ['on_text' => '开启', 'on_value' => 1, 'off_text' => '关闭', 'off_value' => 0]
                ]
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'width' => 210,
                'align' => 'center',
                'type'  => 'btn',
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
                    ],
                    [
                        'field'      => 'c',
                        'text'       => '新增子节点',
                        'ico'        => 'fa fa-plus',
                        'class'      => 'btn btn-xs btn-success',
                        'url'        => url('add'),
                        'query_data' => '{"field":["id","module"]}'
                    ]
                ]
            ]
        ]);

        // 渲染模板
        return $view->fetch();
    }

    /**
     * 模块排序
     * @author 仇仇天
     */
    public function moduleSort()
    {
        // 保存模块排序
        if ($this->request->isPost()) {
            $modules = $this->request->post('sort/a');
            if ($modules) {
                $data = [];
                foreach ($modules as $key => $module) {
                    $data[] = [
                        'id'   => $module,
                        'sort' => $key + 1
                    ];
                }
                $MenuModel = new AdminMenuModel();
                if (false !== $MenuModel->saveAll($data)) {

                    // 记录日志
                    adminActionLog('admin.menu_edit');

                    // 刷新缓存
                    $this->refreshCache();

                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }

        // 配置分组信息
        $list_group = AdminModuleModel::getModuleDataInfo();
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title'] = $value['title'];
            $tab_list[$key]['ico']   = $value['icon'];
            $tab_list[$key]['url']   = url('index', ['group' => $value['name']]);
        }

        $map['status'] = 1;
        $map['pid']    = 0;
        $modules       = AdminMenuModel::where($map)->order('sort,id')->column('icon,title', 'id');
        $this->assign('modules', $modules);


        $explanation = ['按住表头可拖动节点，调整后点击【保存节点】。'];
        $this->assign('explanation', $explanation);
        $this->assign('tab_nav', ['tab_list' => $tab_list, 'curr_tab' => 'module-sort']);
        $this->assign('page_title', '节点管理');

        // 渲染模板
        return $this->fetch();
    }

    /**
     * 新增节点
     * @author 仇仇天
     * @param string $module 所属模块
     * @param string $pid 所属节点id
     * @return mixed
     */
    public function add($module = 'admin', $id = 0)
    {
        // 保存数据
        if ($this->request->isPost()) {

            // 获取提交参数
            $data = $this->request->post('', null, 'trim');

            $data['module'] = $module;

            // 验证
            $result = $this->validate($data, 'Menu');

            // 验证失败 输出错误信息
            if (true !== $result) $this->error($result);

            if ($menu = AdminMenuModel::create($data)) {

                // 记录日志
                adminActionLog('admin.menu_add');

                // 刷新缓存
                $this->refreshCache();

                $this->success('新增成功', url('menu/index', ['group' => $module]));
            } else {
                $this->error('新增失败');
            }
        }

        // 模块信息数据
        $modules    = AdminModuleModel::getModuleDataInfo();
        $module_arr = [];
        foreach ($modules AS $key => $value) {
            $module_arr[] = ['title' => $value['title'], 'value' => $value['name']];
        }

        // 节点信息数据
        $pids    = AdminMenuModel::getMenuTree(0, '', $module);
        $pid_arr = [];
        foreach ($pids AS $key => $value) {
            $pid_arr[] = ['title' => $value, 'value' => $key];
        }

        // 上级信息
        $adminMenuInfo = AdminMenuModel::where(['id' => $id])->find();
        $mark          = '';
        $url_value     = '';
        if (!empty($adminMenuInfo)) {
            $mark      = $adminMenuInfo['mark'];
            $url_value = $adminMenuInfo['url_value'];
        }



        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('节点管理 - 新增节点');

        // 设置返回地址
        $form->setReturnUrl(url('index', ['group' => $module]));

        // 设置 提交地址
        $form->setFormUrl(url('add'));

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'pid',
                'name'      => 'pid',
                'form_type' => 'select2',
                'value'     => $id,
                'title'     => '所属节点',
                'option'    => $pid_arr
            ],
            [
                'field'     => 'title',
                'name'      => 'title',
                'form_type' => 'text',
                'title'     => '节点标题'
            ],
            [
                'field'     => 'mark',
                'name'      => 'mark',
                'form_type' => 'text',
                'value'     => $mark,
                'title'     => '节点标识',
                'tips'      => '由英文字母和下划线组成，如 <code>admin_index_index</code>'
            ],
            [
                'field'     => 'url_value',
                'name'      => 'url_value',
                'form_type' => 'text',
                'value'     => $url_value,
                'title'     => '节点链接',
                'tips'      => "可留空，如果是模块链接，请填写<code>模块/控制器/操作</code>，如：<code>admin/menu/add</code>。如果是普通链接，则直接填写url地址，如：<code>http://www.xxx.com</code>"
            ],
            [
                'field'     => 'params',
                'name'      => 'params',
                'form_type' => 'text',
                'title'     => '参数',
                'tips'      => '如：a=1&b=2'
            ],
            [
                'field'     => 'url_target',
                'name'      => 'url_target',
                'form_type' => 'radio',
                'value'     => '_self',
                'title'     => '打开方式',
                'option'    => [
                    ['title' => '当前窗口', 'value' => '_self'],
                    ['title' => '新窗口', 'value' => '_blank']
                ]
            ],
            [
                'field'     => 'system_menu',
                'name'      => 'system_menu',
                'form_type' => 'switch',
                'title'     => '是否系统菜单',
                'option'    => [
                    'on_text'   => '是',
                    'on_value'  => 1,
                    'off_text'  => '否',
                    'off_value' => 0
                ]
            ],
            [
                'field'     => 'is_hide',
                'name'      => 'is_hide',
                'form_type' => 'switch',
                'title'     => '是否隐藏菜单',
                'option'    => [
                    'on_text'   => '是',
                    'on_value'  => 1,
                    'off_text'  => '否',
                    'off_value' => 0
                ]
            ],
            [
                'field'     => 'icon',
                'name'      => 'icon',
                'form_type' => 'icon',
                'title'     => '图标',
                'tips'      => '导航图标'
            ],
            [
                'field'     => 'remarks',
                'name'      => 'remarks',
                'form_type' => 'textarea',
                'title'     => '备注'
            ],
            [
                'field'     => 'sort',
                'name'      => 'sort',
                'form_type' => 'number',
                'title'     => '排序',
            ]
        ]);

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'module', 'value' => $module]]);

        return $form->fetch();
    }

    /**
     * 编辑节点
     * @author 仇仇天
     * @param int $id 节点ID
     * @return mixed
     */
    public function edit($id = 0)
    {
        if ($id === 0) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {

            // 获取提交参数
            $data = $this->request->post('', null, 'trim');

            $save_data = [];

            // 是否行内修改
            if (!empty($data['extend_field'])) {

                $save_data[$data['extend_field']] = $data[$data['extend_field']];

                // 验证
                $result = $this->validate($data, 'Menu.' . $data['extend_field']);

                // 验证提示报错
                if (true !== $result) $this->error($result);

            }

            // 普通编辑
            else {

                // 验证
                $result = $this->validate($data, 'Menu');

                // 验证提示报错
                if (true !== $result) $this->error($result);

                // 验证是否更改所属模块，如果是，则该节点的所有子孙节点的模块都要修改
                $map['id']     = $data['id'];
                $map['module'] = $data['module'];
                $info          = AdminMenuModel::where($map)->find();
                if (!$info) $this->error('未找到该节点数据');
                $save_data = $data;
            }

            $map['id'] = $data['id'];

            $module    = AdminMenuModel::where($map)->value('module');

            if (false !== AdminMenuModel::where($map)->update($save_data)) {

                // 记录行为
                adminActionLog('admin.menu_edit');

                // 刷新缓存
                $this->refreshCache();

                $this->success('编辑成功', url('menu/index', ['group' => $module]));

            } else {
                $this->error('编辑失败');
            }
        }

        // 获取数据
        $info = AdminMenuModel::get($id);

        // 节点数据
        $role_menu_data     = AdminModuleModel::getModuleDataInfo();
        $role_menu_data_arr = [];
        foreach ($role_menu_data as $role_menu_data_arr_key => $role_menu_data_arr_value) {
            $role_menu_data_arr[] = ['title' => $role_menu_data_arr_value['title'], 'value' => $role_menu_data_arr_value['name']];
        }

        // 所属节点数据
        $subnode     = AdminMenuModel::getMenuTree(0, '', $info['module']);
        $subnode_arr = [];
        foreach ($subnode as $subnode_key => $subnode_value) {
            $subnode_arr[] = ['title' => $subnode_value, 'value' => $subnode_key];
        }

        // 使用ZBuilder快速创建表单
        $form           = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('节点管理 - 编辑节点');

        // 设置返回地址
        $form->setReturnUrl(url('menu/index', ['group' => $info['module']]));

        // 设置隐藏表单
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'module',
                'name'      => 'module',
                'form_type' => 'select',
                'title'     => '所属模块',
                'option'    => $role_menu_data_arr
            ],
            [
                'field'     => 'pid',
                'name'      => 'pid',
                'form_type' => 'select',
                'title'     => '所属节点',
                'tips'      => '所属上级节点',
                'option'    => $subnode_arr
            ],
            [
                'field'     => 'title',
                'name'      => 'title',
                'form_type' => 'text',
                'title'     => '节点标题',
                'require'   =>true
            ],
            [
                'field'     => 'mark',
                'name'      => 'mark',
                'form_type' => 'text',
                'title'     => '节点标识',
                'tips'      => '由英文字母和下划线组成，如 <code>admin_index_index</code>'

            ],
            [
                'field'     => 'url_value',
                'name'      => 'url_value',
                'form_type' => 'text',
                'title'     => '节点链接',
                'tips'      => "可留空，如果是模块链接，请填写<code>模块/控制器/操作</code>，如：<code>admin/menu/add</code>。如果是普通链接，则直接填写url地址，如：<code>http://www.xxx.com</code>"
            ],
            [
                'field'     => 'params',
                'name'      => 'params',
                'form_type' => 'text',
                'title'     => '参数',
                'tips'      => '如：a=1&b=2'
            ],
            [
                'field'     => 'url_target',
                'name'      => 'url_target',
                'form_type' => 'radio',
                'title'     => '打开方式',
                'option'    => [
                    ['title' => '当前窗口', 'value' => '_self'],
                    ['title' => '新窗口', 'value' => '_blank']
                ]
            ],
            [
                'field'     => 'is_hide',
                'name'      => 'is_hide',
                'form_type' => 'switch',
                'title'     => '是否隐藏菜单',
                'option'    => [
                    'on_text'   => '是',
                    'on_value'  => 1,
                    'off_text'  => '否',
                    'off_value' => 0
                ]
            ],
            [
                'field'     => 'icon',
                'name'      => 'icon',
                'form_type' => 'icon',
                'title'     => '图标',
                'tips'      => '导航图标'
            ],
            [
                'field'     => 'sort',
                'name'      => 'sort',
                'form_type' => 'number',
                'title'     => '排序',
            ],
            [
                'field'     => 'remarks',
                'name'      => 'remarks',
                'form_type' => 'textarea',
                'title'     => '备注'
            ]
        ]);

        $form->listNumber(2);

        // 设置表单数据
        $form->setFormdata($info);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 删除节点
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

                // 获取该节点的所有后辈节点id
                $menu_childs = AdminMenuModel::getChildsId($value['id']);

                // 要删除的所有节点id
                $ids = array_merge($ids, $menu_childs);

            }
            $where = [['id', 'in', $ids]];
        }

        // 删除
        else {
            if (empty($data['id'])) $this->error('参数错误');

            // 获取该节点的所有后辈节点id
            $menu_childs = AdminMenuModel::getChildsId($data['id']);

            $ids = [];

            $ids [] = $data['id'];

            // 要删除的所有节点id
            $ids = array_merge($ids, $menu_childs);

            $where = [['id', 'in', $ids]];
        }

        if (false !== AdminMenuModel::del($where)) {

            // 记录日志
            adminActionLog('admin.menu_del');

            // 刷新缓存
            $this->refreshCache();

            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 设置配置状态：禁用、启用
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

        $result = AdminMenuModel::where($where)->setField('status', $data['status']);

        if (false !== $result) {
            adminActionLog('admin.menu_edit_status');
            $this->refreshCache();
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }

    }

    /**
     * 保存节点排序
     * @author 仇仇天
     */
    public function save()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!empty($data)) {
                $menus = $this->parseMenu($data['menus']);
                foreach ($menus as $menu) {
                    if ($menu['pid'] == 0) {
                        continue;
                    }
                    AdminMenuModel::update($menu);
                }
                // 刷新缓存
                $this->refreshCache();
                // 记录行为
                adminActionLog('admin.menu_edit');
                $this->success('保存成功');
            } else {
                $this->error('没有需要保存的节点');
            }
        }
        $this->error('非法请求');
    }

    /**
     * 递归解析节点
     * @author 仇仇天
     * @param array $menus 节点数据
     * @param int $pid 上级节点id
     * @return array 解析成可以写入数据库的格式
     */
    private function parseMenu($menus = [], $pid = 0)
    {
        $sort   = 1;
        $result = [];
        foreach ($menus as $menu) {
            $result[] = [
                'id'   => (int)$menu['id'],
                'pid'  => (int)$pid,
                'sort' => $sort,
            ];
            if (isset($menu['children'])) {
                $result = array_merge($result, $this->parseMenu($menu['children'], $menu['id']));
            }
            $sort++;
        }
        return $result;
    }

    /**
     * 刷新缓存
     * @author 仇仇天
     */
    private function refreshCache()
    {
        // 删除权限菜单缓存
        $roleData = AdminRoleModel::field('id')->select();
        foreach ($roleData as $roleValue) {
            AdminRoleModel::delCache($roleValue['id']);
        }

        // 删除节点缓存
        AdminMenuModel::delCache();
    }
}
