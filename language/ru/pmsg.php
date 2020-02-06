<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Ваше сообщение было удалено");
define("_PM_PRIVATEMESSAGE","Личные сообщения");
define("_PM_INBOX","Сообщения"); // could use _US_INBOX
define("_PM_FROM","От");
define("_PM_YOUDONTHAVE","Для Вас нет личных сообщений");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Отправлено: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Пред.");
define("_PM_NEXT","След.");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Извините, Вы не зарегистрированный пользователь.");
define("_PM_REGISTERNOW","Регистрация");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","Выбранный пользователь не существует в базе данных.");
define("_PM_PLZTRYAGAIN","Пожалуйста проверьте имя и попробуйте снова.");
define("_PM_MESSAGEPOSTED","Ваше сообщение было отправлено");
define("_PM_CLICKHERE","Вы можете нажать здесь для просмотра личных сообщений для Ваc");
define("_PM_ORCLOSEWINDOW","Или нажмите здесь для закрытия этого окна.");
define("_PM_USERWROTE","%s пишет:");
define("_PM_TO","Кому: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Сообщение: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Очистить");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Не прочитано");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Пожалуйста, сначала зарегистрируйтесь для отправки личных сообщений!");

define("_PM_ONLINE", "Онлайн");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Оповещение о личном сообщении");
