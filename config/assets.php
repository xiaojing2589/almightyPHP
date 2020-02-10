<?php
// 资源路径配置
return [
    // 默认加载
    'core_js' => [
        "__GLODAL_PLUGINS__/jquery/dist/jquery.js",
    ],

    // 默认加载
    'core_css' => [
        // 字体
        "__GLODAL_PLUGINS__/socicon/css/socicon.css",
        // 字体
        "__GLODAL_CUSTOM__/vendors/line-awesome/css/line-awesome.css",
        // 字体
        "__GLODAL_CUSTOM__/vendors/flaticon/flaticon.css",
        // 字体
        "__GLODAL_CUSTOM__/vendors/flaticon2/flaticon.css",
        // 字体
        "__GLODAL_PLUGINS__/@fortawesome/fontawesome-free/css/all.min.css",
    ],

    // 后台核心插件css
    'admin_core_plugin_css'=>[
        // Bootstrap 触针 输入框
        "__GLODAL_PLUGINS__/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css",
        // Bootstrap 下拉列表
        "__GLODAL_PLUGINS__/bootstrap-select/dist/css/bootstrap-select.css",
        // 下拉列表2
        "__GLODAL_PLUGINS__/select2/dist/css/select2.min.css",
        // Bootstrap 开关
        "__GLODAL_PLUGINS__/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css",
        // 动画
        "__GLODAL_PLUGINS__/animate.css/animate.css",
        // 消息通知
        "__GLODAL_PLUGINS__/toastr/build/toastr.css",
        // 弹窗提示插件
        "__GLODAL_PLUGINS__/sweetalert2/dist/sweetalert2.css"
    ],

    // 后台核心css
    'admin_core_css'=>[
        // 核心
        "__ADMIN_CSS__/style.bundle.css",
        "__ADMIN_CSS__/base.css",
        // 布局
        "__ADMIN_CSS__/skins/header/base/light.css",
        "__ADMIN_CSS__/skins/header/menu/light.css",
        "__ADMIN_CSS__/skins/brand/dark.css",
        "__ADMIN_CSS__/skins/aside/dark.css"
    ],

    // 后台核心插件js
    'admin_core_plugin_js'=>[
        //
        "__GLODAL_PLUGINS__/jquery-form/dist/jquery.form.min.js",
        // 定位弹窗
        "__GLODAL_PLUGINS__/popper.js/dist/umd/popper.js",
        // 核心bootstrap
        "__GLODAL_PLUGINS__/bootstrap/dist/js/bootstrap.min.js",
        // cookie操作
        "__GLODAL_PLUGINS__/js-cookie/src/js.cookie.js",
        // 日期时间事件操作
        "__GLODAL_PLUGINS__/moment/min/moment.min.js",
        // 提示操作
        "__GLODAL_PLUGINS__/tooltip.js/dist/umd/tooltip.min.js",
        // 滚动条美化
        "__GLODAL_PLUGINS__/perfect-scrollbar/dist/perfect-scrollbar.js",
        // 网页滚动固定浮动
        "__GLODAL_PLUGINS__/sticky-js/dist/sticky.min.js",
        // 数字和资金格式
        "__GLODAL_PLUGINS__/wnumb/wNumb.js",
        // 遮罩
        "__GLODAL_PLUGINS__/block-ui/jquery.blockUI.js",
        // 弹窗
        "__GLODAL_PLUGINS__/layer/layer.js",
        // 根据内容的大小自动调整Textarea
        "__GLODAL_PLUGINS__/autosize/dist/autosize.js",
        // 弹窗提示插件
        "__GLODAL_PLUGINS__/sweetalert2/dist/sweetalert2.min.js",
        "__GLODAL_CUSTOM__/js/vendors/sweetalert2.init.js",
        // Bootstrap 触针 输入框
        "__GLODAL_PLUGINS__/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js",
        // Bootstrap 输入框最大输入数量
        "__GLODAL_PLUGINS__/bootstrap-maxlength/src/bootstrap-maxlength.js",
        // Bootstrap 下拉列表效果
        "__GLODAL_CUSTOM__/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js",
        // Bootstrap 下拉列表
        "__GLODAL_PLUGINS__/bootstrap-select/dist/js/bootstrap-select.js",
        //  下拉列表2
        "__GLODAL_PLUGINS__/select2/dist/js/select2.full.min.js",
        "__GLODAL_PLUGINS__/select2/dist/js/i18n/zh-CN.js",
        // Bootstrap 开关
        "__GLODAL_PLUGINS__/bootstrap-switch/dist/js/bootstrap-switch.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-switch.init.js",
        // 自动补全
        "__GLODAL_PLUGINS__/typeahead.js/dist/typeahead.bundle.js",
        // Bootstrap 提示框
        "__GLODAL_PLUGINS__/bootstrap-notify/bootstrap-notify.min.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-notify.init.js",
        // 消息通知提示
        "__GLODAL_PLUGINS__/toastr/build/toastr.min.js",
        // 滚动监听
        "__GLODAL_PLUGINS__/waypoints/lib/jquery.waypoints.js",
        // promise
        "__GLODAL_PLUGINS__/es6-promise-polyfill/promise.min.js",
        // 验证
        "__GLODAL_PLUGINS__/jquery-validation/dist/jquery.validate.js",
        "__GLODAL_PLUGINS__/jquery-validation/dist/localization/messages_zh.js",
        "__GLODAL_PLUGINS__/jquery-validation/dist/additional-methods.js",
        "__GLODAL_CUSTOM__/js/vendors/jquery-validation.init.js",
        // XSS 过滤器
        "__GLODAL_PLUGINS__/dompurify/dist/purify.js"
    ],

    // 后台核心js
    'admin_core_js'=>[
        // 重写 jquery Ajax
        "__GLODAL_PLUGINS__/newajax.js",
        // 脚本绑定
        "__ADMIN_JS__/scripts.bundle.js",
        "__ADMIN_JS__/base.js",
    ],

    // 幻灯片和轮播图
    'owlCarousel_js'=>[
        "__GLODAL_PLUGINS__/owl.carousel/dist/owl.carousel.js",
    ],
    'owlCarousel_css' => [
        "__GLODAL_PLUGINS__/owl.carousel/dist/assets/owl.carousel.css",
        "__GLODAL_PLUGINS__/owl.carousel/dist/assets/owl.theme.default.css"
    ],

    // 拖拽上传
    'dropzone_js'=>[
        "__GLODAL_PLUGINS__/dropzone/dist/dropzone.js",
    ],
    'dropzone_css' => [
        "__GLODAL_PLUGINS__/dropzone/dist/dropzone.css"
    ],

    // 编辑器
    'summernote_js'=>[
        "__GLODAL_PLUGINS__/summernote/dist/summernote.js",
    ],
    'summernote_css' => [
        "__GLODAL_PLUGINS__/summernote/dist/summernote.css"
    ],

    // Markdown 编辑器
    'markdown_js'=>[
        "__GLODAL_PLUGINS__/markdown/lib/markdown.js",
        "__GLODAL_PLUGINS__/bootstrap-markdown/js/bootstrap-markdown.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-markdown.init.js",
    ],
    'markdown_css' => [
        "__GLODAL_PLUGINS__/bootstrap-markdown/css/bootstrap-markdown.min.css"
    ],

    // 时间
    'timepicker_js'=>[
        "__GLODAL_PLUGINS__/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-timepicker.init.js",
    ],
    'timepicker_css' => [
        "__GLODAL_PLUGINS__/bootstrap-timepicker/css/bootstrap-timepicker.css"
    ],

    // 日期选择/时间范围
    'datepicker_js' => [
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/locales/bootstrap-datepicker.zh-CN.min.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-datepicker.init.js",
    ],
    'datepicker_css' => [
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css",
    ],

    // 时间选择
    'datetimepicker_js' => [
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js",
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/js/locales/bootstrap-datetimepicker.zh-CN.js",
    ],
    'datetimepicker_css' => [
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css"
    ],

    // 日期时间范围
    'daterangepicker_js' => [
        "__GLODAL_PLUGINS__/bootstrap-daterangepicker/daterangepicker.js"
    ],
    'daterangepicker_css' => [
        "__GLODAL_PLUGINS__/bootstrap-daterangepicker/daterangepicker.css",
    ],

    // 下拉选择器
    'select_js' => [
        "__GLODAL_PLUGINS__/bootstrap-select/dist/js/bootstrap-select.min.js",
        "__GLODAL_PLUGINS__/bootstrap-select/dist/js/i18n/defaults-zh_CN.min.js"
    ],
    'select_css' => [
        "__GLODAL_PLUGINS__/bootstrap-select/dist/css/bootstrap-select.min.css"
    ],

    // 下拉选择器2
    'select2_js' => [
        "__GLODAL_PLUGINS__/select2/dist/js/select2.full.min.js",
        "__GLODAL_PLUGINS__/select2/dist/js/i18n/zh-CN.js",
    ],
    'select2_css' => [
        "__GLODAL_PLUGINS__/select2/dist/css/select2.min.css",
    ],

    // 标签
    'tags_js' => [
        "__GLODAL_PLUGINS__/jquery-tags-Input-master/dist/jquery.tagsinput.min.js",
    ],
    'tags_css' => [
        "__GLODAL_PLUGINS__/jquery-tags-Input-master/dist/jquery.tagsinput.min.css",
    ],

    //js 模板插件
    'jsrender_js' => [
        "__GLODAL_PLUGINS__/jsrender.min.js"
    ],

    //  表格插件
    'bootstraptable_js' => [
        // 表格主要插件js代码
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/bootstrap-table.min.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.min.js",
        // 表格插件扩展js代码(锁定头部)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js",
        // 表格插件扩展js代码(表格编辑)
        "__GLODAL_PLUGINS__/x-editable-develop/dist/bootstrap4-editable/js/bootstrap-editable.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/editable/bootstrap-table-editable.js",
        // 表格插件扩展js代码(固定列)
         "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.js",
        // 表格插件扩展js代码(拖拽行)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js",
        // 表格插件扩展js代码(调整大小)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/resizable/jquery.resizableColumns.min.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/resizable/bootstrap-table-resizable.min.js",
        //  表格插件扩展js代码(导出)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/export/tableExport.min.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/export/jsPDF/jspdf.min.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/export/jsPDF-AutoTable/jspdf.plugin.autotable.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/export/bootstrap-table-export.min.js",
        //  表格插件扩展js代码(固定列)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js",
        //  表格插件扩展js代码(树表)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/treegrid/jquery-treegrid0.2.0/jquery.treegrid.min.js",
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/treegrid/bootstrap-table-treegrid.js",

    ],
    'bootstraptable_css' => [
        // 表格主要插件css代码
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/bootstrap-table.min.css",
        // 表格插件扩展css代码(表格编辑)
        "__GLODAL_PLUGINS__/x-editable-develop/dist/bootstrap4-editable/css/bootstrap-editable.css",
        // 表格插件扩展css代码(粘性标题)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/sticky-header/bootstrap-table-sticky-header.css",
        // 表格插件扩展css代码(固定列)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.css",
        // 表格插件扩展css代码(拖拽行)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.css",
        // 表格插件扩展css代码(调整大小)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/resizable/jquery.resizableColumns.css",
        //  表格插件扩展css代码(固定列)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js",
        //  表格插件扩展css代码(树表)
        "__GLODAL_PLUGINS__/bootstrap-table-master/dist/extensions/treegrid/jquery-treegrid0.2.0/jquery.treegrid.min.css",

    ],

    // 取色器
    'colorpicker_js' => [
        "__GLODAL_PLUGINS__/jquery-minicolors/jquery.minicolors.min.js",
    ],
    'colorpicker_css' => [
        "__GLODAL_PLUGINS__/jquery-minicolors/jquery.minicolors.css",
    ],

    // 格式化输入
    'masked_inputs_js' => [
        "__GLODAL_PLUGINS__/inputmask/dist/jquery.inputmask.bundle.js",
        "__GLODAL_PLUGINS__/inputmask/dist/inputmask/inputmask.date.extensions.js",
        "__GLODAL_PLUGINS__/inputmask/dist/inputmask/inputmask.numeric.extensions.js",
    ],

    // 范围
    'rangeslider_js' => [
        "__GLODAL_PLUGINS__/ion.rangeslider/js/ion.rangeSlider.min.js",
    ],
    'rangeslider_css' => [
        "__GLODAL_PLUGINS__/ion.rangeslider/css/ion.rangeSlider.css",
        "__GLODAL_PLUGINS__/ion.rangeslider/css/ion.rangeSlider.skinHTML5.css"
    ],

    // 拖拽排序
    'nestable_js' => [
        "__GLODAL_PLUGINS__/jquery-nestable/jquery.nestable.js",
    ],
    'nestable_css' => [
        "__GLODAL_PLUGINS__/jquery-nestable/jquery.nestable.css",
    ],

    // 代码编辑器
    'codemirror_js' => [
         // 核心js
        "__GLODAL_PLUGINS__/codemirror/lib/codemirror.js",
        // 编辑器语言

        "__GLODAL_PLUGINS__/codemirror/mode/htmlmixed/htmlmixed.js",
        "__GLODAL_PLUGINS__/codemirror/mode/php/php.js",
        "__GLODAL_PLUGINS__/codemirror/mode/css/css.js",
        "__GLODAL_PLUGINS__/codemirror/mode/javascript/javascript.js",
        "__GLODAL_PLUGINS__/codemirror/mode/xml/xml.js",
        "__GLODAL_PLUGINS__/codemirror/mode/clike/clike.js"
    ],
    'codemirror_css' => [
        //核心css
        "__GLODAL_PLUGINS__/codemirror/lib/codemirror.css",
        // 编辑器主题
        "__GLODAL_PLUGINS__/codemirror/theme/eclipse.css",
        "__GLODAL_PLUGINS__/codemirror/theme/ambiance.css"
    ],

    // jqueryui
    'jqueryui_js' => [
        "__GLODAL_PLUGINS__/jquery-ui/jquery-ui.min.js",
    ],

    // json 编辑器
    'jsoneditor_js' =>[
        "__GLODAL_PLUGINS__/jsoneditor-develop/dist/jsoneditor.min.js"
    ],
    'jsoneditor_css' =>[
        "__GLODAL_PLUGINS__/jsoneditor-develop/dist/jsoneditor.min.css"
    ],

    // 联动下拉插件 编辑器
    'cxselect_js' =>[
        "__GLODAL_PLUGINS__/cxSelect-master/js/jquery.cxselect.js",
    ],

    // 树
    'tree_js'=>[
        "__GLODAL_PLUGINS__/zTree_v3/js/jquery.ztree.core.js",
        "__GLODAL_PLUGINS__/zTree_v3/js/jquery.ztree.excheck.js",
    ],
    'tree_css'=>[
        "__GLODAL_PLUGINS__/zTree_v3/css/zTreeStyle/zTreeStyle.css",
        "__GLODAL_PLUGINS__/zTree_v3/css/demo2.css",
    ],

    // 百度编辑器
    'ueditor_js'=>[
        "__GLODAL_PLUGINS__/ueditor/ueditor.config.js",
        "__GLODAL_PLUGINS__/ueditor/ueditor.all.js",
    ]
];
