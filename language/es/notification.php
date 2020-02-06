<?php


// RMV-NOTIFY

// Text for various templates...

define ('_NOT_NOTIFICATIONOPTIONS', 'Opciones');
define ('_NOT_UPDATENOW', 'Actualizar ahora');
define ('_NOT_UPDATEOPTIONS', 'Actualizar opciones de notificación');

define ('_NOT_CLEAR', 'Claro');
define ('_NOT_CHECKALL', 'Marcar todo');
define ('_NOT_MODULE', 'Módulo');
define ('_NOT_CATEGORY', 'Categoría');
define ('_NOT_ITEMID', 'ID');
define ('_NOT_ITEMNAME', 'Nombre');
define ('_NOT_EVENT', 'Evento');
define ('_NOT_EVENTS', 'Eventos');
define ('_NOT_ACTIVENOTIFICATIONS', 'Notificaciones y marcadores');
define ('_NOT_NAMENOTAVAILABLE', 'Nombre no disponible');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define ('_NOT_ITEMNAMENOTAVAILABLE', 'Nombre del artículo no disponible');
define ('_NOT_ITEMTYPENOTAVAILABLE', 'Tipo de artículo no disponible');
define ('_NOT_ITEMURLNOTAVAILABLE', 'URL del artículo no disponible');
define ('_NOT_DELETINGNOTIFICATIONS', 'Borrando notificaciones');
define ('_NOT_DELETESUCCESS', 'Notificación(es) eliminadas correctamente.');
define ('_NOT_UPDATEOK', 'Opciones de notificación actualizadas');
define ('_NOT_NOTIFICATIONMETHODIS', 'El método de notificación es');
define ('_NOT_EMAIL', 'email');
define ('_NOT_PM', 'mensaje privado');
define ('_NOT_DISABLE', 'deshabilitado');
define ('_NOT_CHANGE', 'Cambiar');

define ('_NOT_NOACCESS', 'No tiene permiso para acceder a esta página.');

// Text for module config options

define ('_NOT_NOTIFICATION', 'Notificación');

define ('_NOT_CONFIG_ENABLED', 'Activar notificación');
define ('_NOT_CONFIG_ENABLEDDSC', 'Este módulo permite a los usuarios seleccionar para ser notificados cuando ciertos eventos ocurran. Elija "Si" para habilitar esta función.');

define ('_NOT_CONFIG_EVENTS', 'Habilitar eventos específicos');
define ('_NOT_CONFIG_EVENTSDSC', 'Seleccione qué eventos de notificación pueden suscribirse sus usuarios.');

define ('_NOT_CONFIG_ENABLE', 'Activar notificación');
define ('_NOT_CONFIG_ENABLEDSC', 'Este módulo permite a los usuarios ser notificados cuando ocurren ciertos eventos. Seleccione si a los usuarios se les deben presentar opciones de notificación en un bloque (estilo Block-), dentro del módulo (estilo Inline), o ambos. Para la notificación de estilo bloque, el bloque de opciones de notificación debe estar habilitado para este módulo.');
define ('_NOT_CONFIG_DISABLE', 'Desactivar notificación');
define ('_NOT_CONFIG_ENABLEBLOCK', 'Activar sólo estilo de bloque');
define ('_NOT_CONFIG_ENABLEINLINE', 'Habilitar sólo estilo en línea');
define ('_NOT_CONFIG_ENABLEBOTH', 'Habilitar notificaciones (ambos estilos)');

// For notification about comment events

define ('_NOT_COMMENT_NOTIFY', 'Comentario añadido');
define ('_NOT_COMMENT_NOTIFYCAP', 'Notificarme cuando se publique un nuevo comentario para este artículo.');
define ('_NOT_COMMENT_NOTIFYDSC', 'Recibir notificación cada vez que un nuevo comentario es publicado (o aprobado) para este artículo.');
define ('_NOT_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notificación automática: Comentario añadido a {X_ITEM_TYPE}');

define ('_NOT_COMMENTSUBMIT_NOTIFY', 'Comentario enviado');
define ('_NOT_COMMENTSUBMIT_NOTIFYCAP', 'Notificarme cuando se envía un nuevo comentario (esperando aprobación) para este artículo.');
define ('_NOT_COMMENTSUBMIT_NOTIFYDSC', 'Recibir notificación cada vez que se envía un nuevo comentario (esperando aprobación) para este artículo.');
define ('_NOT_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} notificación automática: Comentario enviado para {X_ITEM_TYPE}');

// For notification bookmark feature
// (Not really notification, but easy to do with this module)

define ('_NOT_BOOKMARK_NOTIFY', 'Marcador');
define ('_NOT_BOOKMARK_NOTIFYCAP', 'Marcar este elemento (sin notificación).');
define ('_NOT_BOOKMARK_NOTIFYDSC', 'Mantener un seguimiento de este elemento sin recibir ninguna notificación de evento.');

// For user profile
// FIXME: These should be reworded a little...

define ('_NOT_NOTIFYMETHOD', 'Método de notificación: Cuando monitorea por ejemplo un foro, ¿cómo le gustaría recibir notificaciones de actualizaciones?');
define ('_NOT_METHOD_EMAIL', 'Correo electrónico (usar dirección en mi perfil)');
define ('_NOT_METHOD_PM', 'Mensaje privado');
define ('_NOT_METHOD_DISABLE', 'Desactivar temporalmente');

define ('_NOT_NOTIFYMODE', 'Modo de notificación por defecto');
define ('_NOT_MODE_SENDALWAYS', 'Notificarme de todas las actualizaciones seleccionadas');
define ('_NOT_MODE_SENDONCE', 'Notificarme solo una vez');
define ('_NOT_MODE_SENDONCEPERLOGIN', 'Notificarme una vez y desactivar hasta que vuelva a iniciar sesión');

define ('_NOT_NOTHINGTODELETE', 'No hay nada que eliminar.');

// Added in 1.3.1
define("_NOT_RUSUREDEL", "¿Estás seguro de que quieres eliminar estas notificaciones?");