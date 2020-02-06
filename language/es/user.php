<?php

//%%%%%%		File Name user.php 		%%%%%
define('_US_NOTREGISTERED','¿No está registrado? Haga clic <a href="register.php">aquí</a>.');
define('_US_LOSTPASSWORD','¿Perdiste tu contraseña?');
define('_US_NOPROBLEM','No hay problema, simplemente introduzca la dirección de correo electrónico que tenemos en el archivo de su cuenta.');
define('_US_YOUREMAIL','Tu correo electrónico: ');
define('_US_SENDPASSWORD','Enviar contraseña');
define('_US_LOGGEDOUT','Has cerrado la sesión');
define('_US_THANKYOUFORVISIT','Gracias por su visita a nuestro sitio!');
define('_US_INCORRECTLOGIN','¡Inicio de sesión incorrecto!');
define('_US_LOGGINGU','Gracias por iniciar sesión, %s.');
define('_US_RESETPASSWORD','Restablecer su contraseña');
define('_US_SUBRESETPASSWORD','Restablecer contraseña');
define('_US_RESETPASSTITLE','¡Su contraseña ha expirado!');
define('_US_RESETPASSINFO','Por favor, complete el siguiente formulario para restablecer su contraseña. Si tu correo electrónico, nombre de usuario y contraseña actual coinciden con nuestro registro, tu contraseña será cambiada al instante y podrás volver a conectarte!');
define('_US_PASSEXPIRED','Su contraseña ha caducado.<br />Ahora será redirigido a un formulario donde podrá restablecer su contraseña.');
define('_US_SORRYUNAMENOTMATCHEMAIL','¡El nombre de usuario introducido no está asociado con la dirección de correo electrónico dada!');
define('_US_PWDRESET','¡Su contraseña se ha restablecido con éxito!');
define('_US_SORRYINCORRECTPASS','¡Has introducido tu contraseña actual incorrectamente!');

// 2001-11-17 ADD
define('_US_NOACTTPADM','El usuario seleccionado ha sido desactivado o aún no ha sido activado.<br />Póngase en contacto con el administrador para más detalles.');
define('_US_ACTKEYNOT','¡La clave de activación no es correcta!');
define('_US_ACONTACT','¡La cuenta seleccionada ya está activada!');
define('_US_ACTLOGIN','Su cuenta ha sido activada. Por favor, inicie sesión con la contraseña registrada.');
define('_US_NOPERMISS','¡Lo sentimos, no tienes permiso para realizar esta acción!');
define('_US_SURETODEL','¿Está seguro que desea eliminar su cuenta?');
define('_US_REMOVEINFO','Esto eliminará toda su información de nuestra base de datos.');
define('_US_BEENDELED','Tu cuenta ha sido eliminada.');
define('_US_REMEMBERME', 'Recordarme');

//%%%%%%		File Name register.php 		%%%%%
define('_US_USERREG','Registro de usuario');
define('_US_EMAIL','E-mail');
define('_US_ALLOWVIEWEMAIL','Permitir a otros usuarios ver mi dirección de correo electrónico');
define('_US_WEBSITE','Sitio web');
define('_US_TIMEZONE','Zona horaria');
define('_US_AVATAR','Avatar');
define('_US_VERIFYPASS','Verificar contraseña');
define('_US_SUBMIT','Enviar');
define('_US_LOGINNAME','Usuario');
define('_US_FINISH','Finalizar');
define('_US_REGISTERNG','No se pudo registrar un nuevo usuario.');
define('_US_MAILOK','¿Recibir mensajes de correo electrónico ocasionales de administradores y moderadores?');
define('_US_DISCLAIMER','Descargo de responsabilidad');
define('_US_IAGREE','Estoy de acuerdo con lo anterior');
define('_US_UNEEDAGREE', 'Lo sentimos, tienes que aceptar nuestra exención de responsabilidad para registrarte.');
define('_US_NOREGISTER','Lo sentimos, actualmente estamos cerrados por nuevos registros de usuarios');

// %s is username. This is a subject for email
define('_US_USERKEYFOR','Clave de activación de usuario para %s');

define('_US_YOURREGISTERED','Ya está registrado. Un correo electrónico que contiene una clave de activación de usuario ha sido enviado a la cuenta de correo electrónico que ha proporcionado. Siga las instrucciones del correo electrónico para activar su cuenta. ');
define('_US_YOURREGMAILNG','Ahora estás registrado. Sin embargo, no hemos podido enviar el correo de activación a tu cuenta de correo electrónico debido a un error interno ocurrido en nuestro servidor. Sentimos las molestias, por favor envíe al webmaster un correo electrónico notificándole la situación.');
define('_US_YOURREGISTERED2','Ya está registrado. Por favor, espere a que su cuenta sea activada por los administradores. Recibirás un correo electrónico una vez que hayas sido activado. Esto puede tardar un poco en ser paciente.');

