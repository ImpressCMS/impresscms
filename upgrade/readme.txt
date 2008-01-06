Upgrade script instructions
--------------------------------------------

Upgrading from XOOPS 2.0.17/2018 (easy way)

   1. Get the update package from the sourceforge file repository

   2. Overwrite your existing files with the new ones


Upgrading from XOOPS 2.0.14/2.0.15/2.0.16/2.0.17/2.0.18 (using the full package)

   1. Move the "upgrade" folder inside the "htdocs" folder (it's been kept out as it's not needed for full installs)

   2. Delete htdocs/mainfile.php, htdocs/install/, htdocs/cache/, htdocs/template_c/ from the mpressCMS1.x package that you just downloaded (not from your server).

   3. Upload the content of the htdocs folder over your existing files
 
   4. Access <your.site.url>/upgrade/ with a browser.

   5. Follow the instructions to update your database

   6. Delete the upgrade folder


Upgrading from any XOOPS ranging from 2.0.7 to 2.0.13.2 (using the full package):

   1. Move the "upgrade" folder inside the "htdocs" folder (it's been kept out as it's not needed for full installs)

   2. Delete htdocs/mainfile.php, htdocs/install/, htdocs/cache/, htdocs/template_c/ from the mpressCMS1.x package that you just downloaded (not from your server).

   3. Upload the content of the htdocs folder over your existing files

   4. Delete the following folders and files from your server (they belong to an old version):
          * class/smarty/core
          * class/smarty/plugins/resource.db.php

   5. Empty the templates_c folder (except index.html)

   6. Ensure the server can write to mainfile.php

   7. Access <your.site.url>/upgrade/ with a browser, and follow the instructions

   8. Write-protect mainfile.php again

   9. Delete the upgrade folder

  10. Update the "system" module from the modules administration interface
