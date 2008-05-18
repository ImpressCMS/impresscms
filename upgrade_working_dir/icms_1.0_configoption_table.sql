-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 17, 2008 at 11:42 AM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `icms_10_final`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_configoption`
-- 

CREATE TABLE `i83106030_configoption` (
  `confop_id` mediumint(8) unsigned NOT NULL auto_increment,
  `confop_name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `confop_value` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`confop_id`),
  KEY `conf_id` (`conf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=31 ;

-- 
-- Dumping data for table `i83106030_configoption`
-- 

INSERT INTO `i83106030_configoption` VALUES (24, 'PHP mail()', 'mail', 64);
INSERT INTO `i83106030_configoption` VALUES (25, 'sendmail', 'sendmail', 64);
INSERT INTO `i83106030_configoption` VALUES (26, 'SMTP', 'smtp', 64);
INSERT INTO `i83106030_configoption` VALUES (27, 'SMTPAuth', 'smtpauth', 64);
INSERT INTO `i83106030_configoption` VALUES (4, '_FLAT', 'flat', 32);
INSERT INTO `i83106030_configoption` VALUES (10, '_MD_AM_ADMINACTV', '2', 21);
INSERT INTO `i83106030_configoption` VALUES (30, '_MD_AM_AUTH_CONFOPTION_AD', 'ads', 74);
INSERT INTO `i83106030_configoption` VALUES (29, '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', 74);
INSERT INTO `i83106030_configoption` VALUES (28, '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', 74);
INSERT INTO `i83106030_configoption` VALUES (9, '_MD_AM_AUTOACTV', '1', 21);
INSERT INTO `i83106030_configoption` VALUES (23, '_MD_AM_DEBUGMODE0', '0', 13);
INSERT INTO `i83106030_configoption` VALUES (1, '_MD_AM_DEBUGMODE1', '1', 13);
INSERT INTO `i83106030_configoption` VALUES (2, '_MD_AM_DEBUGMODE2', '2', 13);
INSERT INTO `i83106030_configoption` VALUES (14, '_MD_AM_DEBUGMODE3', '3', 13);
INSERT INTO `i83106030_configoption` VALUES (15, '_MD_AM_INDEXFOLLOW', 'index,follow', 43);
INSERT INTO `i83106030_configoption` VALUES (17, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', 43);
INSERT INTO `i83106030_configoption` VALUES (13, '_MD_AM_LIGHT', '2', 23);
INSERT INTO `i83106030_configoption` VALUES (12, '_MD_AM_MEDIUM', '1', 23);
INSERT INTO `i83106030_configoption` VALUES (20, '_MD_AM_METAO14YRS', '14 years', 48);
INSERT INTO `i83106030_configoption` VALUES (19, '_MD_AM_METAOGEN', 'general', 48);
INSERT INTO `i83106030_configoption` VALUES (22, '_MD_AM_METAOMAT', 'mature', 48);
INSERT INTO `i83106030_configoption` VALUES (21, '_MD_AM_METAOREST', 'restricted', 48);
INSERT INTO `i83106030_configoption` VALUES (16, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', 43);
INSERT INTO `i83106030_configoption` VALUES (18, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', 43);
INSERT INTO `i83106030_configoption` VALUES (11, '_MD_AM_STRICT', '0', 23);
INSERT INTO `i83106030_configoption` VALUES (8, '_MD_AM_USERACTV', '0', 21);
INSERT INTO `i83106030_configoption` VALUES (3, '_NESTED', 'nest', 32);
INSERT INTO `i83106030_configoption` VALUES (7, '_NEWESTFIRST', '1', 33);
INSERT INTO `i83106030_configoption` VALUES (6, '_OLDESTFIRST', '0', 33);
INSERT INTO `i83106030_configoption` VALUES (5, '_THREADED', 'thread', 32);
