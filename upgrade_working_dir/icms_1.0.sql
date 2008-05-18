-- DUMP of ImpressCMS 1.0 Database

-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 16, 2008 at 09:03 AM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `icms_10_final`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_avatar`
-- 

CREATE TABLE `i83106030_avatar` (
  `avatar_id` mediumint(8) unsigned NOT NULL auto_increment,
  `avatar_file` varchar(30) NOT NULL default '',
  `avatar_name` varchar(100) NOT NULL default '',
  `avatar_mimetype` varchar(30) NOT NULL default '',
  `avatar_created` int(10) NOT NULL default '0',
  `avatar_display` tinyint(1) unsigned NOT NULL default '0',
  `avatar_weight` smallint(5) unsigned NOT NULL default '0',
  `avatar_type` char(1) NOT NULL default '',
  PRIMARY KEY  (`avatar_id`),
  KEY `avatar_type` (`avatar_type`,`avatar_display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_avatar`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_avatar_user_link`
-- 

CREATE TABLE `i83106030_avatar_user_link` (
  `avatar_id` mediumint(8) unsigned NOT NULL default '0',
  `user_id` mediumint(8) unsigned NOT NULL default '0',
  KEY `avatar_user_id` (`avatar_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_avatar_user_link`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_banner`
-- 

CREATE TABLE `i83106030_banner` (
  `bid` smallint(5) unsigned NOT NULL auto_increment,
  `cid` tinyint(3) unsigned NOT NULL default '0',
  `imptotal` mediumint(8) unsigned NOT NULL default '0',
  `impmade` mediumint(8) unsigned NOT NULL default '0',
  `clicks` mediumint(8) unsigned NOT NULL default '0',
  `imageurl` varchar(255) NOT NULL default '',
  `clickurl` varchar(255) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  `htmlbanner` tinyint(1) NOT NULL default '0',
  `htmlcode` text NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `idxbannercid` (`cid`),
  KEY `idxbannerbidcid` (`bid`,`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `i83106030_banner`
-- 

INSERT INTO `i83106030_banner` VALUES (1, 1, 0, 1, 0, 'http://inbox2.local/icms_1.0_final/htdocs/images/banners/impresscms_banner.gif', 'http://www.impresscms.org/', 1008813250, 0, '');
INSERT INTO `i83106030_banner` VALUES (2, 1, 0, 1, 0, 'http://inbox2.local/icms_1.0_final/htdocs/images/banners/impresscms_banner_2.gif', 'http://www.impresscms.org/', 1008813250, 0, '');
INSERT INTO `i83106030_banner` VALUES (3, 1, 0, 1, 0, 'http://inbox2.local/icms_1.0_final/htdocs/images/banners/banner.swf', 'http://www.impresscms.org/', 1008813250, 0, '');
INSERT INTO `i83106030_banner` VALUES (4, 1, 0, 1, 0, 'http://inbox2.local/icms_1.0_final/htdocs/images/banners/impresscms_banner_3.gif', 'http://www.impresscms.org/', 1008813250, 0, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_bannerclient`
-- 

CREATE TABLE `i83106030_bannerclient` (
  `cid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `contact` varchar(60) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `login` varchar(10) NOT NULL default '',
  `passwd` varchar(10) NOT NULL default '',
  `extrainfo` text NOT NULL,
  PRIMARY KEY  (`cid`),
  KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `i83106030_bannerclient`
-- 

INSERT INTO `i83106030_bannerclient` VALUES (1, 'ImpressCMS', 'ImpressCMS Dev Team', 'info@impresscms.org', '', '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_bannerfinish`
-- 

CREATE TABLE `i83106030_bannerfinish` (
  `bid` smallint(5) unsigned NOT NULL auto_increment,
  `cid` smallint(5) unsigned NOT NULL default '0',
  `impressions` mediumint(8) unsigned NOT NULL default '0',
  `clicks` mediumint(8) unsigned NOT NULL default '0',
  `datestart` int(10) unsigned NOT NULL default '0',
  `dateend` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_bannerfinish`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_block_module_link`
-- 

CREATE TABLE `i83106030_block_module_link` (
  `block_id` mediumint(8) unsigned NOT NULL default '0',
  `module_id` smallint(5) NOT NULL default '0',
  KEY `module_id` (`module_id`),
  KEY `block_id` (`block_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_block_module_link`
-- 

INSERT INTO `i83106030_block_module_link` VALUES (1, 0);
INSERT INTO `i83106030_block_module_link` VALUES (2, 0);
INSERT INTO `i83106030_block_module_link` VALUES (3, 0);
INSERT INTO `i83106030_block_module_link` VALUES (4, 0);
INSERT INTO `i83106030_block_module_link` VALUES (5, 0);
INSERT INTO `i83106030_block_module_link` VALUES (6, 0);
INSERT INTO `i83106030_block_module_link` VALUES (7, 0);
INSERT INTO `i83106030_block_module_link` VALUES (8, 0);
INSERT INTO `i83106030_block_module_link` VALUES (9, 0);
INSERT INTO `i83106030_block_module_link` VALUES (10, 0);
INSERT INTO `i83106030_block_module_link` VALUES (11, 0);
INSERT INTO `i83106030_block_module_link` VALUES (12, 0);
INSERT INTO `i83106030_block_module_link` VALUES (13, 0);
INSERT INTO `i83106030_block_module_link` VALUES (14, -1);
INSERT INTO `i83106030_block_module_link` VALUES (15, -1);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_block_positions`
-- 

CREATE TABLE `i83106030_block_positions` (
  `id` int(11) NOT NULL auto_increment,
  `pname` varchar(30) default '',
  `title` varchar(90) NOT NULL default '',
  `description` text collate latin1_general_ci,
  `block_default` int(1) NOT NULL default '0',
  `block_type` varchar(1) NOT NULL default 'L',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `i83106030_block_positions`
-- 

INSERT INTO `i83106030_block_positions` VALUES (1, 'canvas_left', '_AM_SBLEFT', NULL, 1, 'L');
INSERT INTO `i83106030_block_positions` VALUES (2, 'canvas_right', '_AM_SBRIGHT', NULL, 1, 'L');
INSERT INTO `i83106030_block_positions` VALUES (3, 'page_topleft', '_AM_CBLEFT', NULL, 1, 'C');
INSERT INTO `i83106030_block_positions` VALUES (4, 'page_topcenter', '_AM_CBCENTER', NULL, 1, 'C');
INSERT INTO `i83106030_block_positions` VALUES (5, 'page_topright', '_AM_CBRIGHT', NULL, 1, 'C');
INSERT INTO `i83106030_block_positions` VALUES (6, 'page_bottomleft', '_AM_CBBOTTOMLEFT', NULL, 1, 'C');
INSERT INTO `i83106030_block_positions` VALUES (7, 'page_bottomcenter', '_AM_CBBOTTOM', NULL, 1, 'C');
INSERT INTO `i83106030_block_positions` VALUES (8, 'page_bottomright', '_AM_CBBOTTOMRIGHT', NULL, 1, 'C');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_config`
-- 

CREATE TABLE `i83106030_config` (
  `conf_id` smallint(5) unsigned NOT NULL auto_increment,
  `conf_modid` smallint(5) unsigned NOT NULL default '0',
  `conf_catid` smallint(5) unsigned NOT NULL default '0',
  `conf_name` varchar(25) NOT NULL default '',
  `conf_title` varchar(255) NOT NULL default '',
  `conf_value` text NOT NULL,
  `conf_desc` varchar(255) NOT NULL default '',
  `conf_formtype` varchar(15) NOT NULL default '',
  `conf_valuetype` varchar(10) NOT NULL default '',
  `conf_order` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`conf_id`),
  KEY `conf_mod_cat_id` (`conf_modid`,`conf_catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

-- 
-- Dumping data for table `i83106030_config`
-- 

INSERT INTO `i83106030_config` VALUES (1, 0, 1, 'sitename', '_MD_AM_SITENAME', 'ImpressCMS', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0);
INSERT INTO `i83106030_config` VALUES (2, 0, 1, 'slogan', '_MD_AM_SLOGAN', 'Make a lasting impression ', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (3, 0, 1, 'language', '_MD_AM_LANGUAGE', 'english', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4);
INSERT INTO `i83106030_config` VALUES (4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', '--', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6);
INSERT INTO `i83106030_config` VALUES (5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8);
INSERT INTO `i83106030_config` VALUES (6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10);
INSERT INTO `i83106030_config` VALUES (7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'impresstheme', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12);
INSERT INTO `i83106030_config` VALUES (8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', 'Anonymous', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '0', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16);
INSERT INTO `i83106030_config` VALUES (10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', 'icms_user', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18);
INSERT INTO `i83106030_config` VALUES (11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22);
INSERT INTO `i83106030_config` VALUES (12, 0, 1, 'banners', '_MD_AM_BANNERS', '1', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26);
INSERT INTO `i83106030_config` VALUES (13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24);
INSERT INTO `i83106030_config` VALUES (14, 0, 1, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', 29);
INSERT INTO `i83106030_config` VALUES (15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30);
INSERT INTO `i83106030_config` VALUES (16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'icms_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20);
INSERT INTO `i83106030_config` VALUES (17, 0, 2, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1);
INSERT INTO `i83106030_config` VALUES (18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2);
INSERT INTO `i83106030_config` VALUES (19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4);
INSERT INTO `i83106030_config` VALUES (20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', '1', '_MD_AM_NOTIFYTODSC', 'group', 'int', 6);
INSERT INTO `i83106030_config` VALUES (21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8);
INSERT INTO `i83106030_config` VALUES (22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', '1', '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10);
INSERT INTO `i83106030_config` VALUES (23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12);
INSERT INTO `i83106030_config` VALUES (24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14);
INSERT INTO `i83106030_config` VALUES (27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16);
INSERT INTO `i83106030_config` VALUES (28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18);
INSERT INTO `i83106030_config` VALUES (29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20);
INSERT INTO `i83106030_config` VALUES (30, 0, 1, 'adminmail', '_MD_AM_ADMINML', 'admin@localhost.com', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3);
INSERT INTO `i83106030_config` VALUES (31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22);
INSERT INTO `i83106030_config` VALUES (32, 0, 1, 'com_mode', '_MD_AM_COMMODE', 'nest', '_MD_AM_COMMODEDSC', 'select', 'text', 34);
INSERT INTO `i83106030_config` VALUES (33, 0, 1, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', 36);
INSERT INTO `i83106030_config` VALUES (34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', 'a:3:{i:0;s:9:"webmaster";i:1;s:11:"^impresscms";i:2;s:6:"^admin";}', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24);
INSERT INTO `i83106030_config` VALUES (35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', 'a:1:{i:0;s:15:"impresscms.org$";}', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26);
INSERT INTO `i83106030_config` VALUES (36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3);
INSERT INTO `i83106030_config` VALUES (37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', 'a:1:{i:0;s:9:"127.0.0.1";}', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42);
INSERT INTO `i83106030_config` VALUES (38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'community management system, CMS, content management, social networking, community, blog, support, modules, add-ons, themes', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0);
INSERT INTO `i83106030_config` VALUES (39, 0, 3, 'footer', '_MD_AM_FOOTER', 'Powered by ImpressCMS 1.0 &copy; 2007-2008 <a href="http://www.impresscms.org/" rel="external">The ImpressCMS Project</a>', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20);
INSERT INTO `i83106030_config` VALUES (40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', 'a:5:{i:0;s:4:"fuck";i:1;s:4:"shit";i:2;s:4:"cunt";i:3;s:6:"wanker";i:4;s:7:"bastard";}', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1);
INSERT INTO `i83106030_config` VALUES (42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2);
INSERT INTO `i83106030_config` VALUES (44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1);
INSERT INTO `i83106030_config` VALUES (46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15);
INSERT INTO `i83106030_config` VALUES (47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40);
INSERT INTO `i83106030_config` VALUES (48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4);
INSERT INTO `i83106030_config` VALUES (49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'ImpressCMS', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6);
INSERT INTO `i83106030_config` VALUES (50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright &copy; 2007-2008', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8);
INSERT INTO `i83106030_config` VALUES (51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'ImpressCMS is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1);
INSERT INTO `i83106030_config` VALUES (52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3);
INSERT INTO `i83106030_config` VALUES (53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19);
INSERT INTO `i83106030_config` VALUES (54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', '1', '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30);
INSERT INTO `i83106030_config` VALUES (55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', 'While the administrators and moderators of this site will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to this site express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable.\r\n\r\nYou agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-orientated or any other material that may violate any applicable laws. Doing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. Creating multiple accounts for a single user is not allowed. You agree that the webmaster, administrator and moderators of this site have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent the webmaster, administrator and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised.\r\n\r\nThis site system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above, they serve only to improve your viewing pleasure. The email address is used only for confirming your registration details and password (and for sending new passwords should you forget your current one).\r\n\r\nBy clicking Register below you agree to be bound by these conditions.', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32);
INSERT INTO `i83106030_config` VALUES (56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', '1', '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13);
INSERT INTO `i83106030_config` VALUES (58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26);
INSERT INTO `i83106030_config` VALUES (59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', 'a:1:{i:0;s:1:"1";}', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27);
INSERT INTO `i83106030_config` VALUES (60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', 'The site is currently closed for maintenance. Please come back later.', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28);
INSERT INTO `i83106030_config` VALUES (61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'icms_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31);
INSERT INTO `i83106030_config` VALUES (62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50);
INSERT INTO `i83106030_config` VALUES (63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14);
INSERT INTO `i83106030_config` VALUES (64, 0, 6, 'mailmethod', '_MD_AM_MAILERMETHOD', 'mail', '_MD_AM_MAILERMETHODDESC', 'select', 'text', 4);
INSERT INTO `i83106030_config` VALUES (65, 0, 6, 'smtphost', '_MD_AM_SMTPHOST', 'a:1:{i:0;s:0:"";}', '_MD_AM_SMTPHOSTDESC', 'textarea', 'array', 6);
INSERT INTO `i83106030_config` VALUES (66, 0, 6, 'smtpuser', '_MD_AM_SMTPUSER', '', '_MD_AM_SMTPUSERDESC', 'textbox', 'text', 7);
INSERT INTO `i83106030_config` VALUES (67, 0, 6, 'smtppass', '_MD_AM_SMTPPASS', '', '_MD_AM_SMTPPASSDESC', 'password', 'text', 8);
INSERT INTO `i83106030_config` VALUES (68, 0, 6, 'sendmailpath', '_MD_AM_SENDMAILPATH', '/usr/sbin/sendmail', '_MD_AM_SENDMAILPATHDESC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (69, 0, 6, 'from', '_MD_AM_MAILFROM', '', '_MD_AM_MAILFROMDESC', 'textbox', 'text', 1);
INSERT INTO `i83106030_config` VALUES (70, 0, 6, 'fromname', '_MD_AM_MAILFROMNAME', '', '_MD_AM_MAILFROMNAMEDESC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33);
INSERT INTO `i83106030_config` VALUES (72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', 'a:1:{i:0;s:12:"impresstheme";}', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13);
INSERT INTO `i83106030_config` VALUES (73, 0, 6, 'fromuid', '_MD_AM_MAILFROMUID', '1', '_MD_AM_MAILFROMUIDDESC', 'user', 'int', 3);
INSERT INTO `i83106030_config` VALUES (74, 0, 7, 'auth_method', '_MD_AM_AUTHMETHOD', 'xoops', '_MD_AM_AUTHMETHODDESC', 'select', 'text', 1);
INSERT INTO `i83106030_config` VALUES (75, 0, 7, 'ldap_port', '_MD_AM_LDAP_PORT', '389', '_MD_AM_LDAP_PORT', 'textbox', 'int', 2);
INSERT INTO `i83106030_config` VALUES (76, 0, 7, 'ldap_server', '_MD_AM_LDAP_SERVER', 'your directory server', '_MD_AM_LDAP_SERVER_DESC', 'textbox', 'text', 3);
INSERT INTO `i83106030_config` VALUES (77, 0, 7, 'ldap_base_dn', '_MD_AM_LDAP_BASE_DN', 'dc=icms,dc=org', '_MD_AM_LDAP_BASE_DN_DESC', 'textbox', 'text', 4);
INSERT INTO `i83106030_config` VALUES (78, 0, 7, 'ldap_manager_dn', '_MD_AM_LDAP_MANAGER_DN', 'manager_dn', '_MD_AM_LDAP_MANAGER_DN_DESC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (79, 0, 7, 'ldap_manager_pass', '_MD_AM_LDAP_MANAGER_PASS', 'manager_pass', '_MD_AM_LDAP_MANAGER_PASS_DESC', 'password', 'text', 6);
INSERT INTO `i83106030_config` VALUES (80, 0, 7, 'ldap_version', '_MD_AM_LDAP_VERSION', '3', '_MD_AM_LDAP_VERSION_DESC', 'textbox', 'text', 7);
INSERT INTO `i83106030_config` VALUES (81, 0, 7, 'ldap_users_bypass', '_MD_AM_LDAP_USERS_BYPASS', 'a:1:{i:0;s:5:"admin";}', '_MD_AM_LDAP_USERS_BYPASS_DESC', 'textarea', 'array', 8);
INSERT INTO `i83106030_config` VALUES (82, 0, 7, 'ldap_loginname_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN', 'uid_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN_D', 'yesno', 'int', 9);
INSERT INTO `i83106030_config` VALUES (83, 0, 7, 'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', 10);
INSERT INTO `i83106030_config` VALUES (84, 0, 7, 'ldap_filter_person', '_MD_AM_LDAP_FILTER_PERSON', '', '_MD_AM_LDAP_FILTER_PERSON_DESC', 'textbox', 'text', 11);
INSERT INTO `i83106030_config` VALUES (85, 0, 7, 'ldap_domain_name', '_MD_AM_LDAP_DOMAIN_NAME', 'mydomain', '_MD_AM_LDAP_DOMAIN_NAME_DESC', 'textbox', 'text', 12);
INSERT INTO `i83106030_config` VALUES (86, 0, 7, 'ldap_provisionning', '_MD_AM_LDAP_PROVIS', '0', '_MD_AM_LDAP_PROVIS_DESC', 'yesno', 'int', 13);
INSERT INTO `i83106030_config` VALUES (87, 0, 7, 'ldap_provisionning_group', '_MD_AM_LDAP_PROVIS_GROUP', 'a:1:{i:0;s:1:"2";}', '_MD_AM_LDAP_PROVIS_GROUP_DSC', 'group_multi', 'array', 14);
INSERT INTO `i83106030_config` VALUES (88, 0, 7, 'ldap_mail_attr', '_MD_AM_LDAP_MAIL_ATTR', 'mail', '_MD_AM_LDAP_MAIL_ATTR_DESC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (89, 0, 7, 'ldap_givenname_attr', '_MD_AM_LDAP_GIVENNAME_ATTR', 'givenname', '_MD_AM_LDAP_GIVENNAME_ATTR_DSC', 'textbox', 'text', 16);
INSERT INTO `i83106030_config` VALUES (90, 0, 7, 'ldap_surname_attr', '_MD_AM_LDAP_SURNAME_ATTR', 'sn', '_MD_AM_LDAP_SURNAME_ATTR_DESC', 'textbox', 'text', 17);
INSERT INTO `i83106030_config` VALUES (91, 0, 7, 'ldap_field_mapping', '_MD_AM_LDAP_FIELD_MAPPING_ATTR', 'email=mail|name=displayname', '_MD_AM_LDAP_FIELD_MAPPING_DESC', 'textarea', 'text', 18);
INSERT INTO `i83106030_config` VALUES (92, 0, 7, 'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', 19);
INSERT INTO `i83106030_config` VALUES (93, 0, 7, 'ldap_use_TLS', '_MD_AM_LDAP_USETLS', '0', '_MD_AM_LDAP_USETLS_DESC', 'yesno', 'int', 20);
INSERT INTO `i83106030_config` VALUES (94, 0, 2, 'rank_width', '_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (95, 0, 2, 'rank_height', '_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (96, 0, 2, 'rank_maxsize', '_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (97, 0, 8, 'ml_enable', '_MD_AM_ML_ENABLE', '0', '_MD_AM_ML_ENABLEDEC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (98, 0, 8, 'ml_tags', '_MD_AM_ML_TAGS', 'en,fr', '_MD_AM_ML_TAGSDSC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (99, 0, 8, 'ml_names', '_MD_AM_ML_NAMES', 'english,french', '_MD_AM_ML_NAMESDSC', 'textbox', 'text', 10);
INSERT INTO `i83106030_config` VALUES (100, 0, 8, 'ml_captions', '_MD_AM_ML_CAPTIONS', 'English,Francais', '_MD_AM_ML_CAPTIONSDSC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (101, 0, 8, 'ml_charset', '_MD_AM_ML_CHARSET', 'UTF-8,ISO-8859-15', '_MD_AM_ML_CHARSETDSC', 'textbox', 'text', 16);
INSERT INTO `i83106030_config` VALUES (102, 0, 2, 'remember_me', '_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', 29);
INSERT INTO `i83106030_config` VALUES (103, 0, 2, 'priv_dpolicy', '_MD_AM_PRIVDPOLICY', '0', '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', 33);
INSERT INTO `i83106030_config` VALUES (104, 0, 2, 'priv_policy', '_MD_AM_PRIVPOLICY', '<p>This privacy policy sets out how [website name] uses and protects any information that you give [website name] when you use this website. [website name] is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement. [website name] may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.\r\n</p><p>\r\nThis policy is effective from [date].\r\n</p>\r\n<h2>What we collect</h2>\r\n<p>\r\nWe may collect the following information:\r\n<ul>\r\n<li>name and job title</li>\r\n<li>contact information including email address</li>\r\n<li>demographic information such as postcode, preferences and interests</li>\r\n<li>other information relevant to customer surveys and/or offers</li></ul>\r\n</p>\r\n<h2>What we do with the information we gather</h2>\r\n<p>\r\nWe require this information to understand your needs and provide you with a better service, and in particular for the following reasons:\r\n<ul>\r\n<li>Internal record keeping.</li>\r\n<li>We may use the information to improve our products and services.</li>\r\n<li>We may periodically send promotional email about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>\r\n<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email.</li>\r\n<li>We may use the information to customise the website according to your interests.</li></ul>\r\n</p>\r\n<h2>Security</h2>\r\n<p>\r\nWe are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.\r\n</p>\r\n<h2>How we use cookies</h2>\r\n<p>\r\nA cookie is a small file which asks permission to be placed on your computer''s hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.\r\n</p><p>\r\nWe use traffic log cookies to identify which pages are being used & for authenticating you as a registered member. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.\r\n</p><p>\r\nYou can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website including registration and logging in.\r\n</p>\r\n<h2>Links to other websites</h2>\r\n<p>\r\nOur website may contain links to enable you to visit other websites of interest easily. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.\r\n</p>\r\n<h2>Controlling your personal information</h2>\r\n<p>\r\nYou may choose to restrict the collection or use of your personal information in the following ways:\r\n<ul>\r\n<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li>\r\n<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at [email address]</li></ul>\r\n</p><p>\r\nWe will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen. You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to [address].\r\n</p><p>\r\nIf you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.\r\n</p>', '_MD_AM_PRIVPOLICYDSC', 'textarea', 'text', 34);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_configcategory`
-- 

CREATE TABLE `i83106030_configcategory` (
  `confcat_id` smallint(5) unsigned NOT NULL auto_increment,
  `confcat_name` varchar(255) NOT NULL default '',
  `confcat_order` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`confcat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `i83106030_configcategory`
-- 

INSERT INTO `i83106030_configcategory` VALUES (1, '_MD_AM_GENERAL', 0);
INSERT INTO `i83106030_configcategory` VALUES (2, '_MD_AM_USERSETTINGS', 0);
INSERT INTO `i83106030_configcategory` VALUES (3, '_MD_AM_METAFOOTER', 0);
INSERT INTO `i83106030_configcategory` VALUES (4, '_MD_AM_CENSOR', 0);
INSERT INTO `i83106030_configcategory` VALUES (5, '_MD_AM_SEARCH', 0);
INSERT INTO `i83106030_configcategory` VALUES (6, '_MD_AM_MAILER', 0);
INSERT INTO `i83106030_configcategory` VALUES (7, '_MD_AM_AUTHENTICATION', 0);
INSERT INTO `i83106030_configcategory` VALUES (8, '_MD_AM_MULTILANGUAGE', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- 
-- Dumping data for table `i83106030_configoption`
-- 

INSERT INTO `i83106030_configoption` VALUES (1, '_MD_AM_DEBUGMODE1', '1', 13);
INSERT INTO `i83106030_configoption` VALUES (2, '_MD_AM_DEBUGMODE2', '2', 13);
INSERT INTO `i83106030_configoption` VALUES (3, '_NESTED', 'nest', 32);
INSERT INTO `i83106030_configoption` VALUES (4, '_FLAT', 'flat', 32);
INSERT INTO `i83106030_configoption` VALUES (5, '_THREADED', 'thread', 32);
INSERT INTO `i83106030_configoption` VALUES (6, '_OLDESTFIRST', '0', 33);
INSERT INTO `i83106030_configoption` VALUES (7, '_NEWESTFIRST', '1', 33);
INSERT INTO `i83106030_configoption` VALUES (8, '_MD_AM_USERACTV', '0', 21);
INSERT INTO `i83106030_configoption` VALUES (9, '_MD_AM_AUTOACTV', '1', 21);
INSERT INTO `i83106030_configoption` VALUES (10, '_MD_AM_ADMINACTV', '2', 21);
INSERT INTO `i83106030_configoption` VALUES (11, '_MD_AM_STRICT', '0', 23);
INSERT INTO `i83106030_configoption` VALUES (12, '_MD_AM_MEDIUM', '1', 23);
INSERT INTO `i83106030_configoption` VALUES (13, '_MD_AM_LIGHT', '2', 23);
INSERT INTO `i83106030_configoption` VALUES (14, '_MD_AM_DEBUGMODE3', '3', 13);
INSERT INTO `i83106030_configoption` VALUES (15, '_MD_AM_INDEXFOLLOW', 'index,follow', 43);
INSERT INTO `i83106030_configoption` VALUES (16, '_MD_AM_NOINDEXFOLLOW', 'noindex,follow', 43);
INSERT INTO `i83106030_configoption` VALUES (17, '_MD_AM_INDEXNOFOLLOW', 'index,nofollow', 43);
INSERT INTO `i83106030_configoption` VALUES (18, '_MD_AM_NOINDEXNOFOLLOW', 'noindex,nofollow', 43);
INSERT INTO `i83106030_configoption` VALUES (19, '_MD_AM_METAOGEN', 'general', 48);
INSERT INTO `i83106030_configoption` VALUES (20, '_MD_AM_METAO14YRS', '14 years', 48);
INSERT INTO `i83106030_configoption` VALUES (21, '_MD_AM_METAOREST', 'restricted', 48);
INSERT INTO `i83106030_configoption` VALUES (22, '_MD_AM_METAOMAT', 'mature', 48);
INSERT INTO `i83106030_configoption` VALUES (23, '_MD_AM_DEBUGMODE0', '0', 13);
INSERT INTO `i83106030_configoption` VALUES (24, 'PHP mail()', 'mail', 64);
INSERT INTO `i83106030_configoption` VALUES (25, 'sendmail', 'sendmail', 64);
INSERT INTO `i83106030_configoption` VALUES (26, 'SMTP', 'smtp', 64);
INSERT INTO `i83106030_configoption` VALUES (27, 'SMTPAuth', 'smtpauth', 64);
INSERT INTO `i83106030_configoption` VALUES (28, '_MD_AM_AUTH_CONFOPTION_XOOPS', 'xoops', 74);
INSERT INTO `i83106030_configoption` VALUES (29, '_MD_AM_AUTH_CONFOPTION_LDAP', 'ldap', 74);
INSERT INTO `i83106030_configoption` VALUES (30, '_MD_AM_AUTH_CONFOPTION_AD', 'ads', 74);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

-- 
-- Dumping data for table `i83106030_group_permission`
-- 

INSERT INTO `i83106030_group_permission` VALUES (1, 1, 1, 1, 'module_admin');
INSERT INTO `i83106030_group_permission` VALUES (2, 1, 1, 1, 'module_read');
INSERT INTO `i83106030_group_permission` VALUES (3, 2, 1, 1, 'module_read');
INSERT INTO `i83106030_group_permission` VALUES (4, 3, 1, 1, 'module_read');
INSERT INTO `i83106030_group_permission` VALUES (5, 1, 1, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (6, 1, 2, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (7, 1, 3, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (8, 1, 4, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (9, 1, 5, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (10, 1, 6, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (11, 1, 7, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (12, 1, 8, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (13, 1, 9, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (14, 1, 10, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (15, 1, 11, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (16, 1, 12, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (17, 1, 13, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (18, 1, 14, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (19, 1, 15, 1, 'system_admin');
INSERT INTO `i83106030_group_permission` VALUES (20, 1, 1, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (21, 2, 1, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (22, 3, 1, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (23, 1, 2, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (24, 2, 2, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (25, 3, 2, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (26, 1, 3, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (27, 2, 3, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (28, 3, 3, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (29, 1, 4, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (30, 2, 4, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (31, 3, 4, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (32, 1, 5, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (33, 2, 5, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (34, 3, 5, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (35, 1, 6, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (36, 2, 6, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (37, 3, 6, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (38, 1, 7, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (39, 2, 7, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (40, 3, 7, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (41, 1, 8, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (42, 2, 8, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (43, 3, 8, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (44, 1, 9, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (45, 2, 9, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (46, 3, 9, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (47, 1, 10, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (48, 2, 10, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (49, 3, 10, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (50, 1, 11, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (51, 2, 11, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (52, 3, 11, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (53, 1, 12, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (54, 2, 12, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (55, 3, 12, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (56, 1, 13, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (57, 2, 13, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (58, 3, 13, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (59, 1, 14, 1, 'block_read');
INSERT INTO `i83106030_group_permission` VALUES (60, 3, 15, 1, 'block_read');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_groups`
-- 

CREATE TABLE `i83106030_groups` (
  `groupid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `group_type` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`groupid`),
  KEY `group_type` (`group_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `i83106030_groups`
-- 

INSERT INTO `i83106030_groups` VALUES (1, 'Webmasters', 'Webmasters of this site', 'Admin');
INSERT INTO `i83106030_groups` VALUES (2, 'Registered Users', 'Registered Users Group', 'User');
INSERT INTO `i83106030_groups` VALUES (3, 'Anonymous Users', 'Anonymous Users Group', 'Anonymous');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_groups_users_link`
-- 

CREATE TABLE `i83106030_groups_users_link` (
  `linkid` mediumint(8) unsigned NOT NULL auto_increment,
  `groupid` smallint(5) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`linkid`),
  KEY `groupid_uid` (`groupid`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `i83106030_groups_users_link`
-- 

INSERT INTO `i83106030_groups_users_link` VALUES (1, 1, 1);
INSERT INTO `i83106030_groups_users_link` VALUES (2, 2, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_image`
-- 

CREATE TABLE `i83106030_image` (
  `image_id` mediumint(8) unsigned NOT NULL auto_increment,
  `image_name` varchar(30) NOT NULL default '',
  `image_nicename` varchar(255) NOT NULL default '',
  `image_mimetype` varchar(30) NOT NULL default '',
  `image_created` int(10) unsigned NOT NULL default '0',
  `image_display` tinyint(1) unsigned NOT NULL default '0',
  `image_weight` smallint(5) unsigned NOT NULL default '0',
  `imgcat_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`image_id`),
  KEY `imgcat_id` (`imgcat_id`),
  KEY `image_display` (`image_display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_image`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_imagebody`
-- 

CREATE TABLE `i83106030_imagebody` (
  `image_id` mediumint(8) unsigned NOT NULL default '0',
  `image_body` mediumblob,
  KEY `image_id` (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_imagebody`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_imagecategory`
-- 

CREATE TABLE `i83106030_imagecategory` (
  `imgcat_id` smallint(5) unsigned NOT NULL auto_increment,
  `imgcat_name` varchar(100) NOT NULL default '',
  `imgcat_maxsize` int(8) unsigned NOT NULL default '0',
  `imgcat_maxwidth` smallint(3) unsigned NOT NULL default '0',
  `imgcat_maxheight` smallint(3) unsigned NOT NULL default '0',
  `imgcat_display` tinyint(1) unsigned NOT NULL default '0',
  `imgcat_weight` smallint(3) unsigned NOT NULL default '0',
  `imgcat_type` char(1) NOT NULL default '',
  `imgcat_storetype` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`imgcat_id`),
  KEY `imgcat_display` (`imgcat_display`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_imagecategory`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_imgset`
-- 

CREATE TABLE `i83106030_imgset` (
  `imgset_id` smallint(5) unsigned NOT NULL auto_increment,
  `imgset_name` varchar(50) NOT NULL default '',
  `imgset_refid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`imgset_id`),
  KEY `imgset_refid` (`imgset_refid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `i83106030_imgset`
-- 

INSERT INTO `i83106030_imgset` VALUES (1, 'default', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_imgset_tplset_link`
-- 

CREATE TABLE `i83106030_imgset_tplset_link` (
  `imgset_id` smallint(5) unsigned NOT NULL default '0',
  `tplset_name` varchar(50) NOT NULL default '',
  KEY `tplset_name` (`tplset_name`(10))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_imgset_tplset_link`
-- 

INSERT INTO `i83106030_imgset_tplset_link` VALUES (1, 'default');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_imgsetimg`
-- 

CREATE TABLE `i83106030_imgsetimg` (
  `imgsetimg_id` mediumint(8) unsigned NOT NULL auto_increment,
  `imgsetimg_file` varchar(50) NOT NULL default '',
  `imgsetimg_body` blob NOT NULL,
  `imgsetimg_imgset` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`imgsetimg_id`),
  KEY `imgsetimg_imgset` (`imgsetimg_imgset`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_imgsetimg`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_modules`
-- 

CREATE TABLE `i83106030_modules` (
  `mid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `version` smallint(5) unsigned NOT NULL default '100',
  `last_update` int(10) unsigned NOT NULL default '0',
  `weight` smallint(3) unsigned NOT NULL default '0',
  `isactive` tinyint(1) unsigned NOT NULL default '0',
  `dirname` varchar(25) NOT NULL default '',
  `hasmain` tinyint(1) unsigned NOT NULL default '0',
  `hasadmin` tinyint(1) unsigned NOT NULL default '0',
  `hassearch` tinyint(1) unsigned NOT NULL default '0',
  `hasconfig` tinyint(1) unsigned NOT NULL default '0',
  `hascomments` tinyint(1) unsigned NOT NULL default '0',
  `hasnotification` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `hasmain` (`hasmain`),
  KEY `hasadmin` (`hasadmin`),
  KEY `hassearch` (`hassearch`),
  KEY `hasnotification` (`hasnotification`),
  KEY `dirname` (`dirname`),
  KEY `name` (`name`(15))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `i83106030_modules`
-- 

INSERT INTO `i83106030_modules` VALUES (1, 'System', 100, 1210942824, 0, 1, 'system', 0, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_newblocks`
-- 

CREATE TABLE `i83106030_newblocks` (
  `bid` mediumint(8) unsigned NOT NULL auto_increment,
  `mid` smallint(5) unsigned NOT NULL default '0',
  `func_num` tinyint(3) unsigned NOT NULL default '0',
  `options` varchar(255) NOT NULL default '',
  `name` varchar(150) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `side` tinyint(1) unsigned NOT NULL default '0',
  `weight` smallint(5) unsigned NOT NULL default '0',
  `visible` tinyint(1) unsigned NOT NULL default '0',
  `block_type` char(1) NOT NULL default '',
  `c_type` char(1) NOT NULL default '',
  `isactive` tinyint(1) unsigned NOT NULL default '0',
  `dirname` varchar(50) NOT NULL default '',
  `func_file` varchar(50) NOT NULL default '',
  `show_func` varchar(50) NOT NULL default '',
  `edit_func` varchar(50) NOT NULL default '',
  `template` varchar(50) NOT NULL default '',
  `bcachetime` int(10) unsigned NOT NULL default '0',
  `last_modified` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bid`),
  KEY `mid` (`mid`),
  KEY `visible` (`visible`),
  KEY `isactive_visible_mid` (`isactive`,`visible`,`mid`),
  KEY `mid_funcnum` (`mid`,`func_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- 
-- Dumping data for table `i83106030_newblocks`
-- 

INSERT INTO `i83106030_newblocks` VALUES (1, 1, 1, '', 'User Menu', 'User Menu', '', 1, 0, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_user_show', '', 'system_block_user.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (2, 1, 2, '', 'Login', 'Login', '', 1, 0, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_login_show', '', 'system_block_login.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (3, 1, 3, '', 'Search', 'Search', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_search_show', '', 'system_block_search.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (4, 1, 4, '', 'Waiting Contents', 'Waiting Contents', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_waiting_show', '', 'system_block_waiting.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (5, 1, 5, '', 'Main Menu', 'Main Menu', '', 1, 0, 1, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_main_show', '', 'system_block_mainmenu.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (6, 1, 6, '320|190|s_poweredby.gif|1', 'Site Info', 'Site Info', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_info_show', 'b_system_info_edit', 'system_block_siteinfo.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (7, 1, 7, '', 'Who''s Online', 'Who''s Online', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_online_show', '', 'system_block_online.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (8, 1, 8, '10|1', 'Top Posters', 'Top Posters', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_topposters_show', 'b_system_topposters_edit', 'system_block_topusers.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (9, 1, 9, '10|1', 'New Members', 'New Members', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_newmembers_show', 'b_system_newmembers_edit', 'system_block_newusers.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (10, 1, 10, '10', 'Recent Comments', 'Recent Comments', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_comments_show', 'b_system_comments_edit', 'system_block_comments.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (11, 1, 11, '', 'Notification Options', 'Notification Options', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_notification_show', '', 'system_block_notification.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (12, 1, 12, '0|80', 'Themes', 'Themes', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_themes_show', 'b_system_themes_edit', 'system_block_themes.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (13, 1, 13, '', 'Language Selection', 'Language Selection', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_multilanguage_show', '', 'system_block_multilanguage.html', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (14, 1, 0, '', 'Custom Block (Auto Format + smilies)', 'Welcome Webmaster !', 'Welcome to your new ImpressCMS powered website. If you haven''t already please delete the [b]install[/b] folder from the server and ensure that [b]mainfile.php[/b] is not writeable (chmod 444).\r\n\r\nTo begin administering your new ImpressCMS powered website you can click the [b]Administration[/b] Menu link located on the left of this page.\r\n\r\nYou may want to begin by editing your website [b]Preferences[/b]: In the admin panel, hover over the [b]System[/b] dropdown and select [b]Preferences.[/b]\r\n\r\nAfterwards you can begin adding [b]Modules[/b] and [b]Themes[/b].\r\nMany of the available modules and themes for ImpressCMS, are available at the [url=http://addons.impresscms.org]Addons[/url] section of the [url=http://www.impresscms.org]projects website[/url].\r\n\r\nYou will also need to begin using [b]Blocks[/b]. You can begin by removing this block. You can do this by navigating to System Admin > Blocks, and the selecting "Webmasters" in the [b]Groups[/b] select box. You will then be able to see the blocks available for the Webmasters group, which this block is!\r\n\r\nFor more information about working with ImpressCMS, please use the links below.\r\n<ul><li>[url=http://wiki.impresscms.org/index.php?title=Category:English#Using_ImpressCMS]Using ImpressCMS[/url]<li>[url=http://wiki.impresscms.org/index.php?title=Category:English#Customizing_ImpressCMS]Customizing ImpressCMS[/url]</li><li>[url=http://wiki.impresscms.org/index.php?title=Category:English#Developing_for_ImpressCMS]Developing for ImpressCMS[/url]</li></ul>\r\nWe warmly invite you to join [url=http://community.impresscms.org]The ImpressCMS Community[/url] - Where you can make contributions, get help, help others, etc...', 4, 0, 1, 'C', 'S', 1, '', '', '', '', '', 0, 1210942824);
INSERT INTO `i83106030_newblocks` VALUES (15, 1, 0, '', 'Custom Block (Auto Format + smilies)', 'Welcome to an ImpressCMS powered website !', 'This is sample text for a block. If you are the administrator please log in to view more information.\r\n\r\nLearn more about ImpressCMS:\r\n<ul><li>[url=http://www.impresscms.org]Project Home[/url]</li><li>[url=http://community.impresscms.org]ImpressCMS Community[/url]</li><li>[url=http://addons.impresscms.org]ImpressCMS Addons[/url]</li><li>[url=http://wiki.impresscms.org]ImpressCMS Wiki[/url]</li><li>[url=http://blog.impresscms.org]ImpressCMS Blog[/url]</li></ul>', 4, 0, 1, 'C', 'S', 1, '', '', '', '', '', 0, 1210942824);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_online`
-- 

CREATE TABLE `i83106030_online` (
  `online_uid` mediumint(8) unsigned NOT NULL default '0',
  `online_uname` varchar(25) NOT NULL default '',
  `online_updated` int(10) unsigned NOT NULL default '0',
  `online_module` smallint(5) unsigned NOT NULL default '0',
  `online_ip` varchar(15) NOT NULL default '',
  KEY `online_module` (`online_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_online`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_priv_msgs`
-- 

CREATE TABLE `i83106030_priv_msgs` (
  `msg_id` mediumint(8) unsigned NOT NULL auto_increment,
  `msg_image` varchar(100) default NULL,
  `subject` varchar(255) NOT NULL default '',
  `from_userid` mediumint(8) unsigned NOT NULL default '0',
  `to_userid` mediumint(8) unsigned NOT NULL default '0',
  `msg_time` int(10) unsigned NOT NULL default '0',
  `msg_text` text NOT NULL,
  `read_msg` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`msg_id`),
  KEY `to_userid` (`to_userid`),
  KEY `touseridreadmsg` (`to_userid`,`read_msg`),
  KEY `msgidfromuserid` (`msg_id`,`from_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_priv_msgs`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_ranks`
-- 

CREATE TABLE `i83106030_ranks` (
  `rank_id` smallint(5) unsigned NOT NULL auto_increment,
  `rank_title` varchar(50) NOT NULL default '',
  `rank_min` mediumint(8) unsigned NOT NULL default '0',
  `rank_max` mediumint(8) unsigned NOT NULL default '0',
  `rank_special` tinyint(1) unsigned NOT NULL default '0',
  `rank_image` varchar(255) default NULL,
  PRIMARY KEY  (`rank_id`),
  KEY `rank_min` (`rank_min`),
  KEY `rank_max` (`rank_max`),
  KEY `rankminrankmaxranspecial` (`rank_min`,`rank_max`,`rank_special`),
  KEY `rankspecial` (`rank_special`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `i83106030_ranks`
-- 

INSERT INTO `i83106030_ranks` VALUES (1, 'Just popping in', 0, 20, 0, 'rank3e632f95e81ca.gif');
INSERT INTO `i83106030_ranks` VALUES (2, 'Not too shy to talk', 21, 40, 0, 'rank3dbf8e94a6f72.gif');
INSERT INTO `i83106030_ranks` VALUES (3, 'Quite a regular', 41, 70, 0, 'rank3dbf8e9e7d88d.gif');
INSERT INTO `i83106030_ranks` VALUES (4, 'Just can not stay away', 71, 150, 0, 'rank3dbf8ea81e642.gif');
INSERT INTO `i83106030_ranks` VALUES (5, 'Home away from home', 151, 10000, 0, 'rank3dbf8eb1a72e7.gif');
INSERT INTO `i83106030_ranks` VALUES (6, 'Moderator', 0, 0, 1, 'rank3dbf8edf15093.gif');
INSERT INTO `i83106030_ranks` VALUES (7, 'Webmaster', 0, 0, 1, 'rank3dbf8ee8681cd.gif');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_session`
-- 

CREATE TABLE `i83106030_session` (
  `sess_id` varchar(32) NOT NULL default '',
  `sess_updated` int(10) unsigned NOT NULL default '0',
  `sess_ip` varchar(15) NOT NULL default '',
  `sess_data` text NOT NULL,
  PRIMARY KEY  (`sess_id`),
  KEY `updated` (`sess_updated`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_session`
-- 

INSERT INTO `i83106030_session` VALUES ('72413187416f9a99ea8d717e35a55ce9', 1210942827, '127.0.0.1', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_smiles`
-- 

CREATE TABLE `i83106030_smiles` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `code` varchar(50) NOT NULL default '',
  `smile_url` varchar(100) NOT NULL default '',
  `emotion` varchar(75) NOT NULL default '',
  `display` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `i83106030_smiles`
-- 

INSERT INTO `i83106030_smiles` VALUES (1, ':-D', 'smil3dbd4d4e4c4f2.gif', 'Very Happy', 1);
INSERT INTO `i83106030_smiles` VALUES (2, ':-)', 'smil3dbd4d6422f04.gif', 'Smile', 1);
INSERT INTO `i83106030_smiles` VALUES (3, ':-(', 'smil3dbd4d75edb5e.gif', 'Sad', 1);
INSERT INTO `i83106030_smiles` VALUES (4, ':-o', 'smil3dbd4d8676346.gif', 'Surprised', 1);
INSERT INTO `i83106030_smiles` VALUES (5, ':-?', 'smil3dbd4d99c6eaa.gif', 'Confused', 1);
INSERT INTO `i83106030_smiles` VALUES (6, '8-)', 'smil3dbd4daabd491.gif', 'Cool', 1);
INSERT INTO `i83106030_smiles` VALUES (7, ':lol:', 'smil3dbd4dbc14f3f.gif', 'Laughing', 1);
INSERT INTO `i83106030_smiles` VALUES (8, ':-x', 'smil3dbd4dcd7b9f4.gif', 'Mad', 1);
INSERT INTO `i83106030_smiles` VALUES (9, ':-P', 'smil3dbd4ddd6835f.gif', 'Razz', 1);
INSERT INTO `i83106030_smiles` VALUES (10, ':oops:', 'smil3dbd4df1944ee.gif', 'Embarrassed', 0);
INSERT INTO `i83106030_smiles` VALUES (11, ':cry:', 'smil3dbd4e02c5440.gif', 'Crying (very sad)', 0);
INSERT INTO `i83106030_smiles` VALUES (12, ':evil:', 'smil3dbd4e1748cc9.gif', 'Evil or Very Mad', 0);
INSERT INTO `i83106030_smiles` VALUES (13, ':roll:', 'smil3dbd4e29bbcc7.gif', 'Rolling Eyes', 0);
INSERT INTO `i83106030_smiles` VALUES (14, ';-)', 'smil3dbd4e398ff7b.gif', 'Wink', 0);
INSERT INTO `i83106030_smiles` VALUES (15, ':pint:', 'smil3dbd4e4c2e742.gif', 'Another pint of beer', 0);
INSERT INTO `i83106030_smiles` VALUES (16, ':hammer:', 'smil3dbd4e5e7563a.gif', 'ToolTimes at work', 0);
INSERT INTO `i83106030_smiles` VALUES (17, ':idea:', 'smil3dbd4e7853679.gif', 'I have an idea', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_tplfile`
-- 

CREATE TABLE `i83106030_tplfile` (
  `tpl_id` mediumint(7) unsigned NOT NULL auto_increment,
  `tpl_refid` smallint(5) unsigned NOT NULL default '0',
  `tpl_module` varchar(25) NOT NULL default '',
  `tpl_tplset` varchar(50) NOT NULL default '',
  `tpl_file` varchar(50) NOT NULL default '',
  `tpl_desc` varchar(255) NOT NULL default '',
  `tpl_lastmodified` int(10) unsigned NOT NULL default '0',
  `tpl_lastimported` int(10) unsigned NOT NULL default '0',
  `tpl_type` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`tpl_id`),
  KEY `tpl_refid` (`tpl_refid`,`tpl_type`),
  KEY `tpl_tplset` (`tpl_tplset`,`tpl_file`(10))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- 
-- Dumping data for table `i83106030_tplfile`
-- 

INSERT INTO `i83106030_tplfile` VALUES (1, 1, 'system', 'default', 'system_imagemanager.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (2, 1, 'system', 'default', 'system_imagemanager2.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (3, 1, 'system', 'default', 'system_userinfo.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (4, 1, 'system', 'default', 'system_userform.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (5, 1, 'system', 'default', 'system_rss.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (6, 1, 'system', 'default', 'system_redirect.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (7, 1, 'system', 'default', 'system_comment.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (8, 1, 'system', 'default', 'system_comments_flat.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (9, 1, 'system', 'default', 'system_comments_thread.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (10, 1, 'system', 'default', 'system_comments_nest.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (11, 1, 'system', 'default', 'system_siteclosed.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (12, 1, 'system', 'default', 'system_dummy.html', 'Dummy template file for holding non-template contents. This should not be edited.', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (13, 1, 'system', 'default', 'system_notification_list.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (14, 1, 'system', 'default', 'system_notification_select.html', '', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (15, 1, 'system', 'default', 'system_block_dummy.html', 'Dummy template for custom blocks or blocks without templates', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (16, 1, 'system', 'default', 'system_privpolicy.html', 'Template for displaying site Privacy Policy', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (17, 1, 'system', 'default', 'system_error.html', 'Template for handling HTTP errors', 1210942824, 1210942824, 'module');
INSERT INTO `i83106030_tplfile` VALUES (18, 1, 'system', 'default', 'system_block_user.html', 'Shows user block', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (19, 2, 'system', 'default', 'system_block_login.html', 'Shows login form', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (20, 3, 'system', 'default', 'system_block_search.html', 'Shows search form block', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (21, 4, 'system', 'default', 'system_block_waiting.html', 'Shows contents waiting for approval', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (22, 5, 'system', 'default', 'system_block_mainmenu.html', 'Shows the main navigation menu of the site', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (23, 6, 'system', 'default', 'system_block_siteinfo.html', 'Shows basic info about the site and a link to Recommend Us pop up window', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (24, 7, 'system', 'default', 'system_block_online.html', 'Displays users/guests currently online', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (25, 8, 'system', 'default', 'system_block_topusers.html', 'Top posters', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (26, 9, 'system', 'default', 'system_block_newusers.html', 'Shows most recent users', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (27, 10, 'system', 'default', 'system_block_comments.html', 'Shows most recent comments', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (28, 11, 'system', 'default', 'system_block_notification.html', 'Shows notification options', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (29, 12, 'system', 'default', 'system_block_themes.html', 'Shows theme selection box', 1210942824, 1210942824, 'block');
INSERT INTO `i83106030_tplfile` VALUES (30, 13, 'system', 'default', 'system_block_multilanguage.html', 'Displays image links to change the site language', 1210942824, 1210942824, 'block');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_tplset`
-- 

CREATE TABLE `i83106030_tplset` (
  `tplset_id` int(7) unsigned NOT NULL auto_increment,
  `tplset_name` varchar(50) NOT NULL default '',
  `tplset_desc` varchar(255) NOT NULL default '',
  `tplset_credits` text NOT NULL,
  `tplset_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tplset_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `i83106030_tplset`
-- 

INSERT INTO `i83106030_tplset` VALUES (1, 'default', 'ImpressCMS Default Template Set', '', 1210942824);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_tplsource`
-- 

CREATE TABLE `i83106030_tplsource` (
  `tpl_id` mediumint(7) unsigned NOT NULL default '0',
  `tpl_source` mediumtext NOT NULL,
  KEY `tpl_id` (`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `i83106030_tplsource`
-- 

INSERT INTO `i83106030_tplsource` VALUES (1, '<!DOCTYPE html PUBLIC ''-//W3C//DTD XHTML 1.0 Transitional//EN'' ''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd''>\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">\r\n<head>\r\n<meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>" />\r\n<meta http-equiv="content-language" content="<{$xoops_langcode}>" />\r\n<title><{$lang_imgmanager}> <{$sitename}></title>\r\n<script type="text/javascript">\r\n<!--//\r\nfunction appendCode(addCode) {\r\n	var targetDom = window.opener.xoopsGetElementById(''<{$target}>'');\r\n	if (targetDom.createTextRange && targetDom.caretPos){\r\n  		var caretPos = targetDom.caretPos;\r\n		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) \r\n== '' '' ? addCode + '' '' : addCode;  \r\n	} else if (targetDom.getSelection && targetDom.caretPos){\r\n		var caretPos = targetDom.caretPos;\r\n		caretPos.text = caretPos.text.charat(caretPos.text.length - 1)  \r\n== '' '' ? addCode + '' '' : addCode;\r\n	} else {\r\n		targetDom.value = targetDom.value + addCode;\r\n  	}\r\n	window.close();\r\n	return;\r\n}\r\n//-->\r\n</script>\r\n<style type="text/css" media="all">\r\nbody {margin: 0;}\r\nimg {border: 0;}\r\ntable {width: 100%; margin: 0;}\r\na:link {color: #3a76d6; font-weight: bold; background-color: transparent;}\r\na:visited {color: #9eb2d6; font-weight: bold; background-color: transparent;}\r\na:hover {color: #e18a00; background-color: transparent;}\r\ntable td {background-color: white; font-size: 12px; padding: 0; border-width: 0; vertical-align: top; font-family: Verdana, Arial, Helvetica, sans-serif;}\r\ntable#imagenav td {vertical-align: bottom; padding: 5px;}\r\ntable#imagemain td {border-right: 1px solid silver; border-bottom: 1px solid silver; padding: 5px; vertical-align: middle;}\r\ntable#imagemain th {border: 0; background-color: #2F5376; color:white; font-size: 12px; padding: 5px; vertical-align: top; text-align:center; font-family: Verdana, Arial, Helvetica, sans-serif;}\r\ntable#header td {width: 100%; background-color: #2F5376; vertical-align: middle;}\r\ntable#header td#headerbar {border-bottom: 1px solid silver; background-color: #dddddd;}\r\ndiv#pagenav {text-align:center;}\r\ndiv#footer {text-align:right; padding: 5px;}\r\n</style>\r\n</head>\r\n\r\n<body onload="window.resizeTo(<{$xsize}>, <{$ysize}>);">\r\n  <table id="header" cellspacing="0">\r\n    <tr>\r\n      <td><img src="<{$xoops_url}>/images/logo.gif" width="150px" height="80px" alt="<{$sitename}>" /></td>\r\n    </tr>\r\n    <tr>\r\n      <td id="headerbar"></td>\r\n    </tr>\r\n  </table>\r\n\r\n  <form action="imagemanager.php" method="get">\r\n    <table cellspacing="0" id="imagenav">\r\n      <tr>\r\n        <td>\r\n          <select name="cat_id" onchange="location=''<{$xoops_url}>/imagemanager.php?target=<{$target}>&cat_id=''+this.options[this.selectedIndex].value"><{$cat_options}></select> <input type="hidden" name="target" value="<{$target}>" /><input type="submit" value="<{$lang_go}>" />\r\n        </td>\r\n\r\n        <{if $show_cat > 0}>\r\n        <td align="right"><a href="<{$xoops_url}>/imagemanager.php?target=<{$target}>&op=upload&imgcat_id=<{$show_cat}>" title="<{$lang_addimage}>"><{$lang_addimage}></a></td>\r\n        <{/if}>\r\n\r\n      </tr>\r\n    </table>\r\n  </form>\r\n\r\n  <{if $image_total > 0}>\r\n\r\n  <table cellspacing="0" id="imagemain">\r\n    <tr>\r\n      <th><{$lang_imagename}></th>\r\n      <th><{$lang_image}></th>\r\n      <th><{$lang_imagemime}></th>\r\n      <th><{$lang_align}></th>\r\n    </tr>\r\n\r\n    <{section name=i loop=$images}>\r\n    <tr align="center">\r\n      <td><input type="hidden" name="image_id[]" value="<{$images[i].id}>" /><{$images[i].nicename}></td>\r\n      <td><img src="<{$images[i].src}>" alt="<{$images[i].nicename}>" /></td>\r\n      <td><{$images[i].mimetype}></td>\r\n      <td><a href="#" onclick="javascript:appendCode(''<{$images[i].lxcode}>'');"><img src="<{$xoops_url}>/images/alignleft.gif" alt="Left" /></a> <a href="#" onclick="javascript:appendCode(''<{$images[i].xcode}>'');"><img src="<{$xoops_url}>/images/aligncenter.gif" alt="Center" /></a> <a href="#" onclick="javascript:appendCode(''<{$images[i].rxcode}>'');"><img src="<{$xoops_url}>/images/alignright.gif" alt="Right" /></a></td>\r\n    </tr>\r\n    <{/section}>\r\n  </table>\r\n\r\n  <{/if}>\r\n\r\n  <div id="pagenav"><{$pagenav}></div>\r\n\r\n  <div id="footer">\r\n    <input value="<{$lang_close}>" type="button" onclick="javascript:window.close();" />\r\n  </div>\r\n\r\n  </body>\r\n</html>');
INSERT INTO `i83106030_tplsource` VALUES (2, '<!DOCTYPE html PUBLIC ''-//W3C//DTD XHTML 1.0 Transitional//EN'' ''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd''>\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">\r\n<head>\r\n<meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>" />\r\n<meta http-equiv="content-language" content="<{$xoops_langcode}>" />\r\n<title><{$lang_imgmanager}> <{$xoops_sitename}></title>\r\n<{$image_form.javascript}>\r\n<style type="text/css" media="all">\r\nbody {margin: 0;}\r\nimg {border: 0;}\r\ntable {width: 100%; margin: 0;}\r\na:link {color: #3a76d6; font-weight: bold; background-color: transparent;}\r\na:visited {color: #9eb2d6; font-weight: bold; background-color: transparent;}\r\na:hover {color: #e18a00; background-color: transparent;}\r\ntable td {background-color: white; font-size: 12px; padding: 0; border-width: 0; vertical-align: top; font-family: Verdana, Arial, Helvetica, sans-serif;}\r\ntable#imagenav td {vertical-align: bottom; padding: 5px;}\r\ntd.body {padding: 5px; vertical-align: middle;}\r\ntd.caption {border: 0; background-color: #2F5376; color:white; font-size: 12px; padding: 5px; vertical-align: top; text-align:left; font-family: Verdana, Arial, Helvetica, sans-serif;}\r\ntable#imageform {border: 1px solid silver;}\r\ntable#header td {width: 100%; background-color: #2F5376; vertical-align: middle;}\r\ntable#header td#headerbar {border-bottom: 1px solid silver; background-color: #dddddd;}\r\ndiv#footer {text-align:right; padding: 5px;}\r\n</style>\r\n</head>\r\n\r\n<body onload="window.resizeTo(<{$xsize}>, <{$ysize}>);">\r\n  <table id="header" cellspacing="0">\r\n    <tr>\r\n      <td><img src="<{$xoops_url}>/images/logo.gif" width="150px" height="80px" alt="<{$xoops_sitename}>" /></td>\r\n    </tr>\r\n    <tr>\r\n      <td id="headerbar" ></td>\r\n    </tr>\r\n  </table>\r\n\r\n  <table cellspacing="0" id="imagenav">\r\n    <tr>\r\n      <td align="left"><a href="<{$xoops_url}>/imagemanager.php?target=<{$target}>&cat_id=<{$show_cat}>" title="<{$lang_imgmanager}>"><{$lang_imgmanager}></a></td>\r\n    </tr>\r\n  </table>\r\n\r\n  <form name="<{$image_form.name}>" id="<{$image_form.name}>" action="<{$image_form.action}>" method="<{$image_form.method}>" <{$image_form.extra}>>\r\n    <table id="imageform" cellspacing="0">\r\n    <!-- start of form elements loop -->\r\n    <{foreach item=element from=$image_form.elements}>\r\n      <{if $element.hidden != true}>\r\n      <tr valign="top">\r\n        <td class="caption"><{$element.caption}></td>\r\n        <td class="body"><{$element.body}></td>\r\n      </tr>\r\n      <{else}>\r\n      <{$element.body}>\r\n      <{/if}>\r\n    <{/foreach}>\r\n    <!-- end of form elements loop -->\r\n    </table>\r\n  </form>\r\n\r\n\r\n  <div id="footer">\r\n    <input value="<{$lang_close}>" type="button" onclick="javascript:window.close();" />\r\n  </div>\r\n\r\n  </body>\r\n</html>');
INSERT INTO `i83106030_tplsource` VALUES (3, '<{if $user_ownpage == true}>\r\n\r\n<form name="usernav" action="user.php" method="post">\r\n\r\n<br /><br />\r\n\r\n<table width="70%" align="center" border="0">\r\n  <tr align="center">\r\n    <td><input type="button" value="<{$lang_editprofile}>" onclick="location=''edituser.php''" />\r\n    <input type="button" value="<{$lang_avatar}>" onclick="location=''edituser.php?op=avatarform''" />\r\n    <input type="button" value="<{$lang_inbox}>" onclick="location=''viewpmsg.php''" />\r\n\r\n    <{if $user_candelete == true}>\r\n    <input type="button" value="<{$lang_deleteaccount}>" onclick="location=''user.php?op=delete''" />\r\n    <{/if}>\r\n\r\n    <input type="button" value="<{$lang_logout}>" onclick="location=''user.php?op=logout''" /></td>\r\n  </tr>\r\n</table>\r\n</form>\r\n\r\n<br /><br />\r\n<{elseif $xoops_isadmin != false}>\r\n\r\n<br /><br />\r\n\r\n<table width="70%" align="center" border="0">\r\n  <tr align="center">\r\n    <td><input type="button" value="<{$lang_editprofile}>" onclick="location=''<{$xoops_url}>/modules/system/admin.php?fct=users&uid=<{$user_uid}>&op=modifyUser''" />\r\n    <input type="button" value="<{$lang_deleteaccount}>" onclick="location=''<{$xoops_url}>/modules/system/admin.php?fct=users&op=delUser&uid=<{$user_uid}>''" />\r\n    </td>\r\n  </tr>\r\n</table>\r\n\r\n<br /><br />\r\n<{/if}>\r\n\r\n<table width="100%" border="0" cellspacing="5px">\r\n  <tr valign="top">\r\n    <td width="50%">\r\n      <table class="outer" cellpadding="4px" cellspacing="1px" width="100%">\r\n        <tr>\r\n          <th colspan="2" align="center"><{$lang_allaboutuser}></th>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_avatar}></td>\r\n          <td align="center" class="even"><img src="<{$user_avatarurl}>" alt="Avatar" /></td>\r\n        </tr>\r\n        <tr>\r\n          <td class="head"><{$lang_realname}></td>\r\n          <td align="center" class="odd"><{$user_realname}></td>\r\n        </tr>\r\n        <tr>\r\n          <td class="head"><{$lang_website}></td>\r\n          <td class="even"><{$user_websiteurl}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_email}></td>\r\n          <td class="odd"><{$user_email}></td>\r\n        </tr>\r\n	<tr valign="top">\r\n          <td class="head"><{$lang_privmsg}></td>\r\n          <td class="even"><{$user_pmlink}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_icq}></td>\r\n          <td class="odd"><{$user_icq}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_aim}></td>\r\n          <td class="even"><{$user_aim}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_yim}></td>\r\n          <td class="odd"><{$user_yim}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_msnm}></td>\r\n          <td class="even"><{$user_msnm}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_location}></td>\r\n          <td class="odd"><{$user_location}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_occupation}></td>\r\n          <td class="even"><{$user_occupation}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_interest}></td>\r\n          <td class="odd"><{$user_interest}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_extrainfo}></td>\r\n          <td class="even"><{$user_extrainfo}></td>\r\n        </tr>\r\n      </table>\r\n    </td>\r\n    <td width="50%">\r\n      <table class="outer" cellpadding="4px" cellspacing="1px" width="100%">\r\n        <tr valign="top">\r\n          <th colspan="2" align="center"><{$lang_statistics}></th>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_membersince}></td>\r\n          <td align="center" class="even"><{$user_joindate}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_rank}></td>\r\n          <td align="center" class="odd"><{$user_rankimage}><br /><{$user_ranktitle}></td>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="head"><{$lang_posts}></td>\r\n          <td align="center" class="even"><{$user_posts}></td>\r\n        </tr>\r\n	<tr valign="top">\r\n          <td class="head"><{$lang_lastlogin}></td>\r\n          <td align="center" class="odd"><{$user_lastlogin}></td>\r\n        </tr>\r\n      </table>\r\n      <br />\r\n      <table class="outer" cellpadding="4px" cellspacing="1px" width="100%">\r\n        <tr valign="top">\r\n          <th colspan="2" align="center"><{$lang_signature}></th>\r\n        </tr>\r\n        <tr valign="top">\r\n          <td class="even"><{$user_signature}></td>\r\n        </tr>\r\n      </table>\r\n    </td>\r\n  </tr>\r\n</table>\r\n\r\n<!-- start module search results loop -->\r\n<{foreach item=module from=$modules}>\r\n\r\n<h4><{$module.name}></h4>\r\n<p>\r\n  <!-- start results item loop -->\r\n  <{foreach item=result from=$module.results}>\r\n\r\n  <img src="<{$result.image}>" alt="<{$module.name}>" /><b><a href="<{$result.link}>" title="<{$result.title}>"><{$result.title}></a></b><br /><small>(<{$result.time}>)</small><br />\r\n\r\n  <{/foreach}>\r\n  <!-- end results item loop -->\r\n\r\n<{$module.showall_link}>\r\n</p>\r\n\r\n<{/foreach}>\r\n<!-- end module search results loop -->\r\n');
INSERT INTO `i83106030_tplsource` VALUES (4, '<div>\r\n	<div style="float: left; width: 45% ">\r\n		<fieldset style="padding: 10px;">\r\n			<legend style="font-weight: bold;"><{$lang_login}></legend>\r\n			<form action="user.php" method="post">\r\n		    	<{$lang_username}><input type="text" name="uname" size="21" maxlength="25" value="<{$usercookie}>" />\r\n		    	<br />\r\n			    <br>\r\n		    	<{$lang_password}><input type="password" name="pass" size="21" maxlength="32" /><br />\r\n			    <{if $rememberme }>    \r\n			    	<input type="checkbox" name="rememberme" value="On" /><{$lang_rememberme}><br />    \r\n			    <{/if}>\r\n			    <input type="hidden" name="op" value="login" />\r\n		    	<input type="hidden" name="xoops_redirect" value="<{$redirect_page}>"/>\r\n			    <br />\r\n		    	<input type="submit" value="<{$lang_login}>" />\r\n			</form>\r\n			<a name="lost"></a>  \r\n			<{if $allow_registration }>  \r\n				<div style="text-align: right;"><{$lang_notregister}><br /></div>\r\n			<{/if}>\r\n		</fieldset>\r\n	</div>\r\n	\r\n	<div style="float: right; width: 50% ">\r\n		<fieldset style="padding: 10px;">\r\n			<legend style="font-weight: bold;"><{$lang_lostpassword}></legend>\r\n			<div>\r\n				<br /><{$lang_noproblem}>\r\n			</div>\r\n			<form action="lostpass.php" method="post">\r\n				<{$lang_youremail}> <input type="text" name="email" size="26" maxlength="60" />&nbsp;&nbsp;<input type="hidden" name="op" value="mailpasswd" /><input type="hidden" name="t" value="<{$mailpasswd_token}>" />\r\n		    	<br />\r\n		    	<br />\r\n		     	<input type="submit" value="<{$lang_sendpassword}>" />\r\n			</form>\r\n		</fieldset>\r\n	</div>\r\n</div>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (5, '<?xml version="1.0" encoding="UTF-8"?>\r\n<rss version="2.0">\r\n  <channel>\r\n    <title><{$channel_title}></title>\r\n    <link><{$channel_link}></link>\r\n    <description><{$channel_desc}></description>\r\n    <lastBuildDate><{$channel_lastbuild}></lastBuildDate>\r\n    <docs>http://backend.userland.com/rss/</docs>\r\n    <generator><{$channel_generator}></generator>\r\n    <category><{$channel_category}></category>\r\n    <managingEditor><{$channel_editor}></managingEditor>\r\n    <webMaster><{$channel_webmaster}></webMaster>\r\n    <language><{$channel_language}></language>\r\n    <{if $image_url != ""}>\r\n    <image>\r\n      <title><{$channel_title}></title>\r\n      <url><{$image_url}></url>\r\n      <link><{$channel_link}></link>\r\n      <width><{$image_width}></width>\r\n      <height><{$image_height}></height>\r\n    </image>\r\n    <{/if}>\r\n    <{foreach item=item from=$items}>\r\n    <item>\r\n      <title><{$item.title}></title>\r\n      <link><{$item.link}></link>\r\n      <description><{$item.description}></description>\r\n      <pubDate><{$item.pubdate}></pubDate>\r\n      <guid><{$item.guid}></guid>\r\n    </item>\r\n    <{/foreach}>\r\n  </channel>\r\n</rss>');
INSERT INTO `i83106030_tplsource` VALUES (6, '<!DOCTYPE html PUBLIC ''-//W3C//DTD XHTML 1.0 Transitional//EN'' ''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd''>\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">\r\n<head>\r\n<meta http-equiv="Content-Type" content="text/html; charset=<{$xoops_charset}>" />\r\n<meta http-equiv="Refresh" content="<{$time}>; url=<{$url}>" />\r\n<title><{$xoops_sitename}></title>\r\n<link rel="stylesheet" type="text/css" media="all" href="<{$xoops_themecss}>" />\r\n</head>\r\n<body>\r\n<div style="text-align:center; background-color: #EBEBEB; border-top: 1px solid #FFFFFF; border-left: 1px solid #FFFFFF; border-right: 1px solid #AAAAAA; border-bottom: 1px solid #AAAAAA; font-weight: bold;">\r\n  <h4><{$message}></h4>\r\n  <p><{$lang_ifnotreload}></p>\r\n</div>\r\n<{if $xoops_logdump != ''''}><div><{$xoops_logdump}></div><{/if}>\r\n</body>\r\n</html>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (7, '<!-- start comment post -->\r\n        <tr>\r\n          <td class="head"><a id="comment<{$comment.id}>"></a> <{$comment.poster.uname}></td>\r\n          <td class="head"><div class="comDate"><span class="comDateCaption"><{$lang_posted}>:</span> <{$comment.date_posted}>&nbsp;&nbsp;<span class="comDateCaption"><{$lang_updated}>:</span> <{$comment.date_modified}></div></td>\r\n        </tr>\r\n        <tr>\r\n\r\n          <{if $comment.poster.id != 0}>\r\n\r\n          <td class="odd"><div class="comUserRank"><div class="comUserRankText"><{$comment.poster.rank_title}></div><img class="comUserRankImg" src="<{$xoops_upload_url}>/<{$comment.poster.rank_image}>" alt="" /></div><img class="comUserImg" src="<{$xoops_upload_url}>/<{$comment.poster.avatar}>" alt="" /><div class="comUserStat"><span class="comUserStatCaption"><{$lang_joined}>:</span> <{$comment.poster.regdate}></div><div class="comUserStat"><span class="comUserStatCaption"><{$lang_from}>:</span> <{$comment.poster.from}></div><div class="comUserStat"><span class="comUserStatCaption"><{$lang_posts}>:</span> <{$comment.poster.postnum}></div><div class="comUserStatus"><{$comment.poster.status}></div></td>\r\n\r\n          <{else}>\r\n\r\n          <td class="odd"> </td>\r\n\r\n          <{/if}>\r\n\r\n          <td class="odd">\r\n            <div class="comTitle"><{$comment.image}><{$comment.title}></div><div class="comText"><{$comment.text}></div>\r\n          </td>\r\n        </tr>\r\n        <tr>\r\n          <td class="even"></td>\r\n\r\n          <{if $xoops_iscommentadmin == true}>\r\n\r\n          <td class="even" align="right">\r\n            <a href="<{$editcomment_link}>&amp;com_id=<{$comment.id}>" title="<{$lang_edit}>"><img src="<{$xoops_url}>/images/icons/edit.gif" alt="<{$lang_edit}>" /></a><a href="<{$deletecomment_link}>&amp;com_id=<{$comment.id}>" title="<{$lang_delete}>"><img src="<{$xoops_url}>/images/icons/delete.gif" alt="<{$lang_delete}>" /></a><a href="<{$replycomment_link}>&amp;com_id=<{$comment.id}>" title="<{$lang_reply}>"><img src="<{$xoops_url}>/images/icons/reply.gif" alt="<{$lang_reply}>" /></a>\r\n          </td>\r\n\r\n          <{elseif $xoops_isuser == true && $xoops_userid == $comment.poster.id}>\r\n\r\n          <td class="even" align="right">\r\n            <a href="<{$editcomment_link}>&amp;com_id=<{$comment.id}>" title=="<{$lang_edit}>"><img src="<{$xoops_url}>/images/icons/edit.gif" alt="<{$lang_edit}>" /></a><a href="<{$replycomment_link}>&amp;com_id=<{$comment.id}>" title="<{$lang_reply}>"><img src="<{$xoops_url}>/images/icons/reply.gif" alt="<{$lang_reply}>" /></a>\r\n          </td>\r\n\r\n          <{elseif $xoops_isuser == true || $anon_canpost == true}>\r\n\r\n          <td class="even" align="right">\r\n            <a href="<{$replycomment_link}>&amp;com_id=<{$comment.id}>" title="<{$lang_reply}>"><img src="<{$xoops_url}>/images/icons/reply.gif" alt="<{$lang_reply}>" /></a>\r\n          </td>\r\n\r\n          <{else}>\r\n\r\n          <td class="even"> </td>\r\n\r\n          <{/if}>\r\n\r\n        </tr>\r\n<!-- end comment post -->');
INSERT INTO `i83106030_tplsource` VALUES (8, '<table class="outer" cellpadding="5px" cellspacing="1px">\r\n  <tr>\r\n    <th width="20%"><{$lang_poster}></th>\r\n    <th><{$lang_thread}></th>\r\n  </tr>\r\n  <{foreach item=comment from=$comments}>\r\n    <{include file="db:system_comment.html" comment=$comment}>\r\n  <{/foreach}>\r\n</table>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (9, '<{section name=i loop=$comments}>\r\n<br />\r\n<table cellspacing="1px" class="outer">\r\n  <tr>\r\n    <th width="20%"><{$lang_poster}></th>\r\n    <th><{$lang_thread}></th>\r\n  </tr>\r\n  <{include file="db:system_comment.html" comment=$comments[i]}>\r\n</table>\r\n\r\n<{if $show_threadnav == true}>\r\n<div style="text-align:left; margin:3px; padding: 5px;">\r\n<a href="<{$comment_url}>" title="<{$lang_top}>"><{$lang_top}></a> | <a href="<{$comment_url}>&amp;com_id=<{$comments[i].pid}>&amp;com_rootid=<{$comments[i].rootid}>#newscomment<{$comments[i].pid}>" title="<{$lang_parent}>"><{$lang_parent}></a>\r\n</div>\r\n<{/if}>\r\n\r\n<{if $comments[i].show_replies == true}>\r\n<!-- start comment tree -->\r\n<br />\r\n<table cellspacing="1px" class="outer">\r\n  <tr>\r\n    <th width="50%"><{$lang_subject}></th>\r\n    <th width="20%" align="center"><{$lang_poster}></th>\r\n    <th align="right"><{$lang_posted}></th>\r\n  </tr>\r\n  <{foreach item=reply from=$comments[i].replies}>\r\n  <tr>\r\n    <td class="even"><{$reply.prefix}> <a href="<{$comment_url}>&amp;com_id=<{$reply.id}>&amp;com_rootid=<{$reply.root_id}>" title=<{$reply.title}>"><{$reply.title}></a></td>\r\n    <td class="odd" align="center"><{$reply.poster.uname}></td>\r\n    <td class="even" align="right"><{$reply.date_posted}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n</table>\r\n<!-- end comment tree -->\r\n<{/if}>\r\n\r\n<{/section}>');
INSERT INTO `i83106030_tplsource` VALUES (10, '<{section name=i loop=$comments}>\r\n<br />\r\n<table cellspacing="1px" class="outer">\r\n  <tr>\r\n    <th width="20%"><{$lang_poster}></th>\r\n    <th><{$lang_thread}></th>\r\n  </tr>\r\n  <{include file="db:system_comment.html" comment=$comments[i]}>\r\n</table>\r\n\r\n<!-- start comment replies -->\r\n<{foreach item=reply from=$comments[i].replies}>\r\n<br />\r\n<table cellspacing="0" border="0">\r\n  <tr>\r\n    <td width="<{$reply.prefix}>"></td>\r\n    <td>\r\n      <table class="outer" cellspacing="1px">\r\n        <tr>\r\n          <th width="20%"><{$lang_poster}></th>\r\n          <th><{$lang_thread}></th>\r\n        </tr>\r\n        <{include file="db:system_comment.html" comment=$reply}>\r\n      </table>\r\n    </td>\r\n  </tr>\r\n</table>\r\n<{/foreach}>\r\n<!-- end comment tree -->\r\n<{/section}>');
INSERT INTO `i83106030_tplsource` VALUES (11, '<!DOCTYPE html PUBLIC ''-//W3C//DTD XHTML 1.0 Transitional//EN'' ''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd''>\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">\r\n<head>\r\n<meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>" />\r\n<meta http-equiv="content-language" content="<{$xoops_langcode}>" />\r\n<title><{$xoops_sitename}></title>\r\n<link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/xoops.css" />\r\n<link rel="stylesheet" type="text/css" media="all" href="<{$xoops_themecss}>" />\r\n\r\n</head>\r\n<body>\r\n<div id="xo-canvas"<{if $columns_layout}> class="<{$columns_layout}>"<{/if}>>\r\n  <!-- Start header -->\r\n    <div id="xo-header">\r\n	    <div id="xo-headerlogo"></div>\r\n    </div>  \r\n  <!-- End header -->\r\n  \r\n<!-- Start Main Content Area -->\r\n<div id="xo-canvas-content">\r\n<center>\r\n<br />\r\n<div style="width: 85%;background-color: #f7e6bd; color: #222222; text-align: center; border-top: 1px solid #DDDDFF; border-left: 1px solid #DDDDFF; border-right: 1px solid #DDDDFF; border-bottom: 1px solid #DDDDFF; font-weight: bold; padding: 10px;"><{$lang_siteclosemsg}>\r\n</div>\r\n<br />\r\n  <br />\r\n  <br />\r\n    <div style="width: 270px;border:1px solid #DDDDFF">\r\n <div style="background-color: #f3ac03;font-weight: bold;font-size: 1.2em; color: white;height: 24px"><{$lang_login}></div>\r\n    <br />\r\n   <form action="<{$xoops_url}>/user.php" method="post">\r\n   <{$lang_username}><input type="text" name="uname" size="21" maxlength="25" value="" /><br />\r\n    <div>\r\n    <{$lang_password}><input type="password" name="pass" size="21" maxlength="32" /><br />\r\n        	<input type="hidden" name="xoops_redirect" value="<{$xoops_requesturi}>" />\r\n        	<input type="hidden" name="xoops_login" value="1" />\r\n        <br />\r\n        	<input type="submit" value="<{$lang_login}>" />\r\n</div> \r\n </form>\r\n<br />\r\n</div>\r\n</center>\r\n</div><!-- Start footer -->\r\n<br class="clear" />\r\n<div id="xo-footer-close">\r\n</div>\r\n<!-- end Footer -->\r\n</div>\r\n  </body>\r\n</html>');
INSERT INTO `i83106030_tplsource` VALUES (12, '<{$dummy_content}>');
INSERT INTO `i83106030_tplsource` VALUES (13, '<h4><{$lang_activenotifications}></h4>\r\n<form name="notificationlist" action="notifications.php" method="post">\r\n<table class="outer">\r\n  <tr>\r\n	<th><input name="allbox" id="allbox" onclick="xoopsCheckAll(''notificationlist'', ''allbox'');" type="checkbox" value="<{$lang_checkall}>" /></th>\r\n    <th><{$lang_event}></th>\r\n    <th><{$lang_category}></th>\r\n    <th><{$lang_itemid}></th>\r\n    <th><{$lang_itemname}></th>\r\n  </tr>\r\n  <{foreach item=module from=$modules}>\r\n  <tr>\r\n    <td class="head"><input name="del_mod[<{$module.id}>]" id="del_mod[]" onclick="xoopsCheckGroup(''notificationlist'', ''del_mod[<{$module.id}>]'', ''del_not[<{$module.id}>][]'');" type="checkbox" value="<{$module.id}>" /></td>\r\n    <td class="head" colspan="4"><{$lang_module}>: <{$module.name}></td>\r\n  </tr>\r\n  <{foreach item=category from=$module.categories}>\r\n  <{foreach item=item from=$category.items}>\r\n  <{foreach item=notification from=$item.notifications}>\r\n  <tr>\r\n    <{cycle values=odd,even assign=class}>\r\n    <td class="<{$class}>"><input type="checkbox" name="del_not[<{$module.id}>][]" id="del_not[<{$module.id}>][]" value="<{$notification.id}>" /></td>\r\n    <td class="<{$class}>"><{$notification.event_title}></td>\r\n    <td class="<{$class}>"><{$notification.category_title}></td>\r\n    <td class="<{$class}>"><{if $item.id != 0}><{$item.id}><{/if}></td>\r\n    <td class="<{$class}>"><{if $item.id != 0}><{if $item.url != ''''}><a href="<{$item.url}>" title="<{$item.name}>"><{/if}><{$item.name}><{if $item.url != ''''}></a><{/if}><{/if}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n  <{/foreach}>\r\n  <{/foreach}>\r\n  <{/foreach}>\r\n  <tr>\r\n    <td class="foot" colspan="5">\r\n      <input type="submit" name="delete_cancel" value="<{$lang_cancel}>" />\r\n      <input type="reset" name="delete_reset" value="<{$lang_clear}>" />\r\n      <input type="submit" name="delete" value="<{$lang_delete}>" />\r\n      <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$notification_token}>" />\r\n    </td>\r\n  </tr>\r\n</table>\r\n</form>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (14, '<{if $xoops_notification.show}>\r\n<form name="notification_select" action="<{$xoops_notification.target_page}>" method="post">\r\n<h4 style="text-align:center;"><{$lang_activenotifications}></h4>\r\n<input type="hidden" name="not_redirect" value="<{$xoops_notification.redirect_script}>" />\r\n<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{php}>echo $GLOBALS[''xoopsSecurity'']->createToken();<{/php}>" />\r\n<table class="outer">\r\n  <tr><th colspan="3"><{$lang_notificationoptions}></th></tr>\r\n  <tr>\r\n    <td class="head"><{$lang_category}></td>\r\n    <td class="head"><input name="allbox" id="allbox" onclick="xoopsCheckAll(''notification_select'',''allbox'');" type="checkbox" value="<{$lang_checkall}>" /></td>\r\n    <td class="head"><{$lang_events}></td>\r\n  </tr>\r\n  <{foreach name=outer item=category from=$xoops_notification.categories}>\r\n  <{foreach name=inner item=event from=$category.events}>\r\n  <tr>\r\n    <{if $smarty.foreach.inner.first}>\r\n    <td class="even" rowspan="<{$smarty.foreach.inner.total}>"><{$category.title}></td>\r\n    <{/if}>\r\n    <td class="odd">\r\n    <{counter assign=index}>\r\n    <input type="hidden" name="not_list[<{$index}>][params]" value="<{$category.name}>,<{$category.itemid}>,<{$event.name}>" />\r\n    <input type="checkbox" id="not_list[]" name="not_list[<{$index}>][status]" value="1" <{if $event.subscribed}>checked="checked"<{/if}> />\r\n    </td>\r\n    <td class="odd"><{$event.caption}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n  <{/foreach}>\r\n  <tr>\r\n    <td class="foot" colspan="3" align="center"><input type="submit" name="not_submit" value="<{$lang_updatenow}>" /></td>\r\n  </tr>\r\n</table>\r\n<div align="center">\r\n<{$lang_notificationmethodis}>:&nbsp;<{$user_method}>&nbsp;&nbsp;[<a href="<{$editprofile_url}>" title="<{$lang_change}>"><{$lang_change}></a>]\r\n</div>\r\n</form>\r\n<{/if}>');
INSERT INTO `i83106030_tplsource` VALUES (15, '<{$block.content}>');
INSERT INTO `i83106030_tplsource` VALUES (16, '<{if $priv_enable !== true}>\r\n	<div>Privacy Policy is not enabled</div>\r\n<{/if}>	\r\n\r\n<{if $priv_enable == true && $priv_poltype == ''page''}>\r\n\r\n	<div class="privacy_policy">\r\n		<div align="center"><h1><{$xoops_sitename}>: <{$lang_privacy_policy}></h1></div>\r\n		<p><{$priv_policy}></p>\r\n	</div>\r\n<{/if}>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (17, '<div id="notfound">\n	<h1><{$lang_error_title}></h1>\n	<{if $lang_error_desc && $lang_error_desc != ''''}>\r\n		<div id="http_error_text"><{$lang_error_desc}></div>\r\n	<{/if}>\n	<br />\n	<ul>\n		<li><{$lang_search_our_site}><br />\n			<form id="http_error_searchform" style="vertical-align: middle;" action="<{$xoops_url}>/search.php" method="get">\n				<input name="query" size="14" style="vertical-align: middle;" type="text" />\n				<input name="action" value="results" type="hidden" />\n				<input src="<{$xoops_url}>/images/search2.gif" style="vertical-align: middle;" alt="<{$lang_search}>" onclick="this.form.submit()" type="image" />\n				&nbsp;&nbsp;<a href="<{$xoops_url}>/search.php"><{$lang_advanced_search}></a>\n			</form>\n		</li>\n		<li><{$lang_start_again}></li>\n		<li><{$lang_found_contact}></li>\n	</ul>\n</div>');
INSERT INTO `i83106030_tplsource` VALUES (18, '<table cellspacing="0">\r\n  <tr>\r\n    <td id="usermenu">\r\n      <{if $xoops_isadmin}>\r\n        <a class="menuTop" href="<{$xoops_url}>/admin.php" title="<{$block.lang_adminmenu}>"><{$block.lang_adminmenu}></a>\r\n	    <a href="<{$xoops_url}>/user.php" title="<{$block.lang_youraccount}>"><{$block.lang_youraccount}></a>\r\n      <{else}>\r\n		<a class="menuTop" href="<{$xoops_url}>/user.php"title="<{$block.lang_youraccount}>"><{$block.lang_youraccount}></a>\r\n      <{/if}>\r\n      <a href="<{$xoops_url}>/edituser.php" title="<{$block.lang_editaccount}>"><{$block.lang_editaccount}></a>\r\n      <a href="<{$xoops_url}>/notifications.php" title="<{$block.lang_notifications}>"><{$block.lang_notifications}></a>\r\n      <{if $block.new_messages > 0}>\r\n        <a class="highlight" href="<{$xoops_url}>/viewpmsg.php" title="<{$block.lang_inbox}>"><{$block.lang_inbox}> (<span style="color:#ff0000; font-weight: bold;"><{$block.new_messages}></span>)</a>\r\n      <{else}>\r\n        <a href="<{$xoops_url}>/viewpmsg.php" title="<{$block.lang_inbox}>"><{$block.lang_inbox}></a>\r\n      <{/if}>\r\n      <a href="<{$xoops_url}>/user.php?op=logout" title="<{$block.lang_logout}>"><{$block.lang_logout}></a>\r\n    </td>\r\n  </tr>\r\n</table>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (19, '<form style="margin-top: 0px;" action="<{$xoops_url}>/user.php" method="post">\r\n    <{$block.lang_username}><br />\r\n    <input type="text" name="uname" size="12" value="<{$block.unamevalue}>" maxlength="25" /><br />\r\n    <{$block.lang_password}><br />\r\n    <input type="password" name="pass" size="12" maxlength="32" /><br />\n    <{if $block.rememberme }>\r\n    <input type="checkbox" name="rememberme" value="On" /><{$block.lang_rememberme}><br />\n    <{/if}>\r\n    <input type="hidden" name="xoops_redirect" value="<{$xoops_requesturi}>" />\r\n    <input type="hidden" name="op" value="login" />\r\n    <input type="submit" value="<{$block.lang_login}>" /><br />\r\n    <{$block.sslloginlink}>\r\n</form>\r\n<a href="<{$xoops_url}>/user.php#lost" title="<{$block.lang_lostpass}>"><{$block.lang_lostpass}></a>\r\n\r\n<{if $block.registration }>\r\n<br /><br />\r\n<a href="<{$xoops_url}>/register.php" title="<{$block.lang_registernow}>"><{$block.lang_registernow}></a>\r\n<{/if}>');
INSERT INTO `i83106030_tplsource` VALUES (20, '<form style="margin-top: 0px;" action="<{$xoops_url}>/search.php" method="get">\r\n  <input type="text" name="query" size="14" /><input type="hidden" name="action" value="results" /><br /><input type="submit" value="<{$block.lang_search}>" />\r\n</form>\r\n<a href="<{$xoops_url}>/search.php" title="<{$block.lang_advsearch}>"><{$block.lang_advsearch}></a>');
INSERT INTO `i83106030_tplsource` VALUES (21, '<ul>\r\n  <{foreach item=module from=$block.modules}>\r\n  <li><a href="<{$module.adminlink}>" title="<{$module.pendingnum}> <{$module.lang_linkname}>"><{$module.lang_linkname}></a>: <{$module.pendingnum}></li>\r\n  <{/foreach}>\r\n</ul>');
INSERT INTO `i83106030_tplsource` VALUES (22, '<table cellspacing="0">\r\n  <tr>\r\n    <td id="mainmenu">\r\n      <a class="menuTop<{if $xoops_dirname==''system''}> actlink<{/if}>" href="<{$xoops_url}>/" title="<{$block.lang_home}>"><{$block.lang_home}></a>\r\n      <!-- start module menu loop -->\r\n      <{foreach item=module from=$block.modules}>\r\n      <a class="menuMain<{if $xoops_dirname==$module.directory}> actlink<{/if}>" href="<{$xoops_url}>/modules/<{$module.directory}>/" title="<{$module.name}>"><{$module.name}></a>\r\n        <{foreach item=sublink from=$module.sublinks}>\r\n          <a class="menuSub" href="<{$sublink.url}>" title="<{$sublink.name}>"><{$sublink.name}></a>\r\n        <{/foreach}>\r\n      <{/foreach}>\r\n      <!-- end module menu loop -->\r\n	  <{if $block.priv_enabled == true}>\r\n		  <a class="menuMain<{if $xoops_dirname==''system''}> actlink<{/if}>" href="<{$xoops_url}>/privpolicy.php" title="<{$block.lang_privpolicy}>"><{$block.lang_privpolicy}></a>\r\n	  <{/if}>\r\n    </td>\r\n  </tr>\r\n</table>');
INSERT INTO `i83106030_tplsource` VALUES (23, '  <{if $block.showgroups == true}>\r\n    <table class="outer" cellspacing="0">\r\n  <!-- start group loop -->\r\n  <{foreach item=group from=$block.groups}>\r\n  <tr>\r\n    <th colspan="2"><{$group.name}></th>\r\n  </tr>\r\n\r\n  <!-- start group member loop -->\r\n  <{foreach item=user from=$group.users}>\r\n  <tr>\r\n    <td class="even" valign="middle" align="center"><img src="<{$user.avatar}>" alt="<{$user.name}>''s avatar" width="32px" /><br /><a href="<{$xoops_url}>/userinfo.php?uid=<{$user.id}>" title="<{$user.name}>"><{$user.name}></a></td><td class="odd" width="20%" align="right" valign="middle"><{$user.msglink}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n  <!-- end group member loop -->\r\n\r\n  <{/foreach}>\r\n  <!-- end group loop -->\r\n</table>\r\n<{/if}>\r\n<br />\r\n\r\n<div style="margin: 3px; text-align:center;">\r\n  <img src="<{$block.logourl}>" alt="<{$xoops_sitename}>" border="0" /><br /><{$block.recommendlink}>\r\n</div>');
INSERT INTO `i83106030_tplsource` VALUES (24, '<{$block.online_total}><br /><br /><{$block.lang_members}>: <{$block.online_members}><br /><{$block.lang_guests}>: <{$block.online_guests}><br /><br /><{$block.online_names}> <a href="javascript:openWithSelfMain(''<{$xoops_url}>/misc.php?action=showpopups&amp;type=online'',''Online'',420,350);" title="<{$block.lang_more}>"><{$block.lang_more}></a>');
INSERT INTO `i83106030_tplsource` VALUES (25, '<table cellspacing="1px" class="outer">\r\n  <{foreach item=user from=$block.users}>\r\n  <tr class="<{cycle values="even,odd"}>" valign="middle">\r\n    <td><{$user.rank}></td>\r\n    <td align="center">\r\n      <{if $user.avatar != ""}>\r\n      <img src="<{$user.avatar}>" alt="<{$user.name}>''s avatar" width="32px" /><br />\r\n      <{/if}>\r\n      <a href="<{$xoops_url}>/userinfo.php?uid=<{$user.id}>" title="<{$user.name}>"><{$user.name}></a>\r\n    </td>\r\n    <td align="center"><{$user.posts}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n</table>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (26, '<table cellspacing="1px" class="outer">\r\n  <{foreach item=user from=$block.users}>\r\n    <tr class="<{cycle values="even,odd"}>" valign="middle">\r\n      <td align="center">\r\n      <{if $user.avatar != ""}>\r\n      <img src="<{$user.avatar}>" alt="<{$user.name}>''s avatar" width="32px" /><br />\r\n      <{/if}>\r\n      <a href="<{$xoops_url}>/userinfo.php?uid=<{$user.id}>" title="<{$user.name}>"><{$user.name}></a>\r\n      </td>\r\n      <td align="center"><{$user.joindate}></td>\r\n    </tr>\r\n  <{/foreach}>\r\n</table>\r\n');
INSERT INTO `i83106030_tplsource` VALUES (27, '<table width="100%" cellspacing="1px" class="outer">\r\n  <{foreach item=comment from=$block.comments}>\r\n  <tr class="<{cycle values="even,odd"}>">\r\n    <td align="center"><img src="<{$xoops_url}>/images/subject/<{$comment.icon}>" alt="" /></td>\r\n    <td><{$comment.title}></td>\r\n    <td align="center"><{$comment.module}></td>\r\n    <td align="center"><{$comment.poster}></td>\r\n    <td align="right"><{$comment.time}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n</table>');
INSERT INTO `i83106030_tplsource` VALUES (28, '<form action="<{$block.target_page}>" method="post">\r\n<table class="outer">\r\n  <{foreach item=category from=$block.categories}>\r\n  <{foreach name=inner item=event from=$category.events}>\r\n  <{if $smarty.foreach.inner.first}>\r\n  <tr>\r\n    <td class="head" colspan="2"><{$category.title}></td>\r\n  </tr>\r\n  <{/if}>\r\n  <tr>\r\n    <td class="odd"><{counter assign=index}><input type="hidden" name="not_list[<{$index}>][params]" value="<{$category.name}>,<{$category.itemid}>,<{$event.name}>" /><input type="checkbox" name="not_list[<{$index}>][status]" value="1" <{if $event.subscribed}>checked="checked"<{/if}> /></td>\r\n    <td class="odd"><{$event.caption}></td>\r\n  </tr>\r\n  <{/foreach}>\r\n  <{/foreach}>\r\n  <tr>\r\n    <td class="foot" colspan="2"><input type="hidden" name="not_redirect" value="<{$block.redirect_script}>"><input type="hidden" value="<{$block.notification_token}>" name="XOOPS_TOKEN_REQUEST" /><input type="submit" name="not_submit" value="<{$block.submit_button}>" /></td>\r\n  </tr>\r\n</table>\r\n</form>');
INSERT INTO `i83106030_tplsource` VALUES (29, '<div style="text-align: center;">\r\n<form action="index.php" method="post">\r\n<{$block.theme_select}>\r\n</form>\r\n</div>');
INSERT INTO `i83106030_tplsource` VALUES (30, '<div align="center">\n	<{$block.ml_tag}>\n</div>');

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_users`
-- 

CREATE TABLE `i83106030_users` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `uname` varchar(25) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `user_avatar` varchar(30) NOT NULL default 'blank.gif',
  `user_regdate` int(10) unsigned NOT NULL default '0',
  `user_icq` varchar(15) NOT NULL default '',
  `user_from` varchar(100) NOT NULL default '',
  `user_sig` tinytext NOT NULL,
  `user_viewemail` tinyint(1) unsigned NOT NULL default '0',
  `actkey` varchar(8) NOT NULL default '',
  `user_aim` varchar(18) NOT NULL default '',
  `user_yim` varchar(25) NOT NULL default '',
  `user_msnm` varchar(100) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `posts` mediumint(8) unsigned NOT NULL default '0',
  `attachsig` tinyint(1) unsigned NOT NULL default '0',
  `rank` smallint(5) unsigned NOT NULL default '0',
  `level` tinyint(3) unsigned NOT NULL default '1',
  `theme` varchar(100) NOT NULL default '',
  `timezone_offset` float(3,1) NOT NULL default '0.0',
  `last_login` int(10) unsigned NOT NULL default '0',
  `umode` varchar(10) NOT NULL default '',
  `uorder` tinyint(1) unsigned NOT NULL default '0',
  `notify_method` tinyint(1) NOT NULL default '1',
  `notify_mode` tinyint(1) NOT NULL default '0',
  `user_occ` varchar(100) NOT NULL default '',
  `bio` tinytext NOT NULL,
  `user_intrest` varchar(150) NOT NULL default '',
  `user_mailok` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`uid`),
  KEY `uname` (`uname`),
  KEY `email` (`email`),
  KEY `uiduname` (`uid`,`uname`),
  KEY `unamepass` (`uname`,`pass`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `i83106030_users`
-- 

INSERT INTO `i83106030_users` VALUES (1, '', 'admin', 'admin@localhost.com', 'http://inbox2.local/icms_1.0_final/htdocs/', 'blank.gif', 1210942824, '', '', '', 0, '', '', '', '', '5f4dcc3b5aa765d61d8327deb882cf99', 0, 0, 7, 5, 'impresstheme', 0.0, 1210942824, 'thread', 0, 1, 0, '', '', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_xoopscomments`
-- 

CREATE TABLE `i83106030_xoopscomments` (
  `com_id` mediumint(8) unsigned NOT NULL auto_increment,
  `com_pid` mediumint(8) unsigned NOT NULL default '0',
  `com_rootid` mediumint(8) unsigned NOT NULL default '0',
  `com_modid` smallint(5) unsigned NOT NULL default '0',
  `com_itemid` mediumint(8) unsigned NOT NULL default '0',
  `com_icon` varchar(25) NOT NULL default '',
  `com_created` int(10) unsigned NOT NULL default '0',
  `com_modified` int(10) unsigned NOT NULL default '0',
  `com_uid` mediumint(8) unsigned NOT NULL default '0',
  `com_ip` varchar(15) NOT NULL default '',
  `com_title` varchar(255) NOT NULL default '',
  `com_text` text NOT NULL,
  `com_sig` tinyint(1) unsigned NOT NULL default '0',
  `com_status` tinyint(1) unsigned NOT NULL default '0',
  `com_exparams` varchar(255) NOT NULL default '',
  `dohtml` tinyint(1) unsigned NOT NULL default '0',
  `dosmiley` tinyint(1) unsigned NOT NULL default '0',
  `doxcode` tinyint(1) unsigned NOT NULL default '0',
  `doimage` tinyint(1) unsigned NOT NULL default '0',
  `dobr` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`com_id`),
  KEY `com_pid` (`com_pid`),
  KEY `com_itemid` (`com_itemid`),
  KEY `com_uid` (`com_uid`),
  KEY `com_title` (`com_title`(40))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_xoopscomments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_xoopsnotifications`
-- 

CREATE TABLE `i83106030_xoopsnotifications` (
  `not_id` mediumint(8) unsigned NOT NULL auto_increment,
  `not_modid` smallint(5) unsigned NOT NULL default '0',
  `not_itemid` mediumint(8) unsigned NOT NULL default '0',
  `not_category` varchar(30) NOT NULL default '',
  `not_event` varchar(30) NOT NULL default '',
  `not_uid` mediumint(8) unsigned NOT NULL default '0',
  `not_mode` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`not_id`),
  KEY `not_modid` (`not_modid`),
  KEY `not_itemid` (`not_itemid`),
  KEY `not_class` (`not_category`),
  KEY `not_uid` (`not_uid`),
  KEY `not_event` (`not_event`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `i83106030_xoopsnotifications`
-- 

