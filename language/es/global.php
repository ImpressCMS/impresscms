<?php

//%%%%%%	File Name mainfile.php 	%%%%%
define('_PLEASEWAIT','Por favor, espere');
define('_FETCHING','Cargando...');
define('_TAKINGBACK','Te devolvemos a donde estabas....');
define('_LOGOUT','Cerrar sesión');
define('_SUBJECT','Asunto');
define('_MESSAGEICON','Icono de mensaje');
define('_COMMENTS','Comentarios');
define('_POSTANON','Publicar anónimamente');
define('_DISABLESMILEY','Desactivar smiley');
define('_DISABLEHTML','Desactivar html');
define('_PREVIEW','Vista previa');

define('_GO','Go!');
define('_NESTED','Anidado');
define('_NOCOMMENTS','No hay comentarios');
define('_FLAT','Plano');
define('_THREADED','Hilo');
define('_OLDESTFIRST','Más antiguo primero');
define('_NEWESTFIRST','Más nuevo primero');
define('_MORE','más...');
define('_IFNOTRELOAD','Si la página no se recarga automáticamente, por favor haga clic <a href="%s">aquí</a>');
define('_WARNINSTALL2','ADVERTENCIA: El directorio %s existe en su servidor. <br />Por favor, elimine este directorio por razones de seguridad.');
define('_WARNINWRITEABLE','ADVERTENCIA: El servidor puede escribir en el archivo %s . <br />Por favor, cambie el permiso de este archivo por razones de seguridad.<br /> en Unix (444), en Win32 (sólo lectura)');
define('_WARNINNOTWRITEABLE','ADVERTENCIA: El servidor no puede escribir en el archivo %s . <br />Por favor, cambie el permiso de este archivo por razones de funcionalidad.<br /> en Unix (777), en Win32 (escribible)');

// Error messages issued by icms_core_Object::cleanVars()
define( '_XOBJ_ERR_REQUIRED', '%s es obligatorio' );
define( '_XOBJ_ERR_SHORTERTHAN', '%s debe ser más corto que %d caracteres.' );

//%%%%%%	File Name themeuserpost.php 	%%%%%
define('_PROFILE','Perfil');
define('_POSTEDBY','Publicado por');
define('_VISITWEBSITE','Visitar sitio web');
define('_SENDPMTO','Enviar mensaje privado a %s');
define('_SENDEMAILTO','Enviar correo electrónico a %s');
define('_ADD','Añadir');
define('_REPLY','Responder');
define('_DATE','Fecha');   // Posted date

//%%%%%%	File Name admin_functions.php 	%%%%%
define('_MAIN','Principal');
define('_MANUAL','Manual');
define('_INFO','Info');
define('_CPHOME','Panel de control de admin');
define('_YOURHOME','Página de inicio');

//%%%%%%	File Name misc.php (who's-online popup)	%%%%%
define('_WHOSONLINE','Quién está conectado');
define('_GUESTS', 'Invitados');
define('_MEMBERS', 'Miembros');
define('_ONLINEPHRASE','<b>%s</b> usuario(s) están conectados');
define('_ONLINEPHRASEX','<b>%s</b> usuario(s) están navegando <b>%s</b>');
define('_CLOSE','Cerrar');  // Close window

//%%%%%%	File Name module.textsanitizer.php 	%%%%%
define('_QUOTEC','Cotización:');

//%%%%%%	File Name admin.php 	%%%%%
define("_NOPERM","Lo sentimos, no tienes permiso para acceder a esta área.");

