<?php


// 加载自定义公共文件
if (is_file(think\facade\Env::get('app_path') . 'function.php')) {
    include_once think\facade\Env::get('app_path') . 'function.php';
}

/**
 * 加载静态资源
 * @author 仇仇天
 * @param string|Array $assets 资源名称
 * @param string $type 资源类型 css/javascript
 * @return mixed|string
 */
function load_assets($assets = '', $type = 'css')
{
    // 获取相应的静态资源
//    $assets_list = config('assets.' . $assets);

    $assets_list = !is_array($assets) ? config('assets.' . $assets) : $assets_list;

    // 设置资源
    $result      = '';
    foreach ($assets_list as $item) {
        if ($type == 'css') {
            $result .= '<link rel="stylesheet" href="' . $item . '?v=' . config('asset_version') . '">';
        } else {
            $result .= '<script src="' . $item . '?v=' . config('asset_version') . '"></script>';
        }
    }
    $result = str_replace(array_keys(config('template.tpl_replace_string')), array_values(config('template.tpl_replace_string')), $result);
    return $result;
}

/**
 * 合并输出js代码或css代码
 * @author 仇仇天
 * @param string $type 类型：group-分组，file-单个文件，base-基础目录
 * @param string $files 文件名或分组名
 */
function minify($type = '', $files = '')
{
    // 获取需要加载 静态资源组
    $files = !is_array($files) ? $files : implode(',', $files);

    $url   = PUBLIC_PATH. 'min/?';

    switch ($type) {
        case 'group':
            $url .= 'g=' . $files;
            break;
        case 'file':
            $url .= 'f=' . $files;
            break;
        case 'base':
            $url .= 'b=' . $files;
            break;
    }
    echo $url.'&v='.config('asset_version');
}

/**
 * 监听钩子
 * @author 仇仇天
 * @param string $name 钩子名称
 * @param mixed $params 传入参数
 * @param bool $once 只获取一个有效返回值
 */
function hook($name = '', $params = null, $once = false)
{
    \think\facade\Hook::listen($name, $params, $once);
}

/**
 * 获取浏览器类型
 * @author 仇仇天
 * @return string
 */
function get_browser_type()
{
    $agent = $_SERVER["HTTP_USER_AGENT"];
    if (strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) return "ie";
    if (strpos($agent, 'Firefox') !== false) return "firefox";
    if (strpos($agent, 'Chrome') !== false) return "chrome";
    if (strpos($agent, 'Opera') !== false) return 'opera';
    if ((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false) return 'safari';
    if (false !== strpos($_SERVER['HTTP_USER_AGENT'], '360SE')) return '360SE';
    return 'unknown';
}

/**
 * 生成 或 验证 数据签名认证
 * @author 仇仇天
 * @param array $data 被认证的数据
 * @return string
 */
function data_auth_sign($data = [])
{
    // 数据类型检测
    if (!is_array($data)) $data = (array)$data;

    // 排序
    ksort($data);

    // url编码并生成query字符串
    $code = http_build_query($data);

    // 生成签名
    $sign = sha1($code);

    return $sign;
}

/**
 * 获取客户端IP地址
 * @author 仇仇天
 * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param bool $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 数组对象转数组
 * @author 仇仇天
 * @param array $object_array 数组对象
 * @return array
 */
function to_arrays($object_array)
{
    return json_decode(json_encode($object_array), true);
}

/**
 * 计算字节大小
 * @author 仇仇天
 * @param $num 大小
 * @return String
 */
function getFilesize($num)
{
    $p      = 0;
    $format = 'bytes';
    if ($num > 0 && $num < 1024) {
        $p = 0;
        return number_format($num) . ' ' . $format;
    }
    if ($num >= 1024 && $num < pow(1024, 2)) {
        $p      = 1;
        $format = 'KB';
    }
    if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
        $p      = 2;
        $format = 'MB';
    }
    if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
        $p      = 3;
        $format = 'GB';
    }
    if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
        $p      = 3;
        $format = 'TB';
    }
    $num /= pow(1024, $p);
    return number_format($num, 3) . ' ' . $format;
}

/**
 * 记录行为日志，并执行该行为的规则
 * @author  仇仇天
 * @param null $action 行为标识
 * @param array $param
 *                 username  执行用户
 *                 userid    执行用户id
 *
 * @return bool
 * @throws Exception
 */
