<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","信息已被删除");
define("_PM_PRIVATEMESSAGE","私人信息");
define("_PM_INBOX","信箱"); // could use _US_INBOX
define("_PM_FROM","从");
define("_PM_YOUDONTHAVE","没有任何私人信息");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","发送到: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","上一信息");
define("_PM_NEXT","下一信息");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","对不起! 你不是注册用户.");
define("_PM_REGISTERNOW","现在注册!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","数据库中找不到所选的用户");
define("_PM_PLZTRYAGAIN","请检查用户名，再试一次");
define("_PM_MESSAGEPOSTED","信息已发布");
define("_PM_CLICKHERE","点击这里查看你的私人信息");
define("_PM_ORCLOSEWINDOW","点击这里关闭本窗口");
define("_PM_USERWROTE","%s 写入:");
define("_PM_TO","至: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","信息: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","清除");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","未读");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","请注册，以发送私人信息!");

define("_PM_ONLINE", "在线");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] 私人信息通知");
