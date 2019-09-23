<?php
// mymenu
define('_MD_A_MYMENU_MYTPLSADMIN','');
define('_MD_A_MYMENU_MYBLOCKSADMIN','Permissions');
define('_MD_A_MYMENU_MYPREFERENCES','Preferences');

// index.php
define("_AM_TH_DATETIME","Time");
define("_AM_TH_USER","User");
define("_AM_TH_IP","IP");
define("_AM_TH_AGENT","AGENT");
define("_AM_TH_TYPE","Type");
define("_AM_TH_DESCRIPTION","Description");

define( "_AM_TH_BADIPS" , 'Bad IPs<br /><br /><span style="font-weight:normal;">Write each IP on a separate line<br />blank means all IPs are allowed</span>' ) ;

define( "_AM_TH_GROUP1IPS" , 'Allowed IPs for Group=1<br /><br /><span style="font-weight:normal;">Write each IP on a separate line.<br />192.168. means 192.168.*<br />blank means all IPs are allowed</span>' ) ;

define( "_AM_LABEL_COMPACTLOG" , "Compact log" ) ;
define( "_AM_BUTTON_COMPACTLOG" , "Compact it!" ) ;
define( "_AM_JS_COMPACTLOGCONFIRM" , "Duplicate (IP,Type) records will be removed" ) ;
define( "_AM_LABEL_REMOVEALL" , "Remove all records" ) ;
define( "_AM_BUTTON_REMOVEALL" , "Remove all!" ) ;
define( "_AM_JS_REMOVEALLCONFIRM" , "All logs will be permanently removed. Are you really sure?" ) ;
define( "_AM_LABEL_REMOVE" , "Remove the records checked:" ) ;
define( "_AM_BUTTON_REMOVE" , "Remove!" ) ;
define( "_AM_JS_REMOVECONFIRM" , "Remove OK?" ) ;
define( "_AM_MSG_IPFILESUPDATED" , "Files for IPs have been updated" ) ;
define( "_AM_MSG_BADIPSCANTOPEN" , "The file for badip cannot be opened" ) ;
define( "_AM_MSG_GROUP1IPSCANTOPEN" , "The file for allowing group=1 cannot be opened" ) ;
define( "_AM_MSG_REMOVED" , "Records are removed" ) ;
define( "_AM_FMT_CONFIGSNOTWRITABLE" , "Make the configs directory writable: %s" ) ;

// advisory.php
define("_AM_ADV_NOTSECURE","Not secure");

define("_AM_ADV_TRUSTPATHPUBLIC","If you can see an image that displays -NG- or the link returns normal page, your ICMS_TRUST_PATH is not placed properly. The best place for ICMS_TRUST_PATH is outside of DocumentRoot. If you cannot do that, you have to put .htaccess (DENY FROM ALL) just under ICMS_TRUST_PATH as the second best way.");
define("_AM_ADV_TRUSTPATHPUBLICLINK","Check php files inside TRUST_PATH are private (it must be 404,403 or 500 error");
define("_AM_ADV_REGISTERGLOBALS","This setting invites a variety of injecting attacks.<br />If you can put .htaccess, edit or create...");
define("_AM_ADV_ALLOWURLFOPEN","This setting allows attackers to execute arbitrary scripts on remote servers.<br />Only administrator can change this option.<br />If you are an admin, edit php.ini or httpd.conf.<br /><b>Sample of httpd.conf:<br /> &nbsp; php_admin_flag &nbsp; allow_url_fopen &nbsp; off</b><br />Else, report it to your administrators.");
define("_AM_ADV_USETRANSSID","Your Session ID will be displayed in anchor tags etc.<br />For preventing from session hi-jacking, add a line into .htaccess in ICMS_ROOT_PATH.<br /><b>php_flag session.use_trans_sid off</b>");
define("_AM_ADV_DBPREFIX","This setting invites 'SQL Injections'.<br />Don't forget turning 'Force sanitizing *' on in this module's preferences.");
define("_AM_ADV_LINK_TO_PREFIXMAN","Go to prefix manager");
define("_AM_ADV_MAINUNPATCHED","Please upload the protector.php file located in the <strong>extras</strong> folder of the ImpressCMS package to ".ICMS_URL."/plugins/preloads/.");
define("_AM_ADV_DBFACTORYPATCHED","Your databasefactory is ready for DBLayer Trapping anti-SQL-Injection");
define("_AM_ADV_DBFACTORYUNPATCHED","Your databasefactory is not ready for DBLayer Trapping anti-SQL-Injection. Some patches are required.");

define("_AM_ADV_SUBTITLECHECK","Check if Protector works well");
define("_AM_ADV_CHECKCONTAMI","Contaminations");
define("_AM_ADV_CHECKISOCOM","Isolated Comments");
?>