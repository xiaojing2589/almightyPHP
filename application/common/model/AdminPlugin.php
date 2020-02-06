<?php
namespace app\common\model;

use think\Model;

/**
 * 插件公共模型
 * @package app\common\model
 */
class AdminPlugin extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_plugin';

    protected static $cacheName = 'plugins'; // 缓存名称

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取所有插件信息
     * @author 仇仇天
     * @param string $keyword 查找关键词
     * @param string $status 查找状态
     * @return array|bool
     */
    public function getAll($keyword = '', $status = '')
    {
        // 获取插件目录下的所有插件目录
        $dirs = array_map('basename', glob(config('plugin_path').'*', GLOB_ONLYDIR));

        if ($dirs === false || !file_exists(config('plugin_path'))) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }

        // 读取数据库插件表
        $plugins = $this->order('sort asc,id desc')->column(true, 'name');

        // 读取未安装的插件
        foreach ($dirs as $plugin) {
            if (!isset($plugins[$plugin])) {
                $plugins[$plugin]['name'] = $plugin;
                $class = get_plugin_class($plugin); // 获取插件类名
                // 插件类不存在则跳过
                if (!class_exists($class)) {
                    $plugins[$plugin]['status'] = '-2'; // 插件的入口文件不存在！
                    continue;
                }

                // 从配置文件获取
                if (is_file(config('plugin_path'). $plugin . '/config.php')) {
                    $plugin_config = include config('plugin_path'). $plugin . '/config.php';
                }

                // 插件插件信息缺失
                if (!isset($plugin_config['info']) || empty($plugin_config['info'])) {
                    // 插件信息缺失！
                    $plugins[$plugin]['status'] = '-3';
                    continue;
                }

                // 插件插件信息不完整
                if (!$this->checkInfo($plugin_config['info'])) {
                    $plugins[$plugin]['status'] = '-4';
                    continue;
                }

                // 插件未安装
                $plugins[$plugin] = $plugin_config['info'];
                $plugins[$plugin]['status'] = '-1';

            }
        }

        // 数量统计
        $total = [
            'all' => count($plugins), // 所有插件数量
            '-2'  => 0,               // 错误插件数量
            '-1'  => 0,               // 未安装数量
            '2'   => 0,               // 未启用数量
            '1'   => 0,               // 已启用数量
        ];

        // 过滤查询结果和统计数量
        foreach ($plugins as $key => $value) {
            // 统计数量
            if (in_array($value['status'], ['-2', '-3', '-4'])) {
                $total['-2']++;// 已损坏数量
            } else {
                $total[(string)$value['status']]++;
            }

            // 过滤查询
            if ($status != '') {
                if ($status == '-2') {
                    // 过滤掉非已损坏的插件
                    if (!in_array($value['status'], ['-2', '-3', '-4'])) {
                        unset($plugins[$key]);
                        continue;
                    }
                } else if ($value['status'] != $status) {
                    unset($plugins[$key]);
                    continue;
                }
            }
            if ($keyword != '') {
                if (stristr($value['name'], $keyword) === false && (!isset($value['title']) || stristr($value['title'], $keyword) === false) && (!isset($value['author']) || stristr($value['author'], $keyword) === false)) {
                    unset($plugins[$key]);
                    continue;
                }
            }
        }

        $result = ['total' => $total, 'plugins' => $plugins];

        return $result;
    }

    /**
     * 检查插件插件信息是否完整
     * @author 仇仇天
     * @param string $info 插件插件信息
     * @return bool
     */
    private function checkInfo($info = '')
    {
        $default_item = ['name','title','author','version'];
        foreach ($default_item as $item) {
            if (!isset($info[$item]) || $info[$item] == '') {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取插件配置
     * @author 仇仇天
     * @param string $name 插件名称
     * @param string $item 指定返回的插件配置项
     * @return array|mixed
     */
    public function getConfig($name = '', $item = '')
    {
        $config = cache('plugin_config_'.$name); //从缓存中获取配置
        if (!$config) {
            $config = $this->where('name', $name)->value('config');
            if (!$config) {
                return [];
            }
            $config = json_decode($config, true);
            // 非开发模式，缓存数据
            if (config('develop_mode') == 0) {
                cache('plugin_config_'.$name, $config);
            }
        }

        if (!empty($item)) {
            $items = explode(',', $item);
            if (count($items) == 1) {
                return isset($config[$item]) ? $config[$item] : '';
            }

            $result = [];
            foreach ($items as $item) {
                $result[$item] = isset($config[$item]) ? $config[$item] : '';
            }
            return $result;
        }
        return $config;
    }

    /**
     * 设置插件配置
     * @author 仇仇天
     * @param string $name 插件名.配置名
     * @param string $value 配置值
     * @return bool
     */
    public function setConfig($name = '', $value = '')
    {
        $item = '';
        if (strpos($name, '.')) {
            list($name, $item) = explode('.', $name);
        }

        // 获取缓存
        $config = cache('plugin_config_'.$name);

        if (!$config) {
            $config = $this->where('name', $name)->value('config');
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

        if (false === $this->where('name', $name)->setField('config', json_encode($config))) {
            return false;
        }

        // 非开发模式，缓存数据
        if (config('develop_mode') == 0) {
            cache('plugin_config_'.$name, $config);
        }

        return true;
    }

    /**
     * 删除类型缓存
     * @author 仇仇天
     */
    public static function delCache()
    {
        dkcache(self::$cacheName);
    }

}
