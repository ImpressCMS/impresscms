-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 17, 2008 at 11:41 AM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `icms_11`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_configoption`
-- 

CREATE TABLE `i83106030_configoption` (
  `confop_id` mediumint(8) unsigned NOT NULL auto_increment,
  `confop_name` varchar(255) NOT NULL default '',
  `confop_value` varchar(255) NOT NULL default '',
  `conf_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`confop_id`),
  KEY `conf_id` (`conf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

-- 
-- Dumping data for table `i83106030_configoption`
-- 

INSERT INTO `i83106030_configoption` VALUES (47, 'PHP mail()', 'mail', 92);
INSERT INTO `i83106030_configoption` VALUES (48, 'sendmail', 'sendmail', 92);
INSERT INTO `i83106030_configoption` VALUES (49, 'SMTP', 'smtp', 92);
INSERT INTO `i83106030_configoption` VALUES (50, 'SMTPAuth', 'smtpauth', 92);
INSERT INTO `i83106030_configoption` VALUES (6, '_FLAT', 'flat', 30);
INSERT INTO `i83106030_configoption` VALUES (17, '_MD_AM_ADMINACTV', '2', 49);
INSERT INTO `i83106030_configoption` VALUES (53, '_MD_AM_AUTH_CONFOPTION_AD', 'ads', 97);
INSERT INTO `i83106030_configoption` VALUES (52, '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', 97);
INSERT INTO `i83106030_configoption` VALUES (51, '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', 97);
INSERT INTO `i83106030_configoption` VALUES (16, '_MD_AM_AUTOACTV', '1', 49);
INSERT INTO `i83106030_configoption` VALUES (1, '_MD_AM_DEBUGMODE0', '0', 21);
INSERT INTO `i83106030_configoption` VALUES (2, '_MD_AM_DEBUGMODE1', '1', 21);
INSERT INTO `i83106030_configoption` VALUES (3, '_MD_AM_DEBUGMODE2', '2', 21);
INSERT INTO `i83106030_configoption` VALUES (4, '_MD_AM_DEBUGMODE3', '3', 21);
INSERT INTO `i83106030_configoption` VALUES (29, '_MD_AM_ENC_HAVAL1284', '7', 70);
INSERT INTO `i83106030_configoption` VALUES (34, '_MD_AM_ENC_HAVAL1285', '12', 70);
INSERT INTO `i83106030_configoption` VALUES (30, '_MD_AM_ENC_HAVAL1604', '8', 70);
INSERT INTO `i83106030_configoption` VALUES (35, '_MD_AM_ENC_HAVAL1605', '13', 70);
INSERT INTO `i83106030_configoption` VALUES (31, '_MD_AM_ENC_HAVAL1924', '9', 70);
INSERT INTO `i83106030_configoption` VALUES (36, '_MD_AM_ENC_HAVAL1925', '14', 70);
INSERT INTO `i83106030_configoption` VALUES (32, '_MD_AM_ENC_HAVAL2244', '10', 70);
INSERT INTO `i83106030_configoption` VALUES (37, '_MD_AM_ENC_HAVAL2245', '15', 70);
INSERT INTO `i83106030_configoption` VALUES (33, '_MD_AM_ENC_HAVAL2564', '11', 70);
INSERT INTO `i83106030_configoption` VALUES (38, '_MD_AM_ENC_HAVAL2565', '16', 70);
INSERT INTO `i83106030_configoption` VALUES (22, '_MD_AM_ENC_MD5', '0', 70);
INSERT INTO `i83106030_configoption` VALUES (26, '_MD_AM_ENC_RIPEMD128', '4', 70);
INSERT INTO `i83106030_configoption` VALUES (27, '_MD_AM_ENC_RIPEMD160', '5', 70);
INSERT INTO `i83106030_configoption` VALUES (23, '_MD_AM_ENC_SHA256', '1', 70);
INSERT INTO `i83106030_configoption` VALUES (24, '_MD_AM_ENC_SHA384', '2', 70);
INSERT INTO `i83106030_configoption` VALUES (25, '_MD_AM_ENC_SHA512', '3', 70);
INSERT INTO `i83106030_configoption` VALUES (28, '_MD_AM_ENC_WHIRLPOOL', '6', 70);
INSERT INTO `i83106030_configoption` VALUES (39, '_MD_AM_INDEXFOLLOW', 'index,follow', 73);
INSERT INTO `i83106030_configoption` VALUES (41, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', 73);
INSERT INTO `i83106030_configoption` VALUES (21, '_MD_AM_LIGHT', '2', 51);
INSERT INTO `i83106030_configoption` VALUES (20, '_MD_AM_MEDIUM', '1', 51);
INSERT INTO `i83106030_configoption` VALUES (44, '_MD_AM_METAO14YRS', '14 years', 74);
INSERT INTO `i83106030_configoption` VALUES (43, '_MD_AM_METAOGEN', 'general', 74);
INSERT INTO `i83106030_configoption` VALUES (46, '_MD_AM_METAOMAT', 'mature', 74);
INSERT INTO `i83106030_configoption` VALUES (45, '_MD_AM_METAOREST', 'restricted', 74);
INSERT INTO `i83106030_configoption` VALUES (40, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', 73);
INSERT INTO `i83106030_configoption` VALUES (42, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', 73);
INSERT INTO `i83106030_configoption` VALUES (10, '_MD_AM_PASSLEVEL1', '20', 38);
INSERT INTO `i83106030_configoption` VALUES (11, '_MD_AM_PASSLEVEL2', '40', 38);
INSERT INTO `i83106030_configoption` VALUES (12, '_MD_AM_PASSLEVEL3', '60', 38);
INSERT INTO `i83106030_configoption` VALUES (13, '_MD_AM_PASSLEVEL4', '80', 38);
INSERT INTO `i83106030_configoption` VALUES (14, '_MD_AM_PASSLEVEL5', '95', 38);
INSERT INTO `i83106030_configoption` VALUES (18, '_MD_AM_REGINVITE', '3', 49);
INSERT INTO `i83106030_configoption` VALUES (19, '_MD_AM_STRICT', '0', 51);
INSERT INTO `i83106030_configoption` VALUES (15, '_MD_AM_USERACTV', '0', 49);
INSERT INTO `i83106030_configoption` VALUES (5, '_NESTED', 'nest', 30);
INSERT INTO `i83106030_configoption` VALUES (9, '_NEWESTFIRST', '1', 31);
INSERT INTO `i83106030_configoption` VALUES (8, '_OLDESTFIRST', '0', 31);
INSERT INTO `i83106030_configoption` VALUES (7, '_THREADED', 'thread', 30);