//%%%%%		Common Phrases		%%%%%
define("_NO","Nu");
define("_YES","Sí");
define("_EDIT","Editar");
define("_DELETE","Eliminar");
define("_SUBMIT","Enviar");
define("_MODULENOEXIST","¡El módulo seleccionado no existe!");
define("_ALIGN","Align");
define("_LEFT","Queda");
define("_CENTER","Centrar");
define("_RIGHT","Derecha");
define("_FORM_ENTER", "Por favor, introduzca %s");
// %s represents file name
define("_MUSTWABLE","¡El archivo %s debe tener permisos de escritura por el servidor!");
// Module info
define('_PREFERENCES', 'Preferencias');
define("_VERSION", "Versión");
define("_DESCRIPTION", "Descripción");
define("_ERRORS", "Errores");
define("_NONE", "Ninguna");
define('_ON','en');
define('_READS','lecturas');
define('_SEARCH','Buscar');
define('_ALL', 'Todos');
define('_TITLE', 'Título');
define('_OPTIONS', 'Opciones');
define('_QUOTE', 'Cotización');
define('_HIDDENC', 'Contenido oculto:');
define('_HIDDENTEXT', 'This content is hidden for anonymous users, please <a href="'.ICMS_URL.'/register.php" title="Registration at ' . htmlspecialchars ( $icmsConfig ['sitename'], ENT_QUOTES ) . '">register</a> to be able to see it.');
define('_LIST', 'Lista');
define('_LOGIN','Inicio de sesión');
define('_USERNAME','Usuario: ');
define('_PASSWORD','Contraseña: ');
define("_SELECT","Seleccionar");
define("_IMAGE","Imagen");
define("_SEND","Enviar");
define("_CANCEL","Cancelar");
define("_ASCENDING","Orden ascendente");
define("_DESCENDING","Orden descendente");
define('_BACK', 'Atrás');
define('_NOTITLE', 'Sin título');

/* Image manager */
define('_IMGMANAGER','Gestor de imágenes');
define('_NUMIMAGES', '%s imágenes');
define('_ADDIMAGE','Añadir archivo de imagen');
define('_IMAGENAME','Nombre:');
define('_IMGMAXSIZE','Tamaño máximo permitido (bytes):');
define('_IMGMAXWIDTH','Ancho máximo permitido (píxeles):');
define('_IMGMAXHEIGHT','Altura máxima permitida (pixeles):');
define('_IMAGECAT','Categoría:');
define('_IMAGEFILE','Archivo de imagen:');
define('_IMGWEIGHT','Pedido:');
define('_IMGDISPLAY','¿Mostrar esta imagen?');
define('_IMAGEMIME','Tipo MIME:');
define('_FAILFETCHIMG', 'No se pudo cargar el archivo %s');
define('_FAILSAVEIMG', 'Error al almacenar la imagen %s en la base de datos');
define('_NOCACHE', 'Sin caché');
define('_CLONE', 'Clonar');
define('_INVISIBLE', 'Invisible');

//%%%%%	File Name class/xoopsform/formmatchoption.php 	%%%%%
define("_STARTSWITH", "Comienza por");
define("_ENDSWITH", "Termina con");
define("_MATCHES", "Partidas");
define("_CONTAINS", "Contiene");

//%%%%%%	File Name commentform.php 	%%%%%
define("_REGISTER","Registrarse");

//%%%%%%	File Name xoopscodes.php 	%%%%%
define("_SIZE","TAMAÑO");  // font size
define("_FONT","FONT");  // font family
define("_COLOR","COLOR");  // font color
define("_EXAMPLE","APARECER");
define("_ENTERURL","Introduzca la URL del enlace que desea añadir:");
define("_ENTERWEBTITLE","Introduzca el título del sitio web:");
define("_ENTERIMGURL","Introduzca la URL de la imagen que desea añadir.");
define("_ENTERIMGPOS","Ahora, introduzca la posición de la imagen.");
define("_IMGPOSRORL","'R' o 'r' para la derecha, 'L' o 'l' para la izquierda, 'C' o 'c' para el centro, o dejarlo en blanco.");
define("_ERRORIMGPOS","¡ERROR! Introduzca la posición de la imagen.");
define("_ENTEREMAIL","Introduzca la dirección de correo electrónico que desea añadir.");
define("_ENTERCODE","Introduzca los códigos que desea añadir.");
define("_ENTERQUOTE","Introduzca el texto que desea que se cite.");
define("_ENTERHIDDEN","Introduzca el texto que desea ocultar para los usuarios anónimos.");
define("_ENTERTEXTBOX","Por favor, introduzca texto en el cuadro de texto.");

//%%%%%		TIME FORMAT SETTINGS   %%%%%
define('_SECOND', '1 segundo');
define('_SECONDS', '%s segundos');
define('_MINUTE', '1 minuto');
define('_MINUTES', '%s minutos');
define('_HOUR', '1 hora');
define('_HOURS', '%s horas');
define('_DAY', '1 día');
define('_DAYS', '%s días');
define('_WEEK', '1 semana');
define('_MONTH', '1 mes');

