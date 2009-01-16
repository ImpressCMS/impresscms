# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Apr 26, 2004 at 04:23 AM
# Server version: 3.23.56
# PHP Version: 4.3.4
# 
# Database : `xoops2`
# 

# --------------------------------------------------------


# Structure de la table `priv_msgs`    

CREATE TABLE `priv_msgs` (
  `msg_id` mediumint(8) unsigned NOT NULL auto_increment,
  `msg_pid` mediumint(8) unsigned NOT NULL default '0',
  `msg_image` varchar(100) default NULL,
  `subject` varchar(255) NOT NULL default '',
  `from_userid` mediumint(8) unsigned NOT NULL default '0',
  `to_userid` mediumint(8) unsigned NOT NULL default '0',
  `msg_time` int(10) unsigned NOT NULL default '0',
  `msg_text` text NOT NULL,
  `read_msg` tinyint(1) unsigned NOT NULL default '0',
  `reply_msg` tinyint(1) unsigned NOT NULL default '0',
  `anim_msg` varchar(100) default NULL,
  `cat_msg` mediumint(8) NOT NULL default '1',
  `file_msg` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`msg_id`),
  KEY `to_userid` (`to_userid`),
  KEY `msgidfromuserid` (`msg_id`,`from_userid`)
) TYPE=MyISAM; 

#