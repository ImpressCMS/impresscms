# ImpressCMS ChangeLog
## ImpressCMS 2.0.3

* Date : 12 April 2026
* DB Version : 48
* Build Version : 118

### What's Changed
* fix:the DB update to v48 is now correctly positioned by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1619
* fix:Correct condition in previewTarea by @skenow in https://github.com/ImpressCMS/impresscms/pull/1664
* feat:Add conditional rendering for theme selection selector by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1669
* feat:Add default escape characters to icms_core_DataFilter::addSlashes for backwards compatibility by @Copilot in https://github.com/ImpressCMS/impresscms/pull/1675
* feat:Add copilot agent instructions by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1690
* Fix:1687 remove unnecessary comment in admin tpl by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1692
* Feat:Add locale constants to translation files to improve date and time display by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1691
* fix:apply deterministic refactor of regex patterns to counter ReDoS by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1671
* chore:Finalizing 2.0.3 release by @fiammybe

### Previous Versions
#### 2.0.3 Beta (Build 117)
* Date : 05 April 2026
* Changes from all beta fixes

**Full Changelog**: https://github.com/ImpressCMS/impresscms/compare/v2.0.2...v2.0.3
## ImpressCMS 2.0.2

* Date : 01 October 2025
* DB Version : 48
* Build Version : 116

### What's Changed
### Bugfixes
* fix:reference to non-existant properties in templated form elements by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1646
* Fix scope issues with global variables in ACP admin pages by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1645
* Fix 1551 admin theme overrides use correct theme by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1654
* Fix 1550 db collation prefill in mysql8+ by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1655
* fix:templated checkbox doesn't take checked state into account by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1651
* fix:github version check now handles newer installed versions than latest on github by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1648
* In some cases, the update to version 48 was not persisted in the DB by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1616
* icms_core_Object cleanVars and getVar errors with empty arrays by @skenow in https://github.com/ImpressCMS/impresscms/pull/1623
* Remove duplicate code from icms_core_object by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1625
* Search for user URL fixed by @skenow in https://github.com/ImpressCMS/impresscms/pull/1627
### New
* Add a security policy to the repository by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1644
* Testing for icms::$module before trying to check DB version by @skenow in https://github.com/ImpressCMS/impresscms/pull/1642
* chore:upgrade to GeSHI 1.0.9.1 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1647
* feat:Core versioncheck using GitHub by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1631
* chore:no need to check for PHP_VERSION_ID anymore by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1652
* Add function to count files in a directory to icms_core_Filesystem by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1626
* Adding automatic update of the system module by @skenow in https://github.com/ImpressCMS/impresscms/pull/1629
* Templated form elements by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1584


## ImpressCMS 2.0.1
* Date : 08 Jan 2025
* DB Version: 48
* Build Version : 113

Yes, a quick update to fix an embarassing typo that broke the system module.
### What's Changed
#### Bugfixes
* Fix typo by @fiammybe in https://github.com/ImpressCMS/impresscms/issues/1614

## ImpressCMS 2.0.0
* Date : 06 Jan 2025
* DB Version: 48
* Build Version : 112

