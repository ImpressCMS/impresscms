CREATE TABLE `profile_visibility` (
  `fieldid` int(12) NOT NULL DEFAULT 0,
  `user_group` int(12) NOT NULL DEFAULT 0,
  `profile_group` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fieldid`, `user_group`, `profile_group`),
  KEY `visible` (`user_group`, `profile_group`)
) TYPE=MyISAM;
