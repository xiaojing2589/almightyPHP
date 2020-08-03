/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : cadmin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-06-01 20:15:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cad_admin_access
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_access`;
CREATE TABLE `cad_admin_access` (
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模型名称',
  `group` varchar(16) NOT NULL DEFAULT '' COMMENT '权限分组标识',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `nid` varchar(16) NOT NULL DEFAULT '' COMMENT '授权节点id',
  `tag` varchar(16) NOT NULL DEFAULT '' COMMENT '分组标签'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='统一授权表';

-- ----------------------------
-- Records of cad_admin_access
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_action
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_action`;
CREATE TABLE `cad_admin_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '所属模块名',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '行为标题',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8 COMMENT='系统行为表';

-- ----------------------------
-- Records of cad_admin_action
-- ----------------------------
INSERT INTO `cad_admin_action` VALUES ('1', 'user', 'user_add', '添加用户', '添加用户', '', '[user|get_nickname] 添加了用户：[record|get_nickname]', '1', '1480156399', '1480163853');
INSERT INTO `cad_admin_action` VALUES ('2', 'user', 'user_edit', '编辑用户', '编辑用户', '', '[user|get_nickname] 编辑了用户：[details]', '1', '1480164578', '1480297748');
INSERT INTO `cad_admin_action` VALUES ('3', 'user', 'user_delete', '删除用户', '删除用户', '', '[user|get_nickname] 删除了用户：[details]', '1', '1480168582', '1480168616');
INSERT INTO `cad_admin_action` VALUES ('4', 'user', 'user_enable', '启用用户', '启用用户', '', '[user|get_nickname] 启用了用户：[details]', '1', '1480169185', '1480169185');
INSERT INTO `cad_admin_action` VALUES ('5', 'user', 'user_disable', '禁用用户', '禁用用户', '', '[user|get_nickname] 禁用了用户：[details]', '1', '1480169214', '1480170581');
INSERT INTO `cad_admin_action` VALUES ('6', 'user', 'user_access', '用户授权', '用户授权', '', '[user|get_nickname] 对用户：[record|get_nickname] 进行了授权操作。详情：[details]', '1', '1480221441', '1480221563');
INSERT INTO `cad_admin_action` VALUES ('7', 'user', 'role_add', '添加角色', '添加角色', '', '[user|get_nickname] 添加了角色：[details]', '1', '1480251473', '1480251473');
INSERT INTO `cad_admin_action` VALUES ('8', 'user', 'role_edit', '编辑角色', '编辑角色', '', '[user|get_nickname] 编辑了角色：[details]', '1', '1480252369', '1480252369');
INSERT INTO `cad_admin_action` VALUES ('9', 'user', 'role_delete', '删除角色', '删除角色', '', '[user|get_nickname] 删除了角色：[details]', '1', '1480252580', '1480252580');
INSERT INTO `cad_admin_action` VALUES ('10', 'user', 'role_enable', '启用角色', '启用角色', '', '[user|get_nickname] 启用了角色：[details]', '1', '1480252620', '1480252620');
INSERT INTO `cad_admin_action` VALUES ('11', 'user', 'role_disable', '禁用角色', '禁用角色', '', '[user|get_nickname] 禁用了角色：[details]', '1', '1480252651', '1480252651');
INSERT INTO `cad_admin_action` VALUES ('12', 'user', 'attachment_enable', '启用附件', '启用附件', '', '[user|get_nickname] 启用了附件：附件ID([details])', '1', '1480253226', '1480253332');
INSERT INTO `cad_admin_action` VALUES ('13', 'user', 'attachment_disable', '禁用附件', '禁用附件', '', '[user|get_nickname] 禁用了附件：附件ID([details])', '1', '1480253267', '1480253340');
INSERT INTO `cad_admin_action` VALUES ('14', 'user', 'attachment_delete', '删除附件', '删除附件', '', '[user|get_nickname] 删除了附件：附件ID([details])', '1', '1480253323', '1480253323');
INSERT INTO `cad_admin_action` VALUES ('15', 'admin', 'config_add', '添加配置', '添加配置', '', '[user|get_nickname] 添加了配置，[details]', '1', '1480296196', '1480296196');
INSERT INTO `cad_admin_action` VALUES ('16', 'admin', 'config_edit', '编辑配置', '编辑配置', '', '[user|get_nickname] 编辑了配置：[details]', '1', '1480296960', '1480296960');
INSERT INTO `cad_admin_action` VALUES ('17', 'admin', 'config_enable', '启用配置', '启用配置', '', '[user|get_nickname] 启用了配置：[details]', '1', '1480298479', '1480298479');
INSERT INTO `cad_admin_action` VALUES ('18', 'admin', 'config_disable', '禁用配置', '禁用配置', '', '[user|get_nickname] 禁用了配置：[details]', '1', '1480298506', '1480298506');
INSERT INTO `cad_admin_action` VALUES ('19', 'admin', 'config_delete', '删除配置', '删除配置', '', '[user|get_nickname] 删除了配置：[details]', '1', '1480298532', '1480298532');
INSERT INTO `cad_admin_action` VALUES ('20', 'admin', 'database_export', '备份数据库', '备份数据库', '', '[user|get_nickname] 备份了数据库：[details]', '1', '1480298946', '1480298946');
INSERT INTO `cad_admin_action` VALUES ('21', 'admin', 'database_import', '还原数据库', '还原数据库', '', '[user|get_nickname] 还原了数据库：[details]', '1', '1480301990', '1480302022');
INSERT INTO `cad_admin_action` VALUES ('22', 'admin', 'database_optimize', '优化数据表', '优化数据表', '', '[user|get_nickname] 优化了数据表：[details]', '1', '1480302616', '1480302616');
INSERT INTO `cad_admin_action` VALUES ('23', 'admin', 'database_repair', '修复数据表', '修复数据表', '', '[user|get_nickname] 修复了数据表：[details]', '1', '1480302798', '1480302798');
INSERT INTO `cad_admin_action` VALUES ('24', 'admin', 'database_backup_delete', '删除数据库备份', '删除数据库备份', '', '[user|get_nickname] 删除了数据库备份：[details]', '1', '1480302870', '1480302870');
INSERT INTO `cad_admin_action` VALUES ('25', 'admin', 'hook_add', '添加钩子', '添加钩子', '', '[user|get_nickname] 添加了钩子：[details]', '1', '1480303198', '1480303198');
INSERT INTO `cad_admin_action` VALUES ('26', 'admin', 'hook_edit', '编辑钩子', '编辑钩子', '', '[user|get_nickname] 编辑了钩子：[details]', '1', '1480303229', '1480303229');
INSERT INTO `cad_admin_action` VALUES ('27', 'admin', 'hook_delete', '删除钩子', '删除钩子', '', '[user|get_nickname] 删除了钩子：[details]', '1', '1480303264', '1480303264');
INSERT INTO `cad_admin_action` VALUES ('28', 'admin', 'hook_enable', '启用钩子', '启用钩子', '', '[user|get_nickname] 启用了钩子：[details]', '1', '1480303294', '1480303294');
INSERT INTO `cad_admin_action` VALUES ('29', 'admin', 'hook_disable', '禁用钩子', '禁用钩子', '', '[user|get_nickname] 禁用了钩子：[details]', '1', '1480303409', '1480303409');
INSERT INTO `cad_admin_action` VALUES ('30', 'admin', 'menu_add', '添加节点', '添加节点', '', '[user|get_nickname] 添加了节点：[details]', '1', '1480305468', '1480305468');
INSERT INTO `cad_admin_action` VALUES ('31', 'admin', 'menu_edit', '编辑节点', '编辑节点', '', '[user|get_nickname] 编辑了节点：[details]', '1', '1480305513', '1480305513');
INSERT INTO `cad_admin_action` VALUES ('32', 'admin', 'menu_delete', '删除节点', '删除节点', '', '[user|get_nickname] 删除了节点：[details]', '1', '1480305562', '1480305562');
INSERT INTO `cad_admin_action` VALUES ('33', 'admin', 'menu_enable', '启用节点', '启用节点', '', '[user|get_nickname] 启用了节点：[details]', '1', '1480305630', '1480305630');
INSERT INTO `cad_admin_action` VALUES ('34', 'admin', 'menu_disable', '禁用节点', '禁用节点', '', '[user|get_nickname] 禁用了节点：[details]', '1', '1480305659', '1480305659');
INSERT INTO `cad_admin_action` VALUES ('35', 'admin', 'module_install', '安装模块', '安装模块', '', '[user|get_nickname] 安装了模块：[details]', '1', '1480307558', '1480307558');
INSERT INTO `cad_admin_action` VALUES ('36', 'admin', 'module_uninstall', '卸载模块', '卸载模块', '', '[user|get_nickname] 卸载了模块：[details]', '1', '1480307588', '1480307588');
INSERT INTO `cad_admin_action` VALUES ('37', 'admin', 'module_enable', '启用模块', '启用模块', '', '[user|get_nickname] 启用了模块：[details]', '1', '1480307618', '1480307618');
INSERT INTO `cad_admin_action` VALUES ('38', 'admin', 'module_disable', '禁用模块', '禁用模块', '', '[user|get_nickname] 禁用了模块：[details]', '1', '1480307653', '1480307653');
INSERT INTO `cad_admin_action` VALUES ('39', 'admin', 'module_export', '导出模块', '导出模块', '', '[user|get_nickname] 导出了模块：[details]', '1', '1480307682', '1480307682');
INSERT INTO `cad_admin_action` VALUES ('40', 'admin', 'packet_install', '安装数据包', '安装数据包', '', '[user|get_nickname] 安装了数据包：[details]', '1', '1480308342', '1480308342');
INSERT INTO `cad_admin_action` VALUES ('41', 'admin', 'packet_uninstall', '卸载数据包', '卸载数据包', '', '[user|get_nickname] 卸载了数据包：[details]', '1', '1480308372', '1480308372');
INSERT INTO `cad_admin_action` VALUES ('42', 'admin', 'system_config_update', '更新系统设置', '更新系统设置', '', '[user|get_nickname] 更新了系统设置：[details]', '1', '1480309555', '1480309642');
INSERT INTO `cad_admin_action` VALUES ('146', 'cms', 'advert_edit', '编辑广告', '编辑广告', '', '[user|get_nickname] 编辑了广告：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('145', 'cms', 'advert_delete', '删除广告', '删除广告', '', '[user|get_nickname] 删除了广告：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('144', 'cms', 'advert_enable', '启用广告', '启用广告', '', '[user|get_nickname] 启用了广告：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('143', 'cms', 'advert_disable', '禁用广告', '禁用广告', '', '[user|get_nickname] 禁用了广告：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('142', 'cms', 'advert_type_add', '添加广告分类', '添加广告分类', '', '[user|get_nickname] 添加了广告分类：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('141', 'cms', 'advert_type_edit', '编辑广告分类', '编辑广告分类', '', '[user|get_nickname] 编辑了广告分类：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('140', 'cms', 'advert_type_delete', '删除广告分类', '删除广告分类', '', '[user|get_nickname] 删除了广告分类：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('139', 'cms', 'advert_type_enable', '启用广告分类', '启用广告分类', '', '[user|get_nickname] 启用了广告分类：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('138', 'cms', 'advert_type_disable', '禁用广告分类', '禁用广告分类', '', '[user|get_nickname] 禁用了广告分类：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('137', 'cms', 'column_add', '添加栏目', '添加栏目', '', '[user|get_nickname] 添加了栏目：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('136', 'cms', 'column_edit', '编辑栏目', '编辑栏目', '', '[user|get_nickname] 编辑了栏目：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('135', 'cms', 'column_delete', '删除栏目', '删除栏目', '', '[user|get_nickname] 删除了栏目：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('134', 'cms', 'column_enable', '启用栏目', '启用栏目', '', '[user|get_nickname] 启用了栏目：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('133', 'cms', 'column_disable', '禁用栏目', '禁用栏目', '', '[user|get_nickname] 禁用了栏目：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('132', 'cms', 'field_add', '添加模型字段', '添加模型字段', '', '[user|get_nickname] 添加了模型字段：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('131', 'cms', 'field_edit', '编辑模型字段', '编辑模型字段', '', '[user|get_nickname] 编辑了模型字段：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('130', 'cms', 'field_delete', '删除模型字段', '删除模型字段', '', '[user|get_nickname] 删除了模型字段：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('129', 'cms', 'field_enable', '启用模型字段', '启用模型字段', '', '[user|get_nickname] 启用了模型字段：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('128', 'cms', 'field_disable', '禁用模型字段', '禁用模型字段', '', '[user|get_nickname] 禁用了模型字段：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('127', 'cms', 'link_add', '添加友情链接', '添加友情链接', '', '[user|get_nickname] 添加了友情链接：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('126', 'cms', 'link_edit', '编辑友情链接', '编辑友情链接', '', '[user|get_nickname] 编辑了友情链接：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('125', 'cms', 'link_delete', '删除友情链接', '删除友情链接', '', '[user|get_nickname] 删除了友情链接：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('124', 'cms', 'link_enable', '启用友情链接', '启用友情链接', '', '[user|get_nickname] 启用了友情链接：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('123', 'cms', 'link_disable', '禁用友情链接', '禁用友情链接', '', '[user|get_nickname] 禁用了友情链接：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('122', 'cms', 'menu_add', '添加导航菜单', '添加导航菜单', '', '[user|get_nickname] 添加了导航菜单：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('121', 'cms', 'menu_edit', '编辑导航菜单', '编辑导航菜单', '', '[user|get_nickname] 编辑了导航菜单：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('120', 'cms', 'menu_delete', '删除导航菜单', '删除导航菜单', '', '[user|get_nickname] 删除了导航菜单：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('119', 'cms', 'menu_enable', '启用导航菜单', '启用导航菜单', '', '[user|get_nickname] 启用了导航菜单：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('118', 'cms', 'menu_disable', '禁用导航菜单', '禁用导航菜单', '', '[user|get_nickname] 禁用了导航菜单：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('117', 'cms', 'model_add', '添加内容模型', '添加内容模型', '', '[user|get_nickname] 添加了内容模型：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('116', 'cms', 'model_edit', '编辑内容模型', '编辑内容模型', '', '[user|get_nickname] 编辑了内容模型：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('115', 'cms', 'model_delete', '删除内容模型', '删除内容模型', '', '[user|get_nickname] 删除了内容模型：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('114', 'cms', 'model_enable', '启用内容模型', '启用内容模型', '', '[user|get_nickname] 启用了内容模型：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('113', 'cms', 'model_disable', '禁用内容模型', '禁用内容模型', '', '[user|get_nickname] 禁用了内容模型：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('112', 'cms', 'nav_add', '添加导航', '添加导航', '', '[user|get_nickname] 添加了导航：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('111', 'cms', 'nav_edit', '编辑导航', '编辑导航', '', '[user|get_nickname] 编辑了导航：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('110', 'cms', 'nav_delete', '删除导航', '删除导航', '', '[user|get_nickname] 删除了导航：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('109', 'cms', 'nav_enable', '启用导航', '启用导航', '', '[user|get_nickname] 启用了导航：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('108', 'cms', 'nav_disable', '禁用导航', '禁用导航', '', '[user|get_nickname] 禁用了导航：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('107', 'cms', 'document_restore', '还原文档', '还原文档', '', '[user|get_nickname] 还原了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('106', 'cms', 'document_delete', '删除文档', '删除文档', '', '[user|get_nickname] 删除了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('105', 'cms', 'slider_add', '添加滚动图片', '添加滚动图片', '', '[user|get_nickname] 添加了滚动图片：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('104', 'cms', 'slider_edit', '编辑滚动图片', '编辑滚动图片', '', '[user|get_nickname] 编辑了滚动图片：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('103', 'cms', 'slider_delete', '删除滚动图片', '删除滚动图片', '', '[user|get_nickname] 删除了滚动图片：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('102', 'platform', 'storage_update', '更新插件', '更新插件', '', '[user|get_nickname] 更新对象存储插件：[details]', '1', '1559187255', '1559187255');
INSERT INTO `cad_admin_action` VALUES ('101', 'platform', 'storage_uninstall', '卸载对象存储插件', '卸载对象存储插件', '', '[user|get_nickname] 卸载对象存储插件：[details]', '1', '1559187255', '1559187255');
INSERT INTO `cad_admin_action` VALUES ('100', 'platform', 'storage_install', '安装对象存储插件', '安装对象存储插件', '', '[user|get_nickname] 安装对象存储插件：[details]', '1', '1559187255', '1559187255');
INSERT INTO `cad_admin_action` VALUES ('147', 'cms', 'advert_add', '添加广告', '添加广告', '', '[user|get_nickname] 添加了广告：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('148', 'cms', 'document_disable', '禁用文档', '禁用文档', '', '[user|get_nickname] 禁用了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('149', 'cms', 'document_enable', '启用文档', '启用文档', '', '[user|get_nickname] 启用了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('150', 'cms', 'document_trash', '回收文档', '回收文档', '', '[user|get_nickname] 回收了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('151', 'cms', 'document_edit', '编辑文档', '编辑文档', '', '[user|get_nickname] 编辑了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('152', 'cms', 'document_add', '添加文档', '添加文档', '', '[user|get_nickname] 添加了文档：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('153', 'cms', 'slider_enable', '启用滚动图片', '启用滚动图片', '', '[user|get_nickname] 启用了滚动图片：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('154', 'cms', 'slider_disable', '禁用滚动图片', '禁用滚动图片', '', '[user|get_nickname] 禁用了滚动图片：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('155', 'cms', 'support_add', '添加客服', '添加客服', '', '[user|get_nickname] 添加了客服：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('156', 'cms', 'support_edit', '编辑客服', '编辑客服', '', '[user|get_nickname] 编辑了客服：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('157', 'cms', 'support_delete', '删除客服', '删除客服', '', '[user|get_nickname] 删除了客服：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('158', 'cms', 'support_enable', '启用客服', '启用客服', '', '[user|get_nickname] 启用了客服：[details]', '1', '1559197275', '1559197275');
INSERT INTO `cad_admin_action` VALUES ('159', 'cms', 'support_disable', '禁用客服', '禁用客服', '', '[user|get_nickname] 禁用了客服：[details]', '1', '1559197275', '1559197275');

-- ----------------------------
-- Table structure for cad_admin_attachment
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_attachment`;
CREATE TABLE `cad_admin_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(128) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(8) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `width` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `height` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '图片高度',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of cad_admin_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_config
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_config`;
CREATE TABLE `cad_admin_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '类型',
  `value` text NOT NULL COMMENT '配置值',
  `options` text NOT NULL COMMENT '配置项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '配置提示',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- ----------------------------
-- Records of cad_admin_config
-- ----------------------------
INSERT INTO `cad_admin_config` VALUES ('1', 'web_site_status', '站点开关', 'base', 'switch', '1', '', '站点关闭后将不能访问，后台可正常登录', '', '', '', '', '', '2', '', '', '', '', '1475240395', '1556900153', '1', '1');
INSERT INTO `cad_admin_config` VALUES ('2', 'web_site_title', '站点标题', 'base', 'text', 'PHP', '', '调用方式：<code>config(\'web_site_title\')</code>', '', '', '', '', '', '2', '', '', '', '', '1475240646', '1477710341', '2', '1');
INSERT INTO `cad_admin_config` VALUES ('3', 'web_site_slogan', '站点标语', 'base', 'text', '极简、极速、极致', '', '站点口号，调用方式：<code>config(\'web_site_slogan\')</code>', '', '', '', '', '', '2', '', '', '', '', '1475240994', '1477710357', '3', '1');
INSERT INTO `cad_admin_config` VALUES ('4', 'web_site_logo', '站点LOGO', 'base', 'image', '', '', '', '', '', '', '', '', '2', '', '', '', '', '1475241067', '1475241067', '4', '1');
INSERT INTO `cad_admin_config` VALUES ('5', 'web_site_description', '站点描述', 'base', 'textarea', '', '', '网站描述，有利于搜索引擎抓取相关信息', '', '', '', '', '', '2', '', '', '', '', '1475241186', '1475241186', '6', '1');
INSERT INTO `cad_admin_config` VALUES ('6', 'web_site_keywords', '站点关键词', 'base', 'text', '海豚PHP、PHP开发框架、后台框架', '', '网站搜索引擎关键字', '', '', '', '', '', '2', '', '', '', '', '1475241328', '1475241328', '7', '1');
INSERT INTO `cad_admin_config` VALUES ('7', 'web_site_copyright', '版权信息', 'base', 'text', 'Copyright © 2015-2017 DolphinPHP All rights reserved.', '', '调用方式：<code>config(\'web_site_copyright\')</code>', '', '', '', '', '', '2', '', '', '', '', '1475241416', '1477710383', '8', '1');
INSERT INTO `cad_admin_config` VALUES ('8', 'web_site_icp', '备案信息', 'base', 'text', '', '', '调用方式：<code>config(\'web_site_icp\')</code>', '', '', '', '', '', '2', '', '', '', '', '1475241441', '1477710441', '9', '1');
INSERT INTO `cad_admin_config` VALUES ('9', 'web_site_statistics', '站点统计', 'base', 'textarea', '', '', '网站统计代码，支持百度、Google、cnzz等，调用方式：<code>config(\'web_site_statistics\')</code>', '', '', '', '', '', '2', '', '', '', '', '1475241498', '1477710455', '10', '1');
INSERT INTO `cad_admin_config` VALUES ('10', 'config_group', '配置分组', 'system', 'array', 'base:基本\r\nsystem:系统\r\nupload:上传\r\ndevelop:开发\r\ndatabase:数据库', '', '', '', '', '', '', '', '2', '', '', '', '', '1475241716', '1477649446', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('11', 'form_item_type', '配置类型', 'system', 'array', 'text:单行文本\r\ntextarea:多行文本\r\nstatic:静态文本\r\npassword:密码\r\ncheckbox:复选框\r\nradio:单选按钮\r\ndate:日期\r\ndatetime:日期+时间\r\nhidden:隐藏\r\nswitch:开关\r\narray:数组\r\nselect:下拉框\r\nlinkage:普通联动下拉框\r\nlinkages:快速联动下拉框\r\nimage:单张图片\r\nimages:多张图片\r\nfile:单个文件\r\nfiles:多个文件\r\nueditor:UEditor 编辑器\r\nwangeditor:wangEditor 编辑器\r\neditormd:markdown 编辑器\r\nckeditor:ckeditor 编辑器\r\nicon:字体图标\r\ntags:标签\r\nnumber:数字\r\nbmap:百度地图\r\ncolorpicker:取色器\r\njcrop:图片裁剪\r\nmasked:格式文本\r\nrange:范围\r\ntime:时间', '', '', '', '', '', '', '', '2', '', '', '', '', '1475241835', '1495853193', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('12', 'upload_file_size', '文件上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '', '', '', '', '', '2', '', '', '', '', '1475241897', '1477663520', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('13', 'upload_file_ext', '允许上传的文件后缀', 'upload', 'tags', 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z', '', '多个后缀用逗号隔开，不填写则不限制类型', '', '', '', '', '', '2', '', '', '', '', '1475241975', '1477649489', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('14', 'upload_image_size', '图片上传大小限制', 'upload', 'text', '0', '', '0为不限制大小，单位：kb', '', '', '', '', '', '2', '', '', '', '', '1475242015', '1477663529', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('15', 'upload_image_ext', '允许上传的图片后缀', 'upload', 'tags', 'gif,jpg,jpeg,bmp,png', '', '多个后缀用逗号隔开，不填写则不限制类型', '', '', '', '', '', '2', '', '', '', '', '1475242056', '1477649506', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('16', 'list_rows', '分页数量', 'system', 'number', '20', '', '每页的记录数', '', '', '', '', '', '2', '', '', '', '', '1475242066', '1476074507', '101', '1');
INSERT INTO `cad_admin_config` VALUES ('17', 'system_color', '后台配色方案', 'system', 'radio', 'amethyst', 'default:Default\r\namethyst:Amethyst\r\ncity:City\r\nflat:Flat\r\nmodern:Modern\r\nsmooth:Smooth', '', '', '', '', '', '', '2', '', '', '', '', '1475250066', '1477316689', '102', '1');
INSERT INTO `cad_admin_config` VALUES ('18', 'develop_mode', '开发模式', 'develop', 'radio', '1', '0:关闭\r\n1:开启', '', '', '', '', '', '', '2', '', '', '', '', '1476864205', '1476864231', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('19', 'app_trace', '显示页面Trace', 'develop', 'radio', '0', '0:否\r\n1:是', '', '', '', '', '', '', '2', '', '', '', '', '1476866355', '1476866355', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('21', 'data_backup_path', '数据库备份根路径', 'database', 'text', '../data/', '', '路径必须以 / 结尾', '', '', '', '', '', '2', '', '', '', '', '1477017745', '1477018467', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('22', 'data_backup_part_size', '数据库备份卷大小', 'database', 'text', '20971520', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '', '', '', '', '', '2', '', '', '', '', '1477017886', '1477017886', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('23', 'data_backup_compress', '数据库备份文件是否启用压缩', 'database', 'radio', '1', '0:否\r\n1:是', '压缩备份文件需要PHP环境支持 <code>gzopen</code>, <code>gzwrite</code>函数', '', '', '', '', '', '2', '', '', '', '', '1477017978', '1477018172', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('24', 'data_backup_compress_level', '数据库备份文件压缩级别', 'database', 'radio', '9', '1:最低\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '', '', '', '', '', '2', '', '', '', '', '1477018083', '1477018083', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('25', 'top_menu_max', '顶部导航模块数量', 'system', 'text', '10', '', '设置顶部导航默认显示的模块数量', '', '', '', '', '', '2', '', '', '', '', '1477579289', '1477579289', '103', '1');
INSERT INTO `cad_admin_config` VALUES ('26', 'web_site_logo_text', '站点LOGO文字', 'base', 'image', '', '', '', '', '', '', '', '', '2', '', '', '', '', '1477620643', '1477620643', '5', '1');
INSERT INTO `cad_admin_config` VALUES ('27', 'upload_image_thumb', '缩略图尺寸', 'upload', 'text', '', '', '不填写则不生成缩略图，如需生成 <code>300x300</code> 的缩略图，则填写 <code>300,300</code> ，请注意，逗号必须是英文逗号', '', '', '', '', '', '2', '', '', '', '', '1477644150', '1477649513', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('28', 'upload_image_thumb_type', '缩略图裁剪类型', 'upload', 'radio', '1', '1:等比例缩放\r\n2:缩放后填充\r\n3:居中裁剪\r\n4:左上角裁剪\r\n5:右下角裁剪\r\n6:固定尺寸缩放', '该项配置只有在启用生成缩略图时才生效', '', '', '', '', '', '2', '', '', '', '', '1477646271', '1477649521', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('29', 'upload_thumb_water', '添加水印', 'upload', 'switch', '0', '', '', '', '', '', '', '', '2', '', '', '', '', '1477649648', '1477649648', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('30', 'upload_thumb_water_pic', '水印图片', 'upload', 'image', '', '', '只有开启水印功能才生效', '', '', '', '', '', '2', '', '', '', '', '1477656390', '1477656390', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('31', 'upload_thumb_water_position', '水印位置', 'upload', 'radio', '9', '1:左上角\r\n2:上居中\r\n3:右上角\r\n4:左居中\r\n5:居中\r\n6:右居中\r\n7:左下角\r\n8:下居中\r\n9:右下角', '只有开启水印功能才生效', '', '', '', '', '', '2', '', '', '', '', '1477656528', '1477656528', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('32', 'upload_thumb_water_alpha', '水印透明度', 'upload', 'text', '50', '', '请输入0~100之间的数字，数字越小，透明度越高', '', '', '', '', '', '2', '', '', '', '', '1477656714', '1477661309', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('33', 'wipe_cache_type', '清除缓存类型', 'system', 'checkbox', 'TEMP_PATH', 'TEMP_PATH:应用缓存\r\nLOG_PATH:应用日志\r\nCACHE_PATH:项目模板缓存', '清除缓存时，要删除的缓存类型', '', '', '', '', '', '2', '', '', '', '', '1477727305', '1477727305', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('34', 'captcha_signin', '后台验证码开关', 'system', 'switch', '1', '', '后台登录时是否需要验证码', '', '', '', '', '', '2', '', '', '', '', '1478771958', '1478771958', '99', '1');
INSERT INTO `cad_admin_config` VALUES ('35', 'home_default_module', '前台默认模块', 'system', 'select', 'index', '', '前台默认访问的模块，该模块必须有Index控制器和index方法', '', '', '', '', '', '0', '', '', '', '', '1486714723', '1486715620', '104', '1');
INSERT INTO `cad_admin_config` VALUES ('36', 'minify_status', '开启minify', 'system', 'switch', '0', '', '开启minify会压缩合并js、css文件，可以减少资源请求次数，如果不支持minify，可关闭', '', '', '', '', '', '0', '', '', '', '', '1487035843', '1487035843', '99', '1');
INSERT INTO `cad_admin_config` VALUES ('37', 'upload_driver', '上传驱动', 'upload', 'radio', 'local', 'local:本地', '图片或文件上传驱动', '', '', '', '', '', '0', '', '', '', '', '1501488567', '1501490821', '100', '1');
INSERT INTO `cad_admin_config` VALUES ('38', 'system_log', '系统日志', 'system', 'switch', '1', '', '是否开启系统日志功能', '', '', '', '', '', '0', '', '', '', '', '1512635391', '1512635391', '99', '1');
INSERT INTO `cad_admin_config` VALUES ('39', 'asset_version', '资源版本号', 'develop', 'text', '20180327', '', '可通过修改版号强制用户更新静态文件', '', '', '', '', '', '0', '', '', '', '', '1522143239', '1522143239', '100', '1');

-- ----------------------------
-- Table structure for cad_admin_hook
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_hook`;
CREATE TABLE `cad_admin_hook` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子来自哪个插件',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子描述',
  `system` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统钩子',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='钩子表';