function action_log($action = null, $params = [])
{
    // 获取当前域名
    $domain = request()->domain();

    // 获取当前入口文件
    $baseFile = request()->baseFile();

    // 访问ip地址
    $ip = request()->ip();

    // 资源类型
    $type = request()->type();

    // 当前模块名称
    $module = request()->module();

    // 当前控制器名称
    $controller = request()->controller();

    // 当前操作名称
    $actions = request()->action();

    // 当前操作参数
    $param = request()->param();

    // 全部日志
    $action_info_log = think\facade\Log::getLog();

    // 获取行为缓存
    $action_info = rcache('action_config.' . $action);

    // 写入数据库
    $data = [
        // 行为名称
        'action_name'   => $action_info['name'],
        // 执行者用户名
        'user_name'     => empty($params['username']) ? '游客' : $params['username'],
        // 执行者用户id
        'user_id'       => empty($params['userid'])  ?  '' : $params['userid'],
        // 执行者用户ip
        'action_ip'     => $ip,
        // 资源类型
        'rq_type'       => $type,
        // 获取当前域名
        'rq_domain'     => $domain,
        // 入口文件
        'rq_basefile'   => $baseFile,
        // 模块
        'rq_module'     => $module,
        // 控制器
        'rq_controller' => $controller,
        // 方法
        'rq_action'     => $actions,
        // 参数
        'rq_param'      => json_encode($param, JSON_UNESCAPED_UNICODE),
        // 详细日志
        'rq_log_info'   => json_encode($action_info_log, JSON_UNESCAPED_UNICODE),
        // 时间
        'create_time'   => request()->time()
    ];

    // 判断是否开启系统日志功能
    $res_status = app\common\model\AdminLog::insert($data);
    if ($res_status !== false) {
        return true;
    } else {
        return false;
    }

}

/**
 * 消息队列
 * 文档请参考：https://github.com/coolseven/notes/tree/master/thinkphp-queue
 * @author 仇仇天
 * @param array $param 参数
 * @param string $module 模块
 * @param string $function 方法
 * @return bool
 */
function queueS($param = [], $module = 'common', $function = 'fire')
{
    // 当前任务将由哪个类来负责处理。当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
    $jobHandlerClassName = 'app\\' . $module . '\\model\\QueueS@' . $function;
    // 立即执行入列
    if (empty($param['later'])) {
        // 即时执行，将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = think\Queue::push($jobHandlerClassName, $param);
    } // 延迟执行入列
    else {
        // 延迟执行，将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = think\Queue::later($param['later'], $jobHandlerClassName, $param);
    }
    // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
    return ($isPushed !== false) ? true : false;
}

/**
 * 通知邮件/通知消息 内容转换函数
 * @author 仇仇天
 * @param $message 内容模板
 * @param $param 内容参数数组
 * @return bool|mixed 通知内容
 */
function ncReplaceText($message, $param)
{
    if (!is_array($param)) return false;
    foreach ($param as $k => $v) {
        $message = str_replace('{$' . $k . '}', $v, $message);
    }
    return $message;
}

/**
 * 发送邮件方法
 * @author 仇仇天
 * @param $to 接收者邮箱地址
 * @param $title 邮件的标题
 * @param $content true:发送成功 false:发送失败
 * @return bool
 */
function sendMail($to, $title, $content)
{
    // 实例化PHPMailer核心类
    $mail            = new PHPMailer\PHPMailer\PHPMailer();

    // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = false;

    // 使用smtp鉴权方式发送邮件
    $mail->isSMTP();

    // smtp需要鉴权 这个必须是true
    $mail->SMTPAuth   = true;

    // 链接qq域名邮箱的服务器地址
    $mail->Host       = 'smtp.qq.com';

    // 设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';

    // 设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
    $mail->Port       = 465;

    // 设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
    $mail->Hostname   = 'localhost';

    // 设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet    = 'UTF-8';

    // 设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName   = config('web_site_title');

    // smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username   = '125917130@qq.com';

    // smtp登录的密码 使用生成的授权码 你的最新的授权码
    $mail->Password   = 'ldgmcswcnrjhbged';

    // 设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From       = '125917130@qq.com';

    // 邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);

    // 设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($to);

    // 添加该邮件的主题
    $mail->Subject = $title;

    // 添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body    = $content;

    // 发送短信
    $status        = $mail->send();

    //简单的判断与提示信息
    if ($status) {
        return true;
    } else {
        return false;
    }
}

/**
 * 过滤js内容
 * @author 仇仇天
 * @param string $str 要过滤的字符串
 * @return string|string[]|null
 */
function clear_js($str = '')
{
    $search = "/<script[^>]*?>.*?<\/script>/si";
    $str    = preg_replace($search, '', $str);
    return $str;
}

/**
 * 生成随机字符串
 * @param int $length 生成长度
 * @param int $type 生成类型：0-小写字母+数字，1-小写字母，2-大写字母，3-数字，4-小写+大写字母，5-小写+大写+数字
 * @return string
 * @author 仇仇天
 */