define("_DATESTRING","Y/n/j G:i:s");
define("_MEDIUMDATESTRING","Y/n/j G:i");
define("_SHORTDATESTRING","Y/n/j");
/*
 The following characters are recognized in the format string:
 a - "am" or "pm"
 A - "AM" or "PM"
 d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
 D - day of the week, textual, 3 letters; i.e. "Fri"
 F - month, textual, long; i.e. "January"
 h - hour, 12-hour format; i.e. "01" to "12"
 H - hour, 24-hour format; i.e. "00" to "23"
 g - hour, 12-hour format without leading zeros; i.e. "1" to "12"
 G - hour, 24-hour format without leading zeros; i.e. "0" to "23"
 i - minutes; i.e. "00" to "59"
 j - day of the month without leading zeros; i.e. "1" to "31"
 l (lowercase 'L') - day of the week, textual, long; i.e. "Friday"
 L - boolean for whether it is a leap year; i.e. "0" or "1"
 m - month; i.e. "01" to "12"
 n - month without leading zeros; i.e. "1" to "12"
 M - month, textual, 3 letters; i.e. "Jan"
 s - seconds; i.e. "00" to "59"
 S - English ordinal suffix, textual, 2 characters; i.e. "th", "nd"
 t - number of days in the given month; i.e. "28" to "31"
 T - Timezone setting of this machine; i.e. "MDT"
 U - seconds since the epoch
 w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday)
 Y - year, 4 digits; i.e. "1999"
 y - year, 2 digits; i.e. "99"
 z - day of the year; i.e. "0" to "365"
 Z - timezone offset in seconds (i.e. "-43200" to "43200")
 */

//%%%%%		LANGUAGE SPECIFIC SETTINGS   %%%%%
define('_CHARSET', 'u-8');
define('_LANGCODE', 'es');

// change 0 to 1 if this language is a multi-bytes language
define("XOOPS_USE_MULTIBYTES", "0");
// change 0 to 1 if this language is a RTL (right to left) language
define("_ADM_USE_RTL","0");

define('_MODULES','Módulos');
define('_SYSTEM','Sistema');
define('_IMPRESSCMS_NEWS','Noticias');
define('_ABOUT','Proyecto ImpressCMS');
define('_IMPRESSCMS_HOME','Inicio del proyecto');
define('_IMPRESSCMS_COMMUNITY','Comunidad');
define('_IMPRESSCMS_ADDONS','Addons');
define('_IMPRESSCMS_WIKI','Wiki');
define('_IMPRESSCMS_BLOG','Blog');
define('_IMPRESSCMS_DONATE','¡Donar!');
define("_IMPRESSCMS_Support","¡Apoya el proyecto!");
define('_IMPRESSCMS_SOURCEFORGE','Proyecto SourceForge');
define('_IMPRESSCMS_ADMIN','Administración de');
/** The default separator used in icms_view_Tree::getNicePathFromId */
define('_BRDCRMB_SEP','&nbsp;:&nbsp;');
//Content Manager
define('_CT_NAV','Inicio');
define('_CT_RELATEDS','Páginas relacionadas');
//Security image (captcha)
define("_SECURITYIMAGE_GETCODE","Introduzca el código de seguridad");
define("_WARNINGUPDATESYSTEM","Felicidades, acaba de actualizar su sitio a la última versión de ImpressCMS!<br />Por lo tanto, para finalizar el proceso de actualización necesitará hacer clic aquí y actualizar su módulo del sistema.<br />Haga clic aquí para procesar la actualización.");

// This shows local support site in ImpressCMS menu, (if selected language is not English)
define('_IMPRESSCMS_LOCAL_SUPPORT', 'https://www.impresscms.org'); //add the local support site's URL
define('_IMPRESSCMS_LOCAL_SUPPORT_TITLE','Sitio de soporte local');
define("_ALLEFTCON","Introduzca el texto a alinear en el lado izquierdo.");
define("_ALCENTERCON","Introduzca el texto a alinear en el lado central.");
define("_ALRIGHTCON","Introduzca el texto a alinear en el lado derecho.");

