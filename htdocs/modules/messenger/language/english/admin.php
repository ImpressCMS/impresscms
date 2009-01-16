<?php
//define('_MP_ADMENU0','Message priv� - Administration.');

// Nom des menus
define('_MP_ADMENU0','Index');
define("_MP_ADMENU1","Sort");
define("_MP_ADMENU2","Write");
define("_MP_ADMENU3","Purge");
define("_MP_ADMENU4","Notification");
define("_MP_ADMENU5","Stats");
define('_MP_ADMENU6','Maps');
define('_MP_ADMENU7','Winks');
define('_MP_ADMENU8','Rights');
define('_MP_ADMENU9','General Configuration');
define('_MP_MENU_GOTOMOD','Go to module');
define('_MP_INSTALL','Integration');
define ("_MP_ABOUT","About");
define ("_MP_UPDATE","Update");
define ("_MP_MODULEADMIN", "Module administration:");

//definition des pages
define ("_MP_NB", "Stats");
define ("_MP_ESP", "Used Disk Space");
define ("_MP_STOCK", "Storage Alert");
define ("_MP_DTRIS", "Sorting your messages");
define ("_MP_DRESULT", "Result");
define ("_MP_DLIRE", "Read a Message");
define ("_MP_DREAD", "Write a Message");
define ("_MP_DAUTO", "Send an automated message to your new members");
define ("_MP_DPURGE", "Delete your Messages");
define ("_MP_PERM", "Control Permissions");
define("_MP_WARNING", "Warning : no confirmation will be required and the removed messages will not be recoverable.");

// About.php constants
define('_MP_AUTHOR_INFO', "More about the author");
define('_MP_AUTHOR_NAME', "Author");
define('_MP_AUTHOR_WEBSITE', "Authors's Website");
define('_MP_AUTHOR_EMAIL', "Email de l'auteur");
define('_MP_AUTHOR_CREDITS', "Credits");
define('_MP_MODULE_INFO', "Information about development");
define('_MP_MODULE_STATUS', "Status");
define('_MP_MODULE_DEMO', "Demo website");
define('_MP_MODULE_SUPPORT', "Offical support site");
define('_MP_MODULE_BUG', "Report a bug to the author");
define('_MP_MODULE_FEATURE', "You can propose a new function for this module");
define('_MP_MODULE_DISCLAIMER', "Warning");
define('_MP_AUTHOR_WORD', "Author's word");
define('_MP_BY','By');

//purge
define("_MP_PURGE", "Purge your privates messages by:");
define("_PM_AM_INCLUDESAVE", "To include the Archive");
define("_PM_AM_INCLUDESEND", "To include the sent items");
define("_PM_AM_ONLYREADMESSAGES", "To include the Unread Items");
define("_PM_AM_INCLUDEFILE", "To include the files users");
define("_PM_AM_PRUNEAFTER", "After this date (white for any date)");
define("_PM_AM_PRUNEBEFORE", "Before this date (white for any date)");
define("_PM_AM_NOTIFYUSERS", "To inform the users of the purging");
define("_MP_GROUPE", "Groups");
define("_MP_NICKNAME", "Nickname of the user");
define("_MP_REELNAME", "Real name");
define('_MP_ETAT','Status');
define("_MP_ALL", "Everybody");
define("_MP_LIMIT", "Limit of the purge");
define("_MP_SUBJECT_PRUNE", "Messages removed during cleaning");
//stats
define("_MP_ID", "Id");
define("_MP_POURC", "% of the database");
define("_MP_MESSAGE", "n° Item(s)");
define("_MP_10DATE", "Last 5 items");
define("_MP_10FROM", "Best 5 Authors");
define("_MP_10ARCH", "Best 10 Archivers");
define("_MP_10TO", "Best 5 Recipients");

