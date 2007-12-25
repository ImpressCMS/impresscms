<?php
// $Id: upgrade.php 558 2006-06-20 06:35:23Z skalpa $
//Traducción para ImpressCMS 0.5 por debianus

define( "_XOOPS_UPGRADE", "Actualización del sistema ImpressCMS" );
define( "_CHECKING_APPLIED", "Comprobando las actualizaciones aplicadas:" );
define( "_SET_FILES_WRITABLE", "Debe asignar permisos de escritura a los siguientes archivos antes de proceder:" );
define( "_NO_NEED_UPGRADE", "La actualización no es necesaria. Por favor, elimine este directorio del servidor" );
define( "_NEED_UPGRADE", "La actualización es necesaria" );
define( "_PROCEED_UPGRADE", "Proceder con la actualizadión" );
define( "_PERFORMING_UPGRADE", "Ejecutando actualización %s" );

define( "_USER_LOGIN", "Inicio de sesión como usuario" );

define( "_MANUAL_INSTRUCTIONS", "Instrucciones de actualización: manual" );

// %s is filename
define( "_FAILED_PATCH", "Falló el parche %s" );
define( "_APPLY_NEXT", "Aplicar próxima actualización (%s)" );
define( "_COPY_RED_LINES", "Copiar las líneas rojas siguientes a %s" );

define( "_FINISH", "Finalizado" );
define( "_RELOAD", "Recargar" );

define('_UPGRADE_CHARSET', _CHARSET); 

