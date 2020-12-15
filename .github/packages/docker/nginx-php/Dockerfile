FROM alpine:3.11

ENV URL=http://localhost \
    DB_TYPE=pdo.mysql \
    DB_HOST="" \
    DB_USER=root \
    DB_PASS="" \
    DB_PCONNECT=0 \
    DB_NAME=icms \
    DB_CHARSET=utf8 \
    DB_COLLATION=utf8_general_ci \
    DB_PREFIX="" \
    APP_KEY="" \
    DB_PORT=3306 \
    INSTALL_ADMIN_PASS="" \
    INSTALL_ADMIN_LOGIN="" \
    INSTALL_ADMIN_NAME="" \
    INSTALL_ADMIN_EMAIL="" \
    INSTALL_LANGUAGE=english \
    WEB_PORT=80 \
    SERVER_NAME=ICMSServer

COPY ./build/ /srv/www/
COPY ./etc/ /etc/
COPY ./bin/ /usr/local/bin/

RUN apk add --no-cache \
		nginx \
		php7-fpm \
		php7-json \
		php7-pdo \
		php7-gd \
		php7-curl \
		php7-mbstring \
		php7-session \
		php7-ctype \
		php7-fileinfo \
		php7-gettext \
		php7-iconv \
		php7-opcache \
		php7-pcntl \
		php7-pdo_mysql \
		php7-phar \
		php7-posix \
		yarn \
		netcat-openbsd \
		gettext

RUN rm -rf /var/www && \
	mkdir -p www && \
	ln -s /srv/www /var/www

RUN yarn global add pm2 && \
	pm2 start --pid /var/run/nginx.pid --interpreter none /usr/sbin/nginx -- -c /etc/nginx/nginx.conf && \
	pm2 start --pid /var/run/php-fpm.pid --interpreter none --name php-fpm /usr/sbin/php-fpm7 -- -R && \
	pm2 save

WORKDIR /srv/www

VOLUME /srv/www/storage
VOLUME /srv/www/htdocs/uploads
VOLUME /etc/impresscms

EXPOSE $WEB_PORT/tcp

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]