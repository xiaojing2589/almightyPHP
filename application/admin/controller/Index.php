<?php
namespace app\admin\controller;

use app\common\controller\Admin;
use think\facade\Cache;

/**
 * 后台默认控制器
 * @package app\admin\controller
 */
class Index extends Admin
{
    /**
     * 后台首页
     * @author 仇仇天
     * @return string
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 清空系统缓存
     * @author 仇仇天
     */
    public function wipeCache()
    {
        $wipe_cache_type = config('wipe_cache_type');
        if (!empty($wipe_cache_type)) {
            Cache::clear();
            $this->success('清空成功');
        } else {
            $this->error('请在系统设置中选择需要清除的缓存类型');
        }
    }

    /**
     * 检查版本更新
     * @author 仇仇天
     * @return \think\response\Json
     * @throws \think\db\exception\BindParamException
     * @throws \think\exception\PDOException
     */
    public function checkUpdate()
    {
        $params = config('dolphin.');
        $params['domain']  = request()->domain();
        $params['website'] = config('web_site_title');
        $params['ip']      = $_SERVER['SERVER_ADDR'];
        $params['php_os']  = PHP_OS;
        $params['php_version'] = PHP_VERSION;
        $params['mysql_version'] = db()->query('select version() as version')[0]['version'];
        $params['server_software'] = $_SERVER['SERVER_SOFTWARE'];
        $params = http_build_query($params);

        $opts = [
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL            => config('dolphin.product_update'),
            CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $params
        ];

        // 初始化并执行curl请求
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($data, true);

        if ($result['code'] == 1) {
            return json([
                'update' => '<a class="badge badge-primary" href="http://www.dolphinphp.com/download" target="_blank">有新版本：'.$result["version"].'</a>',
                'auth'   => $result['auth']
            ]);
        } else {
            return json([
                'update' => '',
                'auth'   => $result['auth']
            ]);
        }
    }
}
