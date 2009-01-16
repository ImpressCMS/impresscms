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


#
# Table structure for table `priv_msgscat`
#

CREATE TABLE `priv_msgscat` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `uid` mediumint(8) default NULL,
  `ver` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `pid` (`pid`)
) TYPE=MyISAM;


   


# Structure de la table `priv_msgscont`


CREATE TABLE `priv_msgscont` (
  `ct_userid` mediumint(10) unsigned NOT NULL default '0',
  `ct_contact` mediumint(10) unsigned NOT NULL default '0',
  `ct_name` varchar(60) NOT NULL default '',
  `ct_uname` varchar(25) NOT NULL default '',
  `ct_regdate` int(10) unsigned NOT NULL default '0',
  KEY `userid` (`ct_userid`)
) TYPE=MyISAM;

#
 
# Structure de la table `priv_msgsopt`


CREATE TABLE `priv_msgsopt` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `notif` tinyint(1) unsigned NOT NULL default '0',
  `resend` tinyint(1) NOT NULL default '0',
  `limite` tinyint(2) default NULL,
  `home` tinyint(1) unsigned NOT NULL default '1',
  `sortname` varchar(15) default NULL,
  `sortorder` varchar(15) default NULL,
  `vieworder` varchar(15) default NULL,
  `formtype` tinyint(1) default NULL,
  PRIMARY KEY  (`userid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;
#

# Structure de la table `priv_msgsup`

CREATE TABLE `priv_msgsup` (
  `msg_id` mediumint(8) unsigned NOT NULL auto_increment,
  `u_id` mediumint(8) default NULL,
  `u_name` varchar(30) NOT NULL default '',
  `u_mimetype` varchar(30) NOT NULL default '',
  `u_file` varchar(100) default NULL,
  `u_weight` smallint(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`msg_id`)
) TYPE=MyISAM;

#        