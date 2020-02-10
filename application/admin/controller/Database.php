<?php
namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\builder\ZBuilder;
use util\Database as DatabaseModel;
use think\Db;

/**
 * 数据库管理
 * @package app\admin\controller
 */
class Database extends Admin
{
    /**
     * 数据库管理
     * @author 仇仇天
     * @param string $group 分组
     * @return mixed
     */
    public function index($group = 'export')
    {
        // 配置分组信息
        $list_group = [
            [
                'title'=>'备份数据库',
                'value'=>'export',
                'url'=>url('index', ['group' =>'export']),
                'ico'=>'fa fa-database',
                'default'=>($group == 'export') ? true : false],
            [
                'title'=>'还原数据库',
                'ico'=>'fa fa-database',
                'value'=>'import',
                'url'=>url('index', ['group' =>'import']),
                'default'=>($group == 'import') ? true : false
            ]
        ];

        switch ($group) {
            // 备份数据库
            case 'export':

                // 初始化 表格
                $view = ZBuilder::make('tables');

                // 加载数据
                if ($this->request->isAjax()) {

                    // 获取数据
                    $requrest_data = input();

                    // 名称筛选
                    if (!empty($requrest_data['name']))$map[] = ['name', 'like', "%".$requrest_data['name']."%"];

                    // 标题筛选
                    if (!empty($requrest_data['title']))$map[] = ['title', 'like', "%".$requrest_data['title']."%"];

                    // 状态筛选
                    if (!empty($requrest_data['status']))$map[] = ['status', '=', $requrest_data['status']];

                    // 数据列表
                    $data_list = Db::query("SHOW TABLE STATUS");

                    // 设置表格数据
                    $view->setRowList($data_list);
                }

                // 设置页面标题
                $view->setPageTitle('备份数据库管理');

                // 标签分组信息
                $view->setGroup($list_group);

                // 设置头部按钮 立即备份
                $view->addTopButton('custom',[
                    'title'=>'立即备份',
                    'icon'  => 'fa fa-fw fa-copy',
                    'class' => 'btn btn-primary ajax-post confirm',
                    'url'=>url('export'),
                    'jump_way'=>'ajax',
                    'query_type'=>'post',
                    'disabled'=>false,
                    'batch'=>true
                ]);

                // 设置头部按钮 优化表
                $view->addTopButton('custom',[
                    'title' => '优化表',
                    'icon'  => 'fa fa-fw fa-cogs',
                    'class' => 'btn btn-brand',
                    'url'=>url('optimize'),
                    'query_data'=>'{"action":"optimization_batch"}',
                    'jump_way'=>'ajax',
                    'query_type'=>'post',
                    'disabled'=>false,
                    'batch'=>true
                ]);

                // 设置头部按钮 修复表
                $view->addTopButton('custom',[
                    'title' => '修复表',
                    'icon'  => 'fa fa-fw fa-wrench',
                    'class' => 'btn btn-success',
                    'url'=>url('repair'),
                    'query_data'=>'{"action":"repair_batch"}',
                    'jump_way'=>'ajax',
                    'query_type'=>'post',
                    'disabled'=>false,
                    'batch'=>true
                ]);

                // 设置列
                $view->setColumn([
                    [
                        'field' => 'asdasd',
                        'title' => '全选',
                        'align'=>'center',
                        'checkbox'=>true
                    ],
                    [
                        'field' => 'Name',
                        'title' => '表名',
                        'align'=>'center'
                    ],
                    [
                        'field' => 'Rows',
                        'title' => '行数',
                        'align'=>'center'
                    ],
                    [
                        'field' => 'Data_length',
                        'title' => '大小',
                        'align'=>'center',
                        'show_type'=>'byte'
                    ],
                    [
                        'field' => 'Data_free',
                        'title' => '冗余',
                        'align'=>'center',
                        'show_type'=>'byte'
                    ],
                    [
                        'field' => 'Comment',
                        'title' => '备注',
                        'align'=>'center'
                    ],
                    [
                        'field' => 'peration',
                        'title' => '操作',
                        'align' => 'center',
                        'type' => 'btn',
                        'btn'=>[
                            [
                                'field'=>'c',
                                'text'=>'优化表',
                                'ico'=>'fa fa-fw fa-cogs',
                                'class' => 'btn btn-xs btn-brand',
                                'url'=>url('optimize'),
                                'query_data'=>'{"field":["Name"],"extentd_field":{"action":"optimization"}}',
                                'query_jump'=>'ajax',
                                'query_type'=>'post'
                            ],
                            [
                                'field'=>'c',
                                'text'=>'修复表',
                                'ico'=>'fa fa-fw fa-wrench',
                                'class' => 'btn btn-xs btn-success',
                                'url'=>url('repair'),
                                'query_data'=>'{"field":["Name"],"extentd_field":{"action":"repair"}}',
                                'query_jump'=>'ajax',
                                'query_type'=>'post'
                            ]
                        ]
                    ]
                ]);

                // 设置页面
                return $view->fetch();

                break;
            // 还原数据库
            case 'import':

                // 初始化 表格
                $view = ZBuilder::make('tables');

                // 设置页面标题
                $view->setPageTitle('备份数据库列表');

                // 标签分组信息
                $view->setGroup($list_group);

                // 设置列
                $view->setColumn([
                    [
                        'field' => 'name',
                        'title' => '备份名称',
                        'align'=>'center',
                        'show_type'=>'datetime',
                        'show_config'=>['format'=>'YYYYMMDD-HHmmss']
                    ],
                    [
                        'field' => 'part',
                        'title' => '卷数',
                        'align'=>'center'
                    ],
                    [
                        'field' => 'compress',
                        'title' => '压缩',
                        'align'=>'center'
                    ],
                    [
                        'field' => 'size',
                        'title' => '数据大小',
                        'align'=>'center',
                        'show_type'=>'byte'
                    ],
                    [
                        'field' => 'time',
                        'title' => '备份时间',
                        'align'=>'center',
                        'show_type'=>'datetime'
                    ],
                    [
                        'field' => 'peration',
                        'title' => '操作',
                        'align' => 'center',
                        'type' => 'btn',
                        'btn'=>[
                            [
                                'field'=>'c',
                                'text'=>'还原',
                                'ico'=>'fa fa-fw fa-reply',
                                'class' => 'btn btn-xs btn-success',
                                'url'=>url('import'),
                                'query_data'=>'{"field":["name"]}',
                                'query_jump'=>'ajax',
                                'query_type'=>'post'
                            ],
                            [
                                'field'=>'d',
                                'confirm'=>'确认删除',
                                'query_jump'=>'ajax',
                                'url'=>url('database/delete'),
                                'query_data'=>'{"field":["name"]}',
                                'query_type'=>'post'
                            ]
                        ]
                    ]
                ]);

                if ($this->request->isAjax()) {

                    // 列出备份文件列表
                    $path = config('data_backup_path');
                    if(!is_dir($path)){
                        mkdir($path, 0755, true);
                    }
                    $path = realpath($path);
                    $flag = \FilesystemIterator::KEY_AS_FILENAME;
                    $glob = new \FilesystemIterator($path, $flag);

                    $data_list = [];
                    foreach ($glob as $name => $file) {
                        if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                            $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                            $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                            $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                            $part = $name[6];

                            if(isset($data_list["{$date} {$time}"])){
                                $info = $data_list["{$date} {$time}"];
                                $info['part'] = max($info['part'], $part);
                                $info['size'] = $info['size'] + $file->getSize();
                            } else {
                                $info['part'] = $part;
                                $info['size'] = $file->getSize();
                            }
                            $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                            $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                            $info['time']     = strtotime("{$date} {$time}");
                            $info['name']     = $info['time'];

                            $data_list["{$date} {$time}"] = $info;
                        }
                    }

                    $data_list = !empty($data_list) ? array_values($data_list) : $data_list;
                    // 设置表格数据
                    $view->setRowList($data_list);
                }
                return $view->fetch();
                break;
        }
    }

    /**
     * 备份数据库
     * @author 仇仇天
     * @param integer $start 起始行数
     */
    public function export($start = 0)
    {
        $data = $this->request->post();

        if(empty($data['batch_data']))$this->error('请选择要优化的表');


        $path = config('data_backup_path'); // 设置保存路径

        // 创建目录
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }

        // 读取备份配置
        $config = array(
            'path'     => realpath($path) . DIRECTORY_SEPARATOR,
            'part'     => config('data_backup_part_size'),
            'compress' => config('data_backup_compress'),
            'level'    => config('data_backup_compress_level'),
        );

        // 检查是否有正在执行的任务
        $lock = "{$config['path']}backup.lock";
        if(is_file($lock)){
            $this->error('检测到有一个备份任务正在执行，请稍后再试！');
        } else {
            // 创建锁文件
            file_put_contents($lock, $this->request->time());
        }

        // 检查备份目录是否可写
        is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');

        // 生成备份文件信息
        $file = array(
            'name' => date('Ymd-His', $this->request->time()),
            'part' => 1,
        );

        // 创建备份文件
        $Database = new DatabaseModel($file, $config);
        if(false !== $Database->create()){

            // 备份指定表
            foreach ($data['batch_data']  as $table) {
                $start = $Database->backup($table['Name'], $start);
                while (0 !== $start) {
                    // 出错
                    if (false === $start) {
                        $this->error('备份出错！');
                    }
                    $start = $Database->backup($table['Name'], $start[0]);
                }
            }

            // 备份完成，删除锁定文件
            unlink($lock);

            // 记录行为
            adminActionLog('admin.database_export');

            $this->success('备份完成！');
        } else {
            $this->error('初始化失败，备份文件创建失败！');
        }
    }

    /**
     * 还原数据库
     * @author 仇仇天
     * @param int $time 文件时间戳
     */
    public function import($name = 0)
    {
        if ($name === 0) $this->error('参数错误！');

        // 初始化
        $name  = date('Ymd-His', $name) . '-*.sql*';
        $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        $list  = array();
        foreach($files as $name){
            $basename = basename($name);
            $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $list[$match[6]] = array($match[6], $name, $gz);
        }
        ksort($list);

        // 检测文件正确性
        $last = end($list);
        if(count($list) === $last[0]){
            foreach ($list as $item) {
                $config = [
                    'path'     => realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR,
                    'compress' => $item[2]
                ];
                $Database = new DatabaseModel($item, $config);
                $start = $Database->import(0);

                // 循环导入数据
                while (0 !== $start) {
                    if (false === $start) { // 出错
                        $this->error('还原数据出错！');
                    }
                    $start = $Database->import($start[0]);
                }
            }
            // 记录行为
            adminActionLog('admin.database_import');
            $this->success('还原完成！');
        } else {
            $this->error('备份文件可能已经损坏，请检查！');
        }
    }

    /**
     * 优化表
     * @author 仇仇天
     * @param null|string|array $ids 表名
     */
    public function optimize()
    {
        $data = $this->request->post();

        if (empty($data['action'])) $this->error('参数错误');

        // 单独
        if($data['action'] == 'optimization'){
            if(empty($data['Name']))$this->error('请选择要优化的表');
            $result =   $list = Db::query("OPTIMIZE TABLE `{$data['Name']}`");;
            if (false !== $result) {
                // 记录行为
                adminActionLog('admin.database_optimize');
                $this->success("数据表'{$data['Name']}'优化完成！");
            }else{
                $this->error("数据表'{$data['Name']}'优化出错请重试！");
            }
        }

        // 批量
        else if($data['action'] == 'optimization_batch'){
            if(empty($data['batch_data']))$this->error('请选择要优化的表');
            $ids = [];
            foreach ($data['batch_data']  as $value){
                $ids[] = $value['Name'];
            }
            $tables = implode('`,`', $ids);
            $result = Db::query("OPTIMIZE TABLE `{$tables}`");
            if (false !== $result) {
                // 记录行为
                adminActionLog('admin.database_optimize');
                $this->success("数据表优化完成！");
            }else{
                $this->error("数据表优化出错请重试！");
            }
        }

    }

    /**
     * 修复表
     * @author 仇仇天
     * @param null|string|array $ids 表名
     */
    public function repair()
    {

        $data = $this->request->post();

        if (empty($data['action'])) $this->error('参数错误');

        // 单独
        if($data['action'] == 'repair'){
            if(empty($data['Name']))$this->error('请指定要修复的表');
            $result =   $list = Db::query("REPAIR TABLE `{$data['Name']}`");;
            if (false !== $result) {
                // 记录行为
                adminActionLog('admin.database_repair');
                $this->success("数据表'{$data['Name']}'修复完成！");
            }else{
                $this->error("数据表'{$data['Name']}'修复出错请重试！");
            }
        }

        // 批量
        else if($data['action'] == 'repair_batch'){
            if(empty($data['batch_data']))$this->error('请指定要修复的表');
            $ids = [];
            foreach ($data['batch_data']  as $value){
                $ids[] = $value['Name'];
            }
            $tables = implode('`,`', $ids);
            $result = Db::query("REPAIR TABLE `{$tables}`");
            if (false !== $result) {
                // 记录行为
                adminActionLog('admin.database_repair');
                $this->success("数据表修复完成！");
            }else{
                $this->error("数据表修复出错请重试！");
            }
        }

    }

    /**
     * 删除备份文件
     * @author 仇仇天
     * @param int $ids 备份时间
     * @return mixed
     */
    public function delete($name= 0)
    {
        if ($name == 0) $this->error('参数错误！');
        $name  = date('Ymd-His', $name) . '-*.sql*';
        $path  = realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR . $name;
        array_map("unlink", glob($path));
        if(count(glob($path))){
            $this->error('备份文件删除失败，请检查权限！');
        } else {
            // 记录行为
            adminActionLog('admin.database_backup_delete');
            $this->success('备份文件删除成功！');
        }
    }
}
