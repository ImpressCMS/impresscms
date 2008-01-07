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
11. Update the "system" module from the modules administration interface.