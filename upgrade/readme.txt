Upgrade script instructions
--------------------------------------------
See below the procedures to migrate your XOOPS site for the newest version of ImpressCMS. 
We strongly recommend that you follow all the steps faithfully for your site is updated with security.


Upgrading from XOOPS 2.0.17/2.0.18 (easy way):

1. Get the update package from the sourceforge file repository. 
2. Overwrite your existing files with the new ones. 
3. Ensure the server can write to mainfile.php 
4. Access <your.site.url>/upgrade/ with a browser. 
5. Follow the instructions to update your database. 
6. Write-protect mainfile.php again. 
7. Delete the upgrade folder.
8. Enter the Admin area & then flush your browsers cache (on windows IE & Firefox you can do this by pressing CTRL+F5. MAC & Safari users can press CTRL+R, Linux Users can also press CTRL+R or CTRL+F5 when using Firefox), this will ensure that the new admin interface will display properly.
9. Set the new preferences found in 1.1 by visiting System > Preferences 

--------------------------------------------------------------------------------

Upgrading from any XOOPS ranging from 2.0.7 to 2.0.16 (using the full package):

1. Get the full ImpressCMS package from the sourceforge file repository.
2. Move the "upgrade" folder inside the "htdocs" folder (it's been kept out as it's not needed for full installs).
3. Delete htdocs/mainfile.php, htdocs/install/, htdocs/cache/, htdocs/template_c/ from the ImpressCMS1.x package that you just downloaded (not from your server). 
4. Upload the content of the local htdocs folder over your existing files. 
5. Delete the following folders and files from your server (they belong to an old version): 
   - class/smarty/core 
   - class/smarty/plugins/resource.db.php 
6. Empty the templates_c folder (except index.html). 
7. Ensure the server can write to mainfile.php 
8. Access <your.site.url>/upgrade/ with a browser, and follow the instructions. 
9. Write-protect mainfile.php again. 
10. Delete the upgrade folder.
11. Enter the Admin area & then flush your browsers cache (on windows IE & Firefox you can do this by pressing CTRL+F5. MAC & Safari users can press CTRL+R, Linux Users can also press CTRL+R or CTRL+F5 when using Firefox), this will ensure that the new admin interface will display properly. 12. Update the "system" module from the modules administration interface.
12. Set the new preferences found in 1.1 by visiting System > Preferences

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