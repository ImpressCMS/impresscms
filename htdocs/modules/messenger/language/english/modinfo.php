<?php
// Nom du module
define('_MI_MP_NAME','MP Manager');

// Description du module
define('_MI_MP_DESC','Private Messaging Management');

// Nom des menus
define('_MI_MP_ADMENU0','Index');
define('_MI_MP_ADMENU1','Sort');
define("_MI_MP_ADMENU2","Write");
define("_MI_MP_ADMENU3","Purge");
define("_MI_MP_ADMENU4","Notification");
define("_MI_MP_ADMENU5","Stats");
define("_MI_MP_ADMENU6","Folders");
define("_MI_MP_ADMENU7","Wink");
define("_MI_MP_ADMENU8","Rights");

//menu utilisateur
define("_MPMANAGER_MI_MES","Message");
define("_MPMANAGER_MI_CONT","Contact");
define("_MPMANAGER_MI_FILE","File");
define("_MPMANAGER_MI_OPTION","Option");

//block
define("_BL_MP_NEW","New message");
define("_BL_MP_CONT","My Contacts");

//form
define("_PM_FALALERT","Storage Alert (in bytes)");
define("_PM_FALALERTCOM","Will inform you if the threshold of your database reaches this value.");
define("_PM_ALERTBOITE","Full mailbox Alert");
define("_PM_COMALERTBOITE","Will inform the users by a Css popup which them box is full.");

define("_PM_ALERTCOOK","Frequency of Alert");
define("_PM_COMALERTCOOK","Number of minutes before displaying the alert again / 0 for display the alert as soon as a new PM arrives.");

define("_PM_FALOPT","Optimize messages database");
define("_PM_USEALERT","Max. number of messages for your members");
define("_PM_FILEMAX","Max. number of folders for your members");
define("_PM_NEWMSG","Alert for new messages");
define("_PM_POPUP","Css Popup");
define("_PM_HPOPUP","Center the Popup / High edge");
define("_PM_LPOPUP","Center the Popup / Left edge");
define("_PM_TEXTE","Text");
define("_PM_IMAGE","Image");
define("_PM_SON","Sound");
define("_PM_ANIM","Animation");
define("_PM_FALOPTCOM","Will optimize the messages database after each deletion of message");
define("_PM_CSSPBACK","CSS Window / Background color");
define("_PM_CSSPTEXT","CSS Window / Text color");
define("_PM_CSSBTEXT","Text new messages / Text color");
define("_PM_CSSBBACK","Text new messages / Background color");
define("_PM_CLINOEIL","Activate Winks");
define("_PM_SENDUSER","Max. simultaneous members to be selected for a message");
define("_PM_NOTIF","Activate mail notification");
define("_PM_DESC_NOTIF","Can be deactivated individually by each member");
define("_MP_WYSIWYG","Choose a Wysiwyg Editor");
define("_MP_WYSIWYG_DESC","The user will be able to choose between the editors to select.");
define("_PM_CORP_NOTIF","Mail body");
define("_PM_CORP_DESC","
<b>Usefull tags</b> :<br /><span class='small'>
{X_UNAME} to display username<br />
{X_ADMINMAIL} to display webmaster's mail<br />
{X_SITENAME} to display sitename<br />
{X_SITEURL} to display website's url<br />
{X_LINK} to display a link to the inbox</span>");
define("_MP_AUTO","Automessage auto");
define("_MP_AUTO_DESC","Automatically send a message to all your new members");
define("_MP_AUTO_SUBJECT","Subject for automessage");
define("_MP_AUTO_TEXT","Text for automessage");

define("_MP_NOTIF_MAIL","Hello {X_UNAME},

You received a new message in your Inbox located on  {X_SITENAME} 

You can consult it directly has this address :

{X_LINK}

-----------
({X_SITEURL}) 
The Webmaster
{X_ADMINMAIL}

------------
");

define("_MP_AUTO_MAILS","Welcome");
define("_MP_AUTO_MAIL","Hello {X_UNAME},

We are glad to have you on {X_SITENAME} 

-----------
({X_SITEURL}) 
The Webmaster
{X_ADMINMAIL}

------------
");

define("_PM_AUTO_PRUNE","Message of the purge");
define("_PM_PRUNE_DESC","
<b>Useful beacons</b> :<br /><span class='small'>
{X_COUNT} Will display the number of deleted items</span>");
define("_MP_NOTIF_PRUNE","Hello ,

During a cleaning of private messages, 
we have removed {X_COUNT} message(s) in your inbox.
We do this in order to save space and our webresources.");
// Nom des blocks
define('_MI_MP_BNAME','MP Manager');

//fichier joint
define("_MP_UPMAX","A maximum number of file per sending:");
//define("_MP_MIMETYPE","The authorized files:");
define("_MP_MIMEMAX","Maximum size of the files attached in bytes");

//profile
define("_MP_MI_LINK_TITLE", "PM Link");
define("_MP_MI_LINK_DESCRIPTION", "Shows a link to send a private message to the user");
define("_MP_MI_MESSAGE", "Write a message to");
?>