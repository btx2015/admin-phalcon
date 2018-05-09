/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : btx_admin

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-05-09 16:42:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_access
-- ----------------------------
DROP TABLE IF EXISTS `admin_access`;
CREATE TABLE `admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `node_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_access
-- ----------------------------
INSERT INTO `admin_access` VALUES ('24', '2', '3', '1523943148');
INSERT INTO `admin_access` VALUES ('25', '2', '4', '1523943148');
INSERT INTO `admin_access` VALUES ('26', '2', '5', '1523943148');
INSERT INTO `admin_access` VALUES ('27', '3', '1', '1523943177');
INSERT INTO `admin_access` VALUES ('28', '3', '2', '1523943177');

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '菜单',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `node_id` int(11) NOT NULL DEFAULT '0' COMMENT '节点ID',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜单状态',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_admin_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------

-- ----------------------------
-- Table structure for admin_node
-- ----------------------------
DROP TABLE IF EXISTS `admin_node`;
CREATE TABLE `admin_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '操作节点',
  `tittle` varchar(30) NOT NULL DEFAULT '' COMMENT '节点名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '节点级别',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_node
-- ----------------------------
INSERT INTO `admin_node` VALUES ('1', 'admin', '系统管理', '0', '1', '1523608667', '2018-05-04 14:44:16');
INSERT INTO `admin_node` VALUES ('2', 'role', '角色管理', '1', '2', '1523608879', '2018-05-04 11:16:47');
INSERT INTO `admin_node` VALUES ('3', 'index,list', '查看', '2', '4', '1523608897', '2018-05-04 11:19:30');
INSERT INTO `admin_node` VALUES ('4', 'add,all', '添加', '2', '4', '1523608897', '2018-05-04 11:19:31');
INSERT INTO `admin_node` VALUES ('5', 'edit,all', '编辑', '2', '4', '1523608897', '2018-05-04 11:19:33');
INSERT INTO `admin_node` VALUES ('6', 'enable', '启用', '2', '3', '1523608897', '2018-05-04 11:13:17');
INSERT INTO `admin_node` VALUES ('7', 'disable', '禁用', '2', '3', '1523608897', '2018-05-04 11:13:16');
INSERT INTO `admin_node` VALUES ('8', 'delete', '删除', '2', '3', '1523608897', '2018-05-04 11:13:16');
INSERT INTO `admin_node` VALUES ('9', 'assign', '查看权限', '2', '3', '1523608897', '2018-05-04 11:17:18');
INSERT INTO `admin_node` VALUES ('10', 'assignAccess', '分配权限', '2', '3', '1523608897', '2018-05-04 11:25:48');
INSERT INTO `admin_node` VALUES ('11', 'user', '管理员管理', '1', '2', '1523608897', '2018-05-04 15:38:45');
INSERT INTO `admin_node` VALUES ('12', 'index,list', '查看', '11', '4', '1523608897', '2018-05-04 15:53:17');
INSERT INTO `admin_node` VALUES ('13', 'add,all', '添加', '11', '4', '1523608897', '2018-05-04 15:53:18');
INSERT INTO `admin_node` VALUES ('14', 'edit,all', '编辑', '11', '4', '1523608897', '2018-05-04 15:53:19');
INSERT INTO `admin_node` VALUES ('15', 'enable', '启用', '11', '3', '1523608897', '2018-05-04 15:40:26');
INSERT INTO `admin_node` VALUES ('16', 'disable', '禁用', '11', '3', '1523608897', '2018-05-04 15:40:27');
INSERT INTO `admin_node` VALUES ('17', 'delete', '删除', '11', '3', '1523608897', '2018-05-04 15:40:28');
INSERT INTO `admin_node` VALUES ('18', 'config', '系统设置', '1', '2', '1523608897', '2018-05-04 15:53:02');
INSERT INTO `admin_node` VALUES ('19', 'index', '查看', '18', '3', '1523608897', '2018-05-04 15:53:32');
INSERT INTO `admin_node` VALUES ('20', 'edit', '编辑', '18', '3', '1523608897', '2018-05-04 15:53:49');
INSERT INTO `admin_node` VALUES ('21', 'user', '用户管理', '0', '1', '1523608897', '2018-05-04 15:54:28');
INSERT INTO `admin_node` VALUES ('22', 'user', '用户列表', '21', '2', '1523608897', '2018-05-04 15:57:45');
INSERT INTO `admin_node` VALUES ('23', 'index,list', '查看', '22', '4', '1523608897', '2018-05-04 15:56:51');
INSERT INTO `admin_node` VALUES ('24', 'add', '添加', '22', '3', '1523608897', '2018-05-04 15:56:52');
INSERT INTO `admin_node` VALUES ('25', 'edit', '编辑', '22', '3', '1523608897', '2018-05-04 15:56:53');
INSERT INTO `admin_node` VALUES ('26', 'enable', '启用', '22', '3', '1523608897', '2018-05-04 15:56:54');
INSERT INTO `admin_node` VALUES ('27', 'disable', '禁用', '22', '3', '1523608897', '2018-05-04 15:56:55');
INSERT INTO `admin_node` VALUES ('28', 'delete', '删除', '22', '3', '1523608897', '2018-05-04 15:56:56');

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '角色' COMMENT '角色名',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_admin_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES ('1', '超级管理员', '0', '1', '0', '2018-05-04 14:03:32', '0');
INSERT INTO `admin_role` VALUES ('2', '管理员', '1', '2', '1523515774', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('3', '财务', '1', '2', '1523515813', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('4', '风控', '2', '2', '1523515933', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('5', '销售', '2', '2', '1523516107', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('6', '客服1', '2', '2', '1523516135', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('7', '客服2', '2', '2', '1523516135', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('8', '客服3', '2', '2', '1523515774', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('9', '客服4', '2', '2', '1523515813', '2018-05-03 17:30:38', '0');
INSERT INTO `admin_role` VALUES ('10', '客服5', '2', '2', '1523515933', '2018-05-03 17:28:38', '0');
INSERT INTO `admin_role` VALUES ('11', '客服6', '2', '2', '1523516107', '2018-05-03 17:28:38', '0');
INSERT INTO `admin_role` VALUES ('12', '客服7', '2', '2', '1523516135', '2018-05-03 17:28:38', '0');
INSERT INTO `admin_role` VALUES ('13', '测试添加', '1', '1', '1525317805', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('14', '测试添加1', '1', '1', '1525317823', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('15', '策划四', '1', '1', '1525317838', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('16', '测试添加2', '1', '1', '1525331379', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('17', '测试添加3', '1', '1', '1525335508', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('18', '测试添加4', '1', '1', '1525336623', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('19', '测试添加5', '1', '1', '1525338791', '2018-05-03 19:04:59', '0');
INSERT INTO `admin_role` VALUES ('20', '测试添加6', '1', '1', '1525344849', '0000-00-00 00:00:00', '0');
INSERT INTO `admin_role` VALUES ('21', '测试添加7', '1', '1', '1525345233', '0000-00-00 00:00:00', '0');
INSERT INTO `admin_role` VALUES ('22', '测试添加8', '1', '1', '1525345320', '0000-00-00 00:00:00', '0');
INSERT INTO `admin_role` VALUES ('23', '测试添加9', '1', '1', '1525345531', '0000-00-00 00:00:00', '0');
INSERT INTO `admin_role` VALUES ('24', '测试添加10', '23', '1', '1525411179', '0000-00-00 00:00:00', '0');

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT '0',
  `true_name` varchar(30) NOT NULL DEFAULT '真实姓名',
  `phone` varchar(11) NOT NULL DEFAULT '手机号',
  `email` varchar(255) NOT NULL DEFAULT '邮箱',
  `state` tinyint(1) NOT NULL DEFAULT '1' COMMENT '管理员状态',
  `login_at` int(11) NOT NULL DEFAULT '0' COMMENT '登陆时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '登录IP',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_error` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登录错误次数',
  `modify_pwd` tinyint(1) NOT NULL DEFAULT '0' COMMENT '修改密码标识',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_admin_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES ('1', 'admin', '456', '1', '真实姓名', '123', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522651581', '2018-04-12 15:53:21', '0');
INSERT INTO `admin_users` VALUES ('2', '1234', '456', '2', '真实姓名', '1234', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522651581', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('3', '1234', '456', '3', '真实姓名', '12345', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522651581', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('4', '用户名', '', '4', '真实姓名', '手机号', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522660353', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('5', '123456', '456', '4', '真实姓名', '123456', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522660977', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('6', 'ceshi', '123', '5', '真实姓名', '12345678964', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522754994', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('7', 'ceshi1', '123', '6', '真实姓名', '12345678964', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522755038', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('8', 'ceshi12', '123', '6', '真实姓名', '12345678964', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522755300', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('9', 'ceshi123', '123', '6', '真实姓名', '12345678964', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522755380', '2018-04-26 16:57:04', '0');
INSERT INTO `admin_users` VALUES ('10', 'ceshi12345', '123', '6', '真实姓名', '1234567896', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522755472', '2018-04-12 15:54:00', '0');
INSERT INTO `admin_users` VALUES ('11', 'ceshi123456', '123', '6', '真实姓名', '12345678968', '邮箱', '1', '0', '登录IP', '0', '0', '0', '1522755497', '2018-04-12 15:54:02', '0');
