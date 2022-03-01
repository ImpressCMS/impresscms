# ImpressCMS ChangeLog

## ImpressCMS 1.4.4
* Date : 01 Mar 2022
* DB Version: 46
* Build Version : 104

This release fixes a security vulnerability that was found in ImpressCMS 1.4.3. 

### Security Fix
Applying the filters to inner elements of arrays by @skenow https://github.com/ImpressCMS/impresscms/pull/1162


## ImpressCMS 1.4.3
* Date : 06 Feb 2022
* DB Version: 46
* Build Version : 103

This release contains mainly fixes for several security vulnerabilities that where found during HackerOne security Checks. 

### What's Changed
* Changing filter method for request_uri to filter_sanitize_string by @skenow in https://github.com/ImpressCMS/impresscms/pull/1136
* Fixed display of allowed admin IPs in protecter admin area by @skenow in https://github.com/ImpressCMS/impresscms/pull/1154
* Making sure protector gets installed during the site installation by @skenow in https://github.com/ImpressCMS/impresscms/pull/1137
* Fixed some warnings and notices in installer for newer PHP versions by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/882
* Protector get_magic_quotes_gpc fix for php 7.4 by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/884
* Smiles in misc.php now are escaped by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/890
* Fix "#881 trying to send mails with SMTP auth gives missing smtp class" by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/889
* Added exception handler by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/888
* Fixed bug when handlers from module separate files cant be loaded by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/887
* Fixes 'Notice: Only variables should be passed by reference in /home/vagrant/impresscms/htdocs/libraries/icms/config/Handler.php on line 237' by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/886
* Fixed bug when admin menu can't regenerate when module folder is removed before uninstalling by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/897
* Fixed syntax error in include/registerform.php by @MekDrop in https://github.com/ImpressCMS/impresscms/pull/896
* fix vulnerability in autoloader by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/913
* block path traversal in image editor, transform .. to _ by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/915
* Fixes/ipf table filtering - limitsel missing POST value by @skenow in https://github.com/ImpressCMS/impresscms/pull/937
* Adjusted template file inclusion for correct path. Fixes #603 by @skenow in https://github.com/ImpressCMS/impresscms/pull/944
* Increase input sanitizing for system module and submodules by @skenow in https://github.com/ImpressCMS/impresscms/pull/943
* Dev/jquery inclusion by @skenow in https://github.com/ImpressCMS/impresscms/pull/935
* Fix for modules admin; user language files - fix #948 by @skenow in https://github.com/ImpressCMS/impresscms/pull/949
* Update release_notes.md by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1058
* Added filtering to the input in setSortOrder in icms_ipf_table by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/966
* filter url variable in findusers.php by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/967
* Remove the old FCKEditor - no longer supported by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/833
* add CKEditor 4.17.1 by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1095
* Protector updates - PHP8 compatibility, update and remove legacy code by @skenow in https://github.com/ImpressCMS/impresscms/pull/1098
* Preparations for the 1.4.3 RC release by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1099
* Add a default parameter to addSlashes by @fiammybe in https://github.com/ImpressCMS/impresscms/pull/1108


**Full Changelog**: https://github.com/ImpressCMS/impresscms/compare/v1.4.2...v1.4.3

## ImpressCMS 1.4.2
Date: 24 Dec 2020
DB Version: 45
Build Version: 100

This release fixes several bugs that were found during the HackerOne initial penetration test run on the 1.4.1 release. Some improvements and bugfixes are present as well.

