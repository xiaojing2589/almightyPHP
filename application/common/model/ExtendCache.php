<?php

namespace app\common\model;

use app\common\model\AdminHookPlugin as HookPluginModel;
use app\common\model\AdminHook as HookModel;
use app\common\model\AdminPlugin as PluginModel;

use app\common\model\AdminAction as AdminActionModel;
use app\common\model\AdminConfig as AdminConfigModel;
use app\common\model\AdminModule as AdminModuleModel;
use app\common\model\AdminRole as AdminRole;
use app\common\model\AdminMenu as AdminMenuModel;
use app\common\model\AdminConfigGroup as AdminConfigGroup;
use app\common\model\PStorage as PStorageModel;
use app\common\model\PSms as PSmsModel;
use app\common\model\PSmsTpl as PSmsTplModel;

use think\Model;
use think\facade\Cache;
use think\Db;

/**
 * 缓存扩展
 * @package app\common\model
 */
class ExtendCache extends Model
{
    /**
     * 获取缓存配置
     * @param $idORfield 缓存id/缓存字段名
     * @return array
     * @author 仇仇天
     */
    public static function getCacheConfig($idORfield)
    {
        $res  = [];
        $data = self::extend_cache();
        foreach ($data as $key => $value) {
            if ($value['field_name'] == $idORfield || $idORfield == $value['id']) {
                if (!empty($value['project_config'])) {
                    $res = $value['project_config'];
                }
            }
        }
        return $res;
    }

    /**
     * 获取缓存配置数据数据
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @return array|mixed
     * @author 仇仇天
     */
    public static function extend_cache($Identification = '', $extend_param = [])
    {
        $extend_cache = Cache::connect(config('system.extend_cache'))->get('extend_cache');
        // 如果不存在
        $resData = [];
        if ($extend_cache === false) {
            $data = Db::name('extend_cache')->select();
            foreach ($data as $key => $value) {
                $project_config = json_decode($value['project_config'], true);
                if (!empty($project_config)) {
                    $value['project_config'] = $project_config;
                }
                $resData[$value['field_name']] = $value;
            }
            $resData = to_arrays($resData);
            Cache::connect(config('system.extend_cache'))->set('extend_cache', $resData);
        } else {
            $resData = $extend_cache;
        }
        return $resData;
    }

    /**
     * 获取所有配置详细数据
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @return array
     * @author 仇仇天
     */
    public function system_config_info($Identification = '', $extend_param = [])
    {
        $data = AdminConfigModel::where(['status' => 1])->column('*', 'name');
        $data = to_arrays($data);
        return $data;
    }

    /**
     * 获取所有模块
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function system_module($Identification = '', $extend_param = [])
    {
        $data = AdminModuleModel::column('*', 'name');
        return $data;
    }

    /**
     * 获取所有模块配置分组
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function module_config_group($Identification = '', $extend_param = [])
    {
        $module_data = rcache('system_module');
        $data        = to_arrays(AdminConfigGroup::select());
        $res_dat     = [];
        foreach ($module_data as $key => $value) {
            $res_dat[$key] = [];
            foreach ($data as $key_config => $value_config) {
                if ($value['name'] == $value_config['module']) {
                    $res_dat[$key][] = $value_config;
                }
            }
        }
        return $res_dat;
    }

    /**
     * 所有插件
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function plugins($Identification = '', $extend_param = [])
    {
        $plugins = PluginModel::where('status', 1)->column('status', 'name');
        return to_arrays($plugins);
    }

    /**
     * 钩子对应的插件
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function hook_plugins($Identification = '', $extend_param = [])
    {
        $hook_plugins = HookPluginModel::where('status', 1)->order('hook,sort')->select();
        return to_arrays($hook_plugins);
    }

    /**
     * 所有钩子
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function hooks($Identification = '', $extend_param = [])
    {
        $hooks = HookModel::where('status', 1)->column('status', 'name');
        return to_arrays($hooks);
    }

    /**
     * 行为配置
     * @param string $Identification 标识
     * @param array $extend_param 扩展参数
     * @author 仇仇天
     */
    public function action_config($Identification = '', $extend_param = [])
    {
        $action_config = to_arrays(AdminActionModel::select());
        $system_module = rcache('system_module');
        $res_data      = [];
        foreach ($system_module as $key => $value) {
            foreach ($action_config as $key_action => $value_action) {
                if ($key == $value_action['module']) {
                    $res_data[$key][$value_action['name']] = $value_action;
                }
            }
        }
        return $res_data;
    }

    /**
     * 云存储
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function storage($Identification = '', $extend_param = [])
    {
        $data = PStorageModel::column('*', 'mark');
        foreach ($data as &$value) {
            $value['config'] = json_decode($value['config'], true);
            if (!empty($value['config'])) {
                foreach ($value['config'] as $values) {
                    $value['config_value'][$values['field']] = $values['value'];
                }
            }
        }
        return to_arrays($data);
    }

    /**
     * 短信插件信息
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function sms($Identification = '', $extend_param = [])
    {
        $data = PSmsModel::column('*', 'mark');
        foreach ($data as &$value) {
            $value['config'] = json_decode($value['config'], true);
        }
        return to_arrays($data);
    }

    /**
     * 短信模板
     * @param string $Identification
     * @param array $extend_param
     * @return array
     * @author 仇仇天
     */
    public function sms_tpl($Identification = '', $extend_param = [])
    {
        $data = PSmsTplModel::column('*', 'code');
        return to_arrays($data);
    }

    /**
     * 后台全部节点
     * @param string $Identification
     * @param array $extend_param
     * @author 仇仇天
     */
    public function admin_menu($Identification = '', $extend_param = [])
    {
        $resData = AdminMenuModel::order('sort ACS')->column('*', 'mark');
        return to_arrays($resData);
    }

    /**
     * 获取后台角色权限数据
     * @param string $Identification 角色id
     * @param array $extend_param
     * @author 仇仇天
     */
    public function admin_role_menu_auth($Identification = '', $extend_param = [])
    {
        $roleMenuAuth = false;
        if (!empty($Identification)) {
            $roleMenuAuth = AdminRole::where('id', $Identification)->value('menu_auth');
        }
        return $roleMenuAuth;
    }
}
