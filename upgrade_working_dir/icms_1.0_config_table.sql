-- phpMyAdmin SQL Dump
-- version 2.9.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 17, 2008 at 10:37 AM
-- Server version: 5.0.24
-- PHP Version: 5.1.6
-- 
-- Database: `icms_10_final`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `i83106030_config`
-- 

CREATE TABLE `i83106030_config` (
  `conf_id` smallint(5) unsigned NOT NULL auto_increment,
  `conf_modid` smallint(5) unsigned NOT NULL default '0',
  `conf_catid` smallint(5) unsigned NOT NULL default '0',
  `conf_name` varchar(25) collate latin1_general_ci NOT NULL default '',
  `conf_title` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_value` text collate latin1_general_ci NOT NULL,
  `conf_desc` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_formtype` varchar(15) collate latin1_general_ci NOT NULL default '',
  `conf_valuetype` varchar(10) collate latin1_general_ci NOT NULL default '',
  `conf_order` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`conf_id`),
  KEY `conf_mod_cat_id` (`conf_modid`,`conf_catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=105 ;

-- 
-- Dumping data for table `i83106030_config`
-- 

INSERT INTO `i83106030_config` VALUES (22, 0, 2, 'activation_group', '_MD_AM_ACTVGROUP', '1', '_MD_AM_ACTVGROUPDSC', 'group', 'int', 10);
INSERT INTO `i83106030_config` VALUES (21, 0, 2, 'activation_type', '_MD_AM_ACTVTYPE', '0', '_MD_AM_ACTVTYPEDSC', 'select', 'int', 8);
INSERT INTO `i83106030_config` VALUES (30, 0, 1, 'adminmail', '_MD_AM_ADMINML', 'admin@localhost.com', '_MD_AM_ADMINMLDSC', 'textbox', 'text', 3);
INSERT INTO `i83106030_config` VALUES (52, 0, 2, 'allow_chgmail', '_MD_AM_ALLWCHGMAIL', '0', '_MD_AM_ALLWCHGMAILDSC', 'yesno', 'int', 3);
INSERT INTO `i83106030_config` VALUES (56, 0, 2, 'allow_register', '_MD_AM_ALLOWREG', '1', '_MD_AM_ALLOWREGDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (8, 0, 1, 'anonymous', '_MD_AM_ANONNAME', 'Anonymous', '_MD_AM_ANONNAMEDSC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (74, 0, 7, 'auth_method', '_MD_AM_AUTHMETHOD', 'xoops', '_MD_AM_AUTHMETHODDESC', 'select', 'text', 1);
INSERT INTO `i83106030_config` VALUES (24, 0, 2, 'avatar_allow_upload', '_MD_AM_AVATARALLOW', '0', '_MD_AM_AVATARALWDSC', 'yesno', 'int', 14);
INSERT INTO `i83106030_config` VALUES (28, 0, 2, 'avatar_height', '_MD_AM_AVATARH', '80', '_MD_AM_AVATARHDSC', 'textbox', 'int', 18);
INSERT INTO `i83106030_config` VALUES (29, 0, 2, 'avatar_maxsize', '_MD_AM_AVATARMAX', '35000', '_MD_AM_AVATARMAXDSC', 'textbox', 'int', 20);
INSERT INTO `i83106030_config` VALUES (46, 0, 2, 'avatar_minposts', '_MD_AM_AVATARMP', '0', '_MD_AM_AVATARMPDSC', 'textbox', 'int', 15);
INSERT INTO `i83106030_config` VALUES (27, 0, 2, 'avatar_width', '_MD_AM_AVATARW', '80', '_MD_AM_AVATARWDSC', 'textbox', 'int', 16);
INSERT INTO `i83106030_config` VALUES (35, 0, 2, 'bad_emails', '_MD_AM_BADEMAILS', 'a:1:{i:0;s:15:"impresscms.org$";}', '_MD_AM_BADEMAILSDSC', 'textarea', 'array', 26);
INSERT INTO `i83106030_config` VALUES (37, 0, 1, 'bad_ips', '_MD_AM_BADIPS', 'a:1:{i:0;s:9:"127.0.0.1";}', '_MD_AM_BADIPSDSC', 'textarea', 'array', 42);
INSERT INTO `i83106030_config` VALUES (34, 0, 2, 'bad_unames', '_MD_AM_BADUNAMES', 'a:3:{i:0;s:9:"webmaster";i:1;s:11:"^impresscms";i:2;s:6:"^admin";}', '_MD_AM_BADUNAMESDSC', 'textarea', 'array', 24);
INSERT INTO `i83106030_config` VALUES (12, 0, 1, 'banners', '_MD_AM_BANNERS', '1', '_MD_AM_BANNERSDSC', 'yesno', 'int', 26);
INSERT INTO `i83106030_config` VALUES (40, 0, 4, 'censor_enable', '_MD_AM_DOCENSOR', '0', '_MD_AM_DOCENSORDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (42, 0, 4, 'censor_replace', '_MD_AM_CENSORRPLC', '#OOPS#', '_MD_AM_CENSORRPLCDSC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (41, 0, 4, 'censor_words', '_MD_AM_CENSORWRD', 'a:5:{i:0;s:4:"fuck";i:1;s:4:"shit";i:2;s:4:"cunt";i:3;s:6:"wanker";i:4;s:7:"bastard";}', '_MD_AM_CENSORWRDDSC', 'textarea', 'array', 1);
INSERT INTO `i83106030_config` VALUES (58, 0, 1, 'closesite', '_MD_AM_CLOSESITE', '0', '_MD_AM_CLOSESITEDSC', 'yesno', 'int', 26);
INSERT INTO `i83106030_config` VALUES (59, 0, 1, 'closesite_okgrp', '_MD_AM_CLOSESITEOK', 'a:1:{i:0;s:1:"1";}', '_MD_AM_CLOSESITEOKDSC', 'group_multi', 'array', 27);
INSERT INTO `i83106030_config` VALUES (60, 0, 1, 'closesite_text', '_MD_AM_CLOSESITETXT', 'The site is currently closed for maintenance. Please come back later.', '_MD_AM_CLOSESITETXTDSC', 'textarea', 'text', 28);
INSERT INTO `i83106030_config` VALUES (32, 0, 1, 'com_mode', '_MD_AM_COMMODE', 'nest', '_MD_AM_COMMODEDSC', 'select', 'text', 34);
INSERT INTO `i83106030_config` VALUES (33, 0, 1, 'com_order', '_MD_AM_COMORDER', '0', '_MD_AM_COMORDERDSC', 'select', 'int', 36);
INSERT INTO `i83106030_config` VALUES (13, 0, 1, 'debug_mode', '_MD_AM_DEBUGMODE', '0', '_MD_AM_DEBUGMODEDSC', 'select', 'int', 24);
INSERT INTO `i83106030_config` VALUES (6, 0, 1, 'default_TZ', '_MD_AM_DEFAULTTZ', '0', '_MD_AM_DEFAULTTZDSC', 'timezone', 'float', 10);
INSERT INTO `i83106030_config` VALUES (47, 0, 1, 'enable_badips', '_MD_AM_DOBADIPS', '0', '_MD_AM_DOBADIPSDSC', 'yesno', 'int', 40);
INSERT INTO `i83106030_config` VALUES (44, 0, 5, 'enable_search', '_MD_AM_DOSEARCH', '1', '_MD_AM_DOSEARCHDSC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (39, 0, 3, 'footer', '_MD_AM_FOOTER', 'Powered by ImpressCMS 1.0 &copy; 2007-2008 <a href="http://www.impresscms.org/" rel="external">The ImpressCMS Project</a>', '_MD_AM_FOOTERDSC', 'textarea', 'text', 20);
INSERT INTO `i83106030_config` VALUES (69, 0, 6, 'from', '_MD_AM_MAILFROM', '', '_MD_AM_MAILFROMDESC', 'textbox', 'text', 1);
INSERT INTO `i83106030_config` VALUES (70, 0, 6, 'fromname', '_MD_AM_MAILFROMNAME', '', '_MD_AM_MAILFROMNAMEDESC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (73, 0, 6, 'fromuid', '_MD_AM_MAILFROMUID', '1', '_MD_AM_MAILFROMUIDDESC', 'user', 'int', 3);
INSERT INTO `i83106030_config` VALUES (9, 0, 1, 'gzip_compression', '_MD_AM_USEGZIP', '0', '_MD_AM_USEGZIPDSC', 'yesno', 'int', 16);
INSERT INTO `i83106030_config` VALUES (45, 0, 5, 'keyword_min', '_MD_AM_MINSEARCH', '5', '_MD_AM_MINSEARCHDSC', 'textbox', 'int', 1);
INSERT INTO `i83106030_config` VALUES (3, 0, 1, 'language', '_MD_AM_LANGUAGE', 'english', '_MD_AM_LANGUAGEDSC', 'language', 'other', 4);
INSERT INTO `i83106030_config` VALUES (77, 0, 7, 'ldap_base_dn', '_MD_AM_LDAP_BASE_DN', 'dc=icms,dc=org', '_MD_AM_LDAP_BASE_DN_DESC', 'textbox', 'text', 4);
INSERT INTO `i83106030_config` VALUES (85, 0, 7, 'ldap_domain_name', '_MD_AM_LDAP_DOMAIN_NAME', 'mydomain', '_MD_AM_LDAP_DOMAIN_NAME_DESC', 'textbox', 'text', 12);
INSERT INTO `i83106030_config` VALUES (91, 0, 7, 'ldap_field_mapping', '_MD_AM_LDAP_FIELD_MAPPING_ATTR', 'email=mail|name=displayname', '_MD_AM_LDAP_FIELD_MAPPING_DESC', 'textarea', 'text', 18);
INSERT INTO `i83106030_config` VALUES (84, 0, 7, 'ldap_filter_person', '_MD_AM_LDAP_FILTER_PERSON', '', '_MD_AM_LDAP_FILTER_PERSON_DESC', 'textbox', 'text', 11);
INSERT INTO `i83106030_config` VALUES (89, 0, 7, 'ldap_givenname_attr', '_MD_AM_LDAP_GIVENNAME_ATTR', 'givenname', '_MD_AM_LDAP_GIVENNAME_ATTR_DSC', 'textbox', 'text', 16);
INSERT INTO `i83106030_config` VALUES (83, 0, 7, 'ldap_loginldap_attr', '_MD_AM_LDAP_LOGINLDAP_ATTR', 'uid', '_MD_AM_LDAP_LOGINLDAP_ATTR_D', 'textbox', 'text', 10);
INSERT INTO `i83106030_config` VALUES (82, 0, 7, 'ldap_loginname_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN', 'uid_asdn', '_MD_AM_LDAP_LOGINNAME_ASDN_D', 'yesno', 'int', 9);
INSERT INTO `i83106030_config` VALUES (88, 0, 7, 'ldap_mail_attr', '_MD_AM_LDAP_MAIL_ATTR', 'mail', '_MD_AM_LDAP_MAIL_ATTR_DESC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (78, 0, 7, 'ldap_manager_dn', '_MD_AM_LDAP_MANAGER_DN', 'manager_dn', '_MD_AM_LDAP_MANAGER_DN_DESC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (79, 0, 7, 'ldap_manager_pass', '_MD_AM_LDAP_MANAGER_PASS', 'manager_pass', '_MD_AM_LDAP_MANAGER_PASS_DESC', 'password', 'text', 6);
INSERT INTO `i83106030_config` VALUES (75, 0, 7, 'ldap_port', '_MD_AM_LDAP_PORT', '389', '_MD_AM_LDAP_PORT', 'textbox', 'int', 2);
INSERT INTO `i83106030_config` VALUES (86, 0, 7, 'ldap_provisionning', '_MD_AM_LDAP_PROVIS', '0', '_MD_AM_LDAP_PROVIS_DESC', 'yesno', 'int', 13);
INSERT INTO `i83106030_config` VALUES (87, 0, 7, 'ldap_provisionning_group', '_MD_AM_LDAP_PROVIS_GROUP', 'a:1:{i:0;s:1:"2";}', '_MD_AM_LDAP_PROVIS_GROUP_DSC', 'group_multi', 'array', 14);
INSERT INTO `i83106030_config` VALUES (92, 0, 7, 'ldap_provisionning_upd', '_MD_AM_LDAP_PROVIS_UPD', '1', '_MD_AM_LDAP_PROVIS_UPD_DESC', 'yesno', 'int', 19);
INSERT INTO `i83106030_config` VALUES (76, 0, 7, 'ldap_server', '_MD_AM_LDAP_SERVER', 'your directory server', '_MD_AM_LDAP_SERVER_DESC', 'textbox', 'text', 3);
INSERT INTO `i83106030_config` VALUES (90, 0, 7, 'ldap_surname_attr', '_MD_AM_LDAP_SURNAME_ATTR', 'sn', '_MD_AM_LDAP_SURNAME_ATTR_DESC', 'textbox', 'text', 17);
INSERT INTO `i83106030_config` VALUES (81, 0, 7, 'ldap_users_bypass', '_MD_AM_LDAP_USERS_BYPASS', 'a:1:{i:0;s:5:"admin";}', '_MD_AM_LDAP_USERS_BYPASS_DESC', 'textarea', 'array', 8);
INSERT INTO `i83106030_config` VALUES (93, 0, 7, 'ldap_use_TLS', '_MD_AM_LDAP_USETLS', '0', '_MD_AM_LDAP_USETLS_DESC', 'yesno', 'int', 20);
INSERT INTO `i83106030_config` VALUES (80, 0, 7, 'ldap_version', '_MD_AM_LDAP_VERSION', '3', '_MD_AM_LDAP_VERSION_DESC', 'textbox', 'text', 7);
INSERT INTO `i83106030_config` VALUES (64, 0, 6, 'mailmethod', '_MD_AM_MAILERMETHOD', 'mail', '_MD_AM_MAILERMETHODDESC', 'select', 'text', 4);
INSERT INTO `i83106030_config` VALUES (36, 0, 2, 'maxuname', '_MD_AM_MAXUNAME', '10', '_MD_AM_MAXUNAMEDSC', 'textbox', 'int', 3);
INSERT INTO `i83106030_config` VALUES (49, 0, 3, 'meta_author', '_MD_AM_METAAUTHOR', 'ImpressCMS', '_MD_AM_METAAUTHORDSC', 'textbox', 'text', 6);
INSERT INTO `i83106030_config` VALUES (50, 0, 3, 'meta_copyright', '_MD_AM_METACOPYR', 'Copyright &copy; 2007-2008', '_MD_AM_METACOPYRDSC', 'textbox', 'text', 8);
INSERT INTO `i83106030_config` VALUES (51, 0, 3, 'meta_description', '_MD_AM_METADESC', 'ImpressCMS is a dynamic Object Oriented based open source portal script written in PHP.', '_MD_AM_METADESCDSC', 'textarea', 'text', 1);
INSERT INTO `i83106030_config` VALUES (38, 0, 3, 'meta_keywords', '_MD_AM_METAKEY', 'community management system, CMS, content management, social networking, community, blog, support, modules, add-ons, themes', '_MD_AM_METAKEYDSC', 'textarea', 'text', 0);
INSERT INTO `i83106030_config` VALUES (48, 0, 3, 'meta_rating', '_MD_AM_METARATING', 'general', '_MD_AM_METARATINGDSC', 'select', 'text', 4);
INSERT INTO `i83106030_config` VALUES (43, 0, 3, 'meta_robots', '_MD_AM_METAROBOTS', 'index,follow', '_MD_AM_METAROBOTSDSC', 'select', 'text', 2);
INSERT INTO `i83106030_config` VALUES (17, 0, 2, 'minpass', '_MD_AM_MINPASS', '5', '_MD_AM_MINPASSDSC', 'textbox', 'int', 1);
INSERT INTO `i83106030_config` VALUES (18, 0, 2, 'minuname', '_MD_AM_MINUNAME', '3', '_MD_AM_MINUNAMEDSC', 'textbox', 'int', 2);
INSERT INTO `i83106030_config` VALUES (100, 0, 8, 'ml_captions', '_MD_AM_ML_CAPTIONS', 'English,Francais', '_MD_AM_ML_CAPTIONSDSC', 'textbox', 'text', 15);
INSERT INTO `i83106030_config` VALUES (101, 0, 8, 'ml_charset', '_MD_AM_ML_CHARSET', 'UTF-8,ISO-8859-15', '_MD_AM_ML_CHARSETDSC', 'textbox', 'text', 16);
INSERT INTO `i83106030_config` VALUES (97, 0, 8, 'ml_enable', '_MD_AM_ML_ENABLE', '0', '_MD_AM_ML_ENABLEDEC', 'yesno', 'int', 0);
INSERT INTO `i83106030_config` VALUES (99, 0, 8, 'ml_names', '_MD_AM_ML_NAMES', 'english,french', '_MD_AM_ML_NAMESDSC', 'textbox', 'text', 10);
INSERT INTO `i83106030_config` VALUES (98, 0, 8, 'ml_tags', '_MD_AM_ML_TAGS', 'en,fr', '_MD_AM_ML_TAGSDSC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (62, 0, 1, 'module_cache', '_MD_AM_MODCACHE', '', '_MD_AM_MODCACHEDSC', 'module_cache', 'array', 50);
INSERT INTO `i83106030_config` VALUES (14, 0, 1, 'my_ip', '_MD_AM_MYIP', '127.0.0.1', '_MD_AM_MYIPDSC', 'textbox', 'text', 29);
INSERT INTO `i83106030_config` VALUES (19, 0, 2, 'new_user_notify', '_MD_AM_NEWUNOTIFY', '1', '_MD_AM_NEWUNOTIFYDSC', 'yesno', 'int', 4);
INSERT INTO `i83106030_config` VALUES (20, 0, 2, 'new_user_notify_group', '_MD_AM_NOTIFYTO', '1', '_MD_AM_NOTIFYTODSC', 'group', 'int', 6);
INSERT INTO `i83106030_config` VALUES (103, 0, 2, 'priv_dpolicy', '_MD_AM_PRIVDPOLICY', '0', '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', 33);
INSERT INTO `i83106030_config` VALUES (104, 0, 2, 'priv_policy', '_MD_AM_PRIVPOLICY', '<p>This privacy policy sets out how [website name] uses and protects any information that you give [website name] when you use this website. [website name] is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement. [website name] may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.\r\n</p><p>\r\nThis policy is effective from [date].\r\n</p>\r\n<h2>What we collect</h2>\r\n<p>\r\nWe may collect the following information:\r\n<ul>\r\n<li>name and job title</li>\r\n<li>contact information including email address</li>\r\n<li>demographic information such as postcode, preferences and interests</li>\r\n<li>other information relevant to customer surveys and/or offers</li></ul>\r\n</p>\r\n<h2>What we do with the information we gather</h2>\r\n<p>\r\nWe require this information to understand your needs and provide you with a better service, and in particular for the following reasons:\r\n<ul>\r\n<li>Internal record keeping.</li>\r\n<li>We may use the information to improve our products and services.</li>\r\n<li>We may periodically send promotional email about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>\r\n<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email.</li>\r\n<li>We may use the information to customise the website according to your interests.</li></ul>\r\n</p>\r\n<h2>Security</h2>\r\n<p>\r\nWe are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.\r\n</p>\r\n<h2>How we use cookies</h2>\r\n<p>\r\nA cookie is a small file which asks permission to be placed on your computer''s hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.\r\n</p><p>\r\nWe use traffic log cookies to identify which pages are being used & for authenticating you as a registered member. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.\r\n</p><p>\r\nYou can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website including registration and logging in.\r\n</p>\r\n<h2>Links to other websites</h2>\r\n<p>\r\nOur website may contain links to enable you to visit other websites of interest easily. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.\r\n</p>\r\n<h2>Controlling your personal information</h2>\r\n<p>\r\nYou may choose to restrict the collection or use of your personal information in the following ways:\r\n<ul>\r\n<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li>\r\n<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at [email address]</li></ul>\r\n</p><p>\r\nWe will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen. You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to [address].\r\n</p><p>\r\nIf you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.\r\n</p>', '_MD_AM_PRIVPOLICYDSC', 'textarea', 'text', 34);
INSERT INTO `i83106030_config` VALUES (95, 0, 2, 'rank_height', '_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (96, 0, 2, 'rank_maxsize', '_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (94, 0, 2, 'rank_width', '_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', 21);
INSERT INTO `i83106030_config` VALUES (55, 0, 2, 'reg_disclaimer', '_MD_AM_REGDSCLMR', 'While the administrators and moderators of this site will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to this site express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable.\r\n\r\nYou agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-orientated or any other material that may violate any applicable laws. Doing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. Creating multiple accounts for a single user is not allowed. You agree that the webmaster, administrator and moderators of this site have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent the webmaster, administrator and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised.\r\n\r\nThis site system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above, they serve only to improve your viewing pleasure. The email address is used only for confirming your registration details and password (and for sending new passwords should you forget your current one).\r\n\r\nBy clicking Register below you agree to be bound by these conditions.', '_MD_AM_REGDSCLMRDSC', 'textarea', 'text', 32);
INSERT INTO `i83106030_config` VALUES (54, 0, 2, 'reg_dispdsclmr', '_MD_AM_DSPDSCLMR', '1', '_MD_AM_DSPDSCLMRDSC', 'yesno', 'int', 30);
INSERT INTO `i83106030_config` VALUES (102, 0, 2, 'remember_me', '_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', 29);
INSERT INTO `i83106030_config` VALUES (31, 0, 2, 'self_delete', '_MD_AM_SELFDELETE', '0', '_MD_AM_SELFDELETEDSC', 'yesno', 'int', 22);
INSERT INTO `i83106030_config` VALUES (68, 0, 6, 'sendmailpath', '_MD_AM_SENDMAILPATH', '/usr/sbin/sendmail', '_MD_AM_SENDMAILPATHDESC', 'textbox', 'text', 5);
INSERT INTO `i83106030_config` VALUES (5, 0, 1, 'server_TZ', '_MD_AM_SERVERTZ', '0', '_MD_AM_SERVERTZDSC', 'timezone', 'float', 8);
INSERT INTO `i83106030_config` VALUES (11, 0, 1, 'session_expire', '_MD_AM_SESSEXPIRE', '15', '_MD_AM_SESSEXPIREDSC', 'textbox', 'int', 22);
INSERT INTO `i83106030_config` VALUES (16, 0, 1, 'session_name', '_MD_AM_SESSNAME', 'icms_session', '_MD_AM_SESSNAMEDSC', 'textbox', 'text', 20);
INSERT INTO `i83106030_config` VALUES (1, 0, 1, 'sitename', '_MD_AM_SITENAME', 'ImpressCMS', '_MD_AM_SITENAMEDSC', 'textbox', 'text', 0);
INSERT INTO `i83106030_config` VALUES (2, 0, 1, 'slogan', '_MD_AM_SLOGAN', 'Make a lasting impression ', '_MD_AM_SLOGANDSC', 'textbox', 'text', 2);
INSERT INTO `i83106030_config` VALUES (65, 0, 6, 'smtphost', '_MD_AM_SMTPHOST', 'a:1:{i:0;s:0:"";}', '_MD_AM_SMTPHOSTDESC', 'textarea', 'array', 6);
INSERT INTO `i83106030_config` VALUES (67, 0, 6, 'smtppass', '_MD_AM_SMTPPASS', '', '_MD_AM_SMTPPASSDESC', 'password', 'text', 8);
INSERT INTO `i83106030_config` VALUES (66, 0, 6, 'smtpuser', '_MD_AM_SMTPUSER', '', '_MD_AM_SMTPUSERDESC', 'textbox', 'text', 7);
INSERT INTO `i83106030_config` VALUES (71, 0, 1, 'sslloginlink', '_MD_AM_SSLLINK', 'https://', '_MD_AM_SSLLINKDSC', 'textbox', 'text', 33);
INSERT INTO `i83106030_config` VALUES (61, 0, 1, 'sslpost_name', '_MD_AM_SSLPOST', 'icms_ssl', '_MD_AM_SSLPOSTDSC', 'textbox', 'text', 31);
INSERT INTO `i83106030_config` VALUES (4, 0, 1, 'startpage', '_MD_AM_STARTPAGE', '--', '_MD_AM_STARTPAGEDSC', 'startpage', 'other', 6);
INSERT INTO `i83106030_config` VALUES (63, 0, 1, 'template_set', '_MD_AM_DTPLSET', 'default', '_MD_AM_DTPLSETDSC', 'tplset', 'other', 14);
INSERT INTO `i83106030_config` VALUES (57, 0, 1, 'theme_fromfile', '_MD_AM_THEMEFILE', '0', '_MD_AM_THEMEFILEDSC', 'yesno', 'int', 13);
INSERT INTO `i83106030_config` VALUES (7, 0, 1, 'theme_set', '_MD_AM_DTHEME', 'impresstheme', '_MD_AM_DTHEMEDSC', 'theme', 'other', 12);
INSERT INTO `i83106030_config` VALUES (72, 0, 1, 'theme_set_allowed', '_MD_AM_THEMEOK', 'a:1:{i:0;s:12:"impresstheme";}', '_MD_AM_THEMEOKDSC', 'theme_multi', 'array', 13);
INSERT INTO `i83106030_config` VALUES (23, 0, 2, 'uname_test_level', '_MD_AM_UNAMELVL', '0', '_MD_AM_UNAMELVLDSC', 'select', 'int', 12);
INSERT INTO `i83106030_config` VALUES (10, 0, 1, 'usercookie', '_MD_AM_USERCOOKIE', 'icms_user', '_MD_AM_USERCOOKIEDSC', 'textbox', 'text', 18);
INSERT INTO `i83106030_config` VALUES (53, 0, 1, 'use_mysession', '_MD_AM_USEMYSESS', '0', '_MD_AM_USEMYSESSDSC', 'yesno', 'int', 19);
INSERT INTO `i83106030_config` VALUES (15, 0, 1, 'use_ssl', '_MD_AM_USESSL', '0', '_MD_AM_USESSLDSC', 'yesno', 'int', 30);
