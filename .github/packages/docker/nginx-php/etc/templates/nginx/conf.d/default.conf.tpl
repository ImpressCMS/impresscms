server {
    listen ${WEB_PORT} default;
    server_name ${SERVER_NAME};
    root /srv/www/htdocs/;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files ${DOLLAR}uri ${DOLLAR}uri/ /index.php?${DOLLAR}query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php${DOLLAR} {
        fastcgi_pass php;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME ${DOLLAR}realpath_root${DOLLAR}fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}