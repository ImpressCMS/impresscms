<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Mensagem excluída.");
define("_PM_PRIVATEMESSAGE","Mensagens particulares");
define("_PM_INBOX","Caixa de entrada"); // could use _US_INBOX
define("_PM_FROM","Enviada por");
define("_PM_YOUDONTHAVE","Nenhuma mensagem particular");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Enviado em: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Anterior");
define("_PM_NEXT","Próxima");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","Lamento, você não é um usuário registrado.");
define("_PM_REGISTERNOW","Registre-se agora.");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","O usuário escolhido não está cadastrado.");
define("_PM_PLZTRYAGAIN","Verifique o nome de usuário e tente novamente.");
define("_PM_MESSAGEPOSTED","Mensagem particular enviada");
define("_PM_CLICKHERE","Exibir caixa de entrada");
define("_PM_ORCLOSEWINDOW","Fechar esta janela.");
define("_PM_USERWROTE","%s escreveu:");
define("_PM_TO","Para:"); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Mensagem:"); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Limpar");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","Não lida");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","Registre-se para enviar uma mensagem particular.");

define("_PM_ONLINE", "Online");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Notificação de Mensagem Particular");