function generate_rand_str($length = 8, $type = 0)
{
    $a = 'abcdefghijklmnopqrstuvwxyz';
    $A = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $n = '0123456789';

    switch ($type) {
        case 1:
            $chars = $a;
            break;
        case 2:
            $chars = $A;
            break;
        case 3:
            $chars = $n;
            break;
        case 4:
            $chars = $a . $A;
            break;
        case 5:
            $chars = $a . $A . $n;
            break;
        default:
            $chars = $a . $n;
    }

    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $str;
}

/**
 * 价格格式化
 * @param $price
 * @return string
 * @author 仇仇天
 */
function ncPriceFormat($price)
{
    return number_format($price, 2, '.', '');
}

/**
 * html安全过滤
 * @author 仇仇天
 * @param string $html 要过滤的内容
 * @return string
 */
function htmlpurifier($html = '')
{
    $config     = HTMLPurifier_Config::createDefault();
    $purifier   = new HTMLPurifier($config);
    $clean_html = $purifier->purify($html);
    return $clean_html;
}

/**
 * 扩展表单项
 * @author 仇仇天
 * @param array $form 类型
 * @param array $_layout 布局参数
 * @return string
 *
 */
function extend_form_item($form = [], $_layout = [])
{
    if (!isset($form['type'])) return '';
    if (!empty($_layout) && isset($_layout[$form['name']])) {
        $form['_layout'] = $_layout[$form['name']];
    }

    $template = './extend/form/' . $form['type'] . '/' . $form['type'] . '.html';
    if (file_exists($template)) {
        $template_content = file_get_contents($template);
        $view             = think\Container::get('view');
        return $view->display($template_content, $form);
    } else {
        return '';
    }
}




/**
 * 导入Excel数据表格
 * @author 仇仇天
 * @param string $fileName 文件名
 * @param int $line 读取几行，默认全部读取
 * @param int $offset 从第几行开始读，默认从第一行读取
 * @return bool|array
 */
function importCsv($fileName, $line = 0, $offset = 0)
{
    // 防止超时
    set_time_limit(0);

    // 防止内存溢出
    ini_set("memory_limit", "512M");

    $handle = fopen($fileName, 'r');
    if (!$handle) {
        return '文件打开失败';
    }

    $i   = 0;
    $j   = 0;
    $arr = [];
    while ($data = fgetcsv($handle)) {
        //小于偏移量则不读取,但$i仍然需要自增
        if ($i < $offset && $offset) {
            $i++;
            continue;
        }
        //大于读取行数则退出
        if ($i > $line && $line) {
            break;
        }

        foreach ($data as $key => $value) {
            $content   = iconv("gbk", "utf-8//IGNORE", $value);//转化编码
            $arr[$j][] = $content;
        }
        $i++;
        $j++;
    }
    return $arr;
}

/**
 * 导出Excel数据表格
 * @author 仇仇天
 * @param array $dataList 要导出的数组格式的数据
 * @param array $headList 导出的Excel数据第一列表头
 * @param string $fileName 输出Excel表格文件名
 * @param string $exportUrl 直接输出到浏览器or输出到指定路径文件下
 * @return bool|false|string
 */
function exportCsv($dataList, $headList, $fileName, $exportUrl = 'php://output')
{
    // 防止超时
    set_time_limit(0);

    // 防止内存溢出
    ini_set("memory_limit", "512M");

    // 完整文件名
    $file_name = $fileName.'.csv';

    // 打开PHP文件句柄,php://output 表示直接输出到浏览器,$exportUrl表示输出到指定路径文件下
    if($exportUrl == 'php://output'){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen($exportUrl, 'a');
    }else{
        $fp = fopen($exportUrl.$file_name, 'a');
    }

    //输出Excel列名信息
    foreach ($headList as $key => $value) {
        $headList[$key] = iconv('utf-8', 'gbk', $value); //CSV的Excel支持GBK编码，一定要转换，否则乱码
    }

    // 将数据通过fputcsv写到文件句柄
    fputcsv($fp, $headList);

    // 计数器
    $num   = 0;

    // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;
    // 逐行取出数据，不浪费内存
    $count = count($dataList);

    for ($i = 0; $i < $count; $i++) {
        $num++;
        //刷新一下输出buffer，防止由于数据过多造成问题
        if ($limit == $num) {
            ob_flush();
            flush();
            $num = 0;
        }
        $row = $dataList[$i];
        foreach ($row as $key => $value) {
//            $row[$key] = iconv('utf-8', 'gbk', $value);
            $row[$key] = mb_convert_encoding($value,"GBK","UTF-8");

        }
        fputcsv($fp, $row);
    }

    return $fileName;
}


