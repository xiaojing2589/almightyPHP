var Base = function () {

    // ueditor编辑器集合（百度编辑器）
    var ueditors    = {};

    /**
     * 检测滚动
     */
    var windowScroll = function(){
        $(window).scroll(function(event){

            var _this = $('.box--sticky');

            if(_this.length > 0){

                var boxKtPortlet = $('.box-kt-portlet');

                var boxKtPortlets = $('.box-sticky-height');

                // 标题栏高度
                var ktPortletHeadHeight = boxKtPortlet.find('.kt-portlet__head').height();

                // 内容跟位置
                var ktPortletBodyOffset =  boxKtPortlet.find('.kt-portlet__body').offset();

                // 固定块 距离顶部
                var top =  ktPortletHeadHeight > 49 ?  '65px' : '49px';

                var offset = 150;

                var st = document.documentElement.scrollTop;

                if (st >= offset) {
                    boxKtPortlets.height(_this.height());
                    boxKtPortlet.addClass('box-portlet--sticky');
                    _this.css({
                        "z-index":90,
                        "top":top,
                        "left":ktPortletBodyOffset.left,
                        "right":"10px",
                        "padding":"0 10px"
                    });
                }else if (st <= offset) {
                    boxKtPortlets.height(0);
                    _this.removeAttr('style');
                    boxKtPortlet.removeClass('box-portlet--sticky');
                }
            }
        });
    }

    /**
     * 轮询检测
     * @author 仇仇天
     */
    var pollingHandle = function(){
        function handleer() {
            $.ajax({
                url: '/admin.php/admin/ajax/lock',
                async:true,
                type: 'post',
                success: function (res) {
                    if(res.data.lock_status == 1){
                        window.location.href = '/admin.php/admin/user/lock';
                    }
                }
            })
        }
         userLock = setInterval(handleer,5000);
    }

    /**
     * 顶部菜单
     * @author 仇仇天
     */
    var topMenu = function () {

        // 顶部菜单点击触发事件
        $('.top-menus').click(function () {

            // 打开方式
            var $target = $(this).attr('target');

            // 节点标识
            var $mark = $(this).data('mark');

            // 改变顶部菜单样
            styleMenus($mark);

            ajaxMenus($mark);

            return false;
        });

        // 顶部模块点击触发
        $('.top-menus-model').click(function () {

            var $target = $(this).attr('target');

            var data = {
                module_id: $(this).data('module-id') || '',
                module: $(this).data('module') || '',
                controller: $(this).data('controller') || ''
            };

            styleMenus(data);

            if ($('#nav-' + data.module_id).length) {
                location.href = $('#nav-' + data.module_id).find('a').not('.nav-submenu').first().attr('href');
            } else {
                ajaxMenus($mark);
            }
            return false;
        });
    };

    /**
     * 请求获取菜单项
     * @author 仇仇天
     */
    var ajaxMenus = function (mark){
        var defaultUrl = '';
        $.post(base.top_menu_url, {mark:mark}, function (res) {
            if (res.code == 1) {

                var menu = '';

                res.data.forEach(function(item, index){

                    if(!item.is_hide){

                        if(index == 0){
                            menu += '<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">';
                        }else{
                            menu += '<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">';
                        }

                        if(item.url_value !== ''){
                            menu += '<a class="kt-menu__link"" href="/admin.php/'+item.url_value+'" target="'+item.url_target+'">'+
                                        '<i class="kt-menu__link-icon '+item.icon+'"></i>'+
                                        '<span class="kt-menu__link-text">'+item.title+'</span>' +
                                    '</a>';
                        }else{
                            menu += '<a class="kt-menu__link kt-menu__toggle" href="javascript:;">'+
                                        '<i class="kt-menu__link-icon '+item.icon+'"></i>'+
                                        '<span class="kt-menu__link-text">'+item.title+'</span>' +
                                        '<i class="kt-menu__ver-arrow la la-angle-right"></i>' +
                                    '</a>';
                        }

                        if($.isArray(item.child) && item.child.length > 0){

                            menu += '<div class="kt-menu__submenu ">'+
                                        '<span class="kt-menu__arrow"></span>'+
                                        '<ul class="kt-menu__subnav">';

                            item.child.forEach(function(items, indexs){
                                if(indexs == 0 && index == 0 && items.is_hide == 0){
                                    defaultUrl = items.url_value;
                                    menu += '<li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true">' +
                                                '<a class="kt-menu__link" href="/admin.php/'+items.url_value+'" target="'+items.url_target+'">' +
                                                    '<i class="kt-menu__link-icon '+items.icon+'"></i>' +
                                                    '<span class="kt-menu__link-text">'+items.title+'</span>' +
                                                '</a>' +
                                            '</li>';
                                }else if(items.is_hide == 0){
                                    menu += '<li class="kt-menu__item " aria-haspopup="true">'+
                                                '<a class="kt-menu__link" href="/admin.php/'+items.url_value+'" target="'+items.url_target+'">'+
                                                    '<i class="kt-menu__link-icon '+items.icon+'"></i>'+
                                                    '<span class="kt-menu__link-text">'+items.title+'</span>'+
                                                '</a>'+
                                            '</li>';
                                }
                            });

                            menu +=     '</ul>'+
                                    '</div>';
                        }

                        menu +=  '</li>';

                    }

                });

                $(".page-sidebar-menu").html(menu);

                $(".kt-menu__item--here").find('kt-menu__item--active');

                return false;

            } else {
                layer.msg('无任何节点权限', {icon: 5});
            }
        }).fail(function (res) {
            layer.msg('服务器内部错误~', {icon: 5});
        });
    };

    /**
     * 顶部菜单样式
     * @author 仇仇天
     * @param $mark  标识
     */
    var styleMenus = function($mark){

        // 删除所有顶部菜单选中样式
        $('.classic-menu-dropdown').removeClass('kt-menu__item--active');

        // 设置点击的顶部菜单选中样式
        $(".top-menus[data-mark='"+$mark+"']").parent('.classic-menu-dropdown').addClass('kt-menu__item--active');
    };

    /**
     * 日期
     * 文档 https://github.com/eternicode/bootstrap-datepicker ，中文文档请查看 https://www.cnblogs.com/tincyho/p/7978483.html
     * @author 仇仇天
     */
    var helperDatepicker = function(){
        $('.js-datepicker').each(function () {

            // 当前对象
            var $this = $(this);

            // 格式
            var $format = $this.data('format');

            $this.datepicker({
                format:$format,
                language: 'zh-CN'
            });
        });
    };

    /**
     * 时间
     * @author 仇仇天
     */
    var helperTimepicker = function(){
        $('.js-time').each(function(){
            var $input = jQuery(this);
            $input.timepicker({
                minuteStep:1,
                secondStep:1,
                showSeconds:false,
                showMeridian:false
            });
        });
    };

    /**
     * 日期时间，
     * 有关更多示例，请查看 https://github.com/Eonasdan/bootstrap-datetimepicker ，中文文档请查看 https://www.cnblogs.com/dazhangli/p/9002329.html
     * @author 仇仇天
     */
    var helperDatetimepicker = function(){
        $('.js-datetimepicker').each(function(){
            var $this = $(this);
            var $format = $this.data('format') ? $this.data('format') : false;
            $this.datetimepicker({
                todayHighlight: true,
                format: $format,
                language:'zh-CN',
                // 步进值,此数值被当做步进值用于构建小时视图。就是最小的视图是每5分钟可选一次。是以分钟为单位的。
                minuteStep:1,
                // 选完时间后是否自动关闭,当选择一个日期之后是否立即关闭此日期时间选择器。
                autoclose:true,

            });
        });
    };

    /**
     * 时间范围选择，
     * 文档参考：http://stackoverflow.com/questions/11933173/how-to-restrict-the-selectable-date-ranges-in-bootstrap-datepicker
     * @author 仇仇天
     */
    var helperDaterangepicker = function(){
        $('.js-daterange').each(function () {

            var _this = $(this);

            // 格式
            var $format = _this.data('format');

            var arrows;

            if (KTUtil.isRTL()) {
                arrows = {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            } else {
                arrows = {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }

            _this.datepicker({
                rtl: KTUtil.isRTL(),
                language: 'zh-CN',
                format:$format,
                todayHighlight: true,
                templates: arrows,
                orientation:'bottom',
                // 显示清除按钮
                clearBtn:true
            });
        });
    };

    /**
     * 日期格式化
     * @author 仇仇天
     * @param format  日期格式
     */
    var handleDate = function(){
        Date.prototype.format = function(format) {
            var date = {
                "M+": this.getMonth() + 1,
                "d+": this.getDate(),
                "h+": this.getHours(),
                "m+": this.getMinutes(),
                "s+": this.getSeconds(),
                "q+": Math.floor((this.getMonth() + 3) / 3),
                "S+": this.getMilliseconds()
            };
            if (/(y+)/i.test(format)) {
                format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
            }
            for (var k in date) {
                if (new RegExp("(" + k + ")").test(format)) {
                    format = format.replace(RegExp.$1, RegExp.$1.length == 1
                        ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
                }
            }
            return format;
        }
    };

    /**
     * 标签
     * 有关更多示例 https://github.com/xoxco/jQuery-Tags-Input
     * @author 仇仇天
     */
    var helperTagsInputs = function(){
        $('.js-tags').each(function () {
            var _this = $(this);
            _this.tagsInput({
                'height':'100%',
                'width':'100%',
                'defaultText':'添加标签',
            });
        });
    };

    /*
     * 下拉选择器
     * 示例地址：https://developer.snapappointments.com/bootstrap-select/examples/
     * 参数详解地址：https://developer.snapappointments.com/bootstrap-select/options/
     * 事件方法地址：https://developer.snapappointments.com/bootstrap-select/methods/
     * @author 仇仇天
     */
    var helperSelect = function(){
        $('.bs-select').each(function () {
            console.log(123);
            var _this = $(this);

            // 选中方法
            var onselect = _this.data('onselect');

            _this.select2({
                placeholder: "请选择",
                language:'zh-CN'
            });

            // 调用选中方法
            if(onselect){
                _this.on('select2:select', function (e) {
                    // 动态调用方法
                    window[onselect](e);
                });
            }

        });
    };

    /*
     * 下拉选择器2
     * @author 仇仇天
     */
    var helperSelect2 = function(){
        $('.bs-select2').each(function () {

            var _this = $(this);

            // 选中方法
            var onselect = _this.data('onselect');

            _this.select2({
                placeholder: "请选择",
                language:'zh-CN'
            });

            // 调用选中方法
            if(onselect){
                _this.on('select2:select', function (e) {
                    // 动态调用方法
                    window[onselect](e);
                });
            }
        });
    };

    /*
    * 多选下拉选择器
    * 示例地址：https://developer.snapappointments.com/bootstrap-select/examples/
    * 参数详解地址：https://developer.snapappointments.com/bootstrap-select/options/
    * 事件方法地址：https://developer.snapappointments.com/bootstrap-select/methods/
    * @author 仇仇天
    */
    var helperTgSelect = function(){
        $('.tg-select').each(function () {
            var $this = $(this);
            $this.selectpicker({
                iconBase: 'fa',
                tickIcon: 'fa-check'
            });
        });
    };

    /*
     * 滑块范围
     * 参数详解地址：https://github.com/IonDen/ion.rangeSlider，
     * 中文地址：http://www.jq22.com/jquery-info19939
     * @author 仇仇天
     */
    var helperRangeslider = function(){
        $('.js-rangeslider').each(function(){
            var $input = $(this);

            var $type = $input.data('type') !== undefined ? $input.data('type') : 'single';

            var $grid = $input.data('grid') === 'false' ? false : $input.data('type');

            var $min = $input.data('min') !== undefined ? $input.data('min') : 0;

            var $max = $input.data('max') !== undefined ? $input.data('max') : 100;

            var $from = $input.data('from') !== undefined ? $input.data('from') : 0;

            var $to = $input.data('to') !== undefined ? $input.data('to') : 0;

            $input.ionRangeSlider({
                // 设置滑块类型 设置为`type:”double”`时为双滑块，默认为`”single”`单滑块
                type: $type,
                // 坐标分格，设置为`grid:true`时有坐标分格
                grid: $grid,
                // 滑动条最小值
                min: $min,
                // 滑动条最大值
                max: $max,
                // 初始值
                from: $from,
                // 终点值
                to: $to,
            });
        });
    };

    /*
    * 图片上传
    * @author 仇仇天
    */
    var helperUploadImage = function(){
        $('.js-image').each(function(){

            var $this = $(this);

            // 选择图片按钮
            var $uploader_img = $this.find('.uploader-img');

            // 展示图片对象
            var $popover_file = $this.find('.popover-img');

            // 显示图片路径文本域
            var $input_file   = $this.find('.input-img');

            // file 对象
            var $file_img   = $this.find('.file-img');

            // 图片地址
            var imgUrl = null;

            // 获取弹出窗对象
            var popovers = null;

            // 选择图片按钮触发
            $uploader_img.on('click',function(){
                // 模拟触发file
                $file_img.trigger("click");
            });

            // 选择图片
            $file_img.on('change',function(){

                // 预览图片
                imgUrl = URL.createObjectURL(this.files[0]);

                // 设置文本域显示原始图片名称
                $input_file.val(this.files[0].name);

                // 点亮图标
                $popover_file.find('.fa-image').removeClass('font-green');

                $popover_file.find('.fa-image').addClass('font-green');
            });

            // 如果默认有图片点亮
            if($input_file.val()){

                // 点亮图标
                $popover_file.find('.fa-image').addClass('font-green');

                imgUrl = $input_file.val();

            }

            // 鼠标移入移出事件
            $popover_file.hover(function() {
                // 显示 图片
                if(imgUrl){
                    popovers = layer.tips('<img src="'+imgUrl+'"style="max-width: 100px;max-height: 100px">', this, {
                        tips: [3]
                    });
                }
            }, function() {
                // 不显示
                if(popovers){
                    layer.close(popovers);
                }

            });

        });
    };

    /*
    * 多图片上传
    * @author 仇仇天
    */
    var helperUploadImages = function(){

        $('.js-images').each(function(){

            var $this = $(this);

            // 字段名称
            var field_name = $this.data('name');

            // 数组存放图片数据
            base.imagesList[field_name] = [];

            // 提交的form 对象
            var form_class = $this.data('form');

            var form_obj = $('.'+form_class)

            // 选择图片按钮
            var $uploader_img = $this.find('.el-upload');

            // 显示图片路径文本域
            var $input_file   = $this.find('.input-img');

            // file 对象
            var $file_img   = $this.find('.el-upload-input');

            // 预览图片列表
            var $el_upload_list  = $this.find('.el-upload-list');

            // 点击选择图片按钮触发
            $uploader_img.on('click',function(){
                // 模拟触发file
                $file_img.trigger("click");
            });

            // 选择图片
            $file_img.on('change',function(){

                // 获取选择图片数组
                var $fileObjArr = this.files;

                // 渲染图片预览
                var $html = '';

                // 设置图片
                for(var i=0;i<$fileObjArr.length;i++){

                    // 图片信息
                    var fileObjInfo = $fileObjArr[i]

                    // 加入数组
                    base.imagesList[field_name].push(fileObjInfo);

                    // 图片地址
                    var $filePath = URL.createObjectURL(fileObjInfo);

                    $html += '<li class="el-upload-list-item is-ready">' +
                                '<div>' +
                                    '<img src="'+$filePath+'" alt="" class="el-upload-list-item-thumbnail">' +
                                    '<span class="el-upload-list-item-actions">' +
                                        '<span class="el-upload-list-item-preview el-preview" data-filename="'+fileObjInfo.name+'">' +
                                            '<i class="fa fa-search"></i>' +
                                        '</span>' +
                                        '<span class="el-upload-list-item-delete el-download" data-filename="'+fileObjInfo.name+'">' +
                                            '<i class="fa fa-cloud-download"></i>' +
                                        '</span>' +
                                        '<span class="el-upload-list-item-delete el-delete" data-filename="'+fileObjInfo.name+'">' +
                                            '<i class="fa fa-trash"></i>' +
                                        '</span>' +
                                    '</span>' +
                                '</div>' +
                            '</li>';
                }

                if($html){
                    $el_upload_list.append($html);
                }

            });

            // 预览触发
            $this.on('click','.el-preview',function(){

            });

            // 下载触发
            $this.on('click','.el-download',function(){

            });

            // 删除触发
            $this.on('click','.el-delete',function(){
                var delete_htis = $(this);
                var filename = delete_htis.data('filename');
                var liObj = delete_htis.parents('li.el-upload-list-item');
                var $fileListLs = [];
                for(var i=0;i<base.imagesList[field_name].length;i++){
                    if(base.imagesList[field_name][i].name != filename){
                        $fileListLs.push(base.imagesList[field_name][i]);
                    }
                }
                base.imagesList[field_name] = $fileListLs;
                liObj.remove();
            });

        });
    };

    /*
    * 文件上传
    * @author 仇仇天
    */
    var helperUploadFile = function(){
        $('.js-file').each(function(){

            var $this = $(this);

            // 选择文件按钮
            var $uploader_file = $this.find('.uploader-file');

            // 显示文件路径文本域
            var $input_file   = $this.find('.input-file');

            // file 对象
            var $file_file   = $this.find('.file-file');

            // 选择文件按钮触发
            $uploader_file.on('click',function(){
                // 模拟触发file
                $file_file.trigger("click");
            });

            // 选择文件
            $file_file.on('change',function(){
                // 设置文本域显示原始文件名称
                $input_file.val(this.files[0].name);
            });
        });
    };

    /*
   * 多文件上传
   * @author 仇仇天
   */
    var helperUploadFiles = function(){

        $('.js-files').each(function(){

            var $this = $(this);

            // 获取name
            var name = $this.data('name');

            // 点击选择文件按钮触发
            $this.on('click','.filse-btn',function(){

                var control = $(this).parents('.control');

                var filseFile = control.find('.filse-file');

                // 选择文件
                filseFile.trigger("click");

                // 选择图片
                filseFile.on('change',function(){
                    // 文件名称
                    control.find('.filse-text').val(this.files[0].name);
                });
            });

            // 点击添加
            $this.on('click','.files-add',function(){

                var filesList = $(this).prev('.files-list');

                var html = '<div class="input-group control">' +
                                '<input type="text" class="form-control filse-text"placeholder="请选择文件" disabled>' +
                                '<input type="file" name="'+name+'[]" class="filse-file" style="display: none">' +
                                '<div class="input-group-append">' +
                                    '<button class="btn btn-primary filse-btn" type="button">选择文件</button>' +
                                    '<button class="btn btn-danger filse-del" type="button" >删除</button>' +
                                '</div>' +
                            '</div>' +
                            '<div class="kt-space-5" class="partition"></div>';

                filesList.append(html);
            });

            // 点击删除
            $this.on('click','.filse-del',function(){

                var control = $(this).parents('.control');

                // 删除隔断
                control.next().remove();

                // 删除控件
                control.remove();

            });

        });
    };

     /*
      * 百度编辑器
      * @author 仇仇天
      */
    var helperUeditor = function(){
        $('.js-ueditor').each(function () {
            var ueditor_name = $(this).attr('name');
            ueditors[ueditor_name] = UE.getEditor(ueditor_name, {
                initialFrameHeight:400,  //初始化编辑器高度,默认320
                autoHeightEnabled:false,  //是否自动长高
                maximumWords: 50000, //允许的最大字符数
                serverUrl: base.ueditor_upload_url
            });
        });
    };

     /*
      * 开关组件
      * @author 仇仇天
      */
    var handleBootstrapSwitch = function() {
        $('.btn-make-switch').each(function(){
            var $this = $(this);
            var url = $this.data('url');
            var $on_obj = $this.find('button.on-button');
            var $off_obj = $this.find('button.off-button');
            // 开
            $on_obj.on('click',function(){
                var $on_input_obj = $(this).find('input');
                $on_input_obj.attr('checked', 'checked');
                $on_input_obj.attr('checked', 'true');
                $off_obj.find('input').removeAttr('checked');
            });
            //关
            $off_obj.on('click',function(){
                var $off_input_obj = $(this).find('input');
                $off_input_obj.attr('checked', 'checked');
                $off_input_obj.attr('checked', 'true');;
                $on_obj.find('input').removeAttr('checked');
            });

        });
    };

    /*
    * 提示信息
    * @author 仇仇天
    */
    var helperExplanation = function(){
        $('.js-explanation').each(function(){
            var $this = $(this);

            // 操作提示展开收起
            $this.on("click",'.explanationZoom',function(){
                var $_this = $(this);
                if($_this.hasClass("shopUp")){
                    $_this.removeClass("shopUp");
                    $_this.attr("title","收起提示");
                    $_this.html('<i class="fa fa-minus"></i>');
                    $this.find(".ex_tit").css("margin-bottom",10);
                    $this.animate({width:'100%'},300,function(){
                        $this.find("ul").show();
                    });
                }else{
                    $_this.addClass("shopUp");
                    $_this.attr("title","提示相关设置操作时应注意的要点");
                    $_this.html('<i class="fa fa-plus"></i>');
                    $this.find(".ex_tit").css("margin-bottom",0);
                    $this.animate({ width:"123"},300);
                    $this.find("ul").hide();
                }
            });

        });
    };

    /**
     * 处理ajax方式的post提交
     * @author 仇仇天
     */
    var helperAjaxPost = function () {
        $(document).delegate('.ajax-post', 'click', function () {

            var msg;
            var self = $(this);

            // 获取 url 地址
            var ajax_url = self.attr("href") || self.data("url");

            // 获取form 对象
            var target_form = self.attr("target-form");

            //  获取提示信息
            var text = self.data('tips');

            // 提示标题
            var title = self.data('title') || '确定要执行该操作吗？';

            // 确认按钮
            var confirm_btn = self.data('confirm') || '确定';

            // 取消按钮
            var cancel_btn = self.data('cancel') || '取消';

            // 获取form 对象
            var form = $('form[name=' + target_form + ']');

            // 如果 没有
            if (form.length === 0) {
                // 相关类名查找
                form = $('.' + target_form);
            }

            // 获取form序列对象
            var form_data = form.serialize();

            if ("submit" === self.attr("type") || ajax_url) {

                // 不存在“.target-form”元素则返回false
                if (undefined === form.get(0)) return false;

                // 节点标签名为FORM表单
                if ("FORM" === form.get(0).nodeName) {

                    ajax_url = ajax_url || form.get(0).action;

                    // 提交确认
                    if (self.hasClass('confirm')) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function (res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function (res) {
                                pageLoader('hide');
                                tips($(res.responseText).find('h1').text() || '服务器内部错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                } else if ("INPUT" === form.get(0).nodeName || "SELECT" === form.get(0).nodeName || "TEXTAREA" === form.get(0).nodeName) {
                    // 如果是多选，则检查是否选择
                    if (form.get(0).type === 'checkbox' && form_data === '') {
                        Dolphin.notify('请选择要操作的数据', 'warning');
                        return false;
                    }

                    // 提交确认
                    if (self.hasClass('confirm')) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function (res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function (res) {
                                pageLoader('hide');
                                tips($(res.responseText).find('h1').text() || '服务器内部错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                } else {
                    if (self.hasClass("confirm")) {
                        swal({
                            title: title,
                            text: text || '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d26a5c',
                            confirmButtonText: confirm_btn,
                            cancelButtonText: cancel_btn,
                            closeOnConfirm: true,
                            html: false
                        }, function () {
                            pageLoader();
                            self.attr("autocomplete", "off").prop("disabled", true);
                            form_data = form.find("input,select,textarea").serialize();

                            // 发送ajax请求
                            jQuery.post(ajax_url, form_data, {}, 'json').success(function (res) {
                                pageLoader('hide');
                                msg = res.msg;
                                if (res.code) {
                                    if (res.url && !self.hasClass("no-refresh")) {
                                        msg += " 页面即将自动跳转~";
                                    }
                                    tips(msg, 'success');
                                    setTimeout(function () {
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        return self.hasClass("no-refresh") ? false : void(res.url && !self.hasClass("no-forward") ? location.href = res.url : location.reload());
                                    }, 1500);
                                } else {
                                    jQuery(".reload-verify").length > 0 && jQuery(".reload-verify").click();
                                    tips(msg, 'danger');
                                    setTimeout(function () {
                                        // 刷新父窗口
                                        if (res.data && (res.data === '_parent_reload' || res.data._parent_reload)) {
                                            parent.location.reload();
                                            return false;
                                        }
                                        // 关闭弹出框
                                        if (res.data && (res.data === '_close_pop' || res.data._close_pop)) {
                                            var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                                            parent.layer.close(index);
                                            return false;
                                        }
                                        self.attr("autocomplete", "on").prop("disabled", false);
                                    }, 2000);
                                }
                            }).fail(function (res) {
                                pageLoader('hide');
                                tips($(res.responseText).find('h1').text() || '服务器内部错误~', 'danger');
                                self.attr("autocomplete", "on").prop("disabled", false);
                            });
                        });
                        return false;
                    } else {
                        form_data = form.find("input,select,textarea").serialize();
                        self.attr("autocomplete", "off").prop("disabled", true);
                    }
                }

                // 直接发送ajax请求
                layer.load(2);
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    false:false,
                    dataType: 'json',
                    data: form_data,
                    success: function (res) {
                        layer.closeAll('loading');
                        self.attr("autocomplete", "on").prop("disabled", false);
                        if(res.code == 1){
                            layer.msg(res.msg, {icon: 6});
                        }else if(res.code === 0){
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    fail:function(res){
                        layer.closeAll('loading');
                        layer.msg('服务器内部错误~', {icon: 5});
                    }
                });

            }

            return false;
        });
    };

    /**
     * 颜色选择器
     * @author 仇仇天
     */
    var handleColorPicker = function() {
        $('.js-colorpicker').each(function() {
            var $this = $(this);
            $this.minicolors({
                control: $(this).attr('data-control') || 'hue',
                defaultValue: $(this).attr('data-defaultValue') || '',
                inline: $(this).attr('data-inline') === 'true',
                letterCase: $(this).attr('data-letterCase') || 'lowercase',
                opacity: $(this).attr('data-opacity'),
                position: $(this).attr('data-position') || 'bottom left',
                change: function(hex, opacity) {
                    if (!hex) return;
                    if (opacity) hex += ', ' + opacity;
                    if (typeof console === 'object') {
                        console.log(hex);
                    }
                },
                theme: 'bootstrap'
            });
        });
    };

    /**
     * 格式化输入
     * @author 仇仇天
     */
    var handleInputMasks = function () {
        $('.js-masked').each(function() {
            var $this = $(this);
            // 格式
            var $mask = $this.data('mask');
            $this.inputmask({
                mask: $mask
            });

        });
    };

    /**
     * 图标
     * @author 仇仇天
     */
    var handleIconContent = function () {
        $('.js-icon-picker').each(function() {

            var $this = $(this);

            // 格式
            var $mask = $this.data('mask');

            // 打开图标选择器
            $this.click(function(){
                curr_icon_picker = $(this);
                var icon_input = curr_icon_picker.find('.icon_input');
                if (icon_input.is(':disabled')) {
                    return;
                }
                layer_icon = layer.open({
                    type: 1,
                    title: '图标选择器',
                    area: ['90%', '90%'],
                    scrollbar: false,
                    content: $('#icon_tab')
                });
            });

            // 选择图标
            $('.kt-demo-icon').click(function () {
                var icon = $(this).find('i').attr('class');
                curr_icon_picker.find('.input-group-addon.icon').html('<i class="'+icon+'"></i>');
                curr_icon_picker.find('.icon_input').val(icon);
                layer.close(layer_icon);
            });

            // 搜索图片
            var $searchItems = $('.js-icon-list > li');
            var $searchValue = '';
            $('.js-icon-search').on('keyup', function(){
                $searchValue = $(this).val().toLowerCase();

                if ($searchValue.length) {
                    $searchItems.hide();

                    $('code', $searchItems)
                        .each(function(){
                            if ($(this).text().match($searchValue)) {
                                $(this).parent('li').show();
                            }
                        });
                } else if ($searchValue.length === 0) {
                    $searchItems.show();
                }
            });

        });
    };

    /**
     * 下拉联动
     * @author 仇仇天
     */
    var handleLinkage = function() {
        $('.js-linkage').each(function() {
            var $this = $(this);

            // 配置
            try {
                var $option = $this.data('config');
                $option = $option.replace(/”/g, '"');
                $option = JSON.parse($option);
            }catch(err) {
                var $option = [];
            }

            var $config_length = $option.config.length;

            var cxSelectConfig = {};

            if($option.type == 1){
                cxSelectConfig.url = $option.url;
            }else{
                cxSelectConfig.data = $option.value;
            }

            if($option.empty_style){
                cxSelectConfig.emptyStyle = 'none';
            }

            if($option.first_title){
                cxSelectConfig.firstTitle = $option.first_title;
            }

            if($option.first_value){
                cxSelectConfig.firstValue = $option.first_value;
            }

            if($option.data_find){
                cxSelectConfig.dataFind = $option.data_find;
            }

            if($option.json_name){
                cxSelectConfig.jsonName = $option.json_name;
            }

            if($option.json_value){
                cxSelectConfig.jsonValue = $option.json_value;
            }

            if($option.json_sub){
                cxSelectConfig.jsonSub = $option.json_sub;
            }


            if($config_length > 0){

                // 设置分组个数
                var $col_md = 12 / $config_length;
                if($col_md < 1){
                    $col_md = 1;
                }else{
                    $col_md = Math.trunc($col_md);
                }

                var selects_config = [];

                var manner_data = [];

                // 渲染
                var html = '<div class="row">';
                $.each($option.config,function(index,element){

                    // var show =  index > 0 ? 'none' : 'inline';

                    var show = 'inline'

                    selects_config.push(element.name);

                    manner_data.push(element.title);


                    html= html + '<div class="col-md-'+$col_md+'" style="display: '+show+'">' +
                        '<div class="form-group">';

                    if($option.manner){
                        if(element.title){
                            html= html +'<label class="control-label col-md-3">'+element.title+'</label>' ;
                        }
                    }

                    if($option.manner){
                        if(element.title){
                            html= html +'<div class="col-md-9">' ;
                        }else{
                            html= html +'<div class="col-md-12">' ;
                        }
                    }else{
                        html= html +'<div class="col-md-12">' ;
                    }

                    html= html +'<select class="bs-select form-control '+element.name+'" name="'+element.name+'" data-url="'+element.url+'"></select></div></div></div>';

                })
                html = html + '</div>';
                $this.append(html);

                if(!$option.manner){
                    cxSelectConfig.mannerData = manner_data;
                }

                cxSelectConfig.selects = selects_config;
                $this.cxSelect(cxSelectConfig);
            }

        });
    };

    /**
     * 数组
     * @author 仇仇天
     */
    var handleArray = function() {
        $('.js-array').each(function() {

            var _this = $(this);

            // 编辑器
            var $jsoneditors = _this.find('.jsoneditors');

            var $jsoneditors_value = _this.find('.jsoneditors_value');

            var $value = $jsoneditors_value.val();

            // 创建json编辑器
            var options = {

                // 编辑触发
                onChangeText:function(staingJson){
                    $jsoneditors_value.val(staingJson);
                },

                // 默认展示类型
                mode: 'code',

                // 设置可展示类型 'text', 'code','tree','view','form','preview'
                modes: ['code','tree']
            };

            var jspneditor = new JSONEditor($jsoneditors[0], options);

            //设置 json
            jspneditor.set($value ? JSON.parse($value) : []);
        })
    };

    /**
     * 视频
     * @author 仇仇天
     */
    var handleVideo = function() {
        $('.js-video').each(function() {
            var _this = $(this);
            _this.click(function(){
                console.log(1);
            });
            // layer.open({
            //     title:'视频',
            //     type: 1,
            //     skin: 'layui-layer-rim', //加上边框
            //     area: ['420px', '100px'], //宽高
            //     content: '<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="100" height="100"poster="http://vjs.zencdn.net/v/oceans.png">' +
            //         '<source src="http://vjs.zencdn.net/v/oceans.mp4" type="video/mp4">' +
            //         '</video>'
            // });
        });
    };

    /**
     * 树
     * @author 仇仇天
     */
    var handleTree = function() {

        $('.js-tree').each(function() {
            var _this = $(this);

            // 设置是否开启异步加载模式
            var enable = _this.data('async_enable') ? true : false;

            // 设置异步Ajax 获取数据的 URL 地址
            var url = _this.data('async_url') ? _this.data('async_url') : '';

            // 展示配置
            var view = _this.data('view') ;

            // 数据参数配置
            var data = _this.data('data') ;

            // 异步请求配置
            var async = _this.data('async') ;

            // 复选/单选配置
            var check = _this.data('check') ;

            // 事件方法
            var callback = _this.data('callback') ;
            $.each(callback,function(i,v){
                callback[i] = window[v];
            })

            // 唯一标识
            var markname = _this.data('markname');

            // 配置参数
            var setting = {
                // 异步配置
                async: async,
                // 数据配置
                data: data,
                // 复选/单选 选项
                check: check,
                // 展示配置
                view: view,
                // 事件方法
                callback:callback
            };

            if(markname){
                // eval("base.ztrees."+markname+"=" + 1);
                base.ztrees[markname] = $.fn.zTree.init(_this, setting);
            }else{
                $.fn.zTree.init(_this, setting);
            }

            // zTreeObj.expandAll(false);

        });
    };

    /**
     * 多行文本
     * @author 仇仇天
     */
    var helperTextarea = function(){
        $('.js-textarea').each(function () {
            // 当前对象
            var $this = $(this);
            autosize($this);
        });
    };





    /**
     * 等比例缩放
     * @param imgObj 图片元素对象(jquery)
     * @param boxWidth 需要设置 宽度
     * @param boxHeight 需要设置 高度
     * @constructor
     */
    var funDrawImg = function (imgObj, boxWidth, boxHeight) {
        var imgWidth = imgObj.width();
        var imgHeight = imgObj.height();
        //比较imgBox的长宽比与img的长宽比大小
        if ((boxWidth / boxHeight) >= (imgWidth / imgHeight)) {
            //重新设置img的width和height
            imgObj.width((boxHeight * imgWidth) / imgHeight);
            imgObj.height(boxHeight);
            //让图片居中显示
            var margin = (boxWidth - imgObj.width()) / 2;
            imgObj.css("margin-left", margin);
        } else {
            //重新设置img的 width和height
            imgObj.width(boxWidth);
            imgObj.height((boxWidth * imgHeight) / imgWidth);
            // 让图片居中显示
            // var margin = (boxHeight - imgObj.height()) / 2;
            // imgObj.css("margin-top", margin);
        }
    }

    /**
     * 计算字节
     * @author 仇仇天
     * @param format  日期格式
     */
    var funDyteConvert = function(bytes) {
        if (isNaN(bytes)) {
            return '';
        }
        var symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        var exp = Math.floor(Math.log(bytes)/Math.log(2));
        if (exp < 1) {
            exp = 0;
        }
        var i = Math.floor(exp / 10);
        bytes = bytes / Math.pow(2, 10 * i);

        if (bytes.toString().length > bytes.toFixed(2).toString().length) {
            bytes = bytes.toFixed(2);
        }
        return bytes + ' ' + symbols[i];
    };

    /**
     * 模拟form表单的提交
     * @author 仇仇天
     * @param url    跳转地址
     * @param params  传输参数
     * @param method 传输方式 get/post
     * @param target 跳转方式
     *              _blank 在新窗口中打开。
     *              _self	默认。在相同的框架中打开。
     *              _parent	在父框架集中打开。
     *               _top	在整个窗口中打开。
     *               framename	在指定的框架中打开。
     */
    var funSimulationForm = function(url, params,method, target){

        // 创建form对象
        var tempform = document.createElement("form");

        // 跳转地址
        tempform.action = url;

        // 请求方式
        tempform.method = (method=='get' || method=='GET') ? method : "post";

        // 隐藏form
        tempform.style.display="none"

        //设置跳转方式
        if(target) {
            tempform.target = target;
        }

        // 设置参数
        for (var x in params) {

            // 创建表单对象
            var opt = document.createElement("input");

            // 表单name
            opt.name = x;

            // 表单值
            opt.value = params[x];

            // 将表单项加入表单
            tempform.appendChild(opt);
        }

        // 创建提交按钮
        var opt = document.createElement("input");

        // 提交按钮类型
        opt.type = "submit";

        // 将提交项加入表单
        tempform.appendChild(opt);

        // 渲染 form DOM
        document.body.appendChild(tempform);

        // 模拟提交
        tempform.submit();

        // 删除 form DOM
        document.body.removeChild(tempform);
    };

    /**
     * 信息提示
     * @author 仇仇天
     * @param type 提示类型
     * @param msg  提示信息
     * @param time 秒数
     * @param url  跳转地址
     * @param data 传输数据
     */
    var funTips = function(type,msg,time,url,data){
        var config = {};
        msg = msg ? msg : type == 'error' ? '操作失败' : '操作成功';
        msg = (type == 'error') ? msg : msg+'</br>窗口将在<span class="layer-tips-time font-red-sunglo"></span>秒后跳转(<a href="'+url+'">立即跳转</a>)';
        config.icon = (type == 'error') ? 5 : 6;
        config.title = '提示信息';
        config.title = (type == 'error') ? config.title+'(错误)' : config.title+'(成功)';
        config.time = (type == 'error') ? 2000 : 100000;
        config.shade = 0.6;
        config.success = function(layero,index){
            if(type == 'success'){
                var i = time ? time : 3;
                var timer = null;
                var fn = function() {
                    layero.find(".layer-tips-time").html(i);
                    if(!i) {
                        layer.close(index);
                        clearInterval(timer);
                        if(url){
                            window.location.href = url;
                        }
                    }
                    i--;
                };
                timer = setInterval(fn, 1000);
                fn();
            }
        }
        layer.msg(msg,config);
    };

    /**
     * 加载框
     * @param element 遮罩元素
     * @param options  参数 详情参考:http://jquery.malsup.com/block/
     * @returns {*|void}
     */
    var funLoad = function(element,options){

        element = element ? element : 'body';

        var defaultOptions = {overlayColor: '#000000',type: 'v2',state: 'primary',message: '正在提交...'};

        options = options ?  options : {};

        $.extend(defaultOptions, options)

       return  KTApp.block(element, defaultOptions);

    }

    /**
     *  关闭加载框
     * @param element  关闭遮罩元素
     * @returns {*|void}
     */
    var funUnLoad = function(element){

        element = element ? element : 'body';

        return KTApp.unblock(element);

    }

    /**
     * 信息提示框
     * @param type
     * @param options
     */
    var funMessage = function(title,type,msg,options){

        var defoultOptions = {
            type: type ? type : 'success',
            title: title,
            text: msg,
            confirmButtonText: '确定',
            timer: 1000
        }

        options = options ?  options : {};

        $.extend(defoultOptions, options);

        swal.fire(defoultOptions);
    }

    /**
     * 模态框
     * @author 仇仇天
     * @param options 参数
     */
    var funModal = function(options){
        var defoultOptions = {
            // id
            id:'modal_'+new Date().getTime()+'_modal',
            // 标题
            title: '',
            // 宽度
            width:'100%',
            //高度
            height:'100%',
            // 是否显示
            isShow:true,
            // 主体内容
            content: '暂无内容！',
            // 确定按钮
            determineButton:{
                // 文本
                text:'确定',
                // 图标
                icons:null,
                // 是否显示
                isShow:true
            },
            // 关闭按钮
            closeButton:{
                // 文本
                text:'关闭',
                // 图标
                icons:null,
                // 是否显示
                isShow:true
            },
            // 是否通过ajax加载内容
            isAjax:false,
            // ajax加载
             ajax:{
                // 请求方式
                 type: 'POST',
                 // 请求地址
                 url: '',
                 // 请求参数
                 data:{},

            }
        }

        // 覆盖默认参数
        $.extend(defoultOptions, options);

        if(defoultOptions.isAjax){
            funLoad();
            $.ajax({
                type: defoultOptions.ajax.type,
                url:  defoultOptions.ajax.url,
                data: defoultOptions.ajax.data,
                // 同步
                false:false,
                dataType: 'html',
                success: function (res) {
                    funUnLoad();
                    defoultOptions.content = res;
                },
                fail:function(res){
                    funUnLoad();
                    funMessage('服务器内部错误~','error');
                }
            });
        }

        // 确定按钮
        var  determineButton = defoultOptions.determineButton.isShow ? '<button type="button" class="btn btn-primary btn-save">'+defoultOptions.determineButton.text+'</button>' : '';

        // 关闭按钮
        var  closeButton = defoultOptions.closeButton.isShow ?  '<button type="button" class="btn btn-secondary" data-dismiss="modal">'+defoultOptions.closeButton.text+'</button>' : '';

        var height = 700;

        // html 内容
        var html = '<div class="modal fade" id="'+defoultOptions.id+'">'+
                        '<div class="modal-dialog" role="document" style="max-width:90%">'+
                            '<div class="modal-content">'+
                                '<div class="modal-header">'+
                                    '<h4 class="modal-title">模态框头部</h4>'+
                                    '<button type="button" class="close" data-dismiss="modal"></button>'+
                                '</div>'+
                                '<div class="modal-body"><div class="kt-scroll" data-scroll="true" data-height="'+height+'">'+defoultOptions.content+'</div></div>'+
                                '<div class="modal-footer">'+
                                    determineButton+
                                    closeButton+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';

       // 渲染页面
        $('.modalnode').after(html);

        // 主体对象
        var boy = $('#'+defoultOptions.id);

        // console.log(boy.find());


        boy.modal({
            // 包含模态背景元素。或者，为点击时不关闭模式的背景指定静态。
            backdrop:true,
            // 是否显示
            show:defoultOptions.isShow
        });

        return  boy;
    }


    return {

        // 初始化
        init: function () {
            windowScroll();
            // 轮询
            pollingHandle();
            // 顶部菜单
            topMenu();
            // 日期
            helperDatepicker();
            // 时间
            helperTimepicker();
            // 日期时间
            helperDatetimepicker();
            // 时间范围选择
            helperDaterangepicker();
            // 标签
            helperTagsInputs();
            // 下拉选择器
            helperSelect();
            // 下拉选择器2
            helperSelect2();
            // 多选下拉选择器
            helperTgSelect();
            // 范围
            helperRangeslider();
            // 图片上传
            helperUploadImage();
            // 多图上传
            helperUploadImages();
            // 文件上传
            helperUploadFile();
            // 百度编辑器
            helperUeditor();
            // 提示信息
            helperExplanation();
            // ajaxPost提交
            helperAjaxPost();
            // 取色器
            handleColorPicker();
            // 格式化输入
            handleInputMasks();
            // 图标选择
            handleIconContent();
            // 日期格式化
            handleDate();
            // 联动下拉
            handleLinkage();
            // 数组
            handleArray();
            // 视频
            handleVideo();
            // 树
            handleTree();
            // 多行文本
            helperTextarea();
            // 多文件
            helperUploadFiles();
        },

        // 模拟表单
        funSimulationForm: function(url, params,method, target){
            funSimulationForm(url, params,method, target);
        },

        // 信息提示
        funTips: function(type,msg,time,url,data){
            funTips(type,msg,time,url,data);
        },

        // 计算字节
        funDyteConvert: function(bytes){
            funDyteConvert(bytes);
        },

        // 图片等比列缩放
        funDrawImg: function(imgObj, boxWidth, boxHeight){
            funDrawImg(imgObj, boxWidth, boxHeight);
        },

        // 开启加载框
        funLoad:function(element,options){
            return funLoad(element,options);
        },

        // 关闭加载框
        funUnLoad:function(element){
            return funUnLoad(element);
        },

        // 信息提示
        funMessage:function(title,type,msg,options){
            funMessage(title,type,msg,options);
        },

        // 模态窗
        funModal:function(options){
            return funModal(options);
        },
    };

}();
jQuery(document).ready(function() {
    Base.init();
});
