<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use app\common\model\AdminLog as LogModel;
use util\PHPZip;
use util\File;

/**
 * 系统日志控制器
 */
class Log extends Admin
{
    /**
     * 日志列表
     * @return mixed
     * @author 仇仇天
     */
    public function index()
    {
        // 初始化 表格
        $view = ZBuilder::make('tables');

        if ($this->request->isAjax()) {

            $requrest_data = input();

            // 筛选参数设置
            $where = [];

            // 快捷筛选 关键词
            if ((!empty($requrest_data['searchKeyword']) && $requrest_data['searchKeyword'] !== '') && !empty($requrest_data['searchField']) && !empty($requrest_data['searchCondition'])) {

                if ($requrest_data['searchCondition'] == 'like') {
                    $where[] = [$requrest_data['searchField'], 'like', "%" . $requrest_data['searchKeyword'] . "%"];
                } else {
                    $where[] = [$requrest_data['searchField'], $requrest_data['searchCondition'], "%" . $requrest_data['searchKeyword'] . "%"];
                }
            }

            // 执行者id
            if (!empty($requrest_data['user_id'])) {
                $where[] = ['a.user_id', '=', $requrest_data['user_id']];
            }

            // 执行者账号
            if (!empty($requrest_data['user_name'])) {
                $where[] = ['a.user_name', 'like', "%" . $requrest_data['user_name'] . "%"];
            }

            // 执行者ip
            if (!empty($requrest_data['action_ip'])) {
                $where[] = ['a.action_ip', 'like', "%" . $requrest_data['action_ip'] . "%"];
            }

            // 行为名称
            if (!empty($requrest_data['title'])) {
                $where[] = ['b.title', 'like', "%" . $requrest_data['title'] . "%"];
            }

            // 所属模块名称
            if (!empty($requrest_data['cmodule'])) {
                $where[] = ['c.name', 'like', "%" . $requrest_data['cmodule'] . "%"];
            }
            // 创建时间
            if (!empty($requrest_data['create_time[]'])) {
                $time      = $requrest_data['create_time[]'];
                $statrTime = strtotime(trim($time[0]));
                $endTime   = strtotime(trim($time[1]));
                $where[]   = ['a.create_time', '>=', $statrTime];
                $where[]   = ['a.create_time', '<=', $endTime];
            }

            //  排序字段
            $orderSort = empty($requrest_data['sort']) ? '' : $requrest_data['sort'];

            // 排序方式
            $orderMode = $requrest_data['order'];

            // 拼接排序语句
            $order = 'a.' . $orderSort . ' ' . $orderMode;

            // 拼接排序语句
            $order = empty($orderSort) ? 'a.create_time DESC' : $order;

            // 数据列表
            $data_list = LogModel::alias('a')
                ->field('a.*,b.title,c.title AS ctitle')
                ->join('admin_action b', 'a.action_name = b.name')
                ->join('admin_module c', 'b.module = c.name')
                ->where($where)
                ->order($order)
                ->paginate($requrest_data['list_rows']);

            foreach ($data_list as $key => $value) {
                $data_list[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            }

            // 设置表格数据
            $view->setRowList($data_list);
        }

        // 设置页面标题
        $view->setPageTitle('角色管理');

        // 设置搜索框
        $view->setSearch([
            ['title' => '行为名称', 'field' => 'b.title', 'condition' => 'like', 'default' => true],
            ['title' => '执行者ID', 'field' => 'a.user_id', 'condition' => 'like', 'default' => false],
            ['title' => '执行者', 'field' => 'a.user_name', 'condition' => '=', 'default' => false],
            ['title' => '执行者IP', 'field' => 'a.action_ip', 'condition' => 'like', 'default' => false]
        ]);

        // 设置高级搜索
        $view->setSeniorSearch([
            [
                'field'     => 'title',
                'name'      => 'title',
                'title'     => '行为名称',
                'form_type' => 'text'
            ],
            [
                'field'     => 'user_id',
                'name'      => 'user_id',
                'title'     => '执行者ID',
                'form_type' => 'text'
            ],
            [
                'field'     => 'user_name',
                'name'      => 'user_name',
                'title'     => '执行者',
                'form_type' => 'text'
            ],
            [
                'field'     => 'action_ip',
                'name'      => 'action_ip',
                'title'     => '执行者ip',
                'form_type' => 'text'
            ],
            [
                'field'     => 'cmodule',
                'name'      => 'cmodule',
                'title'     => '所属模块',
                'form_type' => 'text'
            ],
            [
                'field'     => 'create_time',
                'name'      => 'create_time',
                'title'     => '执行时间',
                'form_type' => 'daterange'
            ]
        ]);

        // 导出
        $view->addTopButton('export', ['url' => url('export')]);

        // 设置列
        $view->setColumn([
            [
                'field'    => 'id',
                'title'    => 'ID',
                'align'    => 'center',
                'sortable' => true,
                'width'    => 50

            ],
            [
                'field' => 'title',
                'title' => '行为名称',
                'align' => 'center'
            ],
            [
                'field' => 'user_id',
                'title' => '执行者ID',
                'align' => 'center',
                'width' => 50
            ],
            [
                'field' => 'user_name',
                'title' => '执行者',
                'align' => 'center',
            ],
            [
                'field' => 'action_ip',
                'title' => '执行者IP',
                'align' => 'center',
                'width' => 80
            ],
            [
                'field' => 'ctitle',
                'title' => '所属模块',
                'align' => 'center',
                'width' => 100
            ],
            [
                'field' => 'rq_module',
                'title' => '模块',
                'align' => 'center'
            ],
            [
                'field' => 'rq_controller',
                'title' => '控制器',
                'align' => 'center'
            ],
            [
                'field' => 'rq_action',
                'title' => '操作',
                'align' => 'center'
            ],
            [
                'field'    => 'create_time',
                'title'    => '执行时间',
                'align'    => 'center',
                'sortable' => true
            ],
            [
                'field' => 'peration',
                'title' => '操作',
                'align' => 'center',
                'type'  => 'btn',
                'btn'   => [
                    [
                        'field'      => 's',
                        'url'        => url('log/details'),
                        'query_data' => '{"field":["id"]}'
                    ]
                ]
            ]
        ]);

        // 渲染模板
        return $view->fetch();
    }

    /**
     * 日志详情
     * @param null $id 日志id
     * @return mixed
     * @author 仇仇天
     */
    public function details($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 数据列表
        $info = LogModel::alias('a')
            ->field('a.*,b.title,c.title AS ctitle')
            ->join('admin_action b', 'a.action_name = b.name')
            ->join('admin_module c', 'b.module = c.name')
            ->where(['a.id' => $id])
            ->order('id DESC')
            ->find();

        // 使用ZBuilder快速创建表单
        $form = ZBuilder::make('forms');

        // 设置页面标题
        $form->setPageTitle('日志管理 - 查看');

        // 设置返回地址
        $form->setReturnUrl(url('index'));

        // 设置隐藏表单数据
        $form->setFormHiddenData([['name' => 'id', 'value' => $id]]);

        // 隐藏提交按钮
        $form->submitButtonShow(false);

        // 隐藏重置按钮
        $form->buttonButtonShow(false);

        // 设置表单项
        $form->addFormItems([
            [
                'field'     => 'user_id',
                'name'      => 'user_id',
                'form_type' => 'static',
                'title'     => '执行者ID'
            ],
            [
                'field'     => 'user_name',
                'name'      => 'user_name',
                'form_type' => 'static',
                'title'     => '执行者'
            ],
            [
                'field'     => 'action_ip',
                'name'      => 'action_ip',
                'form_type' => 'static',
                'title'     => '执行者ip'
            ],
            [
                'field'     => 'ctitle',
                'name'      => 'ctitle',
                'form_type' => 'static',
                'title'     => '所属模块'
            ],
            [
                'field'     => 'title',
                'name'      => 'title',
                'form_type' => 'static',
                'title'     => '行为名称'
            ],
            [
                'field'     => 'rq_domain',
                'name'      => 'rq_domain',
                'form_type' => 'static',
                'title'     => '请求域名'
            ],
            [
                'field'     => 'rq_basefile',
                'name'      => 'rq_basefile',
                'form_type' => 'static',
                'title'     => '入口文件'
            ],
            [
                'field'     => 'rq_module',
                'name'      => 'rq_module',
                'form_type' => 'static',
                'title'     => '模块'
            ],
            [
                'field'     => 'rq_controller',
                'name'      => 'rq_controller',
                'form_type' => 'static',
                'title'     => '控制器'
            ],
            [
                'field'     => 'rq_action',
                'name'      => 'rq_action',
                'form_type' => 'static',
                'title'     => '方法'
            ],
            [
                'field'     => 'rq_type',
                'name'      => 'rq_type',
                'form_type' => 'static',
                'title'     => '请求类型'
            ],
            [
                'field'     => 'rq_param',
                'name'      => 'rq_param',
                'form_type' => 'array',
                'title'     => '请求参数'
            ],
            [
                'field'     => 'rq_log_info',
                'name'      => 'rq_log_info',
                'form_type' => 'array',
                'title'     => '日志详细信息'
            ]

        ]);

        // 设置表单数据
        $form->setFormdata($info);

        // 渲染页面
        return $form->fetch();
    }

    /**
     * 导出
     * @author 仇仇天
     */
    public function export()
    {

        $data = input();

        // 导出文件存储路径
        $export_path = config('export_path');

        // 临时目录名
        $temp = input('temp');
        if (empty($temp)) $temp = md5(microtime(true));

        // 存储路径
        $storage_path = $export_path . $temp;

        // 要存储的文件名
        $filename = 'log_' . $data['page'];

        // 检查目录是否存在
        if (!is_dir($storage_path)) mkdir($storage_path, 0777, true);

        // 是否下载
        if (!empty($data['download'])) {
            if ($data['page'] == 1) {
                $out = File::downloadFile($storage_path . '/' . $filename);
                File::del_dir($storage_path);
                return $out;
            } else {
                // 打包下载
                $archive = new PHPZip;
                $out     = $archive->ZipAndDownload($storage_path . '/', 'log');
                File::del_dir($storage_path);
                return $out;

            }
        }


        // 筛选参数设置
        $where = [];

        // 执行者id
        if (!empty($requrest_data['user_id'])) $where[] = ['user_id', '=', $requrest_data['user_id']];

        // 执行者账号
        if (!empty($requrest_data['user_name'])) $where[] = ['auser_name', 'like', "%" . $requrest_data['user_name'] . "%"];

        // 执行者ip
        if (!empty($requrest_data['action_ip'])) $where[] = ['action_ip', 'like', "%" . $requrest_data['action_ip'] . "%"];

        // 行为名称
        if (!empty($requrest_data['title'])) $where[] = ['title', 'like', "%" . $requrest_data['title'] . "%"];

        // 创建时间
        if (!empty($requrest_data['create_time'])) {
            $time      = explode('~', $requrest_data['create_time']);
            $statrTime = strtotime(trim($time[0]));
            $endTime   = strtotime(trim($time[1]));
            $where[]   = ['create_time', '>=', $statrTime];
            $where[]   = ['create_time', '<=', $endTime];
        }

        //  排序字段
        $orderSort = empty($requrest_data['sort']) ? '' : $requrest_data['sort'];

        // 排序方式
        $orderMode = $requrest_data['order'];

        // 拼接排序语句
        $order = 'a.' . $orderSort . ' ' . $orderMode;

        // 拼接排序语句
        $order = empty($orderSort) ? 'create_time DESC' : $order;

        // 查询
        $data_list = LogModel::field('
                                id,
                                action_name,
                                user_id,
                                user_name,
                                action_ip,
                                rq_domain,
                                rq_module,
                                rq_controller,
                                rq_action,
                                rq_type,
                                rq_param,
                                rq_log_info,
                                create_time')
            ->where($where)
            ->order($order)
            ->paginate($data['list_rows']);

        if (empty($data_list)) $this->error('导出失败');

        // 查询进度条
        $percentage = round(100 * ($data['page'] / $data_list->lastPage()), 1);

        // 设置数据
        $save_data = to_arrays($data_list)['data'];

        // 生成文件
        exportCsv($save_data, [
            'ID',
            '行为名称',
            '执行者用户id',
            '执行用户',
            '执行行为者ip',
            '请求域名',
            '请求模块',
            '请求控制器',
            '请求操作',
            '请求类型',
            '请求参数',
            '详细信息',
            '生成时间',
        ], $filename, $storage_path . '/');

        if ($data['page'] == $data_list->lastPage()) {
            $this->success('操作成功', '', ['status' => 2, 'percentage' => $percentage, 'temp' => $temp]);
        } else {
            $this->success('操作成功', '', ['status' => 1, 'percentage' => $percentage, 'temp' => $temp]);
        }
    }
}
