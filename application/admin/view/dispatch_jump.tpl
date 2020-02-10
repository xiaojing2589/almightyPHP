<!DOCTYPE html>
<html lang="en">

<!-- begin::Head -->
<head>

    <!--begin::Base Path (base relative path for assets of this page) -->
    <base href="../../../../">

    <!--end::Base Path -->
    <meta charset="utf-8" />
    <title> 错误 | {:config('web_site_title')}</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {// 动态加载核心css文件 }
    {:load_assets('core_css')}

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="__ADMIN_CSS__/pages/login-3.min.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->

    {// 动态加载后台核心插件css }
    {:load_assets('admin_core_plugin_css')}

    {// 动态加载css文件 }
    {notempty name="_css_files"}
        {volist name="_css_files" id="css"}
            {:load_assets($css)}
        {/volist}
    {/notempty}

    {// 动态加载后台核心css }
    {:load_assets('admin_core_css')}

    <link rel="shortcut icon" href="__ADMIN_IMG__/logos/favicon.ico" />

</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--forgot" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(__ADMIN_IMG__/bg/bg-3.jpg);">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo">
                        <a href="#">
                            <img src="__ADMIN_IMG__/logos/logo-5.png">
                        </a>
                    </div>
                    <div class="kt-login__forgot">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title"><?php echo(strip_tags($msg));?></h3>
                            <div class="kt-login__desc">页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间：<b id="wait"><?php echo($wait);?></b>秒<br/></div>
                        </div>
                        <form class="kt-form lock-form" action="{:url('user/lock')}">

                            <div class="kt-login__actions">
                                <a class="btn btn-outline-brand kt-login__btn-primary" href="{$url}">
                                    <i class="flaticon-reply"></i>
                                    立即跳转
                                </a>
                                <button class="btn btn-outline-danger kt-login__btn-primary" type="button" onclick="stop()">
                                    <i class="fa fa-ban"></i>
                                    禁止跳转
                                </button>
                                <a class="btn btn-outline-dark kt-login__btn-primary" href="{$Request.baseFile}">
                                    <i class="flaticon-home-1"></i>
                                    返回首页
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>
<!-- end::Global Config -->

{// 动态加载核心js文件 }
{:load_assets('core_js','js')}

{// 动态加载后台核心插件js }
{:load_assets('admin_core_plugin_js','js')}

{// 动态加载js}
{notempty name="_js_files"}
    {volist name="_js_files" id="js"}
        {:load_assets($js,'js')}
    {/volist}
{/notempty}

{// 动态加载后台核心js }
{:load_assets('admin_core_js','js')}

<script>
    $(function() {
        var wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
        // 禁止跳转
        window.stop = function (){
            clearInterval(interval);
        }
    });
</script>

</body>

<!-- end::Body -->
</html>
