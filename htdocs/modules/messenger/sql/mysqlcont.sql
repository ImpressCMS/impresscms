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