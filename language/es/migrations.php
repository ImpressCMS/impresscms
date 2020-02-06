<?php

/**
 * Constants used for migrations
 */
define('_MIGRATION_RANK_1', 'Solo apareciendo');
define('_MIGRATION_RANK_2', 'No demasiado tímido para hablar');
define('_MIGRATION_RANK_3', 'Muy normal');
define('_MIGRATION_RANK_4', 'Sólo no se puede alejar');
define('_MIGRATION_RANK_5', 'Hogar lejos de casa');
define('_MIGRATION_RANK_6', 'Moderador');
define('_MIGRATION_RANK_7', 'Webmaster');

define('_MIGRATION_SMILE_1', 'Muy feliz');
define('_MIGRATION_SMILE_2', 'Sonrisa');
define('_MIGRATION_SMILE_3', 'Triste');
define('_MIGRATION_SMILE_4', 'Sorprendido');
define('_MIGRATION_SMILE_5', 'Confundido');
define('_MIGRATION_SMILE_6',  'Herramienta');
define('_MIGRATION_SMILE_7', 'Risas');
define('_MIGRATION_SMILE_8', 'Loco');
define('_MIGRATION_SMILE_9', 'Razz');
define('_MIGRATION_SMILE_10', 'Embarazado');
define('_MIGRATION_SMILE_11', 'Llorar (muy triste)');
define('_MIGRATION_SMILE_12', 'Malvado o muy loco');
define('_MIGRATION_SMILE_13', 'Ojos Rolling');
define('_MIGRATION_SMILE_14', 'Vestido');
define('_MIGRATION_SMILE_15', 'Otra pinta de cerveza');
define('_MIGRATION_SMILE_16', 'Horarios de trabajo');
define('_MIGRATION_SMILE_17', 'Tengo una idea');

define('_MIGRATION_PAGE_2', 'Panel de control de admin');
define('_MIGRATION_PAGE_3', 'Avatares');
define('_MIGRATION_PAGE_4', 'Banners');
define('_MIGRATION_PAGE_5', 'Administrador de bloques');
define('_MIGRATION_PAGE_6', 'Bloquear posiciones');
define('_MIGRATION_PAGE_7', 'Comentarios');
define('_MIGRATION_PAGE_9', 'Buscar usuarios');
define('_MIGRATION_PAGE_10', 'Etiqueta personalizada');
define('_MIGRATION_PAGE_11', 'Grupos');
define('_MIGRATION_PAGE_12', 'Gestor de imágenes');
define('_MIGRATION_PAGE_13', 'Usuarios de correo');
define('_MIGRATION_PAGE_14', 'Administrador de módulos');
define('_MIGRATION_PAGE_15', 'Gestor de enlaces');
define('_MIGRATION_PAGE_16', 'Preferencias');
define('_MIGRATION_PAGE_17', 'Sonrisas');
define('_MIGRATION_PAGE_18', 'Plantillas');
define('_MIGRATION_PAGE_19',  'Rango de usuario');
define('_MIGRATION_PAGE_20', 'Editar usuario');
define('_MIGRATION_PAGE_21', 'Comprobador de versiones');

