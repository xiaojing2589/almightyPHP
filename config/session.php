<?php
// | 会话设置
return [
    // SESSION_ID 的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持redis memcache memcached
    'type'           => '',
    // 是否自动开启 SESSION
    'auto_start'     => true,
];