/**
 * 读取缓存
 * @author 仇仇天
 * @param $field_name 字段名
 * @param string $Identification 标识
 * @param array $extend_param 扩展参数
 * @return bool|mixed|string
 */
function rcache($field_name, $Identification = '', $extend_param = [])
{
    // 解析层级
    $field_name_arr = explode('.', $field_name);

    // 配置名
    $field_name     = $field_name_arr[0];

    // 获取缓存配置
    $config         = app\common\model\ExtendCache::getCacheConfig($field_name);

    if (!empty($config)) {

        // 查询缓存
        $value = think\facade\Cache::connect($config)->get($field_name . $Identification);

        // 如果不存在
        if ($value === false || $value == '') {

            // 查询自动创建缓存函数是否存在
            if (!method_exists(new app\common\model\ExtendCache, $field_name)) {

                // 是否是模块缓存
                if (!empty($extend_param['module'])) {

                    $class    = "app\\{$extend_param['module']}\\model\\ExtendCache";

                    // 调用字段相应的函数
                    $callback = [new $class, $field_name];

                    // 回调行数
                    $value    = call_user_func($callback, $Identification, $extend_param);
                }
                if ($value === false || $value == '') $value = '';
            } else {

                // 调用字段相应的函数
                $callback = [new app\common\model\ExtendCache, $field_name];

                // 回调行数
                $value    = call_user_func($callback, $Identification, $extend_param);
            }
            // 写入
            wkcache($field_name, $value, $Identification);
        }

        // 获取层级数据
        $fvalue = $value;
        if (count($field_name_arr) > 1) {
            foreach ($field_name_arr as $keys => $values) {
                if ($keys > 0) {
                    if (!empty($fvalue[$values])) {
                        $fvalue = $fvalue[$values];
                    } else {
                        return '';
                    }
                }
            }
            return $fvalue;
        }
        return $value;
    }
}

/**
 * 写入缓存
 * @author 仇仇天
 * @param $field_name 字段名称
 * @param $value 数据
 * @param string $Identification 标识
 * @return bool
 */
function wkcache($field_name, $value, $Identification = '')
{
    $config = app\common\model\ExtendCache::getCacheConfig($field_name); // 获取缓存配置
    if (!empty($config)) {
        $value = think\facade\Cache::connect($config)->set($field_name . $Identification, $value);
        return $value;
    } else {
        throw new \Exception('Cannot fetch cache object!');
    }
}

/**
 * 缓存删
 * @author 仇仇天
 * @param $field_name 字段名称
 * @param string $Identification 标识
 * @return bool|mixed
 */
function dkcache($field_name, $Identification = '')
{
    $config = app\common\model\ExtendCache::getCacheConfig($field_name); // 获取缓存配置
    if (!empty($config)) {
        // 设置缓存
        $value = think\facade\Cache::connect($config)->pull($field_name . $Identification);
        return $value;
    } else {
        return false;
    }
}



/**
 * 添加附件
 * @author 仇仇天
 * @param $file  文件名称 支持 字符：a.jpg  对象：request->file()
 * @param $file_path 存放位置
 * @param array $param 参数
 * @return array
 */
function attaAdd($file, $file_path, $param = [])
{
    // 返回信息
    $res_arr = ['status' => true, 'msg' => '创建成功', 'data' => []];

    // 检测文件名
    if (empty($file)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '参数错误！';
        return $res_arr;
    }

    // 系统附件大小限制
    $size_limit = config('upload_file_size');
    $size_limit = $size_limit * 1024;

    // 系统附件类型限制
    $ext_limit     = explode(',',config('upload_file_ext'));

    // 对象形式
    if (is_object($file)) {
        $file_name = $file->getInfo('name'); // 文件名称
        $file_size = $file->getInfo('size'); // 文件大小
        $file_mime = $file->getMime(); // 文件类型
        if ($file_mime == 'text/x-php' || $file_mime == 'text/html') {
            $res_arr['status'] = false;
            $res_arr['msg']    = '禁止上传非法文件！';
            return $res_arr;
        }
    }

    // 字符串形式
    if (is_string($file)) {
        // 检测是否为文件,并且文件存在
        if (is_file($file)) {
            $file_info = pathinfo($file);
            $file_name = $file_info['basename']; // 文件名称
            $file_size = filesize($file); // 文件大小
        } else {
            $res_arr['status'] = false;
            $res_arr['msg']    = '文件不存在！';
            return $res_arr;
        }
    }

    $file_ext = strtolower(substr($file_name, strrpos($file_name, '.') + 1)); // 文件格式

    // 检测附件大小
    if ($size_limit > 0 && ($file_size > $size_limit)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '附件过大！';
        return $res_arr;
    }

    // 检测附件类型
    if (!in_array($file_ext, $ext_limit)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = '附件类型不正确！';
        return $res_arr;
    }


    // 配置存储驱动
    $config_driver = config('upload_driver');

    $storage_config = app\common\model\PStorage::getStorageConfigVlue($config_driver);

    // 插件类文件路径
    $obj = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $config_driver . DIRECTORY_SEPARATOR . 'Storage';

    $storage_obj = new $obj($storage_config);

    if (!empty($storage_obj->error)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = $storage_obj->error;
        return $res_arr;
    }

    $info = $storage_obj->uploadFile($file, $file_path, $param); // 上传文

    if (!empty($storage_obj->error)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = $storage_obj->error;
        return $res_arr;
    }

    $res_arr['data'] = $info;
    return $res_arr;
}