// %s is your site name
define('_US_NEWUSERREGAT','Nuevo registro de usuario en %s');
// %s is a username
define('_US_HASJUSTREG','¡%s acaba de registrarse!');

define('_US_INVALIDMAIL','ERROR: Email no válido');
define('_US_INVALIDNICKNAME','ERROR: Nombre de usuario no válido, por favor intente otro nombre de usuario.');
define('_US_NICKNAMETOOLONG','El nombre de usuario es demasiado largo. Debe tener menos de %s caracteres.');
define('_US_NICKNAMETOOSHORT','El nombre de usuario es demasiado corto. Debe tener más de %s caracteres.');
define('_US_NAMERESERVED','ERROR: El nombre está reservado.');
define('_US_NICKNAMENOSPACES','No puede haber ningún espacio en el nombre de usuario.');
define('_US_LOGINNAMETAKEN','ERROR: Nombre de usuario tomado.');
define('_US_NICKNAMETAKEN','ERROR: Nombre de visualización tomado.');
define('_US_EMAILTAKEN','ERROR: Dirección de correo electrónico ya registrada.');
define('_US_ENTERPWD','ERROR: Debes proporcionar una contraseña.');
define('_US_SORRYNOTFOUND','Lo sentimos, no se encontró información de usuario correspondiente.');

define('_US_USERINVITE', 'Invitación a la membresía');
define('_US_INVITENONE','ERROR: El registro es sólo por invitación.');
define('_US_INVITEINVALID','ERROR: Código de invitación incorrecto.');
define('_US_INVITEEXPIRED','ERROR: El código de invitación ya está en uso o ha caducado.');

define('_US_INVITEBYMEMBER','Sólo un miembro existente puede invitar a nuevos miembros; por favor, solicite un correo electrónico de invitación de algún miembro registrado.');
define('_US_INVITEMAILERR','No hemos podido enviar el correo con el enlace de registro a tu cuenta de correo electrónico debido a un error interno ocurrido en nuestro servidor. Sentimos las molestias, por favor inténtelo de nuevo y si el problema persiste, envíe al webmaster un correo electrónico notificándole la situación. <br />');
define('_US_INVITEDBERR','No hemos podido procesar su solicitud de registro debido a un error interno. Sentimos las molestias, por favor inténtelo de nuevo y si el problema persiste, envíe al webmaster un correo electrónico notificándole la situación. <br />');
define('_US_INVITESENT','Un correo electrónico que contiene el enlace de registro ha sido enviado a la cuenta de correo electrónico proporcionada. Por favor, siga las instrucciones del correo electrónico para registrar su cuenta. Esto podría tardar unos minutos así que por favor sea paciente.');
// %s is your site name
define('_US_INVITEREGLINK','Invitación de registro de %s');

// %s is your site name
define('_US_NEWPWDREQ','Nueva solicitud de contraseña en %s');
define('_US_YOURACCOUNT', 'Tu cuenta en %s');

define('_US_MAILPWDNG','mail_password: no se pudo actualizar la entrada de usuario. Contacte al Administrador');
define('_US_RESETPWDNG','reset_password: no se pudo actualizar la entrada del usuario. Póngase en contacto con el administrador');

define('_US_RESETPWDREQ','Reiniciar solicitud de contraseña en %s');
define('_US_MAILRESETPWDNG','reset_password: no se pudo actualizar la entrada del usuario. Póngase en contacto con el administrador');
define('_US_NEWPASSWORD','Nueva contraseña');
define('_US_YOURUSERNAME','Tu nombre de usuario');
define('_US_CURRENTPASS','Su contraseña actual');
define('_US_BADPWD','Contraseña incorrecta, la contraseña no puede contener nombre de usuario.');

// %s is a username
define('_US_PWDMAILED','Contraseña para %s enviada por correo.');
define('_US_CONFMAIL','Correo electrónico de confirmación para %s.');
define('_US_ACTVMAILNG', 'Error al enviar el correo de notificación a %s');
define('_US_ACTVMAILOK', 'Correo de notificación a %s enviado.');