define("_UPGRADE_PRIVPOLICY", "<p>Es necesario que establezca la política de privacidad de su sitio web y que la publique en el mismo en el caso de admitir el registro de nuevos usuarios. Probablemente en su país exista legislación sobre la materia que debe conocer y cumplir. Por ejemplo, en España hay que tener en cuenta lo dispuesto en la Ley Orgánica 15/1999, de 13 de Diciembre, de Protección de Datos de carácter personal (en adelante LOPD) y el Real Decreto 994/1999, de 11 de Junio, que aprueba el Reglamento de medidas de seguridad de los ficheros automatizados que contengan datos de carácter personal (en adelante Reglamento 994/1999). 
</p><p>
También debe tener en cuenta que los requisitos de la política de privacidad pueden ser distintos según cual sea la información exigida a los visitantes para registrarse o la que estos pueden introducir al hacer uso del sitio; piense por ejemplo en el caso de que una web realice algún tipo de transacción económica proporcionándose datos bancarios, o que recoga información médica o política. Normalmente las legislaciones son mucho más estrictas con relación al tratamiento de este tipo de datos de carácter personal.
</p><p>
A continuación presentamos un ejemplo de política de privacidad general para un sitio que no gestiona información cuya seguridad deba ser atendida con medidas especiales. Por favor, ajuste el texto según tus necesidades o introduce uno nuevo. Para ello, en el panel de control del sitio vaya a 'Preferencias' y dentro de éstas a la'Configuración del usuario'; haga clic en 'Editar' y al final de la página que se mostrará tiene la opción de activar la política de privacidad y fijar el texto de la misma.
</p><p>
<h2>Información general</h2>
</p><p>
La política de privacidad que se describe a continuación sólo es aplicable al presente sitio web (puedes poner su nombre), entendiendo como tal todas las páginas y subpáginas incluidas en el dominio (nombre de tu dominio). Declinamos cualquier responsabilidad sobre las diferentes políticas de privacidad y protección de datos de carácter personal que puedan contener los sitios web a los cuales pueda accederse a través de los hipervínculos ubicados en este sitio y no gestionados directamente por nosotros. La presente política de privacidad está vigente en este sitio desde el día.
</p><p>
Ponemos en conocimiento de los usuarios de este sitio web que la presente declaración refleja la política en materia de protección de datos del mismo. Esta política se ha configurado respetando escrupulosamente la normativa vigente en materia de protección de datos personales, esto es, entre otras, la regulada en la Ley Orgánica 15/1999, de 13 de Diciembre, de Protección de Datos de carácter personal (en adelante LOPD) y el Real Decreto 994/1999, de 11 de Junio, que aprueba el Reglamento de medidas de seguridad de los ficheros automatizados que contengan datos de carácter personal (en adelante Reglamento 994/1999).
</p>
<h2>Recogida de datos de carácter personal.</h2>
<p>
No recopilamos datos de carácter personal sin el consentimiento de los usuarios. En caso de que decidas registrarte como usuario en este sitio los únicos datos que solicitaremos con carácter obligatorio son:
</p>
<p>
<ul>
<li>Nombre de usuario, pudiendo elegir el que prefieras. No tiene porqué ser tu nombre y apellidos y el nombre social de las personas físicas</li>
<li>Una dirección de correo electrónico. Es necesaria para activar tu cuenta de usuario y para enviarte una nueva contraseña en caso de haber olvidado la que elegiste o tengas otro problema.</li>
<li>Una contraseña de usuario para acceder al sitio; es de tu libre elección, salvo en cuanto a su extensión.</li></ul>
</p><p>
Nota: caso de tener un sitio web que almacene un información personal, como puede ser un sitio de soporte técnico, de gestión del personal de una empresa, una intranet etc, quizás deberías añadir algo como:
</p><p>
Dichos datos proporcionados voluntariamente por el usuario podrán ser incorporados a un fichero automatizado, registrado ante la Agencia Española de Protección de Datos, bajo la titularidad de (dueño de la web). En consecuencia, el usuario que voluntariamente nos proporcione sus datos personales acepta expresamente el tratamiento de los mismos, con la exclusiva finalidad de gestionar su condición de usuario registrado de los servicios que prestamos. En cualquier caso, los datos recogidos serán tratados siempre respetando la normativa vigente en materia de protección de datos de carácter personal.
</p>
<h2>Medidas de seguridad.</h2> 
<p>
Los datos personales comunicados por el usuario pueden ser almacenados en bases de datos automatizadas o no, cuya titularidad nos corresponde en exclusiva, asumiendo nosotros todas las medidas de índole técnica, organizativa y de seguridad que garantizan la confidencialidad, integridad y calidad de la información contenida en las mismas de acuerdo con lo establecido en la LOPD y en el Reglamento 994/1999.
</p><p>
La comunicación con los usuarios no utiliza un canal seguro y los datos transmitidos no son cifrados, por lo que se solicita a los usuarios que se abstengan de enviar aquellos datos personales que merezcan la consideración de datos especialmente protegidos en los términos del artículo 7 de la LOPD, ya que las medidas de seguridad aplicables a un canal no seguro lo hacen desaconsejable.
</p>
<h2>Finalidad del tratamiento de los datos</h2>
<p>
Los datos de carácter personal que sean comunicados voluntariamente por el usuario se destinarán únicamente a la finalidad concreta de gestionar su condición de tal y serán tratados con la más absoluta confidencialidad, destinándose únicamente a aquellas finalidades para las que fueron recabados y de las que expresamente se informa al usuario en el momento de su recogida por medio de la presente Política de Privacidad.
</p>
<h2>Cesión de datos</h2>
<p>
Los datos de carácter personal recogidos a través de este sitio no serán objeto de cesión a ninguna otra persona física o jurídica, salvo que dicha cesión se encuentre amparada por la LOPD tanto en cuanto a los destinatarios de la misma como en cuanto a su objeto.
</p><p>
Si tu sitio web tiene carácter comercial, puedes añadir algo como:
</p><p>
El usuario que voluntariamente nos comunique sus datos a través de este sitio consiente expresamente la utilización de los mismos para el envío de información comercial por vía electrónica de los productos y servicios comercializados por nosotros, en estricto cumplimiento de lo dispuesto en la legislación vigente en materia de Servicios de la Sociedad de la Información en lo que a comunicaciones comerciales se refiere, salvo que manifieste su oposición.
<p>
<h2>Calidad de los datos: derechos de acceso, oposición, rectificación y cancelación.</h2>
<p>
Nos comprometemos a mantener actualizados en todo momento los datos personales que voluntariamente nos hayan proporcionado los usuarios de este sitio, de manera que respondan verazmente a la identidad y características personales de dichos usuarios. Por ello, cualquier usuario puede en cualquier momento ejercer el derecho a acceder, rectificar y, en su caso, cancelar sus datos de carácter personal suministrados mediante comunicación dirigida al correo de este sitio.
</p>
<h2>Sobre cómo usamos 'cookies'</h2>
<p>
Se denominan así a unos pequeños archivos con respecto a los cuales se te pregunta (según la configuración que hayas elegido en tu navegador) si aceptas que sean almacenados en tu disco duro. Si estás de acuerdo, el archivo ayuda a analizar el tráfico web y conocer cuando visitas nuestro sitio. También son útiles para almacenar información acerca de tus preferencias al visitar nuestro sitio.
</p><p>
Puedes elegir aceptar o rechazar 'cookies'. Muchos navegadores automáticamente los aceptan, pero normalmente puedes modificar la configuración del que uses para rechazarlos como regla general; sin embargo, esto puede impedir el registro y la entrada en un sitio web.
</p>");
?>