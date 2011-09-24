Upgrade script instructions
--------------------------------------------
See below the procedures to migrate your XOOPS or ImpressCMS site for the newest version of ImpressCMS.
We strongly recommend that you follow all the steps faithfully for your site is updated with security.

1. Get the xoops-or-impresscms_1.0-to-impresscms-1.1 package from the sourceforge file repository.
2. Copy the content of htdocs/ over your existing files.
3. On your server, delete the file cache/adminmenu.php
4. Access <your.site.url>/upgrade/ with a browser.
5. Follow the instructions to update your database.
6. You will then be asked to enter the admin area will see a message saying you are entering the admin area for the first time, click on the Submit button.
7. You will then be asked to Update the System module. Follow the instructions.
8. Flush your browsers cache (on windows IE & Firefox you can do this by pressing CTRL+F5. MAC & Safari users can press CTRL+R, Linux Users can also press CTRL+R or CTRL+F5 when using Firefox).
9. A few folders were moved to other place in ImpressCMS structure to introduce more logic in the structure. Thus, you will need to remove these folders and files from your server:
   a) htdocs/class/smarty
   b) htdocs/class/phpmailer
   c) htdocs/class/xoopseditor/dhtmltext
   d) htdocs/class/xoopseditor/dhtmltextarea
   e) htdocs/class/xoopseditor/textarea
   f) htdocs/class/xoopseditor/readme.txt
   g) htdocs/class/xoopseditor/sampleform.inc.php
   h) htdocs/class/xoopseditor/xoopseditor.inc.php
   i) htdocs/class/xoopseditor/xoops_version.php
   j) htdocs/include/calendar-blue.css
   k) htdocs/include/calendar.js
   l) htdocs/modules/system/blocksadmin/blockspadmin.php
   m) htdocs/modules/system/blocksadmin/pblockform.php
   n) htdocs/themes/impresstheme_light/css
   o) htdocs/themes/impresstheme_light/xotpl
10. Enjoy !

==New Features in 1.1==
For a complete list of new features, visit http://wiki.impresscms.org/index.php?title=ImpressCMS_1.1_Features
Admin Preferences
* Authorization
      * Enable OpenID (NO)
* Content - all new options
      * Default page (none)
      * Display navigation menu on user side (YES)
      * Display related pages (YES)
      * Show poster and published info (YES)
* General
      * Use local date/time functions (NO)
      * Default editor (DHTML)
      * Enable editors (none selected)
      * Use CAPTCHA in comment forms (NO)
* Meta + Footer
      * Google Meta information (none)
      * Enable Google Analytics (NO)
      * Google Analytics id (empty)
* Personalization - all new options
      * Admin left logo (ImpressmCMS)
      * Admin left logo link URL (site url)
      * Admin left logo link title (ImpressCMS)
      * Admin right logo (none)
      * Admin right logo link URL (none)
      * Admin right logo link title (none)
      * Admin News feed URL (http://community.impresscms.org/modules/smartsection/backend.php)
      * Edit and Remove blocks from user side? (YES)
      * Prevent multiple login from same user? (NO)
      * Multilogin redirection message
      * Protect email addresses against SPAM? (NO)
      * Font used in email address protection
      * Font size used in email address protection
      * Font color used in email address protection
      * Shadow color used in email address protection
      * X offset of shadow used in email address protection
      * Y offset for shadow used in email address protection
      * Truncate long URLs ? (NO)
      * URL maximum length
      * Amount of starting characters
      * Amount of ending characters
      * Show ImpressCMS Project drop down menu? (YES - same as before)
      * Use Hide content tag? (NO)
      * Use Extended Calendar with Jalali? (NO)
* User Settings
      * Security level (none - same as old method)
      * Use CAPTCHA for registration (OFF)
      * Send Welcome message (NO)
      * Welcome message content
      * Allow signature (YES - same as before)
      * Allow images and HTML in signature (YES - same as before)
      * Max length of signature (255 - same as before)
      * Allow Gravatar (YES)
      * Encryption type (MD5 - old method)