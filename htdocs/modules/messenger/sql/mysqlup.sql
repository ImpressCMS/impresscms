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