/**
 * 删除附件
 * @author 仇仇天
 * @param $file_name 文件名称 支持 字符：a.jpg  对象：request->file()
 * @param array $param 参数
 * @return array
 */
function attaDel($file_name, $param = [])
{
    // 返回信息
    $res_arr = ['status' => true, 'msg' => '删除成功', 'data' => []];

    // 配置存储驱动
    $config_driver = config('upload_driver');

    $storage_config = app\common\model\PStorage::getStorageConfigVlue($config_driver);

    // 插件类文件路径
    $obj = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $config_driver . DIRECTORY_SEPARATOR . 'Storage';

    $storage_obj = new $obj($storage_config);

    if (!empty($storage_obj->error)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = $storage_obj->error;
        return $res_arr;
    }

    $url = $storage_obj->del($file_name, $param);

    if (!empty($storage_obj->error)) {
        $res_arr['status'] = false;
        $res_arr['msg']    = $storage_obj->error;
        return $res_arr;
    }

    return $res_arr;
}

/**
 * 附件url地址
 * @author  仇仇天
 * @param $file_name  完整的文件路径 支持 字符：a.jpg  对象：request->file()
 * @param array $param 参数
 * @return mixed
 * @throws Exception
 */
function attaUrl($file_name, $param = [])
{
    // 配置存储驱动
    $config_driver = config('upload_driver');

    $storage_config = app\common\model\PStorage::getStorageConfigVlue($config_driver);

    $obj = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $config_driver . DIRECTORY_SEPARATOR . 'Storage'; // 插件类文件路径

    $storage_obj = new $obj($storage_config);

    if (!empty($storage_obj->error)) return $storage_obj->error;

    $url = $storage_obj->getUrl($file_name, $param);

    return $url;

}

/**
 * 检测附件是否存在
 * @author 仇仇天
 * @param $file_name 文件名称 支持 字符：a.jpg  对象：request->file()
 * @param array $param 参数
 * @return mixed
 */
function attachExists($file_name, $param = [])
{

    // 配置存储驱动
    $config_driver = config('upload_driver');

    $storage_config = app\common\model\PStorage::getStorageConfigVlue($config_driver);

    $obj = DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . $config_driver . DIRECTORY_SEPARATOR . 'Storage'; // 插件类文件路径

    $storage_obj = new $obj($storage_config);

    if (!empty($storage_obj->error)) return $storage_obj->error;

    return $storage_obj->exist($file_name, $param);
}

/**
 * 添加水印
 * @author 仇仇天
 * @param string $file 要添加水印的文件路径
 * @param string $watermark_img 水印图片
 * @param string $watermark_pos 水印位置
 * @param string $watermark_alpha 水印透明度
 */
function create_water($file = '', $thumb_water_pic = '', $watermark_pos = '', $watermark_alpha = '')
{
    // 系统水印位置
    $upload_thumb_water_position = config('upload_thumb_water_position');
    // 系统透明度
    $upload_thumb_water_alpha = config('upload_thumb_water_alpha');
    if (is_file($thumb_water_pic)) {
        // 读取图片
        $image = think\Image::open($file);
        // 添加水印
        $watermark_pos   = $watermark_pos == '' ? $upload_thumb_water_position : $watermark_pos;
        $watermark_alpha = $watermark_alpha == '' ? $upload_thumb_water_alpha : $watermark_alpha;
        $image->water($thumb_water_pic, $watermark_pos, $watermark_alpha);
        // 保存水印图片，覆盖原图
        $image->save($file);
    }
}

/**
 * 创建缩略图
 * @author 仇仇天
 * @param string $file 目标文件，可以是文件对象或文件路径
 * @param string $dir 保存目录，即目标文件所在的目录名
 * @param string $save_name 缩略图名
 * @param string $thumb_max_width 宽度
 * @param string $thumb_max_height 高度
 * @param string $thumb_type 裁剪类型 1=等比例缩放 2=缩放后填充 3=居中裁剪 4=左上角裁剪 5=右下角裁剪 6=固定尺寸缩放
 * @return string 缩略图路径
 */
