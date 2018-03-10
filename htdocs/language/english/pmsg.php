<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Your message(s) has been deleted");
define("_PM_PRIVATEMESSAGE","Private Messages");
define("_PM_INBOX","Inbox"); // could use _US_INBOX
define("_PM_FROM","From");
define("_PM_YOUDONTHAVE","You don't have any private messages");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Sent: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Previous Message");
define("_PM_NEXT","Next Message");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Sorry! You are not a registered user.");
define("_PM_REGISTERNOW","Register Now!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","The selected user doesn't exist in the database.");
define("_PM_PLZTRYAGAIN","Please check the name and try again.");
define("_PM_MESSAGEPOSTED","Your message has been posted");
define("_PM_CLICKHERE","You can click here to view your private messages");
define("_PM_ORCLOSEWINDOW","Or click here to close this window.");
define("_PM_USERWROTE","%s wrote:");
define("_PM_TO","To: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Message: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Clear");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Not Read");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Please register first to send private messages!");

define("_PM_ONLINE", "Online");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Private Message Notification");
