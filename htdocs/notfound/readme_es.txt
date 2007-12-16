Para poder redirigir automáticamente las páginas 400 y 500 a la página "NotFound", necesitas añadir estas líneas en tu archivo .htaccess:

ErrorDocument 404 http://yoursite.com/notfound/
ErrorDocument 500 http://yoursite.com/notfound/