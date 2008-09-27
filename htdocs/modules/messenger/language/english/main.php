<?php
//Permission
define( "GPERM_MESS" , 8 ) ;
define( "GPERM_OEIL" , 32 ) ;
define( "GPERM_EXP" , 64 ) ;
define( "GPERM_UP" , 128 ) ;

//form
define("_MP_HOME","Home");
define("_PM_RECE","Inbox");
define("_MD_NUMBYTES", "%s bytes");
define("_PM_REDNON","Error: You cannot do this operation");
define("_PM_REDPURGE","Error: Thank you to sort your messages");
define("_PM_PURGEMES","Error: Thank you to sort your messages");
define("_PM_PURGEFILE","Error: You reached the folder size limitation");

define("_PM_ARCH","Archives");
define("_MP_TO2","To");
define("_PM_ARCHIVES","Your message(s) was (were) saved");
define("_MP_CLASSE","Your message(s) was (were) marked");
define("_MP_DELETED","Your item(s) was / were removed");
define("_MP_MOVE","Your item(s) was / were moved");
define("_PM_POSTE","Your item(s) was / were sent");
define("_PM_EMAIL","Your item(s) has /have been sent to your emailaddress");

define("_MP_THEREARE","Your inbox is full to");
define("_MP_THEREARESEND","Your outbox is not taken into account for the allocated space");
define("_MP_THEREARESAVE","Your archive is full to");
define("_MP_THEREARESENDT","Message sent to: ");
define("_MP_VOUS","Your selection %s");
define("_MP_VOUSSEND","You sent");
define("_MP_MSG","Message(s) in the folder: %s");
define("_MP_NEWSMSG","New message(s)");
define("_MP_NEWS","New");
define("_MP_ARCHIVE","Message(s) in your archives");
define("_MP_MESSAGE","Message(s)");
define("_MP_READ","Write message");
define("_MP_TRIS","Display messages from: ");
define("_MP_MESSAGEICON","Icon :");
define("_MP_ALL","All messages");
define("_MP_DAY","Day");
define("_MP_DAYS","Days");
define("_MP_WEEK","Weeks");
define("_MP_MOND","Month(s)");
define("_MP_AN","Year");
define("_MP_READMSG","Write a message");
define("_MP_REPLYMSG","Reply message");
define("_MP_QUICKREPLY","Quick reply");
define("_MP_SAUVMSG","Archiver message");
define("_MP_DELMSG","Delete message");
define("_MP_FORMSAUV","Archiver selection");
define("_MP_FORMDEL","Delete selection");
define("_MP_GROUPE","Group :");
define("_MP_VAL","Valid");
define("_MP_PREVIEW","Preview");
define("_MP_SUBMIT","Send");
define("_MP_CLEAR","Clear");
define("_MP_CANCEL","Cancel");

define("_MP_IMSUR","Confirm ?");
define("_MP_IMSURREAD","Do you want to resort your items?");
define("_MP_IMSURREADALL","Do you want to resort your conversations?");
define("_MP_IMSURMOVE","Do you want to move your items?");
define("_MP_IMSURMOVEALL","Do you want to move the conversation?");
define("_MP_IMSURONE","Do you want to remove the item(s)?");
define("_MP_IMSURALL","Do you want to remove the conversation?");
define("_MP_IMSURCONT","Do you want to remove the contact(s) ?");
define("_MP_IMSURFILE","Do you really want to remove? All items in this folder will be removed");
define("_MP_ALERT","<b>WARNING</b> : Your mailbox is using too much space please cleanup");
define("_MP_AOERT","<b>ATTENTION</b> : We did not save your preferences yet. To do this please go to \"Preferences\"");
define("_MP_AVERT","You won't be able to use the messaging system until you cleanup<br /> Thank you for understanding");

define("_MP_R","Privates Messages readed");
define("_MP_N","Privates Messages not readed");
define("_MP_RE","Privates Messages answered");