function create_thumb($file, $dir, $save_name, $thumb_max_width, $thumb_max_height, $thumb_type = 1, $custom = false)
{
    // 解析文件信息
    $fileInfo = pathinfo($save_name);

    // 读取源图片
    $image = think\Image::open($file);

    // 设置缩放参数
    $image->thumb($thumb_max_width, $thumb_max_height, $thumb_type);

    // 拼接图片路径
    if (!$custom) {
        $thumb_path_name = $dir . DIRECTORY_SEPARATOR . $fileInfo['filename'] . '_' . $thumb_max_width . 'x' . $thumb_max_height . '.' . $fileInfo['extension'];
    } else {
        $thumb_path_name = $dir . DIRECTORY_SEPARATOR . $fileInfo['filename'] . '.' . $fileInfo['extension'];
    }


    // 保存缩略图
    $image->save($thumb_path_name);

    return $thumb_path_name;
}

/**
 * 判断是否图片
 * @author 仇仇天
 * @param $file 支持 字符：a.jpg  对象：request->file()
 * @return bool
 *
 */
function is_img($file)
{

    if (is_object($file)) {
        $ext = pathinfo($file->getInfo('name'))['extension'];
    } else {
        $ext = pathinfo($file)['extension'];
    }

    if (in_array($ext, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'])) {
        return true;
    } else {
        return false;
    }
}



/**
 * 获取插件类名
 * @author 仇仇天
 * @param string $name 插件名
 * @return string
 */
function get_plugin_class($name)
{
    return "plugins\\{$name}\\{$name}";
}

/**
 * 检查插件控制器是否存在某操作
 * @author 仇仇天
 * @param string $name 插件名
 * @param string $controller 控制器
 * @param string $action 动作
 * @return bool
 */
function plugin_action_exists($name = '', $controller = '', $action = '')
{
    if (strpos($name, '/')) {
        list($name, $controller, $action) = explode('/', $name);
    }
    return method_exists("plugins\\{$name}\\controller\\{$controller}", $action);
}

/**
 * 执行插件动作,也可以用这种方式调用：plugin_action('插件名/控制器/动作', [参数1,参数2...])
 * @author 仇仇天
 * @param string $name 插件名
 * @param string $controller 控制器
 * @param string $action 动作
 * @param mixed $params 参数
 * @return mixed
 */
function plugin_action($name = '', $controller = '', $action = '', $params = [])
{
    if (strpos($name, '/')) {
        $params = is_array($controller) ? $controller : (array)$controller;
        list($name, $controller, $action) = explode('/', $name);
    }
    if (!is_array($params)) {
        $params = (array)$params;
    }
    $class = "plugins\\{$name}\\controller\\{$controller}";
    $obj   = new $class;
    return call_user_func_array([$obj, $action], $params);
}

/**
 * 获取或设置某个插件配置参数
 * @author 仇仇天
 * @param string $name 插件名.配置名
 * @param string $value 设置值
 * @return mixed
 */
function plugin_config($name = '', $value = '')
{
    if ($value === '') {
        // 获取插件配置
        if (strpos($name, '.')) {
            list($name, $item) = explode('.', $name);
            return model('common/AdminPlugin')->getConfig($name, $item);
        } else {
            return model('common/AdminPlugin')->getConfig($name);
        }
    } else {
        return model('admin/plugin')->setConfig($name, $value);
    }
}

/**
 * 检查插件模型是否存在
 * @author 仇仇天
 * @param string $name 插件名
 * @return bool
 */
function plugin_model_exists($name = '')
{
    return class_exists("plugins\\{$name}\\model\\{$name}");
}

/**
 * 检查插件验证器是否存在
 * @author 仇仇天
 * @param string $name 插件名
 * @return bool
 */
function plugin_validate_exists($name = '')
{
    return class_exists("plugins\\{$name}\\validate\\{$name}");
}

/**
 * 获取插件模型实例
 * @author 仇仇天
 * @param string $name 插件名
 * @return object
 */
function get_plugin_model($name)
{
    $class = "plugins\\{$name}\\model\\{$name}";
    return new $class;
}

/**
 * 获取插件验证类实例
 * @author 仇仇天
 * @param string $name 插件名
 * @return mixed
 */
function get_plugin_validate($name = '')
{
    $class = "plugins\\{$name}\\validate\\{$name}";
    return new $class;
}

/**
 * 生成插件操作链接
 * @author 仇仇天
 * @param string $url     链接：插件名称/控制器/操作
 * @param array $param    参数
 * @param string $module  模块名，admin需要登录验证，index不需要登录验证
 * @return string
 */
function plugin_url($url = '', $param = [], $module = 'admin')
{
    $params = [];
    $url    = explode('/', $url);
    if (isset($url[0])) {
        $params['_plugin'] = $url[0];
    }
    if (isset($url[1])) {
        $params['_controller'] = $url[1];
    }
    if (isset($url[2])) {
        $params['_action'] = $url[2];
    }

    // 合并参数
    $params = array_merge($params, $param);

    // 返回url地址
    return url($module . '/plugin/execute', $params);
}

/**
 * 生成插件操作链接(不需要登陆验证)
 * @author 仇仇天
 * @param string $url  链接：插件名称/控制器/操作
 * @param array $param 参数
 * @return string
 */
function public_url($url = '', $param = [])
{
    // 返回url地址
    return plugin_url($url, $param, 'index');
}



/**
 * 时间戳格式化
 * @author 仇仇天
 * @param string $time 时间戳
 * @param string $format 输出格式
 * @return false|string
 */
function format_time($time = '', $format = 'Y-m-d H:i')
{
    return !$time ? '' : date($format, intval($time));
}

/**
 * 使用bootstrap-datepicker插件的时间格式来格式化时间戳
 * @author 仇仇天
 * @param null $time 时间戳
 * @param string $format bootstrap-datepicker插件的时间格式 https://bootstrap-datepicker.readthedocs.io/en/stable/options.html#format
 * @return false|string
 */
function format_date($time = null, $format = 'yyyy-mm-dd')
{
    $format_map = [
        'yyyy' => 'Y',
        'yy'   => 'y',
        'MM'   => 'F',
        'M'    => 'M',
        'mm'   => 'm',
        'm'    => 'n',
        'DD'   => 'l',
        'D'    => 'D',
        'dd'   => 'd',
        'd'    => 'j',
    ];

    // 提取格式
    preg_match_all('/([a-zA-Z]+)/', $format, $matches);
    $replace = [];
    foreach ($matches[1] as $match) {
        $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
    }

    // 替换成date函数支持的格式
    $format = str_replace($matches[1], $replace, $format);
    $time   = $time === null ? time() : intval($time);
    return date($format, $time);
}

/**
 * 使用momentjs的时间格式来格式化时间戳
 * @author 仇仇天
 * @param null $time 时间戳
 * @param string $format momentjs的时间格式
 * @return false|string
 */
function format_moment($time = null, $format = 'YYYY-MM-DD HH:mm')
{
    $format_map = [
        // 年、月、日
        'YYYY' => 'Y',
        'YY'   => 'y',
//            'Y'    => '',
        'Q'    => 'I',
        'MMMM' => 'F',
        'MMM'  => 'M',
        'MM'   => 'm',
        'M'    => 'n',
        'DDDD' => '',
        'DDD'  => '',
        'DD'   => 'd',
        'D'    => 'j',
        'Do'   => 'jS',
        'X'    => 'U',
        'x'    => 'u',

        // 星期
//            'gggg' => '',
//            'gg' => '',
//            'ww' => '',
//            'w' => '',
        'e'    => 'w',
        'dddd' => 'l',
        'ddd'  => 'D',
        'GGGG' => 'o',
//            'GG' => '',
        'WW'   => 'W',
        'W'    => 'W',
        'E'    => 'N',

        // 时、分、秒
        'HH'   => 'H',
        'H'    => 'G',
        'hh'   => 'h',
        'h'    => 'g',
        'A'    => 'A',
        'a'    => 'a',
        'mm'   => 'i',
        'm'    => 'i',
        'ss'   => 's',
        's'    => 's',
//            'SSS' => '[B]',
//            'SS'  => '[B]',
//            'S'   => '[B]',
        'ZZ'   => 'O',
        'Z'    => 'P',
    ];

    // 提取格式
    preg_match_all('/([a-zA-Z]+)/', $format, $matches);
    $replace = [];
    foreach ($matches[1] as $match) {
        $replace[] = isset($format_map[$match]) ? $format_map[$match] : '';
    }

    // 替换成date函数支持的格式
    $format = str_replace($matches[1], $replace, $format);
    $time   = $time === null ? time() : intval($time);
    return date($format, $time);
}



/**
 * 获取联动数据
 * @param string $table 表名
 * @param int $pid 父级ID
 * @param string $pid_field 父级ID的字段名
 * @return array|string|\think\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @author 仇仇天
 */
function get_level_data($table = '', $pid = 0, $pid_field = 'pid')
{
    if ($table == '') {
        return '';
    }

    $data_list = think\Db::name($table)->where($pid_field, $pid)->select();

    if ($data_list) {
        return $data_list;
    } else {
        return '';
    }
}

/**
 * 获取联动等级和父级id
 * @param string $table 表名
 * @param int $id 主键值
 * @param string $id_field 主键名
 * @param string $pid_field pid字段名
 * @return mixed
 * @author 仇仇天
 */
function get_level_pid($table = '', $id = 1, $id_field = 'id', $pid_field = 'pid')
{
    return think\Db::name($table)->where($id_field, $id)->value($pid_field);
}

/**
 * 反向获取联动数据
 * @param string $table 表名
 * @param string $id 主键值
 * @param string $id_field 主键名
 * @param string $name_field name字段名
 * @param string $pid_field pid字段名
 * @param int $level 级别
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 * @author 仇仇天
 */
function get_level_key_data($table = '', $id = '', $id_field = 'id', $name_field = 'name', $pid_field = 'pid', $level = 1)
{
    $result             = [];
    $level_pid          = get_level_pid($table, $id, $id_field, $pid_field);
    $level_key[$level]  = $level_pid;
    $level_data[$level] = get_level_data($table, $level_pid, $pid_field);

    if ($level_pid != 0) {
        $data       = get_level_key_data($table, $level_pid, $id_field, $name_field, $pid_field, $level + 1);
        $level_key  = $level_key + $data['key'];
        $level_data = $level_data + $data['data'];
    }
    $result['key']  = $level_key;
    $result['data'] = $level_data;

    return $result;
}



/**
 *  字符串命名风格转换 type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 * @author 仇仇天
 */
function parse_name($name, $type = 0)
{
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {
            return strtoupper($match[1]);
        }, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

/**
 * @describe 生成前台入口url
 * @param string $url 路由地址
 * @param string|array $vars 变量
 * @param bool|string $suffix 生成的URL后缀
 * @param bool|string $domain 域名
 * @return string
 * @author 仇仇天
 */
function home_url($url = '', $vars = '', $suffix = true, $domain = false)
{
    $url = url($url, $vars, $suffix, $domain);
    if (defined('ENTRANCE') && ENTRANCE == 'admin') {
        $base_file = request()->baseFile();
        $base_file = substr($base_file, strripos($base_file, '/') + 1);
        return preg_replace('/\/' . $base_file . '/', '/index.php', $url);
    } else {
        return $url;
    }
}

/**
 * @describe 生成后台入口url
 * @param string $url 路由地址
 * @param string|array $vars 变量
 * @param bool|string $suffix 生成的URL后缀
 * @param bool|string $domain 域名
 * @return string
 * @author 仇仇天
 */
function admin_url($url = '', $vars = '', $suffix = true, $domain = false)
{
    $url = url($url, $vars, $suffix, $domain);
    if (defined('ENTRANCE') && ENTRANCE == 'admin') {
        return $url;
    } else {
        return preg_replace('/\/index.php/', '/' . ADMIN_FILE, $url);
    }
}



/**
 * 返回封装后的 API 数据到客户端
 * @param string $msg 提示信息
 * @param array $data 要返回的数据
 * @param int $code 错误码，默认为0
 * @param string $type 输出类型，支持json/xml/jsonp
 * @param array $header 发送的 Header 信息
 * @param array $extendDaa
 * @author 仇仇天
 */
function msgResult($msg = '', $data = [], $code = 0, $type = 'json', $header = [], $extendDaa = [])
{
    $result = [
        'code' => $code,
        'msg'  => $msg,
        'time' => time(),
        'data' => $data,
    ];
    $result = array_merge($result, $extendDaa);
    echo json_encode($result);
    exit;
}

/**
 * 操作成功返回的数据仇仇天
 * @param string $msg 提示信息
 * @param mixed $data 要返回的数据
 * @param string $type 输出类型
 * @param array $header 发送的 Header 信息
 * @author 仇仇天
 */
function msgSuccess($msg = '', $data = [], $type = 'json', $header = [], $extendDaa = [])
{
    $msg = $msg == '' || $msg == null || $msg == false ? '操作成功' : $msg;
    msgResult($msg, $data, 200, $type, $header, $extendDaa);
}

/**
 * @describe 操作失败返回的数据
 * @param string $msg 提示信息
 * @param mixed $data 要返回的数据
 * @param string $type 输出类型
 * @param array $header 发送的 Header 信息
 * @author 仇仇天
 */
function msgError($msg = '', $data = [], $type = 'json', $header = [], $extendDaa = [])
{
    $msg = $msg == '' || $msg == null || $msg == false ? '操作失败' : $msg;
    msgResult($msg, $data, 400, $type, $header, $extendDaa);
}



