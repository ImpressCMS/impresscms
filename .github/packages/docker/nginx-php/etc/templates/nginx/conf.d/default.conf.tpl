server {
    listen ${WEB_PORT} default;
    server_name ${SERVER_NAME};
    root "/srv/www/htdocs/";

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

	error_page 400 /error.php?e=400;
	error_page 401 /error.php?e=401;
	error_page 402 /error.php?e=402;
	error_page 403 /error.php?e=403;
	error_page 405 /error.php?e=405;
	error_page 406 /error.php?e=406;
	error_page 407 /error.php?e=407;
	error_page 408 /error.php?e=408;
	error_page 409 /error.php?e=409;
	error_page 410 /error.php?e=410;
	error_page 411 /error.php?e=411;
	error_page 412 /error.php?e=412;
	error_page 413 /error.php?e=413;
	error_page 414 /error.php?e=414;
	error_page 415 /error.php?e=415;
	error_page 416 /error.php?e=416;
	error_page 417 /error.php?e=417;
	error_page 426 /error.php?e=426;
	error_page 428 /error.php?e=428;
	error_page 429 /error.php?e=429;
	error_page 431 /error.php?e=431;
	error_page 500 /error.php?e=500;
	error_page 501 /error.php?e=501;
	error_page 502 /error.php?e=502;
	error_page 503 /error.php?e=503;
	error_page 504 /error.php?e=504;
	error_page 505 /error.php?e=505;
	error_page 506 /error.php?e=506;
	error_page 510 /error.php?e=510;
	error_page 511 /error.php?e=511;

	location ~ (index|error)\.php${DOLLAR} {
        fastcgi_pass php;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME ${DOLLAR}realpath_root${DOLLAR}fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}