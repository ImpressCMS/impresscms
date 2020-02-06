<?php

//%%%%%%	File Name readpmsg.php 	%%%%%
define("_PM_DELETED","Su mensaje(s) ha sido eliminado");
define("_PM_PRIVATEMESSAGE","Mensajes privados");
define("_PM_INBOX","Entrada"); // could use _US_INBOX
define("_PM_FROM","De");
define("_PM_YOUDONTHAVE","No tienes ningún mensaje privado");
//define("_PM_FROMC","From: "); 								//replaced with _PM_FROM with colon
define("_PM_SENTC","Enviado: "); // The date of message sent
//define("_PM_PROFILE","Profile"); 								// replaced with global: _PROFILE

// %s is a username
define("_PM_PREVIOUS","Mensaje anterior");
define("_PM_NEXT","Siguiente mensaje");

//%%%%%%	File Name pmlite.php 	%%%%%
define("_PM_SORRY","¡Lo sentimos! No eres un usuario registrado.");
define("_PM_REGISTERNOW","¡Regístrate ahora!");
//define("_PM_GOBACK","Go Back"); 									//Never used. If needed, use global: _BACK
define("_PM_USERNOEXIST","El usuario seleccionado no existe en la base de datos.");
define("_PM_PLZTRYAGAIN","Por favor, comprueba el nombre e inténtalo de nuevo.");
define("_PM_MESSAGEPOSTED","Tu mensaje ha sido publicado");
define("_PM_CLICKHERE","Puedes hacer clic aquí para ver tus mensajes privados");
define("_PM_ORCLOSEWINDOW","O haga clic aquí para cerrar esta ventana.");
define("_PM_USERWROTE","%s escribió:");
define("_PM_TO","Para: "); // with colon
//define("_PM_SUBJECTC","Subject: "); 								//Never used. If needed, use global: _SUBJECT with colon
define("_PM_MESSAGEC","Mensaje: "); 								// (with colon) Duplicated in comments _CM_MESSAGE
define("_PM_CLEAR","Claro");										// Duplicated in notifications _NOT_CLEAR
//define("_PM_CANCELSEND","Cancel Send"); 							// Never used. If needed, use globals: _CANCEL + _SEND
//define("_PM_SUBMIT","Submit"); 									// replaced with global: _SUBMIT

//%%%%%%	File Name viewpmsg.php 	%%%%%
//define("_PM_SUBJECT","Subject"); 									// replaced with global: _SUBJECT
//define("_DATE","Date"); 											// replaced with global: _DATE
define("_PM_NOTREAD","No leído");
//define("_PM_SEND","Send"); 										// replaced with global: _SEND
//define("_PM_DELETE","Delete"); 									// replaced with global: _DELETE
//define("_PM_REPLY", "Reply"); 									// replaced with global: _REPLY
define("_PM_PLZREG","¡Regístrate primero para enviar mensajes privados!");

define("_PM_ONLINE", "En línea");

define("_PM_MESSAGEPOSTED_EMAILSUBJ","[%s] Notificación de mensaje privado");
