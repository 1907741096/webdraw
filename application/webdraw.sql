DROP DATABASE IF EXISTS `webdraw`;
CREATE DATABASE IF NOT EXISTS `webdraw`;
USE `webdraw`;
SET NAMES utf8;

DROP TABLE IF EXISTS `wd_admin`;
CREATE TABLE IF NOT EXISTS `wd_admin`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(30) UNIQUE NOT NULL,
    `password` CHAR(32) NOT NULL,
    `name` VARCHAR(30) ,
    `tel` CHAR(11) ,
    `email` VARCHAR(30) ,
    `power` TINYINT(1) NOT NULL DEFAULT 0,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `create_time` INT NOT NULL,
    `lastlogin_time` INT NOT NULL
)DEFAULT CHARSET = utf8;

INSERT INTO `wd_admin` (`id`,`username`,`password`,`power`,`create_time`,`lastlogin_time`) VALUES (1,'admin',md5('wd_admin'),1,unix_timestamp(now()),unix_timestamp(now()));

DROP TABLE IF EXISTS `wd_user`;
CREATE TABLE IF NOT EXISTS `wd_user`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(30) UNIQUE NOT NULL,
    `password` CHAR(32) NOT NULL,
    `name` VARCHAR(30) ,
    `tel` CHAR(11) ,
    `email` VARCHAR(30) ,
    `status` TINYINT(1) NOT NULL DEFAULT 1,
    `create_time` INT NOT NULL,
    `lastlogin_time` INT NOT NULL
)DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `wd_news`;
CREATE TABLE IF NOT EXISTS `wd_news`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `tags` TINYINT(1) NOT NULL,
    `thumb` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `create_time` INT NOT NULL,
    `update_time` INT NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0
)DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `wd_draw`;
CREATE TABLE IF NOT EXISTS `wd_draw`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `thumb` VARCHAR(255) NOT NULL,
    `create_time` INT NOT NULL,
    `update_time` INT NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0
)DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `wd_content`;
CREATE TABLE IF NOT EXISTS `wd_content`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(50) NOT NULL,
    `thumb` VARCHAR(255) NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `create_time` INT NOT NULL,
    `update_time` INT NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0
)DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS `wd_comment`;
CREATE TABLE IF NOT EXISTS `wd_comment`(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `news_id` INT NOT NULL,
    `content` MEDIUMTEXT NOT NULL,
    `create_time` INT NOT NULL,
    `update_time` INT NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0
)DEFAULT CHARSET = utf8;



