<?php
return [
    // 拒绝ie访问
    'deny_ie'       => false,
    // 模块管理中，不读取模块信息的目录
    'except_module' => ['common', 'admin', 'index', 'extra', 'user', 'install'],
    'extend_cache'  => ['type' => 'file', 'expire' => 0, 'prefix' => 'extend_cache', 'path' => '../runtime/cache/']
];