-- ----------------------------
-- Records of cad_admin_hook
-- ----------------------------
INSERT INTO `cad_admin_hook` VALUES ('1', 'admin_index', '', '后台首页', '1', '1468174214', '1477757518', '1');
INSERT INTO `cad_admin_hook` VALUES ('2', 'plugin_index_tab_list', '', '插件扩展tab钩子', '1', '1468174214', '1468174214', '1');
INSERT INTO `cad_admin_hook` VALUES ('3', 'module_index_tab_list', '', '模块扩展tab钩子', '1', '1468174214', '1468174214', '1');
INSERT INTO `cad_admin_hook` VALUES ('4', 'page_tips', '', '每个页面的提示', '1', '1468174214', '1468174214', '1');
INSERT INTO `cad_admin_hook` VALUES ('5', 'signin_footer', '', '登录页面底部钩子', '1', '1479269315', '1479269315', '1');
INSERT INTO `cad_admin_hook` VALUES ('6', 'signin_captcha', '', '登录页面验证码钩子', '1', '1479269315', '1479269315', '1');
INSERT INTO `cad_admin_hook` VALUES ('7', 'signin', '', '登录控制器钩子', '1', '1479386875', '1479386875', '1');
INSERT INTO `cad_admin_hook` VALUES ('8', 'upload_attachment', '', '附件上传钩子', '1', '1501493808', '1501493808', '1');
INSERT INTO `cad_admin_hook` VALUES ('9', 'page_plugin_js', '', '页面插件js钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('10', 'page_plugin_css', '', '页面插件css钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('11', 'signin_sso', '', '单点登录钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('12', 'signout_sso', '', '单点退出钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('13', 'user_add', '', '添加用户钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('14', 'user_edit', '', '编辑用户钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('15', 'user_delete', '', '删除用户钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('16', 'user_enable', '', '启用用户钩子', '1', '1503633591', '1503633591', '1');
INSERT INTO `cad_admin_hook` VALUES ('17', 'user_disable', '', '禁用用户钩子', '1', '1503633591', '1503633591', '1');

-- ----------------------------
-- Table structure for cad_admin_hook_plugin
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_hook_plugin`;
CREATE TABLE `cad_admin_hook_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子id',
  `plugin` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标识',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='钩子-插件对应表';

-- ----------------------------
-- Records of cad_admin_hook_plugin
-- ----------------------------
INSERT INTO `cad_admin_hook_plugin` VALUES ('1', 'admin_index', 'SystemInfo', '1477757503', '1477757503', '1', '1');
INSERT INTO `cad_admin_hook_plugin` VALUES ('2', 'admin_index', 'DevTeam', '1477755780', '1477755780', '2', '1');

-- ----------------------------
-- Table structure for cad_admin_icon
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_icon`;
CREATE TABLE `cad_admin_icon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '图标名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图标css地址',
  `prefix` varchar(32) NOT NULL DEFAULT '' COMMENT '图标前缀',
  `font_family` varchar(32) NOT NULL DEFAULT '' COMMENT '字体名',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图标表';

-- ----------------------------
-- Records of cad_admin_icon
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_icon_list
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_icon_list`;
CREATE TABLE `cad_admin_icon_list` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `icon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属图标id',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '图标标题',
  `class` varchar(255) NOT NULL DEFAULT '' COMMENT '图标类名',
  `code` varchar(128) NOT NULL DEFAULT '' COMMENT '图标关键词',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='详细图标列表';

-- ----------------------------
-- Records of cad_admin_icon_list
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_log`;
CREATE TABLE `cad_admin_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` longtext NOT NULL COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of cad_admin_log
-- ----------------------------
INSERT INTO `cad_admin_log` VALUES ('1', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(system)', '1', '1555682951');
INSERT INTO `cad_admin_log` VALUES ('2', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(develop)', '1', '1555807545');
INSERT INTO `cad_admin_log` VALUES ('3', '16', '1', '2130706433', 'admin_config', '1', '超级管理员1 编辑了配置：原数据：分组(base)、类型(switch)、标题(站点开关)、名称(web_site_status)', '1', '1555815261');
INSERT INTO `cad_admin_log` VALUES ('4', '16', '1', '2130706433', 'admin_config', '1', '超级管理员1 编辑了配置：原数据：分组(base)、类型(switch)、标题(站点开关1)、名称(web_site_status)', '1', '1555815287');
INSERT INTO `cad_admin_log` VALUES ('5', '16', '1', '2130706433', 'admin_config', '1', '超级管理员1 编辑了配置：原数据：分组(base)、类型(switch)、标题(站点开关1)、名称(web_site_status)', '1', '1555815316');
INSERT INTO `cad_admin_log` VALUES ('6', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555815341');
INSERT INTO `cad_admin_log` VALUES ('7', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555815350');
INSERT INTO `cad_admin_log` VALUES ('8', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555815381');
INSERT INTO `cad_admin_log` VALUES ('9', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555815412');
INSERT INTO `cad_admin_log` VALUES ('10', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555815571');
INSERT INTO `cad_admin_log` VALUES ('11', '31', '1', '2130706433', 'admin_menu', '1', '超级管理员 编辑了节点：节点ID(1)', '1', '1555816129');
INSERT INTO `cad_admin_log` VALUES ('12', '30', '1', '2130706433', 'admin_menu', '236', '超级管理员 添加了节点：所属模块(admin),所属节点ID(4),节点标题(后台用户),节点链接(admin/user/index)', '1', '1555844071');
INSERT INTO `cad_admin_log` VALUES ('13', '31', '1', '2130706433', 'admin_menu', '236', '超级管理员 编辑了节点：节点ID(236)', '1', '1555844150');
INSERT INTO `cad_admin_log` VALUES ('14', '31', '1', '2130706433', 'admin_menu', '236', '超级管理员 编辑了节点：节点ID(236)', '1', '1555844204');
INSERT INTO `cad_admin_log` VALUES ('15', '30', '1', '2130706433', 'admin_menu', '237', '超级管理员 添加了节点：所属模块(admin),所属节点ID(236),节点标题(用户管理),节点链接(admin/user/index)', '1', '1555844251');
INSERT INTO `cad_admin_log` VALUES ('16', '30', '1', '2130706433', 'admin_menu', '238', '超级管理员 添加了节点：所属模块(admin),所属节点ID(236),节点标题(角色管理),节点链接(admin/role/index)', '1', '1555844752');
INSERT INTO `cad_admin_log` VALUES ('17', '34', '1', '2130706433', 'admin_menu', '236', '超级管理员 禁用了节点：节点ID(236),节点标题(后台用户),节点链接()', '1', '1555844913');
INSERT INTO `cad_admin_log` VALUES ('18', '33', '1', '2130706433', 'admin_menu', '236', '超级管理员 启用了节点：节点ID(236),节点标题(后台用户),节点链接()', '1', '1555844916');
INSERT INTO `cad_admin_log` VALUES ('19', '30', '1', '2130706433', 'admin_menu', '239', '超级管理员 添加了节点：所属模块(admin),所属节点ID(236),节点标题(消息),节点链接(admin/usermessage/index)', '1', '1555844964');
INSERT INTO `cad_admin_log` VALUES ('20', '30', '1', '2130706433', 'admin_menu', '240', '超级管理员 添加了节点：所属模块(admin),所属节点ID(237),节点标题(权限管理),节点链接()', '1', '1556895955');
INSERT INTO `cad_admin_log` VALUES ('21', '30', '1', '2130706433', 'admin_menu', '241', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(新增),节点链接(admin/user/add)', '1', '1556896061');
INSERT INTO `cad_admin_log` VALUES ('22', '30', '1', '2130706433', 'admin_menu', '242', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(编辑),节点链接(admin/user/edit)', '1', '1556896128');
INSERT INTO `cad_admin_log` VALUES ('23', '30', '1', '2130706433', 'admin_menu', '243', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(删除),节点链接(amdin/user/delete)', '1', '1556896162');
INSERT INTO `cad_admin_log` VALUES ('24', '30', '1', '2130706433', 'admin_menu', '244', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(启用),节点链接(admin/role/enable)', '1', '1556896266');
INSERT INTO `cad_admin_log` VALUES ('25', '30', '1', '2130706433', 'admin_menu', '245', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(禁用),节点链接(admin/role/disable)', '1', '1556896309');
INSERT INTO `cad_admin_log` VALUES ('26', '30', '1', '2130706433', 'admin_menu', '246', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(快速编辑),节点链接(admin/role/quickedit)', '1', '1556896421');
INSERT INTO `cad_admin_log` VALUES ('27', '31', '1', '2130706433', 'admin_menu', '244', '超级管理员 编辑了节点：节点ID(244)', '1', '1556896470');
INSERT INTO `cad_admin_log` VALUES ('28', '31', '1', '2130706433', 'admin_menu', '245', '超级管理员 编辑了节点：节点ID(245)', '1', '1556896522');
INSERT INTO `cad_admin_log` VALUES ('29', '31', '1', '2130706433', 'admin_menu', '246', '超级管理员 编辑了节点：节点ID(246)', '1', '1556896542');
INSERT INTO `cad_admin_log` VALUES ('30', '30', '1', '2130706433', 'admin_menu', '247', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(启用),节点链接(admin/user/enable)', '1', '1556896621');
INSERT INTO `cad_admin_log` VALUES ('31', '30', '1', '2130706433', 'admin_menu', '248', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(禁用),节点链接(admin/user/disable)', '1', '1556896658');
INSERT INTO `cad_admin_log` VALUES ('32', '30', '1', '2130706433', 'admin_menu', '249', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(授权),节点链接(admin/user/access)', '1', '1556896721');
INSERT INTO `cad_admin_log` VALUES ('33', '31', '1', '2130706433', 'admin_menu', '248', '超级管理员 编辑了节点：节点ID(248)', '1', '1556896752');
INSERT INTO `cad_admin_log` VALUES ('34', '31', '1', '2130706433', 'admin_menu', '249', '超级管理员 编辑了节点：节点ID(249)', '1', '1556896769');
INSERT INTO `cad_admin_log` VALUES ('35', '30', '1', '2130706433', 'admin_menu', '250', '超级管理员 添加了节点：所属模块(admin),所属节点ID(240),节点标题(快速编辑),节点链接(admin/user/quickedit)', '1', '1556896812');
INSERT INTO `cad_admin_log` VALUES ('36', '30', '1', '2130706433', 'admin_menu', '251', '超级管理员 添加了节点：所属模块(admin),所属节点ID(238),节点标题(新增),节点链接(admin/role/add)', '1', '1556896840');
INSERT INTO `cad_admin_log` VALUES ('37', '30', '1', '2130706433', 'admin_menu', '252', '超级管理员 添加了节点：所属模块(admin),所属节点ID(238),节点标题(编辑),节点链接(admin/role/edit)', '1', '1556896864');
INSERT INTO `cad_admin_log` VALUES ('38', '30', '1', '2130706433', 'admin_menu', '253', '超级管理员 添加了节点：所属模块(admin),所属节点ID(238),节点标题(删除),节点链接(admin/role/delete)', '1', '1556896892');
INSERT INTO `cad_admin_log` VALUES ('39', '22', '1', '2130706433', 'database', '0', '超级管理员 优化了数据表：`dp_admin_access`,`dp_admin_action`,`dp_admin_attachment`,`dp_admin_config`,`dp_admin_hook`,`dp_admin_hook_plugin`,`dp_admin_icon`,`dp_admin_icon_list`,`dp_admin_log`,`dp_admin_menu`,`dp_admin_message`,`dp_admin_module`,`dp_admin_packet`,`dp_admin_plugin`,`dp_admin_role`,`dp_admin_user`', '1', '1556897287');
INSERT INTO `cad_admin_log` VALUES ('40', '32', '1', '2130706433', 'admin_menu', '239', '超级管理员 删除了节点：节点ID(239),节点标题(消息),节点链接(admin/usermessage/index)', '1', '1556897394');
INSERT INTO `cad_admin_log` VALUES ('41', '16', '1', '2130706433', 'admin_config', '1', '超级管理员 编辑了配置：原数据：分组(base)、类型(switch)、标题(站点开关1)、名称(web_site_status)', '1', '1556900152');
INSERT INTO `cad_admin_log` VALUES ('42', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(develop)', '1', '1557159968');
INSERT INTO `cad_admin_log` VALUES ('43', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(develop)', '1', '1557237541');
INSERT INTO `cad_admin_log` VALUES ('44', '22', '1', '2130706433', 'database', '0', '超级管理员 优化了数据表：dp_admin_access', '1', '1557245122');
INSERT INTO `cad_admin_log` VALUES ('45', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(base)', '1', '1557278067');
INSERT INTO `cad_admin_log` VALUES ('46', '23', '1', '2130706433', 'database', '0', '超级管理员 修复了数据表：`dp_admin_access`,`dp_admin_action`,`dp_admin_attachment`,`dp_admin_config`,`dp_admin_hook`,`dp_admin_hook_plugin`,`dp_admin_icon`,`dp_admin_icon_list`,`dp_admin_log`,`dp_admin_menu`,`dp_admin_message`,`dp_admin_module`,`dp_admin_packet`,`dp_admin_plugin`,`dp_admin_role`,`dp_admin_user`', '1', '1557362020');
INSERT INTO `cad_admin_log` VALUES ('47', '31', '1', '2130706433', 'admin_menu', '3', '超级管理员 编辑了节点：节点ID(3)', '1', '1557630826');
INSERT INTO `cad_admin_log` VALUES ('48', '32', '1', '2130706433', 'admin_menu', '222', '超级管理员 删除了节点：节点ID(222),节点标题(消息中心),节点链接(admin/message/index)', '1', '1557630855');
INSERT INTO `cad_admin_log` VALUES ('49', '30', '1', '2130706433', 'admin_menu', '254', '超级管理员 添加了节点：所属模块(admin),所属节点ID(2),节点标题(个人设置),节点链接(admin/index/profile)', '1', '1557630970');
INSERT INTO `cad_admin_log` VALUES ('50', '32', '1', '2130706433', 'admin_menu', '212', '超级管理员 删除了节点：节点ID(212),节点标题(个人设置),节点链接(admin/index/profile)', '1', '1557631269');
INSERT INTO `cad_admin_log` VALUES ('51', '32', '1', '2130706433', 'admin_menu', '3', '超级管理员 删除了节点：节点ID(3),节点标题(清空缓存),节点链接(admin/index/wipecache)', '1', '1557631426');
INSERT INTO `cad_admin_log` VALUES ('52', '34', '1', '2130706433', 'admin_menu', '1', '超级管理员 禁用了节点：节点ID(1),节点标题(首页),节点链接(admin/index/index)', '1', '1557631716');
INSERT INTO `cad_admin_log` VALUES ('53', '33', '1', '2130706433', 'admin_menu', '1', '超级管理员 启用了节点：节点ID(1),节点标题(首页),节点链接(admin/index/index)', '1', '1557631723');
INSERT INTO `cad_admin_log` VALUES ('54', '34', '1', '2130706433', 'admin_menu', '70', '超级管理员 禁用了节点：节点ID(70),节点标题(后台首页),节点链接(admin/index/index)', '1', '1557631814');
INSERT INTO `cad_admin_log` VALUES ('55', '32', '1', '2130706433', 'admin_menu', '70', '超级管理员 删除了节点：节点ID(70),节点标题(后台首页),节点链接(admin/index/index)', '1', '1557631817');
INSERT INTO `cad_admin_log` VALUES ('56', '32', '1', '2130706433', 'admin_menu', '254', '超级管理员 删除了节点：节点ID(254),节点标题(个人设置),节点链接(admin/index/profile)', '1', '1557631835');
INSERT INTO `cad_admin_log` VALUES ('57', '31', '1', '2130706433', 'admin_menu', '2', '超级管理员 编辑了节点：节点ID(2)', '1', '1557631868');
INSERT INTO `cad_admin_log` VALUES ('58', '31', '1', '2130706433', 'admin_menu', '2', '超级管理员 编辑了节点：节点ID(2)', '1', '1557631910');
INSERT INTO `cad_admin_log` VALUES ('59', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(system)', '1', '1557677479');
INSERT INTO `cad_admin_log` VALUES ('60', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(base)', '1', '1558891818');
INSERT INTO `cad_admin_log` VALUES ('61', '42', '1', '2130706433', 'admin_config', '0', '超级管理员 更新了系统设置：分组(base)', '1', '1558891829');
INSERT INTO `cad_admin_log` VALUES ('62', '32', '1', '2130706433', 'admin_menu', '221', '超级管理员 删除了节点：节点ID(221),节点标题(快速编辑),节点链接(user/message/quickedit)', '1', '1558915516');
INSERT INTO `cad_admin_log` VALUES ('63', '32', '1', '2130706433', 'admin_menu', '220', '超级管理员 删除了节点：节点ID(220),节点标题(禁用),节点链接(user/message/disable)', '1', '1558915522');
INSERT INTO `cad_admin_log` VALUES ('64', '32', '1', '2130706433', 'admin_menu', '219', '超级管理员 删除了节点：节点ID(219),节点标题(启用),节点链接(user/message/enable)', '1', '1558915527');
INSERT INTO `cad_admin_log` VALUES ('65', '32', '1', '2130706433', 'admin_menu', '218', '超级管理员 删除了节点：节点ID(218),节点标题(删除),节点链接(user/message/delete)', '1', '1558915533');
INSERT INTO `cad_admin_log` VALUES ('66', '32', '1', '2130706433', 'admin_menu', '217', '超级管理员 删除了节点：节点ID(217),节点标题(编辑),节点链接(user/message/edit)', '1', '1558915539');
INSERT INTO `cad_admin_log` VALUES ('67', '32', '1', '2130706433', 'admin_menu', '216', '超级管理员 删除了节点：节点ID(216),节点标题(新增),节点链接(user/message/add)', '1', '1558915545');
INSERT INTO `cad_admin_log` VALUES ('68', '32', '1', '2130706433', 'admin_menu', '215', '超级管理员 删除了节点：节点ID(215),节点标题(消息列表),节点链接(user/message/index)', '1', '1558915551');
INSERT INTO `cad_admin_log` VALUES ('69', '32', '1', '2130706433', 'admin_menu', '214', '超级管理员 删除了节点：节点ID(214),节点标题(消息管理),节点链接()', '1', '1558915557');
INSERT INTO `cad_admin_log` VALUES ('70', '32', '1', '2130706433', 'admin_menu', '235', '超级管理员 删除了节点：节点ID(235),节点标题(快速编辑),节点链接(user/role/quickedit)', '1', '1558915633');
INSERT INTO `cad_admin_log` VALUES ('71', '32', '1', '2130706433', 'admin_menu', '75', '超级管理员 删除了节点：节点ID(75),节点标题(禁用),节点链接(user/role/disable)', '1', '1558915637');
INSERT INTO `cad_admin_log` VALUES ('72', '32', '1', '2130706433', 'admin_menu', '74', '超级管理员 删除了节点：节点ID(74),节点标题(启用),节点链接(user/role/enable)', '1', '1558915641');
INSERT INTO `cad_admin_log` VALUES ('73', '34', '1', '2130706433', 'admin_menu', '73', '超级管理员 禁用了节点：节点ID(73),节点标题(删除),节点链接(user/role/delete)', '1', '1558915644');
INSERT INTO `cad_admin_log` VALUES ('74', '33', '1', '2130706433', 'admin_menu', '73', '超级管理员 启用了节点：节点ID(73),节点标题(删除),节点链接(user/role/delete)', '1', '1558915646');
INSERT INTO `cad_admin_log` VALUES ('75', '32', '1', '2130706433', 'admin_menu', '73', '超级管理员 删除了节点：节点ID(73),节点标题(删除),节点链接(user/role/delete)', '1', '1558915647');
INSERT INTO `cad_admin_log` VALUES ('76', '32', '1', '2130706433', 'admin_menu', '72', '超级管理员 删除了节点：节点ID(72),节点标题(编辑),节点链接(user/role/edit)', '1', '1558915651');
INSERT INTO `cad_admin_log` VALUES ('77', '32', '1', '2130706433', 'admin_menu', '71', '超级管理员 删除了节点：节点ID(71),节点标题(新增),节点链接(user/role/add)', '1', '1558915655');
INSERT INTO `cad_admin_log` VALUES ('78', '32', '1', '2130706433', 'admin_menu', '67', '超级管理员 删除了节点：节点ID(67),节点标题(角色管理),节点链接(user/role/index)', '1', '1558915659');
INSERT INTO `cad_admin_log` VALUES ('79', '32', '1', '2130706433', 'admin_menu', '234', '超级管理员 删除了节点：节点ID(234),节点标题(快速编辑),节点链接(user/index/quickedit)', '1', '1558915662');
INSERT INTO `cad_admin_log` VALUES ('80', '32', '1', '2130706433', 'admin_menu', '76', '超级管理员 删除了节点：节点ID(76),节点标题(授权),节点链接(user/index/access)', '1', '1558915666');
INSERT INTO `cad_admin_log` VALUES ('81', '32', '1', '2130706433', 'admin_menu', '25', '超级管理员 删除了节点：节点ID(25),节点标题(禁用),节点链接(user/index/disable)', '1', '1558959880');
INSERT INTO `cad_admin_log` VALUES ('82', '32', '1', '2130706433', 'admin_menu', '24', '超级管理员 删除了节点：节点ID(24),节点标题(启用),节点链接(user/index/enable)', '1', '1558959886');
INSERT INTO `cad_admin_log` VALUES ('83', '32', '1', '2130706433', 'admin_menu', '23', '超级管理员 删除了节点：节点ID(23),节点标题(删除),节点链接(user/index/delete)', '1', '1558959891');
INSERT INTO `cad_admin_log` VALUES ('84', '32', '1', '2130706433', 'admin_menu', '22', '超级管理员 删除了节点：节点ID(22),节点标题(编辑),节点链接(user/index/edit)', '1', '1558959894');
INSERT INTO `cad_admin_log` VALUES ('85', '32', '1', '2130706433', 'admin_menu', '21', '超级管理员 删除了节点：节点ID(21),节点标题(新增),节点链接(user/index/add)', '1', '1558959899');
INSERT INTO `cad_admin_log` VALUES ('86', '32', '1', '2130706433', 'admin_menu', '20', '超级管理员 删除了节点：节点ID(20),节点标题(用户管理),节点链接(user/index/index)', '1', '1558959905');
INSERT INTO `cad_admin_log` VALUES ('87', '32', '1', '2130706433', 'admin_menu', '19', '超级管理员 删除了节点：节点ID(19),节点标题(权限管理),节点链接()', '1', '1558959910');
INSERT INTO `cad_admin_log` VALUES ('88', '32', '1', '2130706433', 'admin_menu', '68', '超级管理员 删除了节点：节点ID(68),节点标题(用户),节点链接(user/index/index)', '1', '1558959916');
INSERT INTO `cad_admin_log` VALUES ('89', '35', '1', '2130706433', 'admin_module', '0', '超级管理员 安装了模块：门户', '1', '1559180437');
INSERT INTO `cad_admin_log` VALUES ('90', '36', '1', '2130706433', 'admin_module', '0', '超级管理员 卸载了模块：门户', '1', '1559187239');
INSERT INTO `cad_admin_log` VALUES ('91', '35', '1', '2130706433', 'admin_module', '0', '超级管理员 安装了模块：平台', '1', '1559187254');
INSERT INTO `cad_admin_log` VALUES ('92', '30', '1', '2130706433', 'admin_menu', '348', '超级管理员 添加了节点：所属模块(platform),所属节点ID(0),节点标题(平台),节点链接(platform/index/index)', '1', '1559187888');
INSERT INTO `cad_admin_log` VALUES ('93', '30', '1', '2130706433', 'admin_menu', '349', '超级管理员 添加了节点：所属模块(platform),所属节点ID(348),节点标题(存储插件),节点链接()', '1', '1559188199');
INSERT INTO `cad_admin_log` VALUES ('94', '30', '1', '2130706433', 'admin_menu', '350', '超级管理员 添加了节点：所属模块(platform),所属节点ID(349),节点标题(插件管理),节点链接(platform/storage/index)', '1', '1559189074');
INSERT INTO `cad_admin_log` VALUES ('95', '35', '1', '2130706433', 'admin_module', '0', '超级管理员 安装了模块：门户', '1', '1559197274');

-- ----------------------------
-- Table structure for cad_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_menu`;
CREATE TABLE `cad_admin_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单id',
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '模块名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` varchar(16) NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=444 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of cad_admin_menu
-- ----------------------------
INSERT INTO `cad_admin_menu` VALUES ('1', '0', 'admin', '首页', 'fa fa-fw fa-home', 'module_admin', 'admin/index/index', '_self', '0', '1467617722', '1555816129', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('2', '1', 'admin', '首页', 'fa fa-fw fa-folder-open-o', 'module_admin', 'admin/index/index', '_self', '0', '1467618170', '1557631910', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('4', '0', 'admin', '系统', 'fa fa-fw fa-gear', 'module_admin', 'admin/system/index', '_self', '0', '1467618361', '1477710540', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('5', '4', 'admin', '系统功能', 'si si-wrench', 'module_admin', '', '_self', '0', '1467618441', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('6', '5', 'admin', '系统设置', 'fa fa-fw fa-wrench', 'module_admin', 'admin/system/index', '_self', '0', '1467618490', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('7', '5', 'admin', '配置管理', 'fa fa-fw fa-gears', 'module_admin', 'admin/config/index', '_self', '0', '1467618618', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('8', '7', 'admin', '新增', '', 'module_admin', 'admin/config/add', '_self', '0', '1467618648', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('9', '7', 'admin', '编辑', '', 'module_admin', 'admin/config/edit', '_self', '0', '1467619566', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('10', '7', 'admin', '删除', '', 'module_admin', 'admin/config/delete', '_self', '0', '1467619583', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('11', '7', 'admin', '启用', '', 'module_admin', 'admin/config/enable', '_self', '0', '1467619609', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('12', '7', 'admin', '禁用', '', 'module_admin', 'admin/config/disable', '_self', '0', '1467619637', '1477710695', '5', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('13', '5', 'admin', '节点管理', 'fa fa-fw fa-bars', 'module_admin', 'admin/menu/index', '_self', '0', '1467619882', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('14', '13', 'admin', '新增', '', 'module_admin', 'admin/menu/add', '_self', '0', '1467619902', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('15', '13', 'admin', '编辑', '', 'module_admin', 'admin/menu/edit', '_self', '0', '1467620331', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('16', '13', 'admin', '删除', '', 'module_admin', 'admin/menu/delete', '_self', '0', '1467620363', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('17', '13', 'admin', '启用', '', 'module_admin', 'admin/menu/enable', '_self', '0', '1467620386', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('18', '13', 'admin', '禁用', '', 'module_admin', 'admin/menu/disable', '_self', '0', '1467620404', '1477710695', '5', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('211', '64', 'admin', '日志详情', '', 'module_admin', 'admin/log/details', '_self', '0', '1480299320', '1480299320', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('32', '4', 'admin', '扩展中心', 'si si-social-dropbox', 'module_admin', '', '_self', '0', '1467688853', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('33', '32', 'admin', '模块管理', 'fa fa-fw fa-th-large', 'module_admin', 'admin/module/index', '_self', '0', '1467689008', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('34', '33', 'admin', '导入', '', 'module_admin', 'admin/module/import', '_self', '0', '1467689153', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('35', '33', 'admin', '导出', '', 'module_admin', 'admin/module/export', '_self', '0', '1467689173', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('36', '33', 'admin', '安装', '', 'module_admin', 'admin/module/install', '_self', '0', '1467689192', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('37', '33', 'admin', '卸载', '', 'module_admin', 'admin/module/uninstall', '_self', '0', '1467689241', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('38', '33', 'admin', '启用', '', 'module_admin', 'admin/module/enable', '_self', '0', '1467689294', '1477710695', '5', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('39', '33', 'admin', '禁用', '', 'module_admin', 'admin/module/disable', '_self', '0', '1467689312', '1477710695', '6', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('40', '33', 'admin', '更新', '', 'module_admin', 'admin/module/update', '_self', '0', '1467689341', '1477710695', '7', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('41', '32', 'admin', '插件管理', 'fa fa-fw fa-puzzle-piece', 'module_admin', 'admin/plugin/index', '_self', '0', '1467689527', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('42', '41', 'admin', '导入', '', 'module_admin', 'admin/plugin/import', '_self', '0', '1467689650', '1477710695', '1', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('43', '41', 'admin', '导出', '', 'module_admin', 'admin/plugin/export', '_self', '0', '1467689665', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('44', '41', 'admin', '安装', '', 'module_admin', 'admin/plugin/install', '_self', '0', '1467689680', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('45', '41', 'admin', '卸载', '', 'module_admin', 'admin/plugin/uninstall', '_self', '0', '1467689700', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('46', '41', 'admin', '启用', '', 'module_admin', 'admin/plugin/enable', '_self', '0', '1467689730', '1477710695', '5', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('47', '41', 'admin', '禁用', '', 'module_admin', 'admin/plugin/disable', '_self', '0', '1467689747', '1477710695', '6', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('48', '41', 'admin', '设置', '', 'module_admin', 'admin/plugin/config', '_self', '0', '1467689789', '1477710695', '7', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('49', '41', 'admin', '管理', '', 'module_admin', 'admin/plugin/manage', '_self', '0', '1467689846', '1477710695', '8', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('50', '5', 'admin', '附件管理', 'fa fa-fw fa-cloud-upload', 'module_admin', 'admin/attachment/index', '_self', '0', '1467690161', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('52', '50', 'admin', '下载', '', 'module_admin', 'admin/attachment/download', '_self', '0', '1467690334', '1477710695', '2', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('53', '50', 'admin', '启用', '', 'module_admin', 'admin/attachment/enable', '_self', '0', '1467690352', '1477710695', '3', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('54', '50', 'admin', '禁用', '', 'module_admin', 'admin/attachment/disable', '_self', '0', '1467690369', '1477710695', '4', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('55', '50', 'admin', '删除', '', 'module_admin', 'admin/attachment/delete', '_self', '0', '1467690396', '1477710695', '5', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('56', '41', 'admin', '删除', '', 'module_admin', 'admin/plugin/delete', '_self', '0', '1467858065', '1477710695', '11', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('57', '41', 'admin', '编辑', '', 'module_admin', 'admin/plugin/edit', '_self', '0', '1467858092', '1477710695', '10', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('60', '41', 'admin', '新增', '', 'module_admin', 'admin/plugin/add', '_self', '0', '1467858421', '1477710695', '9', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('61', '41', 'admin', '执行', '', 'module_admin', 'admin/plugin/execute', '_self', '0', '1467879016', '1477710695', '14', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('62', '13', 'admin', '保存', '', 'module_admin', 'admin/menu/save', '_self', '0', '1468073039', '1477710695', '6', '1', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('64', '5', 'admin', '系统日志', 'fa fa-fw fa-book', 'module_admin', 'admin/log/index', '_self', '0', '1476111944', '1477710695', '6', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('65', '5', 'admin', '数据库管理', 'fa fa-fw fa-database', 'module_admin', 'admin/database/index', '_self', '0', '1476111992', '1477710695', '8', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('66', '32', 'admin', '数据包管理', 'fa fa-fw fa-database', 'module_admin', 'admin/packet/index', '_self', '0', '1476112326', '1477710695', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('69', '32', 'admin', '钩子管理', 'fa fa-fw fa-anchor', 'module_admin', 'admin/hook/index', '_self', '0', '1476236193', '1477710695', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('77', '69', 'admin', '新增', '', 'module_admin', 'admin/hook/add', '_self', '0', '1476668971', '1477710695', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('78', '69', 'admin', '编辑', '', 'module_admin', 'admin/hook/edit', '_self', '0', '1476669006', '1477710695', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('79', '69', 'admin', '删除', '', 'module_admin', 'admin/hook/delete', '_self', '0', '1476669375', '1477710695', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('80', '69', 'admin', '启用', '', 'module_admin', 'admin/hook/enable', '_self', '0', '1476669427', '1477710695', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('81', '69', 'admin', '禁用', '', 'module_admin', 'admin/hook/disable', '_self', '0', '1476669564', '1477710695', '5', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('183', '66', 'admin', '安装', '', 'module_admin', 'admin/packet/install', '_self', '0', '1476851362', '1477710695', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('184', '66', 'admin', '卸载', '', 'module_admin', 'admin/packet/uninstall', '_self', '0', '1476851382', '1477710695', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('185', '5', 'admin', '行为管理', 'fa fa-fw fa-bug', 'module_admin', 'admin/action/index', '_self', '0', '1476882441', '1477710695', '7', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('186', '185', 'admin', '新增', '', 'module_admin', 'admin/action/add', '_self', '0', '1476884439', '1477710695', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('187', '185', 'admin', '编辑', '', 'module_admin', 'admin/action/edit', '_self', '0', '1476884464', '1477710695', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('188', '185', 'admin', '启用', '', 'module_admin', 'admin/action/enable', '_self', '0', '1476884493', '1477710695', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('189', '185', 'admin', '禁用', '', 'module_admin', 'admin/action/disable', '_self', '0', '1476884534', '1477710695', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('190', '185', 'admin', '删除', '', 'module_admin', 'admin/action/delete', '_self', '0', '1476884551', '1477710695', '5', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('191', '65', 'admin', '备份数据库', '', 'module_admin', 'admin/database/export', '_self', '0', '1476972746', '1477710695', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('192', '65', 'admin', '还原数据库', '', 'module_admin', 'admin/database/import', '_self', '0', '1476972772', '1477710695', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('193', '65', 'admin', '优化表', '', 'module_admin', 'admin/database/optimize', '_self', '0', '1476972800', '1477710695', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('194', '65', 'admin', '修复表', '', 'module_admin', 'admin/database/repair', '_self', '0', '1476972825', '1477710695', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('195', '65', 'admin', '删除备份', '', 'module_admin', 'admin/database/delete', '_self', '0', '1476973457', '1477710695', '5', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('210', '41', 'admin', '快速编辑', '', 'module_admin', 'admin/plugin/quickedit', '_self', '0', '1477713981', '1477713981', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('209', '185', 'admin', '快速编辑', '', 'module_admin', 'admin/action/quickedit', '_self', '0', '1477713939', '1477713939', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('208', '7', 'admin', '快速编辑', '', 'module_admin', 'admin/config/quickedit', '_self', '0', '1477713808', '1477713808', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('207', '69', 'admin', '快速编辑', '', 'module_admin', 'admin/hook/quickedit', '_self', '0', '1477713770', '1477713770', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('225', '32', 'admin', '图标管理', 'fa fa-fw fa-tint', 'module_admin', 'admin/icon/index', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('226', '225', 'admin', '新增', '', 'module_admin', 'admin/icon/add', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('227', '225', 'admin', '编辑', '', 'module_admin', 'admin/icon/edit', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('228', '225', 'admin', '删除', '', 'module_admin', 'admin/icon/delete', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('229', '225', 'admin', '启用', '', 'module_admin', 'admin/icon/enable', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('230', '225', 'admin', '禁用', '', 'module_admin', 'admin/icon/disable', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('231', '225', 'admin', '快速编辑', '', 'module_admin', 'admin/icon/quickedit', '_self', '0', '1520908295', '1520908295', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('232', '225', 'admin', '图标列表', '', 'module_admin', 'admin/icon/items', '_self', '0', '1520923368', '1520923368', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('233', '225', 'admin', '更新图标', '', 'module_admin', 'admin/icon/reload', '_self', '0', '1520931908', '1520931908', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('236', '4', 'admin', '后台用户', 'fa fa-fw fa-user', 'module_admin', '', '_self', '0', '1555844071', '1555844204', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('237', '236', 'admin', '用户管理', 'fa fa-fw fa-user', 'module_admin', 'admin/user/index', '_self', '0', '1555844251', '1555844251', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('238', '236', 'admin', '角色管理', 'fa fa-fw fa-group', 'module_admin', 'admin/role/index', '_self', '0', '1555844752', '1555844752', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('240', '237', 'admin', '权限管理', 'fa fa-fw fa-key', 'module_admin', '', '_self', '0', '1556895955', '1556895955', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('241', '240', 'admin', '新增', '', 'module_admin', 'admin/user/add', '_self', '0', '1556896061', '1556896061', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('242', '240', 'admin', '编辑', '', 'module_admin', 'admin/user/edit', '_self', '0', '1556896129', '1556896129', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('243', '240', 'admin', '删除', '', 'module_admin', 'amdin/user/delete', '_self', '0', '1556896163', '1556896163', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('244', '238', 'admin', '启用', '', 'module_admin', 'admin/role/enable', '_self', '0', '1556896266', '1556896470', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('245', '238', 'admin', '禁用', '', 'module_admin', 'admin/role/disable', '_self', '0', '1556896309', '1556896522', '5', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('246', '238', 'admin', '快速编辑', '', 'module_admin', 'admin/role/quickedit', '_self', '0', '1556896421', '1556896542', '6', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('247', '240', 'admin', '启用', '', 'module_admin', 'admin/user/enable', '_self', '0', '1556896621', '1556896621', '4', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('248', '240', 'admin', '禁用', '', 'module_admin', 'admin/user/disable', '_self', '0', '1556896658', '1556896753', '5', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('249', '240', 'admin', '授权', '', 'module_admin', 'admin/user/access', '_self', '0', '1556896721', '1556896769', '6', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('250', '240', 'admin', '快速编辑', '', 'module_admin', 'admin/user/quickedit', '_self', '0', '1556896812', '1556896812', '7', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('251', '238', 'admin', '新增', '', 'module_admin', 'admin/role/add', '_self', '0', '1556896840', '1556896840', '1', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('252', '238', 'admin', '编辑', '', 'module_admin', 'admin/role/edit', '_self', '0', '1556896864', '1556896864', '2', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('253', '238', 'admin', '删除', '', 'module_admin', 'admin/role/delete', '_self', '0', '1556896892', '1556896892', '3', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('419', '416', 'cms', '删除', '', 'module_admin', 'cms/model/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('418', '416', 'cms', '编辑', '', 'module_admin', 'cms/model/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('417', '416', 'cms', '新增', '', 'module_admin', 'cms/model/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('416', '408', 'cms', '内容模型', 'fa fa-fw fa-th-large', 'module_admin', 'cms/model/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('415', '409', 'cms', '快速编辑', '', 'module_admin', 'cms/column/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('414', '409', 'cms', '禁用', '', 'module_admin', 'cms/column/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('413', '409', 'cms', '启用', '', 'module_admin', 'cms/column/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('412', '409', 'cms', '删除', '', 'module_admin', 'cms/column/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('411', '409', 'cms', '编辑', '', 'module_admin', 'cms/column/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('410', '409', 'cms', '新增', '', 'module_admin', 'cms/column/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('409', '408', 'cms', '栏目分类', 'fa fa-fw fa-sitemap', 'module_admin', 'cms/column/index', '_self', '1', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('408', '351', 'cms', '门户设置', 'fa fa-fw fa-sliders', 'module_admin', '', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('407', '401', 'cms', '快速编辑', '', 'module_admin', 'cms/support/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('406', '401', 'cms', '禁用', '', 'module_admin', 'cms/support/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('405', '401', 'cms', '启用', '', 'module_admin', 'cms/support/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('404', '401', 'cms', '删除', '', 'module_admin', 'cms/support/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('403', '401', 'cms', '编辑', '', 'module_admin', 'cms/support/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('402', '401', 'cms', '新增', '', 'module_admin', 'cms/support/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('401', '372', 'cms', '客服管理', 'fa fa-fw fa-commenting', 'module_admin', 'cms/support/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('400', '394', 'cms', '快速编辑', '', 'module_admin', 'cms/link/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('399', '394', 'cms', '禁用', '', 'module_admin', 'cms/link/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('398', '394', 'cms', '启用', '', 'module_admin', 'cms/link/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('397', '394', 'cms', '删除', '', 'module_admin', 'cms/link/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('396', '394', 'cms', '编辑', '', 'module_admin', 'cms/link/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('395', '394', 'cms', '新增', '', 'module_admin', 'cms/link/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('394', '372', 'cms', '友情链接', 'fa fa-fw fa-link', 'module_admin', 'cms/link/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('393', '387', 'cms', '快速编辑', '', 'module_admin', 'cms/slider/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('392', '387', 'cms', '禁用', '', 'module_admin', 'cms/slider/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('391', '387', 'cms', '启用', '', 'module_admin', 'cms/slider/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('390', '387', 'cms', '删除', '', 'module_admin', 'cms/slider/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('389', '387', 'cms', '编辑', '', 'module_admin', 'cms/slider/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('388', '387', 'cms', '新增', '', 'module_admin', 'cms/slider/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('387', '372', 'cms', '滚动图片', 'fa fa-fw fa-photo', 'module_admin', 'cms/slider/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('386', '380', 'cms', '快速编辑', '', 'module_admin', 'cms/advert_type/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('385', '380', 'cms', '禁用', '', 'module_admin', 'cms/advert_type/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('384', '380', 'cms', '启用', '', 'module_admin', 'cms/advert_type/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('383', '380', 'cms', '删除', '', 'module_admin', 'cms/advert_type/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('382', '380', 'cms', '编辑', '', 'module_admin', 'cms/advert_type/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('381', '380', 'cms', '新增', '', 'module_admin', 'cms/advert_type/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('380', '373', 'cms', '广告分类', '', 'module_admin', 'cms/advert_type/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('379', '373', 'cms', '快速编辑', '', 'module_admin', 'cms/advert/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('378', '373', 'cms', '禁用', '', 'module_admin', 'cms/advert/disable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('377', '373', 'cms', '启用', '', 'module_admin', 'cms/advert/enable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('376', '373', 'cms', '删除', '', 'module_admin', 'cms/advert/delete', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('375', '373', 'cms', '编辑', '', 'module_admin', 'cms/advert/edit', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('374', '373', 'cms', '新增', '', 'module_admin', 'cms/advert/add', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('373', '372', 'cms', '广告管理', 'fa fa-fw fa-handshake-o', 'module_admin', 'cms/advert/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('372', '351', 'cms', '营销管理', 'fa fa-fw fa-money', 'module_admin', '', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('371', '351', 'cms', '内容管理', 'fa fa-fw fa-th-list', 'module_admin', '', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('370', '368', 'cms', '还原', '', 'module_admin', 'cms/recycle/restore', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('369', '368', 'cms', '删除', '', 'module_admin', 'cms/recycle/delete', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('368', '352', 'cms', '回收站', 'fa fa-fw fa-recycle', 'module_admin', 'cms/recycle/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('367', '361', 'cms', '快速编辑', '', 'module_admin', 'cms/page/quickedit', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('366', '361', 'cms', '禁用', '', 'module_admin', 'cms/page/disable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('365', '361', 'cms', '启用', '', 'module_admin', 'cms/page/enable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('364', '361', 'cms', '删除', '', 'module_admin', 'cms/page/delete', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('363', '361', 'cms', '编辑', '', 'module_admin', 'cms/page/edit', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('362', '361', 'cms', '新增', '', 'module_admin', 'cms/page/add', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('361', '352', 'cms', '单页管理', 'fa fa-fw fa-file-word-o', 'module_admin', 'cms/page/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('360', '355', 'cms', '快速编辑', '', 'module_admin', 'cms/document/quickedit', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('359', '355', 'cms', '禁用', '', 'module_admin', 'cms/document/disable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('358', '355', 'cms', '启用', '', 'module_admin', 'cms/document/enable', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('357', '355', 'cms', '删除', '', 'module_admin', 'cms/document/delete', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('356', '355', 'cms', '编辑', '', 'module_admin', 'cms/document/edit', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('355', '352', 'cms', '文档列表', 'fa fa-fw fa-list', 'module_admin', 'cms/document/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('354', '352', 'cms', '发布文档', 'fa fa-fw fa-plus', 'module_admin', 'cms/document/add', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('353', '352', 'cms', '仪表盘', 'fa fa-fw fa-tachometer', 'module_admin', 'cms/index/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('352', '351', 'cms', '常用操作', 'fa fa-fw fa-folder-open-o', 'module_admin', '', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('351', '0', 'cms', '门户', 'fa fa-fw fa-newspaper-o', 'module_admin', 'cms/index/index', '_self', '0', '1559197274', '1559197274', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('350', '349', 'platform', '插件管理', 'fa fa-fw fa-gears', 'module_admin', 'platform/storage/index', '_self', '0', '1559189075', '1559189075', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('349', '348', 'platform', '存储插件', 'fa fa-fw fa-database', 'module_admin', '', '_self', '0', '1559188199', '1559188199', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('348', '0', 'platform', '平台', 'fa fa-fw fa-desktop', 'module_admin', 'platform/index/index', '_self', '0', '1559187889', '1559187889', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('420', '416', 'cms', '启用', '', 'module_admin', 'cms/model/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('421', '416', 'cms', '禁用', '', 'module_admin', 'cms/model/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('422', '416', 'cms', '快速编辑', '', 'module_admin', 'cms/model/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('423', '416', 'cms', '字段管理', '', 'module_admin', 'cms/field/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('424', '423', 'cms', '新增', '', 'module_admin', 'cms/field/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('425', '423', 'cms', '编辑', '', 'module_admin', 'cms/field/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('426', '423', 'cms', '删除', '', 'module_admin', 'cms/field/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('427', '423', 'cms', '启用', '', 'module_admin', 'cms/field/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('428', '423', 'cms', '禁用', '', 'module_admin', 'cms/field/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('429', '423', 'cms', '快速编辑', '', 'module_admin', 'cms/field/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('430', '408', 'cms', '导航管理', 'fa fa-fw fa-map-signs', 'module_admin', 'cms/nav/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('431', '430', 'cms', '新增', '', 'module_admin', 'cms/nav/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('432', '430', 'cms', '编辑', '', 'module_admin', 'cms/nav/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('433', '430', 'cms', '删除', '', 'module_admin', 'cms/nav/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('434', '430', 'cms', '启用', '', 'module_admin', 'cms/nav/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('435', '430', 'cms', '禁用', '', 'module_admin', 'cms/nav/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('436', '430', 'cms', '快速编辑', '', 'module_admin', 'cms/nav/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('437', '430', 'cms', '菜单管理', '', 'module_admin', 'cms/menu/index', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('438', '437', 'cms', '新增', '', 'module_admin', 'cms/menu/add', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('439', '437', 'cms', '编辑', '', 'module_admin', 'cms/menu/edit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('440', '437', 'cms', '删除', '', 'module_admin', 'cms/menu/delete', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('441', '437', 'cms', '启用', '', 'module_admin', 'cms/menu/enable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('442', '437', 'cms', '禁用', '', 'module_admin', 'cms/menu/disable', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');
INSERT INTO `cad_admin_menu` VALUES ('443', '437', 'cms', '快速编辑', '', 'module_admin', 'cms/menu/quickedit', '_self', '0', '1559197275', '1559197275', '100', '0', '1', '');

-- ----------------------------
-- Table structure for cad_admin_message
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_message`;
CREATE TABLE `cad_admin_message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid_receive` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '接收消息的用户id',
  `uid_send` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送消息的用户id',
  `type` varchar(128) NOT NULL DEFAULT '' COMMENT '消息分类',
  `content` text NOT NULL COMMENT '消息内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `read_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';

-- ----------------------------
-- Records of cad_admin_message
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_module
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_module`;
CREATE TABLE `cad_admin_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名称（标识）',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模块标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text COMMENT '配置信息',
  `access` text COMMENT '授权配置',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '模块唯一标识符',
  `system_module` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否为系统模块',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='模块表';

-- ----------------------------
-- Records of cad_admin_module
-- ----------------------------
INSERT INTO `cad_admin_module` VALUES ('1', 'admin', '系统', 'fa fa-fw fa-gear', '系统模块，DolphinPHP的核心模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'admin.dolphinphp.module', '1', '1468204902', '1468204902', '100', '1');
INSERT INTO `cad_admin_module` VALUES ('2', 'user', '用户', 'fa fa-fw fa-user', '用户模块，DolphinPHP自带模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'user.dolphinphp.module', '1', '1468204902', '1468204902', '100', '1');
INSERT INTO `cad_admin_module` VALUES ('4', 'platform', '平台', 'fa fa-fw fa-newspaper-o', '平台模块，\r\n						第三方里有oss对象存储确保有安装 【composer】 并通过 composer require aliyuncs/oss-sdk-php 安装SDK', 'ChouChouTian', 'http://www.xxxx.com', null, null, '1.0.0', 'platform.ming.module', '0', '1559187255', '1559187255', '100', '1');
INSERT INTO `cad_admin_module` VALUES ('5', 'cms', '门户', 'fa fa-fw fa-newspaper-o', '门户模块', 'CaiWeiMing', 'http://www.dolphinphp.com', '{\"summary\":0,\"contact\":\"<div class=\\\"font-s13 push\\\"><strong>\\u6cb3\\u6e90\\u5e02\\u5353\\u9510\\u79d1\\u6280\\u6709\\u9650\\u516c\\u53f8<\\/strong><br \\/>\\r\\n\\u5730\\u5740\\uff1a\\u6cb3\\u6e90\\u5e02\\u6c5f\\u4e1c\\u65b0\\u533a\\u4e1c\\u73af\\u8def\\u6c47\\u901a\\u82d1D3-H232<br \\/>\\r\\n\\u7535\\u8bdd\\uff1a0762-8910006<br \\/>\\r\\n\\u90ae\\u7bb1\\uff1aadmin@zrthink.com<\\/div>\",\"meta_head\":\"\",\"meta_foot\":\"\",\"support_status\":1,\"support_color\":\"rgba(0,158,232,1)\",\"support_wx\":\"\",\"support_extra\":\"\"}', '{\"column\":{\"title\":\"\\u680f\\u76ee\\u6388\\u6743\",\"nodes\":{\"group\":\"column\",\"table_name\":\"cms_column\",\"primary_key\":\"id\",\"parent_id\":\"pid\",\"node_name\":\"name\"}}}', '1.0.0', 'cms.ming.module', '0', '1559197275', '1559197275', '100', '1');

-- ----------------------------
-- Table structure for cad_admin_packet
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_packet`;
CREATE TABLE `cad_admin_packet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包名',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '数据包标题',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者url',
  `version` varchar(16) NOT NULL,
  `tables` text NOT NULL COMMENT '数据表名',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据包表';

-- ----------------------------
-- Records of cad_admin_packet
-- ----------------------------

-- ----------------------------
-- Table structure for cad_admin_plugin
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_plugin`;
CREATE TABLE `cad_admin_plugin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of cad_admin_plugin
-- ----------------------------
INSERT INTO `cad_admin_plugin` VALUES ('1', 'SystemInfo', '系统环境信息', 'fa fa-fw fa-info-circle', '在后台首页显示服务器信息', '蔡伟明', 'http://www.caiweiming.com', '{\"display\":\"1\",\"width\":\"6\"}', '1.0.0', 'system_info.ming.plugin', '0', '1477757503', '1477757503', '100', '1');
INSERT INTO `cad_admin_plugin` VALUES ('2', 'DevTeam', '开发团队成员信息', 'fa fa-fw fa-users', '开发团队成员信息', '蔡伟明', 'http://www.caiweiming.com', '{\"display\":\"1\",\"width\":\"6\"}', '1.0.0', 'dev_team.ming.plugin', '0', '1477755780', '1477755780', '100', '1');

-- ----------------------------
-- Table structure for cad_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_role`;
CREATE TABLE `cad_admin_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text NOT NULL COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否可登录后台',
  `default_module` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '默认访问模块',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of cad_admin_role
-- ----------------------------
INSERT INTO `cad_admin_role` VALUES ('1', '0', '超级管理员', '系统默认创建的角色，拥有最高权限', '', '0', '1476270000', '1468117612', '1', '1', '0');
INSERT INTO `cad_admin_role` VALUES ('2', '0', '测试角色', '111111', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\",\"23\",\"24\",\"25\",\"32\",\"33\",\"34\",\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\",\"45\",\"46\",\"47\",\"48\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\",\"57\",\"60\",\"61\",\"62\",\"64\",\"65\",\"66\",\"67\",\"68\",\"69\",\"70\",\"71\",\"72\",\"73\",\"74\",\"75\",\"76\",\"77\",\"78\",\"79\",\"80\",\"81\",\"183\",\"184\",\"185\",\"186\",\"187\",\"188\",\"189\",\"190\",\"191\",\"192\",\"193\",\"194\",\"195\",\"207\",\"208\",\"209\",\"210\",\"211\",\"212\",\"213\",\"214\",\"215\",\"216\",\"217\",\"218\",\"219\",\"220\",\"221\",\"222\",\"223\",\"224\",\"225\",\"226\",\"227\",\"228\",\"229\",\"230\",\"231\",\"232\",\"233\",\"234\",\"235\",\"236\",\"237\",\"238\",\"240\",\"241\",\"242\",\"243\",\"244\",\"245\",\"246\",\"247\",\"248\",\"249\",\"250\",\"251\",\"252\",\"253\"]', '100', '1557278522', '1557278522', '1', '1', '1');

-- ----------------------------
-- Table structure for cad_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `cad_admin_user`;
CREATE TABLE `cad_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `role` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `group` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `signup_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cad_admin_user
-- ----------------------------
INSERT INTO `cad_admin_user` VALUES ('1', 'admin', '超级管理员', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '123@q.com', '0', '', '0', '0', '0.00', '0', '1', '0', '0', '1476065410', '1559196672', '1559196672', '2130706433', '100', '1');

-- ----------------------------
-- Table structure for cad_cms_advert
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_advert`;
CREATE TABLE `cad_cms_advert` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `tagname` varchar(30) NOT NULL DEFAULT '' COMMENT '广告位标识',
  `ad_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '广告类型',
  `timeset` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '时间限制:0-永不过期,1-在设内时间内有效',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '广告位名称',
  `content` text NOT NULL COMMENT '广告内容',
  `expcontent` text NOT NULL COMMENT '过期显示内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Records of cad_cms_advert
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_advert_type
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_advert_type`;
CREATE TABLE `cad_cms_advert_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告分类表';

-- ----------------------------
-- Records of cad_cms_advert_type
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_column
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_column`;
CREATE TABLE `cad_cms_column` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `content` text NOT NULL COMMENT '内容',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '字体图标',
  `index_template` varchar(32) NOT NULL DEFAULT '' COMMENT '封面模板',
  `list_template` varchar(32) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `detail_template` varchar(32) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `post_auth` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '投稿权限',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `hide` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `rank_auth` int(11) NOT NULL DEFAULT '0' COMMENT '浏览权限，-1待审核，0为开放浏览，大于0则为对应的用户角色id',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '栏目属性：0-最终列表栏目，1-外部链接，2-频道封面',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of cad_cms_column
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_document
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_document`;
CREATE TABLE `cad_cms_document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档模型ID',
  `title` varchar(256) NOT NULL DEFAULT '' COMMENT '标题',
  `shorttitle` varchar(32) NOT NULL DEFAULT '' COMMENT '简略标题',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `flag` set('j','p','b','s','a','f','c','h') DEFAULT NULL COMMENT '自定义属性',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `good` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `bad` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '踩数',
  `mark` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `trash` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '回收站',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档基础表';

-- ----------------------------
-- Records of cad_cms_document
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_field
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_field`;
CREATE TABLE `cad_cms_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段名称',
  `name` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '字段标题',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL DEFAULT '' COMMENT '字段定义',
  `value` text COMMENT '默认值',
  `options` text COMMENT '额外选项',
  `tips` varchar(256) NOT NULL DEFAULT '' COMMENT '提示说明',
  `fixed` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否为固定字段',
  `show` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `model` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属文档模型id',
  `ajax_url` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框ajax地址',
  `next_items` varchar(256) NOT NULL DEFAULT '' COMMENT '联动下拉框的下级下拉框名，多个以逗号隔开',
  `param` varchar(32) NOT NULL DEFAULT '' COMMENT '联动下拉框请求参数名',
  `format` varchar(32) NOT NULL DEFAULT '' COMMENT '格式，用于格式文本',
  `table` varchar(32) NOT NULL DEFAULT '' COMMENT '表名，只用于快速联动类型',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '联动级别，只用于快速联动类型',
  `key` varchar(32) NOT NULL DEFAULT '' COMMENT '键字段，只用于快速联动类型',
  `option` varchar(32) NOT NULL DEFAULT '' COMMENT '值字段，只用于快速联动类型',
  `pid` varchar(32) NOT NULL DEFAULT '' COMMENT '父级id字段，只用于快速联动类型',
  `ak` varchar(32) NOT NULL DEFAULT '' COMMENT '百度地图appkey',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='文档字段表';

-- ----------------------------
-- Records of cad_cms_field
-- ----------------------------
INSERT INTO `cad_cms_field` VALUES ('1', 'id', 'ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', 'ID', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480562978', '1480562978', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('2', 'cid', '栏目', 'select', 'int(11) UNSIGNED NOT NULL', '0', '', '请选择所属栏目', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480562978', '1480562978', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('3', 'uid', '用户ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563110', '1480563110', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('4', 'model', '模型ID', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563110', '1480563110', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('5', 'title', '标题', 'text', 'varchar(128) NOT NULL', '', '', '文档标题', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480575844', '1480576134', '1', '1');
INSERT INTO `cad_cms_field` VALUES ('6', 'shorttitle', '简略标题', 'text', 'varchar(32) NOT NULL', '', '', '简略标题', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480575844', '1480576134', '1', '1');
INSERT INTO `cad_cms_field` VALUES ('7', 'flag', '自定义属性', 'checkbox', 'set(\'j\',\'p\',\'b\',\'s\',\'a\',\'f\',\'h\',\'c\') NULL DEFAULT NULL', '', 'j:跳转\r\np:图片\r\nb:加粗\r\ns:滚动\r\na:特荐\r\nf:幻灯\r\nh:头条\r\nc:推荐', '自定义属性', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480671258', '1480671258', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('8', 'view', '阅读量', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480563149', '1480563149', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('9', 'comment', '评论数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563189', '1480563189', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('10', 'good', '点赞数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563279', '1480563279', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('11', 'bad', '踩数', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563330', '1480563330', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('12', 'mark', '收藏数量', 'text', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563372', '1480563372', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('13', 'create_time', '创建时间', 'datetime', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563406', '1480563406', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('14', 'update_time', '更新时间', 'datetime', 'int(11) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563432', '1480563432', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('15', 'sort', '排序', 'text', 'int(11) NOT NULL', '100', '', '', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480563510', '1480563510', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('16', 'status', '状态', 'radio', 'tinyint(2) UNSIGNED NOT NULL', '1', '0:禁用\r\n1:启用', '', '0', '1', '0', '', '', '', '', '', '0', '', '', '', '', '1480563576', '1480563576', '100', '1');
INSERT INTO `cad_cms_field` VALUES ('17', 'trash', '回收站', 'text', 'tinyint(2) UNSIGNED NOT NULL', '0', '', '', '0', '0', '0', '', '', '', '', '', '0', '', '', '', '', '1480563576', '1480563576', '100', '1');

-- ----------------------------
-- Table structure for cad_cms_link
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_link`;
CREATE TABLE `cad_cms_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型：1-文字链接，2-图片链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '链接标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `logo` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '链接LOGO',
  `contact` varchar(255) NOT NULL DEFAULT '' COMMENT '联系方式',
  `sort` int(11) NOT NULL DEFAULT '100',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='有钱链接表';

-- ----------------------------
-- Records of cad_cms_link
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_menu
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_menu`;
CREATE TABLE `cad_cms_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '导航id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `column` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `page` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单页id',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型：0-栏目链接，1-单页链接，2-自定义链接',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
  `css` varchar(64) NOT NULL DEFAULT '' COMMENT 'css类',
  `rel` varchar(64) NOT NULL DEFAULT '' COMMENT '链接关系网',
  `target` varchar(16) NOT NULL DEFAULT '' COMMENT '打开方式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of cad_cms_menu
-- ----------------------------
INSERT INTO `cad_cms_menu` VALUES ('1', '1', '0', '0', '0', '2', '首页', 'cms/index/index', '', '', '_self', '1492345605', '1492345605', '100', '1');
INSERT INTO `cad_cms_menu` VALUES ('2', '2', '0', '0', '0', '2', '关于我们', 'http://www.dolphinphp.com', '', '', '_self', '1492346763', '1492346763', '100', '1');
INSERT INTO `cad_cms_menu` VALUES ('3', '3', '0', '0', '0', '2', '开发文档', 'http://www.kancloud.cn/ming5112/dolphinphp', '', '', '_self', '1492346812', '1492346812', '100', '1');
INSERT INTO `cad_cms_menu` VALUES ('4', '3', '0', '0', '0', '2', '开发者社区', 'http://bbs.dolphinphp.com/', '', '', '_self', '1492346832', '1492346832', '100', '1');
INSERT INTO `cad_cms_menu` VALUES ('5', '1', '0', '0', '0', '2', '二级菜单', 'http://www.dolphinphp.com', '', '', '_self', '1492347372', '1492347510', '100', '1');
INSERT INTO `cad_cms_menu` VALUES ('6', '1', '5', '0', '0', '2', '子菜单', 'http://www.dolphinphp.com', '', '', '_self', '1492347388', '1492347520', '100', '1');

-- ----------------------------
-- Table structure for cad_cms_model
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_model`;
CREATE TABLE `cad_cms_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '模型名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '模型标题',
  `table` varchar(64) NOT NULL DEFAULT '' COMMENT '附加表名称',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '模型类别：0-系统模型，1-普通模型，2-独立模型',
  `icon` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统模型',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容模型表';

-- ----------------------------
-- Records of cad_cms_model
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_nav
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_nav`;
CREATE TABLE `cad_cms_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL DEFAULT '' COMMENT '导航标识',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='导航表';

-- ----------------------------
-- Records of cad_cms_nav
-- ----------------------------
INSERT INTO `cad_cms_nav` VALUES ('1', 'main_nav', '顶部导航', '1492345083', '1492345083', '1');
INSERT INTO `cad_cms_nav` VALUES ('2', 'about_nav', '底部关于', '1492346685', '1492346685', '1');
INSERT INTO `cad_cms_nav` VALUES ('3', 'support_nav', '服务与支持', '1492346715', '1492346715', '1');

-- ----------------------------
-- Table structure for cad_cms_page
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_page`;
CREATE TABLE `cad_cms_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '单页标题',
  `content` mediumtext NOT NULL COMMENT '单页内容',
  `keywords` varchar(32) NOT NULL DEFAULT '' COMMENT '关键词',
  `description` varchar(250) NOT NULL DEFAULT '' COMMENT '页面描述',
  `template` varchar(32) NOT NULL DEFAULT '' COMMENT '模板文件',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单页封面',
  `view` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页表';

-- ----------------------------
-- Records of cad_cms_page
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_slider
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_slider`;
CREATE TABLE `cad_cms_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '标题',
  `cover` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='滚动图片表';

-- ----------------------------
-- Records of cad_cms_slider
-- ----------------------------

-- ----------------------------
-- Table structure for cad_cms_support
-- ----------------------------
DROP TABLE IF EXISTS `cad_cms_support`;
CREATE TABLE `cad_cms_support` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '客服名称',
  `qq` varchar(16) NOT NULL DEFAULT '' COMMENT 'QQ',
  `msn` varchar(100) NOT NULL DEFAULT '' COMMENT 'msn',
  `taobao` varchar(100) NOT NULL DEFAULT '' COMMENT 'taobao',
  `alibaba` varchar(100) NOT NULL DEFAULT '' COMMENT 'alibaba',
  `skype` varchar(100) NOT NULL DEFAULT '' COMMENT 'skype',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服表';

-- ----------------------------
-- Records of cad_cms_support
-- ----------------------------

-- ----------------------------
-- Table structure for cad_p_storage
-- ----------------------------
DROP TABLE IF EXISTS `cad_p_storage`;
CREATE TABLE `cad_p_storage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_code` char(20) NOT NULL COMMENT '存储插件代码',
  `p_name` varchar(50) NOT NULL COMMENT '插件名称',
  `p_config` text NOT NULL COMMENT '插件规则json配置',
  `p_system` tinyint(1) DEFAULT '2' COMMENT '是否系统插件1=是(不可删除),2=否',
  `p_state` tinyint(1) DEFAULT '1' COMMENT '状态 1=开启,2=关闭',
  `p_ico` varchar(100) NOT NULL DEFAULT '' COMMENT '插件图标',
  `p_doc` varchar(200) NOT NULL DEFAULT '' COMMENT '插件描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`p_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='对象存储插件表';

-- ----------------------------
-- Records of cad_p_storage
-- ----------------------------
INSERT INTO `cad_p_storage` VALUES ('eed', '阿里云OSS', 'sdsd', '2', '1', '', '', '0', '0');

DROP TABLE IF EXISTS `cad_p_storage`;
CREATE TABLE `cad_p_storage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `mark` varchar(50) NOT NULL DEFAULT '' COMMENT '标识',
  `config` text NOT NULL COMMENT '插件规则json配置',
  `system` tinyint(1) DEFAULT '2' COMMENT '是否系统插件1=是(不可删除),2=否',
  `ico` varchar(100) NOT NULL DEFAULT '' COMMENT '插件图标',
  `doc` varchar(200) NOT NULL DEFAULT '' COMMENT '插件描述',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1=正常，2=信息缺失，3=信息不完整，4=未安装',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='对象存储插件表';

INSERT INTO `exdntadmin`.`dp_p_storage` (`p_name`, `p_code`, `p_config`, `p_system`, `p_state`, `p_ico`, `p_doc`, `status`, `create_time`, `update_time`) VALUES ('本地', 'local', 'local', '1', '1', 'local', '本地存储', '1', '0', '0');


DROP TABLE IF EXISTS `dp_b2b2c_goods_class`;
CREATE TABLE `dp_b2b2c_goods_class` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `commis_rate` float unsigned NOT NULL DEFAULT '0' COMMENT '佣金比例',
  `keywords` varchar(255) DEFAULT '' COMMENT '关键词',
  `dc` varchar(255) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表';

CREATE TABLE `dp_b2b2c_goods_class_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'TAGid',
  `gc_id_1` int(10) unsigned NOT NULL COMMENT '一级分类id',
  `gc_id_2` int(10) unsigned NOT NULL COMMENT '二级分类id',
  `gc_id_3` int(10) unsigned NOT NULL COMMENT '三级分类id',
  `gc_tag_name` varchar(255) NOT NULL COMMENT '分类TAG名称',
  `gc_tag_value` text NOT NULL COMMENT '分类TAG值',
  `gc_id` int(10) unsigned NOT NULL COMMENT '商品分类id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类TAG表';




INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('79', '0', '1', 'justyle', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04397468710494742_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('80', '0', '1', '享爱.', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04397468934349942_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('81', '0', '4', '派丽蒙', '', 'P', '1', '0', 'uploads/b2b2c/shop/brand/04397469152627878_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('82', '0', '4', '康妮雅', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04397471448679692_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('83', '0', '4', '秀秀美', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04397471716977022_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('84', '0', '1', '阿迪达斯', '', 'A', '1', '1', 'uploads/b2b2c/shop/brand/04397471910652190_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('85', '0', '6', '猫人', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04397472152849925_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('86', '0', '12', '茵曼（INMAN）', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04397472336312422_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('87', '0', '1', 'Hanes恒适', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04397472577467506_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('88', '0', '74', '缪诗', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04397472716852803_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('89', '0', '1', '真维斯', '', 'Z', '1', '1', 'uploads/b2b2c/shop/brand/04397472838086984_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('90', '0', '47', '金利来', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04397473042647991_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('91', '0', '109', '其乐', '', 'Q', '1', '1', 'uploads/b2b2c/shop/brand/04397473331842699_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('92', '0', '112', 'Newbalance', '', 'N', '1', '1', 'uploads/b2b2c/shop/brand/04397473633585549_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('93', '0', '8', '百丽', '', 'B', '1', '1', 'uploads/b2b2c/shop/brand/04398088925179484_sm.png', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('94', '0', '1', '七匹狼', '', 'Q', '1', '1', 'uploads/b2b2c/shop/brand/04398089136939537_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('95', '0', '7', '李宁', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398089270610035_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('96', '0', '1', '佐丹奴', '', 'Z', '1', '1', 'uploads/b2b2c/shop/brand/04398089412399747_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('97', '0', '93', '百思图', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398089574801901_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('98', '0', '5', '斯波帝卡', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398089726299223_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('99', '0', '5', '梦特娇', '', 'M', '1', '1', 'uploads/b2b2c/shop/brand/04398089942879365_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('100', '0', '1', '宝姿', '', 'B', '1', '1', 'uploads/b2b2c/shop/brand/04398090061006740_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('101', '0', '1', '爱帝', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04398090218578648_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('102', '0', '91', '她他/tata', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04398090459092275_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('103', '0', '1', 'ELLE HOME', '', 'E', '1', '1', 'uploads/b2b2c/shop/brand/04398090611386532_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('104', '0', '4', 'esprit', '', 'E', '1', '1', 'uploads/b2b2c/shop/brand/04398090828687339_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('105', '0', '1', 'westside', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04398090975832253_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('106', '0', '62', 'RDK', '', 'P', '1', '0', 'uploads/b2b2c/shop/brand/04398091763582415_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('107', '0', '43', '皮尔卡丹', '', 'P', '1', '0', 'uploads/b2b2c/shop/brand/04398091877500105_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('108', '0', '1', '挪巍', '', 'N', '1', '0', 'uploads/b2b2c/shop/brand/04398091973797599_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('113', '0', '470', '波斯顿', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398099293923325_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('114', '0', '470', '薇姿', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04398099463167230_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('115', '0', '470', '相宜本草', '', 'X', '1', '1', 'uploads/b2b2c/shop/brand/04398099611242673_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('116', '0', '470', 'Dior', '', 'D', '1', '1', 'uploads/b2b2c/shop/brand/04398099738566948_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('117', '0', '470', '苏菲', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398099870651075_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('118', '0', '470', 'faceshop', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04398100051941493_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('119', '0', '470', '芙丽芳丝', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04398100178308363_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('120', '0', '470', '娇爽', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04398100362129645_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('121', '0', '470', '卡尼尔', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04398100483927289_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('122', '0', '470', '纪梵希', '', 'J', '1', '1', 'uploads/b2b2c/shop/brand/04398100614445814_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('123', '0', '470', '护舒宝', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398100738554064_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('124', '0', '470', '兰蔻', '', 'L', '1', '1', 'uploads/b2b2c/shop/brand/04398100899214207_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('125', '0', '470', '娇兰', '', 'J', '1', '1', 'uploads/b2b2c/shop/brand/04398101035858820_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('126', '0', '470', '高丝洁', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04398101363358081_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('127', '0', '470', '妮维雅', '', 'N', '1', '1', 'uploads/b2b2c/shop/brand/04398101539246004_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('128', '0', '470', '高丝', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04398101708424765_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('129', '0', '470', '狮王', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398101929845854_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('130', '0', '470', '雅顿', '', 'Y', '1', '1', 'uploads/b2b2c/shop/brand/04398102086535787_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('131', '0', '470', 'M.A.C', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04398102231196519_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('132', '0', '470', '李施德林', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398102411008632_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('133', '0', '470', '雅诗兰黛', '', 'Y', '1', '1', 'uploads/b2b2c/shop/brand/04398102581821577_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('134', '0', '470', 'MISS FACE', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04398102756025036_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('135', '0', '470', '佳洁士', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04398102918746492_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('136', '0', '470', '资生堂', '', 'X', '1', '1', 'uploads/b2b2c/shop/brand/04398103163925153_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('137', '0', '470', '倩碧', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04398103335196758_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('138', '0', '470', 'benefit', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398103525876196_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('139', '0', '470', 'SISLEY', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398103731155516_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('140', '0', '470', '爱丽', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04398103883736888_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('141', '0', '470', 'BOBBI BROWN', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398104034802420_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('142', '0', '470', 'SK-ll', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398104206717960_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('143', '0', '530', '施华洛世奇', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398116735872287_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('144', '0', '530', '万宝龙', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04398116855649611_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('145', '0', '530', 'CK', '', 'C', '1', '1', 'uploads/b2b2c/shop/brand/04398116986166995_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('146', '0', '530', 'Disney', '', 'D', '1', '1', 'uploads/b2b2c/shop/brand/04398117134560677_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('147', '0', '530', '佐卡伊', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04398117259027285_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('148', '0', '0', 'ZIPPO', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04398117390207814_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('149', '0', '530', '梅花', '', 'M', '1', '1', 'uploads/b2b2c/shop/brand/04398117504203345_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('150', '0', '530', '高仕', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04398117735732690_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('151', '0', '530', '宝玑', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398117910949174_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('152', '0', '530', '一生一石', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04398118118206423_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('153', '0', '530', 'IDee', '', 'I', '1', '0', 'uploads/b2b2c/shop/brand/04398118344918440_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('154', '0', '530', 'elle', '', 'E', '1', '0', 'uploads/b2b2c/shop/brand/04398118494505137_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('155', '0', '530', '卡西欧', '', 'K', '1', '1', 'uploads/b2b2c/shop/brand/04398118617326698_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('156', '0', '530', '爱卡', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04398118792328978_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('157', '0', '530', '帝舵', '', 'D', '1', '1', 'uploads/b2b2c/shop/brand/04398118894311290_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('158', '0', '530', '新秀', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04398119032319322_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('159', '0', '530', '九钻', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04398119151718735_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('160', '0', '530', '卡地亚', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04398119311706852_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('161', '0', '530', '蓝色多瑙河', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398119501897486_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('162', '0', '530', '浪琴', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398119677440904_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('163', '0', '530', '百利恒', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398119859319840_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('164', '0', '530', '欧米茄', '', 'O', '1', '1', 'uploads/b2b2c/shop/brand/04398119996858692_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('165', '0', '530', 'tissot', '', 'T', '1', '1', 'uploads/b2b2c/shop/brand/04398120131178815_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('166', '0', '530', '新光饰品', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04398120247306694_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('167', '0', '530', '英雄', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04398120419590838_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('168', '0', '530', '瑞士军刀', '', 'R', '1', '0', 'uploads/b2b2c/shop/brand/04398120584040229_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('169', '0', '530', '斯沃琪', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398121090096799_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('170', '0', '530', '阿玛尼', '', 'A', '1', '1', 'uploads/b2b2c/shop/brand/04398121209932680_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('171', '0', '530', '亨得利', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398125089603514_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('172', '0', '530', 'lux-women', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398125296052150_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('173', '0', '530', 'ooh Dear', '', 'O', '1', '0', 'uploads/b2b2c/shop/brand/04398125473712411_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('174', '0', '256', 'acer', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04398155389308089_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('175', '0', '256', '清华同方', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04398155613517981_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('176', '0', '256', '富士通', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04398155751072786_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('177', '0', '256', '微软', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04398155862912765_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('178', '0', '256', '得力', '', 'D', '1', '0', 'uploads/b2b2c/shop/brand/04398156045665837_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('179', '0', '256', 'DELL', '', 'D', '1', '1', 'uploads/b2b2c/shop/brand/04398156232757027_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('180', '0', '256', 'ThinkPad', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04398156358858442_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('181', '0', '256', '联想打印机', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398156503421310_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('182', '0', '256', '金士顿', '', 'J', '1', '1', 'uploads/b2b2c/shop/brand/04398156705753579_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('183', '0', '256', 'TP-LINK', '', 'T', '1', '1', 'uploads/b2b2c/shop/brand/04398156873572761_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('184', '0', '256', '华硕', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398157012150899_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('185', '0', '256', '罗技', '', 'L', '1', '1', 'uploads/b2b2c/shop/brand/04398157235673753_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('186', '0', '256', 'D-Link', '', 'D', '1', '0', 'uploads/b2b2c/shop/brand/04398157356404105_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('187', '0', '256', '雷蛇', '', 'L', '1', '1', 'uploads/b2b2c/shop/brand/04398157472174891_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('188', '0', '256', 'IT-CEO', '', 'I', '1', '0', 'uploads/b2b2c/shop/brand/04398157595321784_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('189', '0', '256', 'hyundri', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398157712394024_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('190', '0', '256', '惠普', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398157881561725_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('191', '0', '256', '迈乐', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04398158065769057_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('192', '0', '256', '爱普生', '', 'A', '1', '1', 'uploads/b2b2c/shop/brand/04398158266047493_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('193', '0', '256', '三木', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398158379932048_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('194', '0', '256', '忆捷', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04398158508475720_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('195', '0', '256', '佰科', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398158666713881_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('196', '0', '256', '飞利浦', '', 'F', '1', '1', 'uploads/b2b2c/shop/brand/04398158808225051_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('197', '0', '256', '雷柏', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398158987559915_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('198', '0', '256', '双飞燕', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398159147857437_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('199', '0', '256', '网件', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04398159314915358_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('200', '0', '256', '山泽', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04398159479959395_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('201', '0', '256', '松下', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398159595550035_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('202', '0', '256', 'TPOS', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04398159795526441_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('203', '0', '256', '富勒', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04398159927301628_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('204', '0', '256', '北通', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04398160061664664_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('205', '0', '256', 'romoss', '', 'R', '1', '0', 'uploads/b2b2c/shop/brand/04398160187629402_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('206', '0', '256', '索爱', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398160348310562_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('207', '0', '256', '台电', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04398160575221477_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('208', '0', '256', '三星', '', 'S', '1', '1', 'uploads/b2b2c/shop/brand/04398160720944823_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('209', '0', '256', '理光', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04398160857676307_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('210', '0', '256', '飞毛腿', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04398161023292593_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('211', '0', '256', '阿尔卡特', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04398161143888870_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('212', '0', '256', '诺基亚', '', 'N', '1', '0', 'uploads/b2b2c/shop/brand/04398161259006857_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('213', '0', '256', '摩托罗拉', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04398161410885588_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('214', '0', '256', '苹果', '', 'P', '1', '1', 'uploads/b2b2c/shop/brand/04398168923750202_sm.png', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('215', '0', '256', 'HTC', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04398169850955399_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('216', '0', '308', '九阳', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04399844516657174_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('217', '0', '308', '索尼', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399833099806870_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('218', '0', '308', '格力', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04399833262328490_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('219', '0', '308', '夏普', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399833425234004_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('220', '0', '308', '美的', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399833601121412_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('221', '0', '308', '博朗', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399833768343488_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('222', '0', '308', 'TCL', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399833953558287_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('223', '0', '308', '欧姆龙', '', 'O', '1', '0', 'uploads/b2b2c/shop/brand/04399834117653152_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('224', '0', '308', '苏泊尔', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399834427362760_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('225', '0', '308', '伊莱克斯', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399834676870929_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('226', '0', '308', '艾力斯特', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399835435836906_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('227', '0', '308', '西门子', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399835594337307_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('228', '0', '308', '三菱电机', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399835807315767_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('229', '0', '308', '奔腾', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399836030618924_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('230', '0', '308', '三洋', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399836185660687_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('231', '0', '308', '大金', '', 'D', '1', '0', 'uploads/b2b2c/shop/brand/04399836403301996_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('232', '0', '308', '三星电器', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399836619819860_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('233', '0', '308', '海尔', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399837024444210_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('234', '0', '308', '格兰仕', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04399837873721609_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('235', '0', '308', '海信', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399838032416433_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('236', '0', '308', '博世', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399838243363042_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('237', '0', '308', '老板', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399838473427197_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('238', '0', '308', '奥克斯', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399838633002147_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('239', '0', '308', 'LG', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399838782976323_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('240', '0', '308', '创维', '', 'C', '1', '0', 'uploads/b2b2c/shop/brand/04399839110204841_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('241', '0', '308', '松下电器', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399839604098052_sm.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('242', '0', '1037', '中国联通', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04399847297781057_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('243', '0', '1037', '中国电信', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04399847472066981_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('244', '0', '1037', '中国移动', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04399847612667714_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('245', '0', '593', '一品玉', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399854316938195_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('246', '0', '593', '金奥力', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04399854503149255_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('247', '0', '593', '北大荒', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399854638913791_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('248', '0', '593', '健安喜', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04399854806939714_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('249', '0', '593', '屯河', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399854945115195_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('250', '0', '593', '养生堂', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399855140966866_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('251', '0', '593', '同庆和堂', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399855332734276_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('252', '0', '593', '黄飞红', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399855513686549_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('253', '0', '593', '乐力', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399855699218750_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('254', '0', '593', '汤臣倍健', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399855941379731_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('255', '0', '593', '康比特', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04399856135110739_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('256', '0', '593', '喜瑞', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399856323294870_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('257', '0', '593', '同仁堂', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399856454919811_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('258', '0', '593', '白兰氏', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399856638765013_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('259', '0', '593', 'Lumi', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399856804968818_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('260', '0', '593', '新西兰十一坊', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399856948519746_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('261', '0', '593', '自然之宝', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04399857092677752_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('262', '0', '593', '善存', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399857246559825_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('263', '0', '593', '长城葡萄酒', '', 'C', '1', '0', 'uploads/b2b2c/shop/brand/04399857399887704_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('264', '0', '593', '凯镛', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04399857579422195_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('267', '0', '959', '惠氏', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399878077210018_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('268', '0', '959', 'lala布书', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399878481448839_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('269', '0', '959', '美赞臣', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399878617014779_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('270', '0', '959', '好奇', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399878791943342_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('271', '0', '959', '多美', '', 'D', '1', '0', 'uploads/b2b2c/shop/brand/04399878980307860_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('272', '0', '959', '嘉宝', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04399879383821119_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('273', '0', '959', '孩之宝', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399879573077116_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('274', '0', '959', '嗳呵', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399879712252398_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('275', '0', '959', '美斯特伦', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399879861821747_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('276', '0', '959', '乐高', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399880083330972_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('277', '0', '959', '芭比', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399880244694286_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('278', '0', '959', 'NUK', '', 'N', '1', '0', 'uploads/b2b2c/shop/brand/04399880420786755_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('279', '0', '959', '魔法玉米', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399880604749242_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('280', '0', '959', '宝贝第一', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399880757446523_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('281', '0', '959', '强生', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04399880892528550_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('282', '0', '959', '澳优', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399881087936122_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('283', '0', '959', '木马智慧', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399881246572965_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('284', '0', '959', '百立乐', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399881709264364_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('285', '0', '959', '雀巢', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04399881950170970_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('286', '0', '959', '帮宝适', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399882134949479_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('287', '0', '959', '万代', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04399882291234767_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('288', '0', '959', '亲贝', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04399882442124015_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('289', '0', '959', '十月天使', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399882581513663_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('290', '0', '959', '多美滋', '', 'D', '1', '0', 'uploads/b2b2c/shop/brand/04399882826616164_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('291', '0', '959', '星辉', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399882966084988_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('292', '0', '959', '布朗博士', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399883157641690_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('293', '0', '959', '新安怡', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399883297614786_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('294', '0', '959', '费雪', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04399883534332035_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('295', '0', '959', 'Hipp', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399883690219411_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('296', '0', '959', '新大王', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399883855598553_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('297', '0', '959', '雅培', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399884035362889_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('298', '0', '959', '亨氏', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399884182772511_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('299', '0', '959', '十月妈咪', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399884360526483_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('300', '0', '959', '好孩子', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399884512865285_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('301', '0', '959', '婴姿坊', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399884644632532_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('302', '0', '959', '妈咪宝贝', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399884799920935_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('303', '0', '662', '直觉', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04399889262024650_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('304', '0', '662', '世达球', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399889410183423_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('305', '0', '662', '悠度', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399889744222357_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('306', '0', '662', '威尔胜', '', 'W', '1', '0', 'uploads/b2b2c/shop/brand/04399889941968796_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('307', '0', '662', '远洋瑜伽', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399890266352034_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('308', '0', '662', '信乐', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399890429362085_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('309', '0', '662', '诺可文', '', 'N', '1', '0', 'uploads/b2b2c/shop/brand/04399890643925803_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('310', '0', '662', '艾威', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399890796771131_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('311', '0', '662', 'LELO', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399890952734102_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('312', '0', '662', '乔山', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04399891122713199_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('313', '0', '662', '皮克朋', '', 'P', '1', '0', 'uploads/b2b2c/shop/brand/04399891285897466_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('314', '0', '662', '捷安特', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04399891438458842_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('315', '0', '662', '开普特', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04399891598799644_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('316', '0', '662', '火枫', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399891771381530_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('317', '0', '662', 'INDEED', '', 'I', '1', '0', 'uploads/b2b2c/shop/brand/04399891911058029_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('318', '0', '662', '欧亚马', '', 'O', '1', '0', 'uploads/b2b2c/shop/brand/04399892067310657_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('319', '0', '662', '李斯特', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399892199751417_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('320', '0', '662', '乐美福', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399892359082323_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('321', '0', '662', '以比赞', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399892526357198_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('322', '0', '662', '皮尔瑜伽', '', 'P', '1', '0', 'uploads/b2b2c/shop/brand/04399893307910546_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('323', '0', '662', '以诗萜', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399893452531024_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('324', '0', '662', '斯伯丁', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399893596931049_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('326', '0', '0', '玛克', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399902137097199_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('327', '0', '0', '美好家', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04399902244747580_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('328', '0', '0', '溢彩年华', '', 'Y', '1', '0', 'uploads/b2b2c/shop/brand/04399902391635130_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('329', '0', '0', '欧司朗', '', 'O', '1', '0', 'uploads/b2b2c/shop/brand/04399902537418591_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('330', '0', '0', '世家洁具', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399902668760247_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('331', '0', '0', '天堂伞', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04399902780394855_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('332', '0', '0', '慧乐家', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399902896835151_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('333', '0', '0', '希格', '', 'X', '1', '0', 'uploads/b2b2c/shop/brand/04399903024936544_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('334', '0', '0', '生活诚品', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399903153847612_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('335', '0', '0', '爱仕达', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399903259361371_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('336', '0', '0', '罗莱', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399903404912119_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('337', '0', '0', '索客', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399903541756673_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('338', '0', '0', '好事达', '', 'H', '1', '0', 'uploads/b2b2c/shop/brand/04399903715622158_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('339', '0', '0', '安睡宝', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04399903832203331_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('340', '0', '0', '博洋家纺', '', 'B', '1', '0', 'uploads/b2b2c/shop/brand/04399903956723469_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('341', '0', '0', '空间大师', '', 'K', '1', '0', 'uploads/b2b2c/shop/brand/04399904058344749_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('342', '0', '0', '富安娜', '', 'F', '1', '0', 'uploads/b2b2c/shop/brand/04399904168163421_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('343', '0', '0', '三光云彩', '', 'S', '1', '0', 'uploads/b2b2c/shop/brand/04399904279499345_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('344', '0', '0', '乔曼帝', '', 'Q', '1', '0', 'uploads/b2b2c/shop/brand/04399904423386126_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('345', '0', '0', '乐扣乐扣', '', 'L', '1', '0', 'uploads/b2b2c/shop/brand/04399904614221217_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('348', '0', '58', '奥唯嘉（Ovega）', '', 'A', '1', '0', 'uploads/b2b2c/shop/brand/04431812331259168_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('351', '0', '6', '曼妮芬（ManniForm）', '', 'M', '1', '0', 'uploads/b2b2c/shop/brand/04431810033957836_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('352', '0', '6', '婷美（TINGMEI）', '', 'T', '1', '0', 'uploads/b2b2c/shop/brand/04431809546541815_sm.png', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('353', '0', '6', '古今', '', 'G', '1', '0', 'uploads/b2b2c/shop/brand/04431807497959652_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('358', '4', '691', '金史密斯（KINGSMITH）', '', 'J', '1', '0', 'uploads/b2b2c/shop/brand/04420592440315393_small.gif', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('359', '0', '532', '周大福', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04420650490304114_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');
INSERT INTO `exdntadmin`.`dp_b2b2c_brand` (`brand_id`, `store_id`, `gc_id`, `brand_name`, `brand_tjstore`, `brand_initial`, `brand_apply`, `brand_recommend`, `brand_pic`, `brand_bgpic`, `brand_xbgpic`, `brand_sort`, `brand_introduction`, `brand_view`) VALUES ('360', '0', '532', '周生生', '', 'Z', '1', '0', 'uploads/b2b2c/shop/brand/04420650201635924_sm.jpg', 'brand_default_max.jpg', 'brand_default_small.jpg', '0', '', '0');


DROP TABLE IF EXISTS `dp_b2b2c_goods`;
CREATE TABLE `dp_b2b2c_goods` (
    `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id(SKU)',
    `goods_commonid` int(10) unsigned NOT NULL COMMENT '商品公共表id',
    `store_id` int(10) unsigned NOT NULL COMMENT '店铺id',
    `gc_id` int(10) unsigned NOT NULL COMMENT '商品分类id',
    `brand_id` int(10) unsigned DEFAULT '0' COMMENT '品牌id',
    `color_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '颜色规格id',
    `transport_id` mediumint(8) unsigned NOT NULL COMMENT '运费模板id',
    `gc_id_1` int(10) unsigned NOT NULL COMMENT '一级分类id',
    `gc_id_2` int(10) unsigned NOT NULL COMMENT '二级分类id',
    `gc_id_3` int(10) unsigned NOT NULL COMMENT '三级分类id',
    `areaid_1` int(10) unsigned NOT NULL COMMENT '一级地区id',
    `areaid_2` int(10) unsigned NOT NULL COMMENT '二级地区id',
    `areaid_3` int(10) unsigned NOT NULL COMMENT '三级地区id',
    `goods_name` varchar(50) NOT NULL COMMENT '商品名称（+规格名称）',
    `goods_image` varchar(100) NOT NULL DEFAULT '' COMMENT '商品主图',
    `goods_body` text NOT NULL COMMENT '商品描述',
    `mobile_body` text NOT NULL COMMENT '手机端商品描述',
    `goods_jingle` varchar(150) DEFAULT '' COMMENT '商品广告词',
    `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
    `goods_marketprice` decimal(10,2) NOT NULL COMMENT '市场价',
    `goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
    `goods_promotion_price` decimal(10,2) NOT NULL COMMENT '商品促销价格',
    `goods_promotion_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '促销类型 0无促销，1抢购，2限时折扣',
    `goods_serial` varchar(50) DEFAULT '' COMMENT '商品货号',
    `goods_storage_alarm` tinyint(3) unsigned NOT NULL COMMENT '库存报警值',
    `goods_barcode` varchar(20) DEFAULT '' COMMENT '商品条形码',
    `goods_click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品点击数量',
    `goods_salenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售数量',
    `goods_collect` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数量',
    `spec_name` varchar(255) NOT NULL COMMENT '规格名称',
    `goods_spec` text NOT NULL COMMENT '商品规格序列化',
    `goods_storage` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
    `goods_state` tinyint(3) unsigned NOT NULL COMMENT '商品状态 0下架，1正常，10违规（禁售）',
    `goods_verify` tinyint(3) unsigned NOT NULL COMMENT '商品审核 1通过，0未通过，10审核中',
    `goods_freight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费 0为免运费',
    `goods_vat` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开具增值税发票 1是，0否',
    `goods_commend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品推荐 1是，0否 默认0',
    `goods_stcids` varchar(255) DEFAULT '' COMMENT '店铺分类id 首尾用,隔开',
    `evaluation_good_star` tinyint(3) unsigned NOT NULL DEFAULT '5' COMMENT '好评星级',
    `evaluation_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价数',
    `is_virtual` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为虚拟商品 1是，0否',
    `virtual_indate` int(10) unsigned NOT NULL COMMENT '虚拟商品有效期',
    `virtual_limit` tinyint(3) unsigned NOT NULL COMMENT '虚拟商品购买上限',
    `virtual_invalid_refund` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许过期退款， 1是，0否',
    `is_fcode` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为F码商品 1是，0否',
    `is_presell` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否是预售商品 1是，0否',
    `presell_deliverdate` int(11) NOT NULL DEFAULT '0' COMMENT '预售商品发货时间',
    `is_book` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为预定商品，1是，0否',
    `book_down_payment` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '定金金额',
    `book_final_payment` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '尾款金额',
    `book_down_time` int(11) NOT NULL DEFAULT '0' COMMENT '预定结束时间',
    `book_buyers` mediumint(9) DEFAULT '0' COMMENT '预定人数',
    `have_gift` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否拥有赠品',
    `is_own_shop` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为平台自营',
    `contract_1` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_2` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_3` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_4` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_5` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_6` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_7` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_8` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_9` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `contract_10` tinyint(1) NOT NULL DEFAULT '0' COMMENT '消费者保障服务状态 0关闭 1开启',
    `is_chain` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为门店商品 1是，0否',
    `goods_trans_v` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '重量或体积',
    `invite_rate` decimal(10,2) DEFAULT '0.00' COMMENT '分销佣金',
    `goods_addtime` int(10) unsigned NOT NULL COMMENT '商品添加时间',
    `goods_edittime` int(10) unsigned NOT NULL COMMENT '商品编辑时间',
    PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

DROP TABLE IF EXISTS `dp_b2b2c_msg_tpl`;
CREATE TABLE `dp_b2b2c_msg_tpl` (
    `code` varchar(50) NOT NULL COMMENT '模板编号',
    `name` varchar(50) NOT NULL COMMENT '模板名称',
    `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '模板类型 1=用户,2=商家,100=其他',
    `message_switch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '站内信接收开关，0否，1是',
    `message_forced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '站内信强制接收，0否，1是',
    `message_content` varchar(255) NOT NULL COMMENT '站内信消息内容',
    `short_switch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '短信接收开关，0否，1是',
    `short_forced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '短信强制接收，0否，1是',
    `short_content` varchar(255) NOT NULL COMMENT '短信接收内容',
    `mail_switch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '邮件接收开关 ，0否，1是',
    `mail_forced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '邮件强制接收，0否，1是',
    `mail_title` varchar(255) NOT NULL COMMENT '邮件标题',
    `mail_content` text NOT NULL COMMENT '邮件内容',
    `wxxcx_switch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '微信小程序接收开关 ，0否，1是',
    `wxxcx_forced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '微信小程序强制接收，0否，1是',
    `wxxcx_title` varchar(255) NOT NULL COMMENT '微信小程序标题',
    `wxxcx_content` text NOT NULL COMMENT '微信小程序内容',
    `wx_switch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '微信公众号接收开关 ，0否，1是',
    `wx_forced` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '微信公众号强制接收，0否，1是',
    `wx_title` varchar(255) NOT NULL COMMENT '微信公众号标题',
    `wx_content` text NOT NULL COMMENT '微信公众号内容',
    PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息模板';

CREATE TABLE `33hao_store_msg_tpl` (
  `smt_code` varchar(100) NOT NULL COMMENT '模板编码',
  `smt_name` varchar(100) NOT NULL COMMENT '模板名称',
  `smt_message_switch` tinyint(3) unsigned NOT NULL COMMENT '站内信默认开关，0关，1开',
  `smt_message_content` varchar(255) NOT NULL COMMENT '站内信内容',
  `smt_message_forced` tinyint(3) unsigned NOT NULL COMMENT '站内信强制接收，0否，1是',
  `smt_short_switch` tinyint(3) unsigned NOT NULL COMMENT '短信默认开关，0关，1开',
  `smt_short_content` varchar(255) NOT NULL COMMENT '短信内容',
  `smt_short_forced` tinyint(3) unsigned NOT NULL COMMENT '短信强制接收，0否，1是',
  `smt_mail_switch` tinyint(3) unsigned NOT NULL COMMENT '邮件默认开，0关，1开',
  `smt_mail_subject` varchar(255) NOT NULL COMMENT '邮件标题',
  `smt_mail_content` text NOT NULL COMMENT '邮件内容',
  `smt_mail_forced` tinyint(3) unsigned NOT NULL COMMENT '邮件强制接收，0否，1是',
  PRIMARY KEY (`smt_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家消息模板';

DROP TABLE IF EXISTS `dp_sms`;
CREATE TABLE `dp_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信验证码表';

DROP TABLE IF EXISTS `dp_p_sms`;
CREATE TABLE `dp_p_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `mark` varchar(50) NOT NULL DEFAULT '' COMMENT '标识',
  `config` text NOT NULL COMMENT '插件规则json配置',
  `system` tinyint(1) DEFAULT '2' COMMENT '是否系统插件1=是(不可删除),2=否',
  `ico` varchar(100) NOT NULL DEFAULT '' COMMENT '插件图标',
  `doc` varchar(200) NOT NULL DEFAULT '' COMMENT '插件描述',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1=正常，2=信息缺失，3=信息不完整，4=未安装',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信插件表';

CREATE TABLE `dp_p_sms_tpl` (
  `code` varchar(50) NOT NULL COMMENT '模板编号',
  `name` varchar(50) NOT NULL COMMENT '模板名称',
  `message_content` varchar(255) NOT NULL COMMENT '站内信消息内容',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信消息模板';

CREATE TABLE `dp_admin_lang_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL COMMENT '标识码',
  `title` varchar(50) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语言类型表';

CREATE TABLE `dp_admin_lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '语言类型id',
  `code` varchar(150) NOT NULL COMMENT '标识码',
  `title` varchar(50) NOT NULL COMMENT '名称',
  `values` varchar(500) NOT NULL COMMENT '值',
  `channel` char(10) NOT NULL COMMENT '渠道 admin=后台,home=前台,api=接口',
  `module` varchar(16) NOT NULL DEFAULT '' COMMENT '所属模块，空=系统',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语言表';


CREATE TABLE `dp_store_grade` (
  `sg_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `sg_name` char(50) DEFAULT NULL COMMENT '等级名称',
  `sg_goods_limit` mediumint(10) unsigned NOT NULL DEFAULT '0' COMMENT '允许发布的商品数量',
  `sg_album_limit` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '允许上传图片数量',
  `sg_space_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传空间大小，单位MB',
  `sg_template_number` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '选择店铺模板套数',
  `sg_template` varchar(255) DEFAULT NULL COMMENT '模板内容',
  `sg_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '开店费用(元/年)',
  `sg_description` text COMMENT '申请说明',
  `sg_function` varchar(255) DEFAULT NULL COMMENT '附加功能',
  `sg_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '级别，数目越大级别越高',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`sg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺等级表';


CREATE TABLE `dp_b2b2c_p_bundling` (
  `bl_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '组合ID',
  `bl_name` varchar(50) NOT NULL COMMENT '组合名称',
  `store_id` int(11) NOT NULL COMMENT '店铺名称',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `bl_discount_price` decimal(10,2) NOT NULL COMMENT '组合价格',
  `bl_freight_choose` tinyint(1) NOT NULL COMMENT '运费承担方式',
  `bl_freight` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `bl_state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '组合状态 0-关闭/1-开启',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`bl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组合销售活动表';

CREATE TABLE `dp_b2b2c_p_bundling_goods` (
  `bl_goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '组合商品id',
  `bl_id` int(11) NOT NULL COMMENT '组合id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `goods_name` varchar(50) NOT NULL COMMENT '商品名称',
  `goods_image` varchar(100) NOT NULL COMMENT '商品图片',
  `bl_goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `bl_appoint` tinyint(3) unsigned NOT NULL COMMENT '指定商品 1是，0否',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`bl_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组合销售活动商品表';

CREATE TABLE `dp_b2b2c_p_bundling_quota` (
  `bl_quota_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '套餐ID',
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `member_name` varchar(50) NOT NULL COMMENT '会员名称',
  `bl_quota_month` tinyint(3) unsigned NOT NULL COMMENT '购买数量（单位月）',
  `bl_quota_starttime` varchar(10) NOT NULL COMMENT '套餐开始时间',
  `bl_quota_endtime` varchar(10) NOT NULL COMMENT '套餐结束时间',
  `bl_state` tinyint(1) unsigned NOT NULL COMMENT '套餐状态：0关闭，1开启。默认为 1',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`bl_quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组合销售套餐表';


CREATE TABLE `dp_b2b2c_p_booth_goods` (
  `booth_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '套餐商品id',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `gc_id` int(10) unsigned NOT NULL COMMENT '商品分类id',
  `booth_state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '套餐状态 1开启 0关闭 默认1',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`booth_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展位商品表';

CREATE TABLE `dp_b2b2c_p_booth_quota` (
  `booth_quota_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '套餐id',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `booth_quota_starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `booth_quota_endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `booth_state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '套餐状态 1开启 0关闭 默认1',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`booth_quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='展位套餐表';


CREATE TABLE `dp_b2b2c_p_xianshi` (
  `xianshi_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时编号',
  `xianshi_name` varchar(50) NOT NULL COMMENT '活动名称',
  `xianshi_title` varchar(10) DEFAULT NULL COMMENT '活动标题',
  `xianshi_explain` varchar(50) DEFAULT NULL COMMENT '活动说明',
  `quota_id` int(10) unsigned NOT NULL COMMENT '套餐编号',
  `start_time` int(10) unsigned NOT NULL COMMENT '活动开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '活动结束时间',
  `member_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `member_name` varchar(50) NOT NULL COMMENT '用户名',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `lower_limit` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '购买下限，1为不限制',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态，0-取消 1-正常',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`xianshi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣活动表';

CREATE TABLE `dp_b2b2c_p_xianshi_goods` (
  `xianshi_goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时折扣商品编号',
  `xianshi_id` int(10) unsigned NOT NULL COMMENT '限时活动编号',
  `xianshi_name` varchar(50) NOT NULL COMMENT '活动名称',
  `xianshi_title` varchar(10) DEFAULT NULL COMMENT '活动标题',
  `xianshi_explain` varchar(50) DEFAULT NULL COMMENT '活动说明',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品编号',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '店铺价格',
  `xianshi_price` decimal(10,2) NOT NULL COMMENT '限时折扣价格',
  `goods_image` varchar(100) NOT NULL COMMENT '商品图片',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `lower_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买下限，0为不限制',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态，0-取消 1-正常',
  `xianshi_recommend` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐标志 0-未推荐 1-已推荐',
  `gc_id_1` mediumint(9) DEFAULT '0' COMMENT '商品分类一级ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`xianshi_goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣商品表';

CREATE TABLE `dp_b2b2c_p_xianshi_quota` (
  `quota_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '限时折扣套餐编号',
  `member_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `member_name` varchar(50) NOT NULL COMMENT '用户名',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `start_time` int(10) unsigned NOT NULL COMMENT '套餐开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '套餐结束时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='限时折扣套餐表';


CREATE TABLE `dp_b2b2c_groupbuy` (
  `groupbuy_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '抢购ID',
  `groupbuy_name` varchar(255) NOT NULL COMMENT '活动名称',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_commonid` int(10) unsigned NOT NULL COMMENT '商品公共表ID',
  `goods_name` varchar(200) NOT NULL COMMENT '商品名称',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '商品原价',
  `groupbuy_price` decimal(10,2) NOT NULL COMMENT '抢购价格',
  `groupbuy_rebate` decimal(10,2) NOT NULL COMMENT '折扣',
  `virtual_quantity` int(10) unsigned NOT NULL COMMENT '虚拟购买数量',
  `upper_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买上限',
  `buyer_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已购买人数',
  `buy_quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `groupbuy_intro` text COMMENT '本团介绍',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抢购状态 10-审核中 20-正常 30-审核失败 31-管理员关闭 32-已结束',
  `recommended` tinyint(1) unsigned NOT NULL COMMENT '是否推荐 0.未推荐 1.已推荐',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `class_id` int(10) unsigned NOT NULL COMMENT '抢购类别编号',
  `s_class_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抢购2级分类id',
  `groupbuy_image` varchar(100) NOT NULL COMMENT '抢购图片',
  `groupbuy_image1` varchar(100) DEFAULT NULL COMMENT '抢购图片1',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `is_vr` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟抢购 1是0否',
  `vr_city_id` int(11) DEFAULT NULL COMMENT '虚拟抢购城市id',
  `vr_area_id` int(11) DEFAULT NULL COMMENT '虚拟抢购区域id',
  `vr_mall_id` int(11) DEFAULT NULL COMMENT '虚拟抢购商区id',
  `vr_class_id` int(11) DEFAULT NULL COMMENT '虚拟抢购大分类id',
  `vr_s_class_id` int(11) DEFAULT NULL COMMENT '虚拟抢购小分类id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`groupbuy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抢购商品表';


CREATE TABLE `dp_b2b2c_groupbuy_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '类别编号',
  `class_name` varchar(20) NOT NULL COMMENT '类别名称',
  `class_parent_id` int(10) unsigned NOT NULL COMMENT '父类别编号',
  `sort` tinyint(1) unsigned NOT NULL COMMENT '排序',
  `deep` tinyint(1) unsigned DEFAULT '0' COMMENT '深度',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抢购类别表';


CREATE TABLE `dp_b2b2c_groupbuy_price_range` (
  `range_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '价格区间编号',
  `range_name` varchar(20) NOT NULL COMMENT '区间名称',
  `range_start` int(10) unsigned NOT NULL COMMENT '区间下限',
  `range_end` int(10) unsigned NOT NULL COMMENT '区间上限',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`range_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抢购价格区间表';


CREATE TABLE `dp_b2b2c_groupbuy_quota` (
  `quota_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '抢购套餐编号',
  `member_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺编号',
  `member_name` varchar(50) NOT NULL COMMENT '用户名',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `start_time` int(10) unsigned NOT NULL COMMENT '套餐开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '套餐结束时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`quota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抢购套餐表';


CREATE TABLE `dp_b2b2c_vr_groupbuy_area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '区域id',
  `area_name` varchar(100) NOT NULL COMMENT '域区名称',
  `parent_area_id` int(11) NOT NULL COMMENT '域区id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `first_letter` char(1) NOT NULL COMMENT '首字母',
  `area_number` varchar(10) DEFAULT NULL COMMENT '区号',
  `post` varchar(10) DEFAULT NULL COMMENT '邮编',
  `hot_city` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0.否 1.是',
  `area_num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='虚拟抢购区域表';


CREATE TABLE `dp_b2b2c_vr_groupbuy_class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `class_name` varchar(100) NOT NULL COMMENT '分类名称',
  `parent_class_id` int(11) NOT NULL COMMENT '父类class_id',
  `class_sort` tinyint(3) unsigned DEFAULT NULL COMMENT '分类排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='虚拟抢购分类表';


CREATE TABLE `dp_b2b2c_goods_browse` (
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `browsetime` int(11) NOT NULL COMMENT '浏览时间',
  `gc_id` int(11) NOT NULL COMMENT '商品分类',
  `gc_id_1` int(11) NOT NULL COMMENT '商品一级分类',
  `gc_id_2` int(11) NOT NULL COMMENT '商品二级分类',
  `gc_id_3` int(11) NOT NULL COMMENT '商品三级分类'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品浏览历史表';



CREATE TABLE `dp_b2b2c_favorites` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `member_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `member_name` varchar(50) NOT NULL COMMENT '会员名',
  `fav_id` int(10) unsigned NOT NULL COMMENT '商品或店铺ID',
  `fav_type` char(5) NOT NULL DEFAULT 'goods' COMMENT '类型:goods为商品,store为店铺,默认为商品',
  `fav_time` int(10) unsigned NOT NULL COMMENT '收藏时间',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺ID',
  `store_name` varchar(20) NOT NULL COMMENT '店铺名称',
  `sc_id` int(10) unsigned DEFAULT '0' COMMENT '店铺分类ID',
  `goods_name` varchar(50) DEFAULT NULL COMMENT '商品名称',
  `goods_image` varchar(100) DEFAULT NULL COMMENT '商品图片',
  `gc_id` int(10) unsigned DEFAULT '0' COMMENT '商品分类ID',
  `log_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品收藏时价格',
  `log_msg` varchar(20) DEFAULT NULL COMMENT '收藏备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`log_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表';

CREATE TABLE `dp_b2b2c_goods_gift` (
  `gift_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '赠品id ',
  `goods_id` int(10) unsigned NOT NULL COMMENT '主商品id',
  `goods_commonid` int(10) unsigned NOT NULL COMMENT '主商品公共id',
  `gift_goodsid` int(10) unsigned NOT NULL COMMENT '赠品商品id ',
  `gift_goodsname` varchar(50) NOT NULL COMMENT '主商品名称',
  `gift_goodsimage` varchar(100) NOT NULL COMMENT '主商品图片',
  `gift_amount` tinyint(3) unsigned NOT NULL COMMENT '赠品数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品赠品表';

CREATE TABLE `dp_b2b2c_p_combo_quota` (
  `cq_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '推荐组合套餐id',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `cq_starttime` int(10) unsigned NOT NULL COMMENT '套餐开始时间',
  `cq_endtime` int(10) unsigned NOT NULL COMMENT '套餐结束时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`cq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐组合套餐表';

CREATE TABLE `dp_b2b2c_p_combo_goods` (
  `cg_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '推荐组合id ',
  `cg_class` varchar(10) NOT NULL COMMENT '推荐组合名称',
  `goods_id` int(10) unsigned NOT NULL COMMENT '主商品id',
  `goods_commonid` int(10) unsigned NOT NULL COMMENT '主商品公共id',
  `store_id` int(10) unsigned NOT NULL COMMENT '所属店铺id',
  `combo_goodsid` int(10) unsigned NOT NULL COMMENT '推荐组合商品id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`cg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品推荐组合表';

CREATE TABLE `dp_b2b2c_p_goods_fcode` (
  `fc_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'F码id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品sku',
  `fc_code` varchar(20) NOT NULL COMMENT 'F码',
  `fc_state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0未使用，1已使用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`fc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品F码';

CREATE TABLE `dp_b2b2c_p_fcode_quota` (
  `fcq_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'F码套餐id',
  `store_id` int(11) NOT NULL COMMENT '店铺id',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `fcq_starttime` int(11) NOT NULL COMMENT '套餐开始时间',
  `fcq_endtime` int(11) NOT NULL COMMENT '套餐结束时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`fcq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='F码商品套餐表';

CREATE TABLE `dp_b2b2c_chain` (
  `chain_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '门店id',
  `store_id` int(10) unsigned NOT NULL COMMENT '所属店铺id',
  `chain_user` varchar(50) NOT NULL COMMENT '登录名',
  `chain_pwd` char(32) NOT NULL COMMENT '登录密码',
  `chain_name` varchar(50) NOT NULL COMMENT '门店名称',
  `chain_img` varchar(50) NOT NULL COMMENT '门店图片',
  `area_id_1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级地区id',
  `area_id_2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级地区id',
  `area_id_3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三级地区id',
  `area_id_4` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '四级地区id',
  `area_id` int(10) unsigned NOT NULL COMMENT '地区id',
  `area_info` varchar(50) NOT NULL COMMENT '地区详情',
  `chain_address` varchar(50) NOT NULL COMMENT '详细地址',
  `chain_phone` varchar(100) NOT NULL COMMENT '联系方式',
  `chain_opening_hours` varchar(100) NOT NULL COMMENT '营业时间',
  `chain_traffic_line` varchar(100) NOT NULL COMMENT '交通线路',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`chain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺门店表';

CREATE TABLE `dp_b2b2c_chain_stock` (
  `chain_id` int(10) unsigned NOT NULL COMMENT '门店id',
  `goods_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `goods_commonid` int(10) unsigned NOT NULL COMMENT '商品SPU',
  `stock` int(10) NOT NULL COMMENT '库存',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`chain_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店商品库存表';

CREATE TABLE `dp_b2b2c_goods_images` (
  `goods_image_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品图片id',
  `goods_commonid` int(10) unsigned NOT NULL COMMENT '商品公共内容id',
  `store_id` int(10) unsigned NOT NULL COMMENT '店铺id',
  `color_id` int(10) unsigned NOT NULL COMMENT '颜色规格值id',
  `goods_image` varchar(1000) NOT NULL COMMENT '商品图片',
  `goods_image_sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '默认主题，1是，0否',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`goods_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品图片';


CREATE TABLE `dp_b2b2c_evaluate_store` (
  `seval_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评价ID',
  `seval_orderid` int(11) unsigned NOT NULL COMMENT '订单ID',
  `seval_orderno` varchar(100) NOT NULL COMMENT '订单编号',
  `seval_storeid` int(11) unsigned NOT NULL COMMENT '店铺ID',
  `seval_storename` varchar(100) NOT NULL COMMENT '店铺名称',
  `seval_memberid` int(11) unsigned NOT NULL COMMENT '买家ID',
  `seval_membername` varchar(100) NOT NULL COMMENT '买家名称',
  `seval_desccredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '描述相符评分',
  `seval_servicecredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '服务态度评分',
  `seval_deliverycredit` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '发货速度评分',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`seval_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺评分表';

CREATE TABLE `dp_b2b2c_store_joinin` (
  `member_id` int(10) unsigned NOT NULL COMMENT '用户编号',
  `member_name` varchar(50) DEFAULT NULL COMMENT '店主用户名',
  `company_name` varchar(50) DEFAULT NULL COMMENT '公司名称',
  `company_province_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所在地省ID',
  `company_address` varchar(50) DEFAULT NULL COMMENT '公司地址',
  `company_address_detail` varchar(50) DEFAULT NULL COMMENT '公司详细地址',
  `company_phone` varchar(20) DEFAULT NULL COMMENT '公司电话',
  `company_employee_count` int(10) unsigned DEFAULT NULL COMMENT '员工总数',
  `company_registered_capital` int(10) unsigned DEFAULT NULL COMMENT '注册资金',
  `contacts_name` varchar(50) DEFAULT NULL COMMENT '联系人姓名',
  `contacts_phone` varchar(20) DEFAULT NULL COMMENT '联系人电话',
  `contacts_email` varchar(50) DEFAULT NULL COMMENT '联系人邮箱',
  `business_licence_number` varchar(50) DEFAULT NULL COMMENT '营业执照号',
  `business_licence_address` varchar(50) DEFAULT NULL COMMENT '营业执所在地',
  `business_licence_start` date DEFAULT NULL COMMENT '营业执照有效期开始',
  `business_licence_end` date DEFAULT NULL COMMENT '营业执照有效期结束',
  `business_sphere` varchar(1000) DEFAULT NULL COMMENT '法定经营范围',
  `business_licence_number_elc` varchar(50) DEFAULT NULL COMMENT '营业执照电子版',
  `organization_code` varchar(50) DEFAULT NULL COMMENT '组织机构代码',
  `organization_code_electronic` varchar(50) DEFAULT NULL COMMENT '组织机构代码电子版',
  `general_taxpayer` varchar(50) DEFAULT NULL COMMENT '一般纳税人证明',
  `bank_account_name` varchar(50) DEFAULT NULL COMMENT '银行开户名',
  `bank_account_number` varchar(50) DEFAULT NULL COMMENT '公司银行账号',
  `bank_name` varchar(50) DEFAULT NULL COMMENT '开户银行支行名称',
  `bank_code` varchar(50) DEFAULT NULL COMMENT '支行联行号',
  `bank_address` varchar(50) DEFAULT NULL COMMENT '开户银行所在地',
  `bank_licence_electronic` varchar(50) DEFAULT NULL COMMENT '开户银行许可证电子版',
  `is_settlement_account` tinyint(1) DEFAULT NULL COMMENT '开户行账号是否为结算账号 1-开户行就是结算账号 2-独立的计算账号',
  `settlement_bank_account_name` varchar(50) DEFAULT NULL COMMENT '结算银行开户名',
  `settlement_bank_account_number` varchar(50) DEFAULT NULL COMMENT '结算公司银行账号',
  `settlement_bank_name` varchar(50) DEFAULT NULL COMMENT '结算开户银行支行名称',
  `settlement_bank_code` varchar(50) DEFAULT NULL COMMENT '结算支行联行号',
  `settlement_bank_address` varchar(50) DEFAULT NULL COMMENT '结算开户银行所在地',
  `tax_registration_certificate` varchar(50) DEFAULT NULL COMMENT '税务登记证号',
  `taxpayer_id` varchar(50) DEFAULT NULL COMMENT '纳税人识别号',
  `tax_registration_certif_elc` varchar(50) DEFAULT NULL COMMENT '税务登记证号电子版',
  `seller_name` varchar(50) DEFAULT NULL COMMENT '卖家账号',
  `store_name` varchar(50) DEFAULT NULL COMMENT '店铺名称',
  `store_class_ids` varchar(1000) DEFAULT NULL COMMENT '店铺分类编号集合',
  `store_class_names` varchar(1000) DEFAULT NULL COMMENT '店铺分类名称集合',
  `joinin_state` varchar(50) DEFAULT NULL COMMENT '申请状态 10-已提交申请 11-缴费完成  20-审核成功 30-审核失败 31-缴费审核失败 40-审核通过开店',
  `joinin_message` varchar(200) DEFAULT NULL COMMENT '管理员审核信息',
  `joinin_year` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开店时长(年)',
  `sg_id` int(10) unsigned DEFAULT NULL COMMENT '店铺等级编号',
  `sg_name` varchar(50) DEFAULT NULL COMMENT '店铺等级名称',
  `sg_info` varchar(200) DEFAULT NULL COMMENT '店铺等级下的收费等信息',
  `sc_id` int(10) unsigned DEFAULT NULL COMMENT '店铺分类编号',
  `sc_name` varchar(50) DEFAULT NULL COMMENT '店铺分类名称',
  `sc_bail` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '店铺分类保证金',
  `store_class_commis_rates` varchar(200) DEFAULT NULL COMMENT '分类佣金比例',
  `paying_money_certificate` varchar(50) DEFAULT NULL COMMENT '付款凭证',
  `paying_money_certif_exp` varchar(200) DEFAULT NULL COMMENT '付款凭证说明',
  `paying_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '付款金额',
  `is_person` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为个人 1是，0否',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺入住表';

CREATE TABLE `dp_area` (
  `area_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `area_parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '地区父ID',
  `area_name` varchar(50) NOT NULL COMMENT '地区名称',
  `area_region` varchar(3) DEFAULT NULL COMMENT '大区名称',
  `area_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `area_deep` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '地区深度，从1开始',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`area_id`),
  KEY `area_parent_id` (`area_parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地区表';

CREATE TABLE `dp_store_bind_class` (
  `bid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺ID',
  `commis_rate` tinyint(4) unsigned DEFAULT '0' COMMENT '佣金比例',
  `class_1` mediumint(9) unsigned DEFAULT '0' COMMENT '一级分类',
  `class_2` mediumint(9) unsigned DEFAULT '0' COMMENT '二级分类',
  `class_3` mediumint(9) unsigned DEFAULT '0' COMMENT '三级分类',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0审核中1已审核 2平台自营店铺',
  PRIMARY KEY (`bid`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺可发布商品类目表';