define("_MD_NUMBYTES", "%s Bytes");
define("_MP_ERRORUP","<span style='color: #ff0000; font-weight: bold'>You must execute update.php as indicated in readme_en.txt</span>");
define("_MP_THEREARE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> Messages in the database");
define("_MP_THEREAREFILE","There are <span style='color: #ff0000; font-weight: bold'>%s</span> folders in the database");
define("_MP_THEREAREUP","There are <span style='color: #ff0000; font-weight: bold'>%s</span> Files in the upload folder.");
define("_MP_LENGTHBRUT","Size of the raw data");
define("_MP_TAILLE","Size");
define("_MP_POURCENTUTIL","% of inbox");
define("_MP_LENGTH","Size of the data");
define("_MP_DATE_FREE","Lost disk space");
define("_MP_OPTAUTO","Automatic optimization");
define("_MP_OPTOK","The table has been successfully optimized.");
define("_MP_OPTNO","The optimization of the table failed!");
define("_MP_OPT","Optimize");
define("_MP_TOTAL","Total of the database");
define("_MP_POURCENT","Your database is using <span style='color: #ff0000; font-weight: bold'> %s </span> from the allocated disk space.");
define("_MP_ALERT","<span style='color: #ff0000; font-weight: bold'>WARNING</span>, Your private message database exceeds the threshold which you allocated.");
//le Trie
define('_MP_TITLE','Subject');
define('_MP_POSTER','Author');
define('_MP_RECEVER','To');
define('_MP_PUBLISHED','Published');
define('_MP_ACTION','Action');
define('_MP_VOIR','Read');
define('_MP_VOIRA','Message reading');
define('_MP_SUPR','Delete');
define('_MP_LAST10ARTS','Message(s)');
define('_MP_MPSAGES','Message ');
define('_MP_NB_MP',"No Private Message");
define("_MP_DELETE","Message(s) deleted");
define("_MP_TRIE_PAR","Display by");
define("_MP_TRIE_LU","Read");
define("_MP_TRIE_NONLU","Not read");
define("_MP_TRIE","Messages sorted by: ");
define("_MP_ICONE","Read Privates Messages");
define("_MP_NICONE","Not read Privates Messages");
define("_MP_MEICONE","<b>Note</b> : (The reading of a message via the admin will not modify its status)");
define("_MP_USERNOEXIST","No User was found");

//form mp
define("_MP_CLEAR","Clear");
define("_MP_SUBMIT","Send");
define("_PM_NAME","Send a private message");
define("_MP_GOBACK","Back");