//%%%%%%		File Name userinfo.php 		%%%%%
define('_US_SELECTNG','Ningún usuario seleccionado! Por favor, vuelve atrás e inténtalo de nuevo.');
define('_US_PM','MP');
define('_US_ICQ','ICQ');
define('_US_AIM','AIM');
define('_US_YIM','YIM');
define('_US_MSNM','MSNM');
define('_US_LOCATION','Ubicación');
define('_US_OCCUPATION','Ocupación');
define('_US_INTEREST','Interés');
define('_US_SIGNATURE','Firma');
define('_US_EXTRAINFO','Extra Info');
define('_US_EDITPROFILE','Editar perfil');
define('_US_LOGOUT','Cerrar sesión');
define('_US_INBOX','Entrada');
define('_US_MEMBERSINCE','Miembro desde');
define('_US_RANK','Rango');
define('_US_POSTS','Comentarios/Mensajes');
define('_US_LASTLOGIN','Último acceso');
define('_US_ALLABOUT','Todo acerca de %s');
define('_US_STATISTICS','Estadísticas');
define('_US_MYINFO','Mi información');
define('_US_BASICINFO','Información básica');
define('_US_MOREABOUT','Más sobre mí');
define('_US_SHOWALL','Mostrar todo');

//%%%%%%		File Name edituser.php 		%%%%%
define('_US_PROFILE','Perfil');
define('_US_REALNAME','Nombre Real');
define('_US_SHOWSIG','Adjuntar siempre mi firma');
define('_US_CDISPLAYMODE','Modo de visualización de comentarios');
define('_US_CSORTORDER','Orden de comentarios');
define('_US_PASSWORD','Contraseña');
define('_US_TYPEPASSTWICE','(escribe una nueva contraseña dos veces para cambiarla)');
define('_US_SAVECHANGES','Guardar Cambios');
define('_US_NOEDITRIGHT',"Lo sentimos, no tienes permiso para editar la información de este usuario.");
define('_US_PASSNOTSAME','Ambas contraseñas son diferentes. Deben ser idénticas.');
define('_US_PWDTOOSHORT','Lo sentimos, tu contraseña debe tener al menos <b>%s</b> caracteres de longitud.');
define('_US_PROFUPDATED','¡Tu perfil actualizado!');
define('_US_USECOOKIE','Guardar mi nombre de usuario en una cookie por 1 año');
//define('_US_NO','No');
define('_US_DELACCOUNT','Eliminar cuenta');
define('_US_MYAVATAR', 'Mi Avatar');
define('_US_UPLOADMYAVATAR', 'Subir Avatar');
define('_US_MAXPIXEL','Max Pixels');
define('_US_MAXIMGSZ','Tamaño Máximo de Imagen (Bytes)');
define('_US_SELFILE','Seleccionar archivo');
define('_US_OLDDELETED','¡Tu avatar antiguo será eliminado!');
define('_US_CHOOSEAVT', 'Seleccionar avatar de la lista disponible');
define('_US_SELECT_THEME', 'Tema por defecto');
define('_US_SELECT_LANG', 'Idioma por defecto');

define('_US_PRESSLOGIN', 'Pulse el botón de abajo para iniciar sesión');

define('_US_ADMINNO', 'El usuario en el grupo de webmasters no puede ser eliminado');
define('_US_GROUPS', 'Grupos de usuarios');

define('_US_YOURREGISTRATION', 'Tu registro en %s');
define('_US_WELCOMEMSGFAILED', 'Error al enviar el correo de bienvenida.');
define('_US_NEWUSERNOTIFYADMINFAIL', 'Error al notificar al administrador sobre el nuevo registro de usuario.');
define('_US_REGFORM_NOJAVASCRIPT', 'Para iniciar sesión en el sitio es necesario que su navegador tenga habilitado javascript.');
define('_US_REGFORM_WARNING', 'Para registrarse en el sitio necesita utilizar una contraseña segura. Intenta crear tu contraseña usando una mezcla de letras (mayúsculas y minúsculas), números y símbolos. Intenta crear una contraseña lo más compleja posible aunque puedes recordarla.');
define('_US_CHANGE_PASSWORD', '¿Cambiar contraseña?');
define('_US_POSTSNOTENOUGH','Lo sentimos, necesitas tener <b>%s</b> mensajes para poder subir tu avatar.');
define('_US_UNCHOOSEAVT', 'Hasta que alcance esta cantidad puede elegir avatar de la lista de abajo.');


define('_US_SERVER_PROBLEM_OCCURRED','¡Hubo un problema al comprobar la lista de spammers!');
define('_US_INVALIDIP','ERROR: Este IP no está autorizado para registrarse');

