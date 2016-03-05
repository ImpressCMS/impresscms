Upgrade script instructions
--------------------------------------------
See below the procedures to migrate your XOOPS or ImpressCMS site for the newest version of ImpressCMS.
We strongly recommend that you follow all the steps faithfully for your site is updated with security.

1. Get the xoops-or-impresscms_1.0-to-impresscms-2.0 package from the sourceforge file repository.
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

== New Features in ImpressCMS 2.0 ==
For a complete list of new features, visit https://impresscmsdev.assembla.com/spaces/dW4voyNP0r4ldbeJe5cbLr/tickets?report[id]=0&report[milestone_id_cond]=0&report[milestone_id_val][]=585723&report[title]=ImpressCMS+2.0+Tickets