### Fixes
 - 574 Test 1.4 on PHP 7.4 PHP7 (fiammybe)
 - 692 Include new version of profile PHP7 (fiammybe)
 - 845 PHP 7.4 : access array offset on value of type null in include/functions.php 1037 php 7.4 (fiammybe)
 - 852 anti-clickjacking security vulnerability (report #1055589 by jrckmcsb on HackerOne) (fiammybe)
 - 825 Improve path sanitizing bug security vulnerability (MekDrop)
 - 814 Better sanitize database queries in installer bug  (report #983710 by solov9ev on HackerOne) (fiammybe)
 - 637 Notice on admin pages in PHP 7.4 duplicate php 7.4 (fiammybe)
 - 843 Fix the amount of cookies (fiammybe)
 - 805 Missing templates in system module (skenow)
 - 838 Remove whitesource config (Mekdrop)
 - 834 + 836 Limit maximum length of password  (report #1033373	by f1v3 on HackerOne) (fiammybe)
 - 821 Fixed possible file system exposing due language cookie on installer (MekDrop)
 - 812 Prevents using submitted filenames with ../ for controller (report #1035311 by siva12 on HackerOne) (MekDrop)
 - 815 Better sanitize database queries in installer (report #983710 by solov9ev on HackerOne) (fiammybe)
 - 811 Remove phpopenid example folder bug (report #1042838 by hackerone_success on HackerOne) (fiammybe)
 - 810 more strict comparison of variables  (report #1036883 by hodorsec on HackerOne) (fiammybe)
 - 806 Include the missing templates for the image manager (skenow)
 - 603 Issue with image inclusion on TinyMCE (fiammybe)

### Improvements
 - 636 errors in form fields on admin account creation page of the installer (fiammybe)
 - 848 Cleanup deprecated functions in functions.php (fiammybe)
 - 694 remove the icms_banner reference. No longer present (fiammybe)

## ImpressCMS 1.4.1
Date: 07 Jul 2020
DB Version: 45
Build Version: 98

This release fixes several bugs that were present in the 1.4.0 release, some of them with security impact.

### Fixes
 - Stored XSS on ImpressCMS 1.4.0 ( #659 ) @Mekdrop
 - Existence of banners folder results in errors ( #600 ) @fiammybe
 - module admin menu is not shown in 1.4 ( #604 ) @skenow
 - ImageManager : admin can no longer preview images ( #590 ) @skenow
 - Fatal error during installation at page_tablescreate.php ( #576 ) @skenow
 - Test 1.4 on PHP 7.3 ( #573 ) @fiammybe
 - Login in Chrome points to blank page ( #100 ) @fiammybe

## ImpressCMS 1.4.0
Date: 19 Dec 2019
DB Version: 45
Build Version: 96

### Improvements
- curl extension in installer now is requirement not optional (#530) @MekDrop
- PHP7 improvements based on mamba7x PR (#507) @fiammybe
- make expiration header dynamic in the past (#504) @fiammybe
- check mysql using PDO now (#487) @fiammybe
- Add a warning when PHP used is below 7.2

### Fixes
- Move prototype inclusion so trust_path creation works fixes #569 (#571) @skenow
- Fixed PathStuffController's constructor (#528) @MekDrop
- Fixed suppressed warning if variable $options['folderName'] is undefined or empty when creating theme (1.4.x) (#510) @MekDrop
- Fixed function signatures in icms_image_Handler (1.4) (#512) @MekDrop
- Fixed installer collation selection (#529) @MekDrop
- Fix the template handling in the system module (#503) @fiammybe
- Add a warning when PHP used is below 7.2

### Update
- Protector update for PDO SQL sanitizing Close #496 (#497) @skenow
- Update Protector for PHP7 (#492) @skenow
- Update php requirements to 5.6 (#505) @fiammybe
- Update of Smarty to 2.6.31 (the latest 2.x release)
- Update of CSS-tidy to work in PHP7
- PHPMailer update to 5.2.7
- PHPOpenID updated for better PHP7 compatibility

### Removed
- Removed installation_notify (#566) @MekDrop
- Remove admin template folder in system module on upgrade (#509) @fiammybe


## ImpressCMS 1.3.11
Date: 08 dec 2018
DB Version: 44
Build Version: 91

### Security
Fix XSS vulnerabilities in installer (as found by Omar Kurt, security researcher at Netsparker (https://www.netsparker.com)

### Improvements
109 - Add extra metadata types property and itemprop
121 - System module now shows the correct version number after install
316 - Add extra languages in installer and core

### Fixes
102 - pagination in the backend generates wrong URLs
116 - Update the links to our website
119 - Update system requirements in installer
117 - update links to translations in installer

### Update
296 - Update HTMLPurifier to 4.10
297 - Update GeSHI to 1.0.8.13
299 - Update jQuery to 3.3.1
125 - Upgrade PHPMailer to 5.2.26

## ImpressCMS 1.3.10
Date: 30 december 2016
DB Version: 43
Build Version: 82

### Fixes
913 - Comment preview loses text of comment
930 - correct the link to the adsense wiki pages
925 - Illegal string offset 'options' core/datafilter in PHP 5.6
919 - System imagemanager clone UI-side feature redirects to invalid URL
922 - Templates for Adsenses ACP cannot be overridden

### Security
927 - SSRF vulnerability in image manager
931 - Vulnerability in PHPMailer 5.2.18

## ImpressCMS 1.3.9
Date: 2 March 2016
DB Version: 43
Build Version: 80

### Improved:
809 : Move minimal PHP version to PHP 5.5
884 : PHP 5.4 Strict errors
751 : Migrating to Universal Google Analytics
868 : Duplicate entry in HTMLValidator class

### Fixed:
898 : Database patch message for Formulise module always remains
889 : Missing definition for for Non-PDO users
881 : Check all button in Group administration does not work consistently

### Update:
877 : Update HTMLPurifier to v4.7.0
807 : Upgrade PHPMailer