define("_MP_QUOTE","Quote");
define("_MP_OEIL","Wink");
define("_MP_OEILOFF","Close");
define("_MP_PLAY","Play");
define("_MP_STOP","Stop");
define("_MP_MESSAGEOEIL","Wink :");
define("_MP_MESSAGEVUOEIL","See winks");
define("_MP_NOOEIL","Your broswer doesn't supports Flash");
define("_MP_REJOUE","Play again");
define("_MP_HIDDEN","Hide wink");
define("_MP_NOREJOUE","Never play again");

define("_MP_NAMEO","Name :");
define("_MP_TAILLEO","Size :");
define("_MP_POIDO","Weight :");
define("_MP_ADDO","Add to your message");

define("_MP_TRI_TRI","Sort by");
define("_MP_TRI_TITLE","Title of subject");
define("_MP_TRI_DATE","Date");
define("_MP_TRI_READ","State");

define("_MP_TRI_OASC","Ascending order");
define("_MP_TRI_ODESC","Downward order");

define("_MP_TRI_PSEUDO","Nickname");
define("_MP_TRI_NAME","Name");

define("_MP_TRI_FLAT","Flat");
define("_MP_TRI_THREAD","By conversation");

define("_MP_NEXT","Next Conversation");
define("_MP_PREVIOUS","Previous Conversation");
define("_MP_VIEWNEXT","Next Message");
define("_MP_VIEWPREVIOUS","Previous Message");

define("_MP_SUBJECTC","Subject:");
define("_MP_TOC","To:");
define("_MP_MESSAGEC","Your Message:");

//reply
define("_MP_JOINED","Joined on ");
define("_MP_FROM2","From ");
define("_MP_POSTS","Posts");
define("_MP_POSTED","Posted on");

