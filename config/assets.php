<?php
// 资源路径配置
return [
    // 默认加载
    'core_js'             => [
        "__ADMIN_JS__/core/jquery.min.js",
        "__ADMIN_JS__/core/bootstrap.min.js",
        "__ADMIN_JS__/core/jquery.slimscroll.min.js",
        "__ADMIN_JS__/core/jquery.scrollLock.min.js",
        "__ADMIN_JS__/core/jquery.appear.min.js",
        "__ADMIN_JS__/core/jquery.countTo.min.js",
        "__ADMIN_JS__/core/jquery.placeholder.min.js",
        "__ADMIN_JS__/core/js.cookie.min.js",
        "__GLODAL_PLUGINS__/magnific-popup/magnific-popup.min.js",
        "__ADMIN_JS__/app.js",
        "__ADMIN_JS__/dolphin.js",
        "__ADMIN_JS__/builder/form.js",
        "__ADMIN_JS__/builder/aside.js",
        "__ADMIN_JS__/builder/table.js",
    ],
    // 默认加载
    'core_css'            => [
        "__GLODAL_PLUGINS__/magnific-popup/magnific-popup.min.css",
        "__ADMIN_CSS__/admin/css/bootstrap.min.css",
        "__ADMIN_CSS__/admin/css/oneui.css",
        "__ADMIN_CSS__/admin/css/dolphin.css",
    ],
    // 默认加载
    'libs_js'             => [
        "__GLODAL_PLUGINS__/bootstrap-notify/bootstrap-notify.min.js",
        "__GLODAL_PLUGINS__/sweetalert/sweetalert.min.js",
    ],
    // 默认加载
    'libs_css'            => [
        "__GLODAL_PLUGINS__/sweetalert/sweetalert.min.css",
    ],
    // 时间
    'timepicker_js'       => [
        "__GLODAL_PLUGINS__/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-timepicker.init.js",
    ],
    'timepicker_css'      => [
        "__GLODAL_PLUGINS__/bootstrap-timepicker/css/bootstrap-timepicker.css"
    ],
    // 日期选择/时间范围
    'datepicker_js'       => [
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js",
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/locales/bootstrap-datepicker.zh-CN.min.js",
        "__GLODAL_CUSTOM__/js/vendors/bootstrap-datepicker.init.js",
    ],
    'datepicker_css'      => [
        "__GLODAL_PLUGINS__/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css",
    ],
    // 时间选择
    'datetimepicker_js'   => [
        "__GLODAL_PLUGINS__/moment.min.js",
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js",
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/js/locales/bootstrap-datetimepicker.zh-CN.js",
    ],
    'datetimepicker_css'  => [
        "__GLODAL_PLUGINS__/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css"
    ],
    // 日期时间范围
    'daterangepicker_js'  => [
        "__GLODAL_PLUGINS__/moment.min.js",
        "__GLODAL_PLUGINS__/bootstrap-daterangepicker/daterangepicker.js"
    ],
    'daterangepicker_css' => [
        "__GLODAL_PLUGINS__/bootstrap-daterangepicker/daterangepicker.css",
    ],
    'moment_js'           => [
        "__GLODAL_PLUGINS__/moment.min.js",
    ],
    // 下拉选择器
    'select_js'           => [
        "__GLODAL_PLUGINS__/bootstrap-select/dist/js/bootstrap-select.min.js",
        "__GLODAL_PLUGINS__/bootstrap-select/dist/js/i18n/defaults-zh_CN.min.js"
    ],
    'select_css'          => [
        "__GLODAL_PLUGINS__/bootstrap-select/dist/css/bootstrap-select.min.css"
    ],
    // 下拉选择器2
    'select2_js'          => [
        "__GLODAL_PLUGINS__/select2/dist/js/select2.full.min.js",
        "__GLODAL_PLUGINS__/select2/dist/js/i18n/zh-CN.js",
    ],
    'select2_css'         => [
        "__GLODAL_PLUGINS__/select2/dist/css/select2.min.css",
    ],
    // 标签
    'tags_js'             => [
        "__GLODAL_PLUGINS__/jquery-tags-Input-master/dist/jquery.tagsinput.min.js",
    ],
    'tags_css'            => [
        "__GLODAL_PLUGINS__/jquery-tags-Input-master/dist/jquery.tagsinput.min.css",
    ],
    // 验证插件
    'validate_js'         => [
        "__GLODAL_PLUGINS__/jquery-validation/dist/jquery.validate.js",
        "__GLODAL_PLUGINS__/jquery-validation/dist/additional-methods.js",
        "__GLODAL_CUSTOM__/js/vendors/jquery-validation.init.js"
    ],
    //js 模板插件
    'jsrender_js'         => [
        "__GLODAL_PLUGINS__/jsrender.min.js"
    ],
    //  表格插件
    'bootstraptable_js'   => [
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
    'bootstraptable_css'  => [
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
    'colorpicker_js'      => [
        "__GLODAL_PLUGINS__/bootstrap-colorpicker/js/bootstrap-colorpicker.js",
        "__GLODAL_PLUGINS__/jquery-minicolors/jquery.minicolors.min.js",
    ],
    'colorpicker_css'     => [
        "__GLODAL_PLUGINS__/bootstrap-colorpicker/css/colorpicker.css",
        "__GLODAL_PLUGINS__/jquery-minicolors/jquery.minicolors.css",
    ],
    // 格式文本
    'masked_inputs_js'    => [
        "__GLODAL_PLUGINS__/jquery-inputmask/jquery.inputmask.bundle.min.js",
    ],
    // 范围
    'rangeslider_js'      => [
        "__GLODAL_PLUGINS__/ion.rangeslider/js/ion.rangeSlider.min.js",
    ],
    'rangeslider_css'     => [
        "__GLODAL_PLUGINS__/ion.rangeslider/css/ion.rangeSlider.css",
        "__GLODAL_PLUGINS__/ion.rangeslider/css/ion.rangeSlider.skinHTML5.css"
    ],
    // 拖拽排序
    'nestable_js'         => [
        "__GLODAL_PLUGINS__/jquery-nestable/jquery.nestable.js",
    ],
    'nestable_css'        => [
        "__GLODAL_PLUGINS__/jquery-nestable/jquery.nestable.css",
    ],
    // 代码编辑器
    'codemirror_js'       => [
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
    'codemirror_css'      => [
        //核心css
        "__GLODAL_PLUGINS__/codemirror/lib/codemirror.css",
        // 编辑器主题
        "__GLODAL_PLUGINS__/codemirror/theme/eclipse.css",
        "__GLODAL_PLUGINS__/codemirror/theme/ambiance.css"
    ],
    // jqueryui
    'jqueryui_js'         => [
        "__GLODAL_PLUGINS__/jquery-ui/jquery-ui.min.js",
    ],
    // json 编辑器
    'jsoneditor_js'       => [
        "__GLODAL_PLUGINS__/jsoneditor-develop/dist/jsoneditor.min.js"
    ],
    'jsoneditor_css'      => [
        "__GLODAL_PLUGINS__/jsoneditor-develop/dist/jsoneditor.min.css"
    ],
    // 联动下拉插件 编辑器
    'cxselect_js'         => [
        "__GLODAL_PLUGINS__/cxSelect-master/js/jquery.cxselect.js",
    ],
    // 树
    'tree_js'             => [
        "__GLODAL_PLUGINS__/zTree_v3/js/jquery.ztree.core.js",
        "__GLODAL_PLUGINS__/zTree_v3/js/jquery.ztree.excheck.js",
    ],
    'tree_css'            => [
        "__GLODAL_PLUGINS__/zTree_v3/css/zTreeStyle/zTreeStyle.css",
        "__GLODAL_PLUGINS__/zTree_v3/css/demo2.css",
    ],
    // 百度编辑器
    'ueditor_js'          => [
        "__GLODAL_PLUGINS__/ueditor/ueditor.config.js",
        "__GLODAL_PLUGINS__/ueditor/ueditor.all.js",
    ]
];
