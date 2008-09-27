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

 
# Structure de la table `priv_msgsopt`


CREATE TABLE `priv_msgsopt` (
  `userid` mediumint(8) unsigned NOT NULL default '0',
  `notif` tinyint(1) unsigned NOT NULL default '0',
  `resend` tinyint(1) NOT NULL default '0',
  `limite` tinyint(2) default NULL,
  `home` tinyint(2) unsigned NOT NULL default '1',
  `sortname` varchar(15) default NULL,
  `sortorder` varchar(15) default NULL,
  `vieworder` varchar(15) default NULL,
  `formtype` tinyint(1) default NULL,
  PRIMARY KEY  (`userid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;
#