//Chercher un user
define("_MP_MINIM","Keywords shorter than 3 characters will be ignored");
define("_MP_USEARCH","Find a user");
define("_MP_SEARCH","Search");
define("_MP_SEARCHBY","Search By");
define("_MP_NOUSER","No user found");
define("_MP_SENDUSER","Error: %s Users maximum Authorized");
define("_MP_CHOSE","Choice");
define("_MP_UNOTE","<small>Maximum %s User</small>");
define("_MP_SEARCH_TEXT","Word");
define("_MP_SEARCH_NAME","Name");
define("_MP_SEARCH_EMAIL","Email");
define("_MP_SEARCH_UNAME","Nickname");
define("_MP_SEARCH_TEXT_DESC","
text - Word exact;<br />
text% - Starting with;<br />
%text - Finishing by ;<br />
%text% - Container");


define("_MP_SEARCH_SELECTUSER","Result of your search");
define("_MP_SEARCH_USERLIST","List");
define("_MP_SEARCH_COUNT","%s User");

//option
define("_MP_DESC_OPT","Modify your Options");
define("_MP_NOTIF","Send me a mail, when a new Pm was sent to me<br /><small>(Only for message(s) from others members)</small>");
define("_MP_REDIF_NOTIF","Your preferences were saved");
define("_MP_MAIL_NOTIF","[%s] Notification of Private Message");
define("_MP_RESEND","Save sent messages<br /><small>(Will use your disk space)</small>");
define("_MP_LIMIT","Number of messages to display on each page");
define("_MP_FORMTYPE","Editor");
define("_MP_OPT_HOME","View Home");
define("_MP_SUBJECT","Subject");
define("_MP_NOSUBJECT","No Subject");
//impression / pdf
define("_MP_DESCPRINTER","Print");
define("_MP_DESCPDF","Export to pdf");
define("_MP_DESCEMAIL","Export to Email");
define("_MP_PRINTER","Printfriendly version");
define("_MP_THISCOMESFROM","This message comes from %s");
//contact
define("_MP_CTT","Contact(s)");
define("_MP_CONTACT","Addressbook");
define("_MP_READCTC","Write to Contact(s)");
define("_MP_YOUDONTCONTACT","You have no contact(s)");
define("_MP_ADDCONTACT","Add a Contact");
define("_MP_ADDCONT","Add this contact");
define("_MP_CLOSEWIN","Close the user window");
define("_MP_ADDCONTACTS","Add from my contacts");
define("_MP_COM","Comments/Posts");
define("_MP_JOINDATE","Member since");
define("_MP_CONTACTLAST","Last Connect");
define("_PM_CONTACTPOSTED","Your contact(s) was (were) added");
define("_MP_YOUCONTACT","You have %s Contact(s) in your contact list");
define("_MP_GROUPES","Groups");
define("_MP_AVATAR","Avatar");
define("_MP_PAR","By: ");
define("_MP_CONTACTDELETED","Your contact(s) was (were) removed");
//redirection
define("_MP_USEREXIT","This user is already in your contact list");
//online
define("_MP_ETAT","Status");
define("_MP_ONLINE","Online");
define("_MP_OFFLINE","Offline");
//dossier
define("_MP_FILE","Folder(s)");
define("_MP_YOUFILE","You have %s Folder(s) in your private messaging");
define("_MP_NAME_FILE","Name of folder");
define("_MP_ADD_FILE","Add a folder");
define("_MP_RENAME_FILE","Rename / Move");
define("_MP_CATEGORY","Category");
define("_MP_CARACTERE","Don/'t use specials characters.");
define("_PM_FILEPOSTED","Your folder(s) was (were) added");
define("_PM_FILEEDIT","Your folder(s) was (were) modified");
define("_MP_FILEDELETED","Your folder(s) was (were) deleted");
define("_MP_YOUDONTFILE","You don't have any folder");

//menu
define("_MP_MDEBIT","Disk usage : %s");
define("_MP_MNEWS","New");
define("_MP_MTOTAL","Total");
define("_MP_MMES","Message");
define("_MP_MCONT","Contact");
define("_MP_MFILE","Folder");
define("_MP_MOPTION","Preferences");
define("_MP_MLIRE","Read");
define("_MP_MREPLY","Reply");
define("_MP_MMOVE","Move");
define("_MP_MARCH","Archive");
define("_MP_MDEL","Delete");
define("_MP_MBOX","Inbox");
define("_MP_MLU","Mark as \"read\"");
define("_MP_MNLU","Mark as \"unread\"");

//fichier joint
define("_MP_MIMETYPE","The authorized files:");
define("_MP_MIMEFILE","Attached files : ");
define("_MP_ERREURDL","The required file is not downloadable.");
define("_MP_MIME","Maximum size of the attached files:");

define("_MP_HELP_MSGBOX","<small>Click on the buttons to carry out the operation of your choice.<br>Notch a file, then your messages and click on the button Move to carry out a moving.</small>");
define("_MP_HELP_MSGVIEW","<small>Click on the buttons to carry out the operation of your choice.<br />The buttons hereabove for the conversation.<br/> buttons sticking to the message for the message.<br />Notch a file, then click on the move button to carry out a moving.</small>");
define("_MP_HELP_MSGSEND","<small>Write your message, then click on the buttons to carry out the operation of your choice.</small>");
define("_MP_HELP_CONTBOX","<small>Notch your contacts, then click on the buttons to carry out the operation of your choice.</small>");
define("_MP_HELP_CONTSEND","<small>Add your contacts, then click on the buttons to carry out the operation of your choice.</small>");
define("_MP_HELP_FILEBOX","<small>Notch a file, then click on the buttons to carry out the operation of your choice.</small>");
define("_MP_HELP_FILESEND","<small>Add/Update your files, the click on the buttons to carry out the operation of your choice.</small>");
define("_MP_HELP_OPTION","<small>To regulate your preferenes here/Your preferences will always have priority on the default once.</small>");

//Index
define("_MP_INDEX_TITLE","Welcome");
define("_MP_MSG_TOTAL"," messages in your Inbox");
define("_MP_INDEX_MSG_DESC","View and manage your messages.");
define("_MP_INDEX_CONTACT_DESC","Contact management.");
define("_MP_INDEX_FOLDER_DESC","File management.");
define("_MP_INDEX_OPTN_DESC","Access to the preferences of your account “private message”");
?>