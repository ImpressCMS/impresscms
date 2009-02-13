CREATE TABLE `profile_category` (
  `catid` int(12) unsigned NOT NULL auto_increment,
  `cat_title` varchar(255) NOT NULL default '',
  `cat_description` text NOT NULL,
  `cat_weight` tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catid`)
) TYPE=MyISAM;

CREATE TABLE `profile_field` (
  `fieldid` int(12) NOT NULL auto_increment,
  `catid` int unsigned NOT NULL default '0',
  `field_type` varchar(30) NOT NULL default '',
  `field_valuetype` tinyint(2) unsigned NOT NULL default '0',
  `field_name` varchar(255) NOT NULL default '',
  `field_title` varchar(255) NOT NULL default '',
  `field_description` text NOT NULL,
  `field_required` tinyint(2) unsigned NOT NULL default '0',
  `field_maxlength` tinyint(6) unsigned NOT NULL default '0',
  `field_weight` tinyint(6) unsigned NOT NULL default '0',
  `field_default` text NOT NULL,
  `field_notnull` tinyint(2) unsigned NOT NULL default '0',
  `field_edit` tinyint(2) unsigned NOT NULL default '0',
  `field_show` tinyint(2) unsigned NOT NULL default '0',
  `field_config` tinyint(2) unsigned NOT NULL default '0',
  `field_options` text NOT NULL default '',
  `exportable` int unsigned NOT NULL default 0,
  `step_id` int unsigned NOT NULL default 0,
  PRIMARY KEY  (`fieldid`),
  UNIQUE KEY `field_name` (`field_name`),
  KEY `step` (`step_id`, `field_weight`)
) TYPE=MyISAM;

CREATE TABLE `profile_visibility` (
  `fieldid` int(12) NOT NULL DEFAULT 0,
  `user_group` int(12) NOT NULL DEFAULT 0,
  `profile_group` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fieldid`, `user_group`, `profile_group`),
  KEY `visible` (`user_group`, `profile_group`)
) TYPE=MyISAM;

CREATE TABLE `profile_regstep` (
  `step_id` int unsigned NOT NULL auto_increment,
  `step_name` varchar(255) NOT NULL DEFAULT '',
  `step_intro` text,
  `step_order` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `step_save` tinyint(2) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`step_id`),
  KEY `sort` (`step_order`, `step_name`)
) Type=MyISAM;

CREATE TABLE `profile_profile` (
  `profileid` int(12) unsigned NOT NULL auto_increment,
  `newemail` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`profileid`)
) TYPE=MyISAM;