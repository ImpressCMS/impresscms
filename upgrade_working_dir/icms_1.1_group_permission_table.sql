-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 17, 2008 at 02:52 PM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `icms_11`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_group_permission`
-- 

CREATE TABLE `i83106030_group_permission` (
  `gperm_id` int(10) unsigned NOT NULL auto_increment,
  `gperm_groupid` smallint(5) unsigned NOT NULL default '0',
  `gperm_itemid` mediumint(8) unsigned NOT NULL default '0',
  `gperm_modid` mediumint(5) unsigned NOT NULL default '0',
  `gperm_name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`gperm_id`),
  KEY `groupid` (`gperm_groupid`),
  KEY `itemid` (`gperm_itemid`),
  KEY `gperm_modid` (`gperm_modid`,`gperm_name`(10))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=80 ;

-- 
-- Dumping data for table `i83106030_group_permission`
-- 

INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 2);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 3);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 4);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 5);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 6);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 7);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 8);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 9);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 10);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 11);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 12);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 13);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 14);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 15);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 16);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 1, 17);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 2);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 3);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 4);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 5);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 6);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 7);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 8);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 9);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 10);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 11);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 12);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 13);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 14);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 15);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 2, 16);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 2);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 3);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 4);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 5);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 6);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 7);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 8);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 9);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 10);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 11);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 12);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 13);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 14);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 15);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 16);
INSERT INTO `i83106030_group_permission` VALUES (1, 'block_read', 3, 18);
INSERT INTO `i83106030_group_permission` VALUES (1, 'group_manager', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'group_manager', 1, 2);
INSERT INTO `i83106030_group_permission` VALUES (1, 'group_manager', 1, 3);
INSERT INTO `i83106030_group_permission` VALUES (1, 'imgcat_read', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'imgcat_write', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'module_admin', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'module_read', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'module_read', 2, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'module_read', 3, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 1);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 2);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 3);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 4);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 5);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 6);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 7);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 8);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 9);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 10);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 11);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 12);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 13);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 14);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 15);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 16);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 17);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 18);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 19);
INSERT INTO `i83106030_group_permission` VALUES (1, 'system_admin', 1, 20);