define('_MIGRATION_WELCOME_ANONYMOUS', <<<EOF
This is sample text for a block. If you are the administrator please log in to view more information.

Learn more about ImpressCMS:
<ul><li>[url=https://www.impresscms.org]Project Home[/url]</li><li>[url=https://www.impresscms.org/modules/iforum/]ImpressCMS Community[/url]</li><li>[url=https://www.impresscms.org/modules/downloads/index.php]ImpressCMS Addons[/url]</li><li>[url=https://www.impresscms.org/modules/simplywiki/]ImpressCMS Wiki[/url]</li><li>[url=https://www.impresscms.org/modules/news/]ImpressCMS Blog[/url]</li></ul>
EOF
);

define('_MIGRATION_WELCOME_WEBMASTER', <<<EOF
Welcome to your new ImpressCMS powered website. If you haven't already, please delete the [b]install[/b] folder from the server and ensure that [b].env[/b] is not writeable (chmod 444).

To begin administering your new ImpressCMS powered website you can click the [b]Administration[/b] Menu link located on the left of this page.

You may want to begin by editing your website [b]Preferences[/b]: In the admin panel, hover over the [b]System[/b] dropdown and select [b]Preferences.[/b]

Afterwards you can begin adding [b]Modules[/b] and [b]Themes[/b].
Many of the available modules and themes for ImpressCMS, are available at the [url=https://www.impresscms.org/modules/downloads/]Addons[/url] section of the [url=http://www.impresscms.org]projects website[/url].

You will also need to begin using [b]Blocks[/b]. You can begin by removing this block. You can do this by navigating to System Admin > Blocks, and the selecting "Webmasters" in the [b]Groups[/b] select box. You will then be able to see the blocks available for the Webmasters group, which this block is!

For more information about working with ImpressCMS, please use the links below.
<ul><li>[url=https://www.impresscms.org/modules/simplywiki/index.php?page=English#Using_ImpressCMS]Using ImpressCMS[/url]</li><li>[url=http://www.impresscms.org/modules/simplywiki/index.php?page=English#Customizing_ImpressCMS]Customizing ImpressCMS[/url]</li><li>[url=http://www.impresscms.org/modules/simplywiki/index.php?page=English#Developing_for_ImpressCMS]Developing for ImpressCMS[/url]</li></ul>
We warmly invite you to join [url=https://www.impresscms.org/modules/iforum/]The ImpressCMS Community[/url] - Where you can make contributions, get help, help others, etc...
EOF
);

define('_MIGRATIONS_LOCAOL_STNAME', 'ImpressCMS');
define('_MIGRATIONS_LOCAL_SLOCGAN', 'Hacer una impresión duradera');
define('_MIGRATIONS_EXT_DATE_FUNC', '0');
define('_MIGRATIONS_INSTALL_ANON', 'Anónimo');
define('_MIGRATIONS_INSTALL_L165', 'El sitio está actualmente cerrado por mantenimiento. Por favor, vuelva más tarde.');
define('_MIGRATIONS_WELCOME_MSG_CONTENT', 'Bienvenido {UNAME},

Su cuenta ha sido activada con éxito en {X_SITENAME}. Como miembro de nuestro sitio, usted se beneficiará de todas las características reservadas a los miembros registrados.

Una vez más, bienvenido a nuestro sitio. Visítenos a menudo !

Si no se registró en nuestro sitio, por favor contáctenos a la siguiente dirección {X_ADMINMAIL}y arreglaremos la situación.

-----------
Verdaderamente,
{X_SITENAME}
{X_SITEURL}');
define('_MIGRATIONS_INSTALL_DISCLMR', 'Mientras que los administradores y moderadores de este sitio intentarán eliminar o editar cualquier material generalmente censurable lo antes posible, es imposible revisar cada mensaje. Por lo tanto, usted reconoce que todos los mensajes realizados en este sitio expresan las opiniones y opiniones del autor y no de los administradores, los moderadores o webmaster (excepto los mensajes de estas personas) y por lo tanto no serán considerados responsables.

Usted acepta no publicar ningún material abusivo, oscuro, vulgar, calumnioso, odioso, amenazante, de orientación sexual o cualquier otro material que pueda violar cualquier ley aplicable. Hacerlo puede llevar a que usted sea prohibido de forma inmediata y permanente (y a que su proveedor de servicios esté informado). La dirección IP de todos los mensajes se registra para ayudar a hacer cumplir estas condiciones. No se permite la creación de cuentas múltiples para un solo usuario. Usted acepta que el webmaster, el administrador y los moderadores de este sitio tienen derecho a eliminar, editar, mover o cerrar cualquier tema en cualquier momento si lo considera oportuno. Como usuario, usted acepta que cualquier información que haya introducido anteriormente sea almacenada en una base de datos. Aunque esta información no será revelada a ningún tercero sin su consentimiento del webmaster, el administrador y los moderadores no pueden ser considerados responsables de cualquier intento de piratería que pueda llevar a que los datos se vean comprometidos.

Este sistema de sitio utiliza cookies para almacenar información en su computadora local. Estas cookies no contienen ninguna de las informaciones que usted ha introducido anteriormente, sólo sirven para mejorar su placer visual. La dirección de correo electrónico se utiliza sólo para confirmar sus datos de registro y contraseña (y para enviar nuevas contraseñas si usted olvida su actual).

Al hacer clic en Registro, usted acepta estar obligado por estas condiciones.');
define('_MIGRATIONS_INSTALL_PRIVPOLICY', <<<EOF
<p>This privacy policy sets out how {X_SITENAME} uses and protects any information that you provide when you use this website. {X_SITENAME} is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement. {X_SITENAME} may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.
</p><p>
This policy is effective from [date].
</p>
<h2>What we collect</h2>
<p>
We may collect the following information:
<ul>
<li>name and job title</li>
<li>contact information including email address</li>
<li>demographic information such as postcode, preferences and interests</li>
<li>other information relevant to customer surveys and/or offers</li></ul>
</p>
<h2>What we do with the information we gather</h2>
<p>
We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:
<ul>
<li>Internal record keeping.</li>
<li>We may use the information to improve our products and services.</li>
<li>We may periodically send promotional email about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>
<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email.</li>
<li>We may use the information to customise the website according to your interests.</li></ul>
</p>
<h2>Security</h2>
<p>
We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.
</p>
<h2>How we use cookies</h2>
<p>
A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences.
</p><p>
We use traffic log cookies to identify which pages are being used & for authenticating you as a registered member. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us.
</p><p>
You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website including registration and logging in.
</p>
<h2>Links to other websites</h2>
<p>
Our website may contain links to enable you to visit other websites of interest easily. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.
</p>
<h2>Controlling your personal information</h2>
<p>
You may choose to restrict the collection or use of your personal information in the following ways:
<ul>
<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li>
<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us at [email address]</li></ul>
</p><p>
We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen. You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please write to [address].
</p><p>
If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible, at the above address. We will promptly correct any information found to be incorrect.
</p>
EOF
);
define('_MIGRATIONS_LOCAL_FOOTER', 'Powered by ImpressCMS &copy; 2007-' . date('Y', time()) . ' <a href=\"https://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a><br />Hosting by <a href="http://www.siteground.com/impresscms-hosting.htm?afcode=7e9aa639d30265c079823a498f5b8f15">SiteGround</a>'); //footer Link to local support site
define('_MIGRATIONS_LOCAL_SENSORTXT', '#OOPS#'); //Add local translation
define('_MIGRATIONS_DEF_LANG_TAGS', 'es,de'); //Add local translation
define('_MIGRATIONS_DEF_LANG_NAMES', 'chico'); //Add local translation
define('_MIGRATIONS_LOCAL_LANG_NAMES', 'Inglés,Deutsch'); //Add local translation
define('_MIGRATIONS_AM_RSSLOCALLINK_DESC', 'https://www.impresscms.org/modules/news/rss.php'); //Link to the rrs of local support site
define('_MIGRATIONS_INSTALL_WEBMASTER', 'Webmasters');
define('_MIGRATIONS_INSTALL_WEBMASTERD', 'Webmasters de este sitio');
define('_MIGRATIONS_INSTALL_REGUSERS', 'Usuarios registrados');
define('_MIGRATIONS_INSTALL_REGUSERSD', 'Grupo de usuarios registrados');
define('_MIGRATIONS_INSTALL_ANONUSERS', 'Usuarios anónimos');
define('_MIGRATIONS_INSTALL_ANONUSERSD', 'Grupo de usuarios anónimo');
define('_MIGRATIONS_WELCOME_WEBMASTER', '¡Bienvenido Webmaster !');
define('_MIGRATIONS_WELCOME_ANONYMOUS', 'Bienvenidos a un sitio web basado en ImpressCMS !');