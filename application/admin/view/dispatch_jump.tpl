<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->


<head>
    <meta charset="utf-8" />
    <title>Metronic | 500 Page Option 2</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_PLUGINS__/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_PLUGINS__/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_PLUGINS__/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_PLUGINS__/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_PLUGINS__/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="__GLODAL_CSS__/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="__GLODAL_CSS__/plugins.min.css" rel="stylesheet" type="text/css" />
    <link href="__ADMIN_CSS__/error.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="favicon.ico" /> </head>


<body class=" page-500-full-page">
<div class="row">
    <div class="col-md-12 page-500">
        {notempty name="code"}
            <div class=" number font-green-turquoise">
                <i class="fa fa-check-circle"></i>
            </div>
            {else/}
            <div class=" number font-red">
                <i class="fa fa-times-circle"></i>
            </div>
        {/notempty}

        <div class=" details">
            <h1 {$code ? 'class="font-green-turquoise"' : 'class="font-red"'}><?php echo(strip_tags($msg));?></h1>
            <p>页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间：<b id="wait"><?php echo($wait);?></b>秒<br/></p>
            <p>
                <a class="btn green btn-outline" href="<?php echo($url);?>"><i class="fa fa-external-link-square"></i> 立即跳转</a>
                <button class="btn yellow btn-outline" type="button" onclick="stop()"><i class="fa fa-ban"></i> 禁止跳转</button>
                <a class="btn btn-minw btn-rounded btn-default" href="{$Request.baseFile}"><i class="fa fa-home"></i> 返回首页</a>
            </p>
        </div>
    </div>
</div>

<!--[if lt IE 9]>
<script src="__GLODAL_PLUGINS__/respond.min.js"></script>
<script src="__GLODAL_PLUGINS__/excanvas.min.js"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="__GLODAL_PLUGINS__/jquery.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/bootstrap/js/bootstrap.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/js.cookie.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/jquery-slimscroll/jquery.slimscroll.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/jquery.blockui.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/uniform/jquery.uniform.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_PLUGINS__/bootstrap-switch/js/bootstrap-switch.min.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__GLODAL_JS__/app.js?v={:config('asset_version')}" type="text/javascript"></script>
<script src="__ADMIN_JS__/base.js?v={:config('asset_version')}" type="text/javascript"></script>


<script type="text/javascript">
    (function(){
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
    })();
</script>

</body>

</html>