######################## Added in 1.2 ###################################
define('_US_LOGIN_NAME', "Nombre de usuario");
define('_US_OLD_PASSWORD', "Contraseña antigua");
define('_US_NICKNAME','Mostrar nombre');
define('_US_MULTLOGIN', 'No fue posible iniciar sesión en el sitio!! <br />
        <p align="left" style="color:red;">
        Causas posibles:<br />
         - Ya has iniciado sesión en el sitio.<br />
         - Alguien más ha iniciado sesión en el sitio usando su nombre de usuario y contraseña.<br />
         - Usted abandonó el sitio o cerró la ventana del navegador sin hacer clic en el botón de cerrar sesión.<br />
        </p>
        Espere unos minutos y vuelva a intentarlo más tarde. Si los problemas persisten contacta al administrador del sitio.');

// added in 1.3
define('_US_NOTIFICATIONS', "Notificaciones");

// relocated from finduser.php in 2.0
// formselectuser.php

define("_MA_USER_MORE", "Buscar usuarios");
define("_MA_USER_REMOVE", "Eliminar usuarios no seleccionados");

//%%%%%%	File Name findusers.php 	%%%%%
define("_MA_USER_ADD_SELECTED", "Añadir usuarios seleccionados");

define("_MA_USER_GROUP", "Grupo");
define("_MA_USER_LEVEL", "Nivel");
define("_MA_USER_LEVEL_ACTIVE", "Activo");
define("_MA_USER_LEVEL_INACTIVE", "Inactivo");
define("_MA_USER_LEVEL_DISABLED", "Deshabilitado");
define("_MA_USER_RANK", "Rango");

define("_MA_USER_FINDUS","Buscar usuarios");
define("_MA_USER_REALNAME","Nombre Real");
define("_MA_USER_REGDATE","Fecha de inscripción");
define("_MA_USER_EMAIL","E-mail");
define("_MA_USER_PREVIOUS","Anterior");
define("_MA_USER_NEXT","Siguiente");
define("_MA_USER_USERSFOUND","%s usuario(s) encontrado");

define("_MA_USER_ACTUS", "Usuarios activos: %s");
define("_MA_USER_INACTUS", "Usuarios inactivos: %s");
define("_MA_USER_NOFOUND","No se encontraron usuarios");
define("_MA_USER_UNAME","Usuario");
define("_MA_USER_ICQ","Número ICQ");
define("_MA_USER_AIM","Manejo AIM");
define("_MA_USER_YIM","Manejo YIM");
define("_MA_USER_MSNM","Manejo MSNM");
define("_MA_USER_LOCATION","Ubicación contiene");
define("_MA_USER_OCCUPATION","Ocupación contiene");
define("_MA_USER_INTEREST","El interés contiene");
define("_MA_USER_URLC","URL contiene");
define("_MA_USER_SORT","Ordenar por");
define("_MA_USER_ORDER","Pedido");
define("_MA_USER_LASTLOGIN","Último acceso");
define("_MA_USER_POSTS","Número de mensajes");
define("_MA_USER_ASC","Orden ascendente");
define("_MA_USER_DESC","Orden descendente");
define("_MA_USER_LIMIT","Número de usuarios por página");
define("_MA_USER_RESULTS", "Resultados de búsqueda");
define("_MA_USER_SHOWMAILOK", "Tipo de usuarios a mostrar");
define("_MA_USER_MAILOK","Solo usuarios que aceptan correo");
define("_MA_USER_MAILNG","Sólo usuarios que no aceptan correo");
define("_MA_USER_BOTH", "Todos");

define("_MA_USER_RANGE_LAST_LOGIN","Sesión iniciada en los últimos <span style='color:#ff0000;'>X</span>días");
define("_MA_USER_RANGE_USER_REGDATE","Registrado en los últimos <span style='color:#ff0000;'>X</span>días");
define("_MA_USER_RANGE_POSTS","Mensajes");

define("_MA_USER_HASAVATAR", "Tiene avatar");
define("_MA_USER_MODE_SIMPLE", "Modo simple");
define("_MA_USER_MODE_ADVANCED", "Modo avanzado");
define("_MA_USER_MODE_QUERY", "Modo de consulta");
define("_MA_USER_QUERY", "Consulta");

define("_MA_USER_SEARCHAGAIN", "Buscar de nuevo");
define("_MA_USER_NOUSERSELECTED", "Ningún usuario seleccionado");
define("_MA_USER_USERADDED", "Se han añadido usuarios");

define("_MA_USER_SENDMAIL","Enviar Email");
