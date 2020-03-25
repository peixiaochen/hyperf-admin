/*
 Navicat Premium Data Transfer

 Source Server         : 本地连接
 Source Server Type    : MySQL
 Source Server Version : 50719
 Source Host           : localhost:3306
 Source Schema         : game_sdk

 Target Server Type    : MySQL
 Target Server Version : 50719
 File Encoding         : 65001

 Date: 17/02/2020 18:02:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_menu` VALUES (1, 0, 0, '根目录', 'root', 'root', NULL, '2019-12-06 18:43:32');
INSERT INTO `admin_menu` VALUES (2, 1, 0, '首页', 'home', '/index', '2020-02-10 13:35:36', '2020-02-10 14:11:08');
INSERT INTO `admin_menu` VALUES (3, 1, 0, '权限管理', 'lock', '/menu', '2020-02-10 14:05:55', '2020-02-10 14:42:07');
INSERT INTO `admin_menu` VALUES (4, 3, 0, '菜单管理', '22', '/menu', '2020-02-10 14:03:40', '2020-02-10 14:06:19');
INSERT INTO `admin_menu` VALUES (5, 3, 0, '管理员列表', '22', '/adminList', '2020-02-11 15:08:02', '2020-02-11 15:08:02');
INSERT INTO `admin_menu` VALUES (6, 3, 0, '权限列表', '1111', '/permission', '2020-02-12 18:50:57', '2020-02-12 18:50:57');
INSERT INTO `admin_menu` VALUES (7, 3, 0, '管理员分组', '22', '/adminGroup', '2020-02-13 13:03:18', '2020-02-13 13:03:18');
INSERT INTO `admin_menu` VALUES (8, 3, 0, '日志列表', '22', '/recordList', '2020-02-13 14:10:51', '2020-02-13 14:10:51');
INSERT INTO `admin_menu` VALUES (9, 1, 0, '用户管理', 'team', '/admin', '2020-02-10 13:37:26', '2020-02-10 14:33:12');
INSERT INTO `admin_menu` VALUES (10, 9, 0, '用户列表', 'unordered-list', '/admin', '2020-02-10 13:38:46', '2020-02-10 14:33:03');
INSERT INTO `admin_menu` VALUES (11, 1, 0, '版本管理', 'branches', '/version', '2020-02-10 13:53:39', '2020-02-10 14:34:19');
INSERT INTO `admin_menu` VALUES (12, 11, 0, '版本记录', '33', '/version', '2020-02-10 13:54:57', '2020-02-10 13:55:25');
COMMIT;


-- ----------------------------
-- Table structure for admin_permission
-- ----------------------------
DROP TABLE IF EXISTS `admin_permission`;
CREATE TABLE `admin_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '为空表示所有方法',
  `http_path` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permission_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_permission
-- ----------------------------
BEGIN;
INSERT INTO `admin_permission` VALUES (1, '菜单管理', '菜单管理', 'get,post', '/admin_menu/store\n/admin_menu/update\n/admin_menu/sort\n/admin_menu/detail', NULL, '2019-12-19 12:21:16', '2019-12-19 12:21:16');
INSERT INTO `admin_permission` VALUES (2, '用户管理', '用户管理', 'get,post', '/admin_user/store\n/admin_user/update\n/admin_user/delete\n/admin_user/index\n/admin_user/detail', NULL, '2019-12-19 12:34:24', '2019-12-19 12:34:24');
INSERT INTO `admin_permission` VALUES (3, '角色管理', '角色管理', 'get,post', '/admin_role/store\n/admin_role/update\n/admin_role/delete\n/admin_role/index\n/admin_role/detail', NULL, '2019-12-19 12:39:37', '2019-12-19 12:39:37');
INSERT INTO `admin_permission` VALUES (4, '权限管理', '权限管理', 'get,post', '/admin_permission/store\n/admin_permission/update\n/admin_permission/delete\n/admin_permission/index\n/admin_permission/detail', NULL, '2019-12-19 14:05:12', '2019-12-19 14:05:12');
INSERT INTO `admin_permission` VALUES (5, '版本管理', '版本管理', 'get,post', '/version/store\n/version/update\n/version/index\n', NULL, '2020-01-15 14:19:59', '2020-01-15 14:19:59');
INSERT INTO `admin_permission` VALUES (6, '前台用户管理', '前台用户管理', 'get,post', '/user/index\n/user/status\n/user/detail\n', NULL, '2020-01-15 14:22:45', '2020-01-15 14:22:45');
INSERT INTO `admin_permission` VALUES (7, '后台日志管理', '后台日志管理', 'get,post', '/admin_operation_log/index', NULL, '2020-01-15 14:52:18', '2020-01-15 14:52:18');
INSERT INTO `admin_permission` VALUES (8, '注销用户', '注销用户', 'get,post', '/user/delete', NULL, '2020-01-21 18:05:29', '2020-01-21 18:05:29');
INSERT INTO `admin_permission` VALUES (9, '修改后台用户状态', '修改后台用户状态', 'get,post', '/admin_user/status', NULL, '2020-02-12 22:03:31', '2020-02-12 22:03:31');
INSERT INTO `admin_permission` VALUES (10, '获取首页数据', '获取首页数据', 'get,post', '/welcome', NULL, '2020-02-13 21:58:03', '2020-02-13 21:58:03');
INSERT INTO `admin_permission` VALUES (11, '修改个人信息', '修改个人信息', 'get,post', '/my_info/update', NULL, '2020-02-13 22:56:05', '2020-02-13 22:56:05');
INSERT INTO `admin_permission` VALUES (12, '文件上传权限', '文件上传权限', 'get,post', '/upload_token', NULL, '2020-02-14 11:21:40', '2020-02-14 11:21:40');
INSERT INTO `admin_permission` VALUES (13, '用户退出', '用户退出', 'post', '/user/logout', NULL, '2020-02-14 11:21:40', '2020-02-14 11:21:40');
INSERT INTO `admin_permission` VALUES (14, '菜单列表', '菜单列表', 'get,post', '/admin_menu/index', NULL, '2019-12-19 12:21:16', '2019-12-19 12:21:16');
COMMIT;

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1->正常，0->禁用',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_role_name_unique` (`name`),
  KEY `admin_role_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
BEGIN;
INSERT INTO `admin_role` VALUES (1, '超级管理员', '拥有所有权限，但是要添加', 1, NULL, '2019-12-19 12:16:04', '2019-12-19 12:16:04');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_menu` VALUES (1, 1, '2020-02-17 16:41:24', '2020-02-17 16:41:49');
INSERT INTO `admin_role_menu` VALUES (1, 2, '2020-02-17 16:41:37', '2020-02-17 16:41:51');
INSERT INTO `admin_role_menu` VALUES (1, 4, '2020-02-17 16:41:43', '2020-02-17 16:41:57');
INSERT INTO `admin_role_menu` VALUES (1, 3, '2020-02-17 16:41:40', '2020-02-17 16:41:54');
INSERT INTO `admin_role_menu` VALUES (1, 5, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_menu` VALUES (1, 6, '2020-02-17 16:41:24', '2020-02-17 16:41:49');
INSERT INTO `admin_role_menu` VALUES (1, 7, '2020-02-17 16:41:37', '2020-02-17 16:41:51');
INSERT INTO `admin_role_menu` VALUES (1, 8, '2020-02-17 16:41:40', '2020-02-17 16:41:54');
INSERT INTO `admin_role_menu` VALUES (1, 9, '2020-02-17 16:41:43', '2020-02-17 16:41:57');
INSERT INTO `admin_role_menu` VALUES (1, 10, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_menu` VALUES (1, 11, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_menu` VALUES (1, 12, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permission`;
CREATE TABLE `admin_role_permission` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_permission_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_permission
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_permission` VALUES (1, 1, '2020-02-17 16:41:24', '2020-02-17 16:41:49');
INSERT INTO `admin_role_permission` VALUES (1, 2, '2020-02-17 16:41:37', '2020-02-17 16:41:51');
INSERT INTO `admin_role_permission` VALUES (1, 4, '2020-02-17 16:41:43', '2020-02-17 16:41:57');
INSERT INTO `admin_role_permission` VALUES (1, 3, '2020-02-17 16:41:40', '2020-02-17 16:41:54');
INSERT INTO `admin_role_permission` VALUES (1, 5, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_permission` VALUES (1, 6, '2020-02-17 16:41:24', '2020-02-17 16:41:49');
INSERT INTO `admin_role_permission` VALUES (1, 7, '2020-02-17 16:41:37', '2020-02-17 16:41:51');
INSERT INTO `admin_role_permission` VALUES (1, 8, '2020-02-17 16:41:40', '2020-02-17 16:41:54');
INSERT INTO `admin_role_permission` VALUES (1, 9, '2020-02-17 16:41:43', '2020-02-17 16:41:57');
INSERT INTO `admin_role_permission` VALUES (1, 10, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_permission` VALUES (1, 11, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_permission` VALUES (1, 12, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_permission` VALUES (1, 13, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
INSERT INTO `admin_role_permission` VALUES (1, 14, '2020-02-17 16:41:45', '2020-02-17 16:41:59');
COMMIT;

-- ----------------------------
-- Table structure for admin_role_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_user`;
CREATE TABLE `admin_role_user` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `admin_role_user_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_role_user
-- ----------------------------
BEGIN;
INSERT INTO `admin_role_user` VALUES (1, 2, '2020-02-17 16:38:54', '2020-02-17 16:38:57');
COMMIT;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1->正常，0->禁用',
  `extra` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '备注信息',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_user_username_unique` (`username`),
  KEY `admin_user_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
BEGIN;
INSERT INTO `admin_user` VALUES (1, 'adv123', '4e80888033b7ea2761d1c4ab0f119f891', '超级超级员', 'http://qn.yocotv.com/FvGDUiBov6bgkrEHZSxe7dgx7hJq', 1, '', NULL, '2019-12-06 18:43:32', '2019-12-06 18:43:32');
INSERT INTO `admin_user` VALUES (2, 'admin', 'd9bd78f03b0e9e2bbc1e7b278b896bac', '王大锤', 'http://qnp.yocotv.com/FoejoX4LlKZIiy9qB3aXviEiND-f', 1, '{\"email\":\"45636856@qq.com\",\"sex\":1,\"phone\":\"16602904647\"}', NULL, '2019-12-19 10:22:17', '2020-02-16 16:34:19');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
