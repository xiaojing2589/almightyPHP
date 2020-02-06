<?php

namespace app\common\model;

use think\Model;
use think\facade\Env;

/**
 * 模块模型
 * @package app\common\model
 */
class AdminModule extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_module';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有模块信息
     * @param string $keyword 查找关键词
     * @param string $status 查找状态
     * @return array|bool
     * @author 仇仇天
     */
    public function getAll($keyword = '', $status = '')
    {
        $result = self::getModuleDataInfo(); // 数据库模块信息
        $dirs = array_map('basename', glob(config('app_path') . '*', GLOB_ONLYDIR)); // 获取应用目录所有的模块目录
        if ($dirs === false || !file_exists(config('app_path'))) {
            $this->error = '模块目录不可读或者不存在';
            return false;
        }
        $except_module = config('system.except_module');// 不读取模块信息的目录
        $dirs          = array_diff($dirs, $except_module);// 正常模块(包括已安装和未安装)
        $modules       = $this->order('sort asc,id desc')->column(true, 'name'); // 读取数据库模块表
        // 读取未安装的模块
        foreach ($dirs as $module) {
            if (!isset($modules[$module])) {
                $info = self::getInfoFromFile($module);   // 获取模块信息
                $modules[$module]['name'] = $module;

                // 模块模块信息缺失
                if (empty($info)) {
                    $modules[$module]['status'] = '-2';
                    continue;
                }

                // 模块模块信息不完整
                if (!$this->checkInfo($info['info'])) {
                    $modules[$module]['status'] = '-3';
                    continue;
                }
                // 模块未安装
                $modules[$module]           = $info['info'];
                $modules[$module]['status'] = '-1'; // 模块未安装
            }
        }
        // 数量统计
        $total = [
            'all' => count($modules), // 所有模块数量
            '-2'  => 0,               // 已损坏数量
            '-1'  => 0,               // 未安装数量
            '2'   => 0,               // 已禁用数量
            '1'   => 0,               // 已启用数量
        ];

        // 过滤查询结果和统计数量
        foreach ($modules as $key => $value) {
            // 统计数量
            if (in_array($value['status'], ['-2', '-3'])) {
                // 已损坏数量
                $total['-2']++;
            } else {
                $total[(string)$value['status']]++;
            }

            // 过滤查询
            if ($status != '') {
                if ($status == '-2') {
                    // 过滤掉非已损坏的模块
                    if (!in_array($value['status'], ['-2', '-3'])) {
                        unset($modules[$key]);
                        continue;
                    }
                } else if ($value['status'] != $status) {
                    unset($modules[$key]);
                    continue;
                }
            }
            if ($keyword != '') {
                if (stristr($value['name'], $keyword) === false && (!isset($value['title']) || stristr($value['title'], $keyword) === false) && (!isset($value['author']) || stristr($value['author'], $keyword) === false)) {
                    unset($modules[$key]);
                    continue;
                }
            }
        }

        $result = ['total' => $total, 'modules' => $modules];

        return $result;
    }

    /**
     * 从文件获取模块信息
     * @param string $name 模块名称
     * @return array|mixed
     * @author 仇仇天
     */
    public static function getInfoFromFile($name = '')
    {
        $info = [];
        if ($name != '') {
            // 从配置文件获取
            if (is_file(Env::get('app_path') . $name . '/config.php')) {
                $info = include Env::get('app_path') . $name . '/config.php';
            }
        }
        return $info;
    }

    /**
     * 检查模块模块信息是否完整
     * @author 仇仇天
     * @param string $info 模块模块信息
     * @return bool
     */
    private function checkInfo($info = '')
    {
        $default_item = ['name', 'title', 'author', 'version'];
        foreach ($default_item as $item) {
            if (!isset($info[$item]) || $info[$item] == '') {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取模型配置信息
     * @param string $name 插件名.配置名
     * @param string $value 配置值
     * @return bool
     * @author 仇仇天
     */
    public static function setConfig($name = '', $value = '')
    {
        $item = '';
        if (strpos($name, '.')) {
            list($name, $item) = explode('.', $name);
        }

        // 获取缓存
        $config = cache('module_config_' . $name);

        if (!$config) {
            $config = self::where('name', $name)->value('config');
            if (!$config) {
                return false;
            }

            $config = json_decode($config, true);
        }

        if ($item === '') {
            // 批量更新
            if (!is_array($value) || empty($value)) {
                // 值的格式错误，必须为数组
                return false;
            }

            $config = array_merge($config, $value);
        } else {
            // 更新单个值
            $config[$item] = $value;
        }

        if (false === self::where('name', $name)->setField('config', json_encode($config))) {
            return false;
        }

        // 非开发模式，缓存数据
        if (config('develop_mode') == 0) {
            cache('module_config_' . $name, $config);
        }

        return true;
    }

    /**
     * @describe 从文件获取模块菜单
     * @param string $name 模块名称
     * @return array|mixed
     * @author 仇仇天
     */
    public static function getMenusFromFile($name = '')
    {
        $menus = [];
        if ($name != '' && is_file(Env::get('app_path') . $name . '/menus.php')) {
            // 从菜单文件获取
            $menus = include Env::get('app_path') . $name . '/menus.php';
        }
        return $menus;
    }

    /**
     * 获取所有的模块信息(取的缓存)
     * @param string $status
     * @author 仇仇天
     */
    public static function getModuleDataInfo($mark = '')
    {
        $moduleDataInfo = rcache('system_module');
        $moduleInfo     = [];
        if (!empty($mark)) {
            foreach ($moduleDataInfo as $key => $value) {
                if ($mark == $key) $moduleInfo = $value;
                if ($value['id'] == $mark) $moduleInfo = $value;
            }
        } else {
            foreach ($moduleDataInfo as $key => $value) {
                $moduleInfo[] = $value;
            }
        }
        return $moduleInfo;
    }

    /**
     * 获取模块配置文件信息
     * @author 仇仇天
     * @param string $name 模块名称
     * @return array|mixed
     */
    public static function getConfgFile($name = '')
    {
        $info = [];
        if ($name != '') {
            // 从配置文件获取
            if (is_file(config('app_path') . $name . '/config.php')) $info = include config('app_path') . $name . '/config.php';
        }
        return $info;
    }
}