define('_MODABOUT_ABOUT', 'Acerca de');
// if you have troubles with this font on your language or it is not working, download tcpdf from: http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf and add the required font in libraries/tcpdf/fonts then write down the font name here. system will then load this font for your language.
define('_PDF_LOCAL_FONT', '');
define('_CALENDAR_TYPE',''); // this value is for the local calendar used in this system, if you're not sure about this leave this value as it is!
define('_CALENDAR','Calendario');
define('_RETRYPOST','Lo sentimos, ha ocurrido un tiempo de espera. ¿Quieres publicar de nuevo?'); // autologin hack GIJ

############# added since 1.2 #############
define('_QSEARCH','Búsqueda rápida');
define('_PREV','Anterior');
define('_NEXT','Siguiente');
define('_LCL_NUM0','0');
define('_LCL_NUM1','1');
define('_LCL_NUM2','2');
define('_LCL_NUM3','3');
define('_LCL_NUM4','4');
define('_LCL_NUM5','5');
define('_LCL_NUM6','6');
define('_LCL_NUM7','7');
define('_LCL_NUM8','8');
define('_LCL_NUM9','9');
// change 0 to 1 if your language has a different numbering than latin`s alphabet
define("_USE_LOCAL_NUM","0");
define("_ICMS_DBUPDATED","Base de datos actualizada con éxito!");
define('_MD_AM_DBUPDATED',_ICMS_DBUPDATED);

define('_TOGGLETINY','Cambiar editor');
define("_ENTERHTMLCODE","Introduzca los códigos HTML que desea añadir.");
define("_ENTERPHPCODE","Introduzca los códigos PHP que desea añadir.");
define("_ENTERCSSCODE","Introduzca los códigos CSS que desea añadir.");
define("_ENTERJSCODE","Introduzca los códigos JavaScript que desea añadir.");
define("_ENTERWIKICODE","Introduzca el término wiki que desea añadir.");
define("_ENTERLANGCONTENT","Introduzca el texto que desea estar en %s.");
define('_LANGNAME', 'Inglés');
define('_ENTERYOUTUBEURL', 'Introducir url de YouTube:');
define('_ENTERHEIGHT', 'Introduzca la altura del marco');
define('_ENTERWIDTH', 'Introducir ancho del marco');
define('_ENTERMEDIAURL', 'Introducir url de medios:');
// !!IMPORTANT!! insert '\' before any char among reserved chars: "a", "A", "B", "c", "d", "D", "F", "g", "G", "h", "H", "i", "I", "j", "l", "L", "m", "M", "n", "O", "r", "s", "S", "t", "T", "U", "w", "W", "Y", "y", "z", "Z"
// insert double '\' before 't', 'r', 'n'
define("_TODAY", "	\\o\\d\\a\\y G:i");
define("_YESTERDAY", "\\Y\e\\s\\t\e\\r\\d\\a\\y G:i");
define("_MONTHDAY", "n/j G:i");
define("_YEARMONTHDAY", "Y/n/j G:i");
define("_ELAPSE", "Hace %s");
define('_VISIBLE', 'Visible');
define('_UP', 'Subir');
define('_DOWN', 'Abajo');
define('_CONFIGURE', 'Configurar');

// Added in 1.2.2
define('_FILE_DELETED', 'El archivo %s se ha eliminado correctamente');

// added in 1.3
define('_CHECKALL', 'Marcar todo');
define('_COPYRIGHT', 'Copyright');
define("_LONGDATESTRING", "F jS Y, h:iA");
define('_AUTHOR', 'Autor');
define("_CREDITS", "Crédito");
define("_LICENSE", "Licencia");
define("_LOCAL_FOOTER", 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define("_BLOCK_ID", "Bloque ID");
define('_IMPRESSCMS_PROJECT','Desarrollo del proyecto');

// added in 1.3.5
define("_FILTERS","Filtros");
define("_FILTER","Filtro");
define("_FILTERS_MSG1","Filtro de entrada: ");
define("_FILTERS_MSG2","Filtro de entrada (HTMLPurifier): ");
define("_FILTERS_MSG3","Filtro de Salida: ");
define("_FILTERS_MSG4","Filtro de salida (HTMLPurifier): ");


// added in 2.0
define('_ENTER_MENTION', 'Introduzca el nombre de usuario a mencionar:');
define( '_ENTER_HASHTAG', 'Introduzca los términos para etiquetar:');
define('_NAME', 'Nombre');

define('_OR', 'o');