//clin doeil
define("_MP_NAMEO","Name :");
define("_MP_TAILLEO","Size :");
define("_MP_POIDO","Weight :");
define("_MP_HELPO","Help for the Winks");
define("_MP_DESCO","Your Winks");
define("_MP_WARNINGO", "<br /> - To add new winks, simply add your files <b>.swf</b> in <b>swf</b> folder of <b>mp manager</b> module.<br />
- You can here <b>remove</b> your winks but they will not be <b>recoverable</b>. <br />
- To create your own Winks, you must add the action script <b>getURL('javascript:cacheFlash()');</b> in the last image of your animation.");
define("_MP_OOK","Wink to remove.");
define("_MP_ONO","The Remove has failed.");
define("_MP_NOACTIF","The Winks are not activated, To activate them, go to the administration of the module.");

//Permission
define("_MP_GPERMUPDATED", "Permissions were successfully changed");
define( "GPERM_MESS" , 8 ) ;
define( "GPERM_OEIL" , 32 ) ;
define( "GPERM_EXP" , 64 ) ;
define( "GPERM_UP" , 128 ) ;
define("_MP_CONF_OEIL","Send Winks");
define("_MP_CONF_EXP","Print / PDF");
define("_MP_CONF_UP","Upload");
define("_MP_CONF_MESS","Write / Reply to messages");

define("_MP_GLOBAL","Global");
define("_MP_GLOBAL_DESC","Choose the actions which the groups can carry out");
define("_MP_GROUPES","Groups");
define("_MP_GROUPE_DESC","Choose the authorized groups! If none selected, Users will not have the choice to send a PM to groups.");
define("_MP_ACCES_DESC","Choose the groups authorized to use mpmanager");
define("_MP_CONF_ACCES","Module access rights");
define("_MP_ACCES","Access rights");
//autre
define("_MP_MESSAGEICON","Icon");
define("_MP_MESSAGEOEIL","Wink");
define("_MP_MESSAGEVUOEIL","See Winks");
define("_PM_MESSAGEPOSTED","Your message was sent");

//Dossier
define("_MP_FILEDESC","User Folder");
define("_MP_FILEPUBLIC","Public Folder");
define("_MP_FILETITLE","Folder name");
define("_MP_FILEMSG","Number of Messages");
define("_MP_IMSURFILE","Confirm ? All the messages in this folder will be removed!");
define("_MP_FILEDELETED","Your folder(s) has (have) been removed!");

//update
define("_MP_UPCONT","Create the 'contact' table (useless for a new installation)");
define("_MP_UPOPTION","Create/update the 'options' table (useless for a new installation)");
define("_MP_UPSAV","Update for the 'archive' table (useless for a new installation)");
define("_MP_UPCAT","Create the 'folder' table (useless for a new installation)");
define("_MP_UPCATFILE","Create the folders (install and update)");
define("_MP_UPDELSAV","Remove the 'archive' table (useless for a new installation)");
define("_MP_BOX1","Inbox");
define("_MP_BOX2","Outbox");
define("_MP_BOX3","Archive");
define("_MP_UPMSG","Modify the 'messages' table (all versions, install and update)");

define("_MP_UPNOTICE","<b>After the update your table message to deprive will be to modify, You must be on:<b><br />");
define("_MP_UPTEXTE","To have correctly uploaded the MPmanager map!<br />
<span style='color:#ff0000;font-weight:bold;'>To have to safeguard your table priv_msgs. VERY IMPORTANT!!</span><br />
<span style='color:#ff0000;font-weight:bold;'>To have to safeguard your table priv_msgsave. VERY IMPORTANT!!</span>");

// upload
define("_MP_MIMEFILE","Enclosure : ");

//ecrire
define("_MP_NOTIFYUSERS","To force the notification by email : ");

//notification
define("_MP_DNOTIF","To regulate the active notifications");
define('_MP_LAST10NOTIF','User(s)');
define("_MP_TRIENOTIF","Members currently sorted by: ");
define("_PM_AM_NOTIFAFTER", "Connection after this date (white for any date)");
define("_PM_AM_NOTIFBEFORE", "Connection before this date (white for any date)");
define("_PM_NOTIF_REGDATE", "Registered is");
define("_PM_NOTIF_LAST", "Last connection");
define("_PM_NOTIF_DEL", "Decontaminate these notifications");
define("_MP_NOTIF_WARNING", "o decontaminate the notifications of the members who did not come on your site for a time. This desactivation concerns only the members having activated the notification by private message.");

//install & surcharge
define('_MP_THEME_SET','Theme installed by default on your site :');
define("_MP_XH_TITLE", "Serveur status");
define("_MP_XH_PHPINI", "<b>Information coming from the file PHP.ini :</b>");
define("_MP_XH_GENERAL", "<b>General Information:</b>");
define("_MP_XH_FCTINI", "<b>Fonction status :</b>");
define("_MP_XH_FCT", "Fonction ");
define("_MP_XH_SAFEMODEPROBLEMS", " (Can cause problems)");
define("_MP_XH_SAFEMODESTATUS", "Safe Mode Status : ");
define("_MP_XH_REGISTERGLOBALS", "Register Globals : ");
define("_MP_XH_SERVERUPLOADSTATUS", "Server Uploads Status : ");
define("_MP_XH_MAXUPLOADSIZE", "Max Upload Size Permitted : ");
define("_MP_XH_MAXPOSTSIZE", "Max Post Size Permitted : ");
define("_MP_XH_SERVERPATH", "Server Path to XOOPS Root : ");
define("_MP_XH_HACKSPATH", "Current Hacks Path : ");
define("_MP_XH_HACKPATHDSC", "Note : The hackpath has to be defined.");
define("_MP_XH_NOTSET", "The hacks access path was not defined yet");
define("_MP_XH_ON", "<b>ON</b>");
define("_MP_XH_OFF", "<b>OFF</b>");
define('_MP_FILE_OK','File found.');
define('_MP_FILE_KO','File not found. You must install the file!!');
?>