ImpressCMS 2.0.0 final. This release rises the minimal PHP version to PHP 7.4, and supports up to PHP 8.4 included.
### What's Changed
#### Bugfixes
* Fix mailuser by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1288
* Fixes DB interfaces incompatibilies between different PHP versions by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1321
* Fixes DB crash if DB encoding value is empty by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1322
* Fix: notice undefined index  utf8 in htdocs/install/page_dbsettings.php on line 138 by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1323
* Fix XSS via DB_CHARSET parameter (H1 #1825770) by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1381
* Fixed #1345 - 'undefined constant in PHP 8 on install/page_tablesfill.php page' by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1388
* Fixed bug when saving system preference by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1389
* Fixed #1090 - user creating and editing by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1390
* Including necessary language file to prevent installation errors in PHP8 by @skenow in https://github.com/ImpressCMS/impresscms/pull/1396
* Fix ternary expression for php8 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1397
* Csstidy warning update - include check for unlock file by @skenow in https://github.com/ImpressCMS/impresscms/pull/1416
* Errors in the Nederlands install language files by @skenow in https://github.com/ImpressCMS/impresscms/pull/1415
* 1.5.x php7+ by @skenow in https://github.com/ImpressCMS/impresscms/pull/1430
* Return correct types for custom session handlers by @skenow in https://github.com/ImpressCMS/impresscms/pull/1464
* Fixes fatal error in googleanalytics preload by @skenow in https://github.com/ImpressCMS/impresscms/pull/1487
* Image editor fixes - crop, resize, filter plugins by @skenow in https://github.com/ImpressCMS/impresscms/pull/1489
* Security fix : upgrade Jquery 3.7.0 & jQuery UI 1.13.2 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1490
* Fix 1.5.x symlink errors by @skenow in https://github.com/ImpressCMS/impresscms/pull/1498
* fix indirect variable handling in IPF select form element by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1574
* fix the module update by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1572
* Remove old columns from the users table Resolves #1561 by @skenow in https://github.com/ImpressCMS/impresscms/pull/1588
* handle if columns to remove from users table have already been removed by @skenow in https://github.com/ImpressCMS/impresscms/pull/1590
* Remove content file and delete during an upgrade if the module is not installed by @skenow in https://github.com/ImpressCMS/impresscms/pull/1592
* Removing extra code block for users table fields by @skenow in https://github.com/ImpressCMS/impresscms/pull/1591
* Convert to current global for $icmsModule by @skenow in https://github.com/ImpressCMS/impresscms/pull/1595
* Small bug fixes by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1594
* fix: Improve Database compatibility with PHP 7.4 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1597
* fix:add array declarations to installer  by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1604
* Fix: Add missing css to admin css by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1602
* fix:the $icmsModule mentioned here was a local variable and should no… by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1598
* Fix:Fix the CKEditor Image Manager that couldn't find WideImage by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1601
* fix:make addslashes parameter nullable by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1600

#### Updates
* upgrade cssTidy to 2.0.3, with support for PHP 8 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1398
* upgrade simplepie to 1.8.0 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1405
* Update 1.5.x mimunum requirements by @skenow in https://github.com/ImpressCMS/impresscms/pull/1409
* Updating PHPMailer for PHP8 support by @skenow in https://github.com/ImpressCMS/impresscms/pull/1453
* PHP8 compliance updates by @skenow in https://github.com/ImpressCMS/impresscms/pull/1459
* Update to HTMLPurifier for PHP8 compliance by @skenow in https://github.com/ImpressCMS/impresscms/pull/1458
* Updating WideImage for PHP8 compliance by @skenow in https://github.com/ImpressCMS/impresscms/pull/1465
* Update to HTMLPurifier 4.15 by @skenow in https://github.com/ImpressCMS/impresscms/pull/1492
* Update language constants for users by @skenow in https://github.com/ImpressCMS/impresscms/pull/1544
* upgrade Ckeditor 4.22.1 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1570
* Upgrade PHPMailer to PHPMailer 6.9.3 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1596

#### Improvements
* improve theme selector by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1485
* Move analytics code to preload by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1429
* Smiley adminstration input filtering by @skenow in https://github.com/ImpressCMS/impresscms/pull/1500
* Updates to DataFilter - string filters for PHP8 and signature filtering by @skenow in https://github.com/ImpressCMS/impresscms/pull/1507
* bool to countable in module object by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1510
* Format code, use strlen instead of sizeof on a string by @skenow in https://github.com/ImpressCMS/impresscms/pull/1517
* Date notation fixes for europe in Dutch translations by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1524
* Remove data for bannerclient from install  by @skenow in https://github.com/ImpressCMS/impresscms/pull/1558
* Remove banner tables and config item by @skenow in https://github.com/ImpressCMS/impresscms/pull/1559
* cleanup icms.css by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1563
* Third batch of $icmsModule removals by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1555
* Update version.php to new 2.0.0 beta 2 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1554
* Icmsmodule cleanup part2 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1567
* Remove use of $icmsModule in notification Handler.php by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1576
* some last lingering $icmsModule uses by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1573
* Adding codeclimate configuration by @skenow in https://github.com/ImpressCMS/impresscms/pull/1582
* Adding automatic upgrades for 2 core tables by @skenow in https://github.com/ImpressCMS/impresscms/pull/1585


#### 🚀 Features
* Cookie hardening by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1331
* get installer working on PHP 8.1 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1476
* Making sure password reset key is unique and temporary by @skenow in https://github.com/ImpressCMS/impresscms/pull/1527
* language switcher refactoring by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1496
* Add current theme info into the theme block by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1513

#### 🧰 Maintenance
* Updated branch references & readme by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1223
* Delete htdocs/editors/tinymce directory by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1273
* Remove openid by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1274
* align syntax of DB interface and mysql implementation with PDO by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1287
* prepare for 1.5.0 beta by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1286
* Cleanup all deprecated files and functions in the core by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1320
* Remove get_magic_quotes_gpc calls by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1327
* removing files from previous versions that are no longer there by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1330
* replace create_function with anonymous function by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1342
* Changes to make the upgrade actually work by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1344
* Implementing new test for TinyMCE and restoring the tests for FCKeditor by @skenow in https://github.com/ImpressCMS/impresscms/pull/1351
* Remove Slack badge from README in 1.5.x branch by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/1354
* Adapt install texts for PHP requirements by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1483
* Remove all references to banners by @skenow in https://github.com/ImpressCMS/impresscms/pull/1509
* Cleanup social provider list by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1553
* replace $icmsModule with icms::$module in about page by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1497

**Full Changelog**: https://github.com/ImpressCMS/impresscms/compare/v1.4.6...v2.0.0
