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
    DB_SALT="" \
    DB_PORT=3306 \
    INSTALL_ADMIN_PASS="" \
    INSTALL_ADMIN_LOGIN="" \
    INSTALL_ADMIN_NAME="" \
    INSTALL_ADMIN_EMAIL="" \
    INSTALL_LANGUAGE=english

COPY ./build/ /srv/www

RUN apk add --no-cache \
		nginx \ # /etc/nginx/conf.d/default.conf
		php7-fpm \
		php7-json \
		php7-pdo \
		php7-gd \
		php7-curl \
		php7-mbstring \
		php7-session \
		yarn \
		gettext && \
	chmod +x /usr/local/bin/*.sh && \
	rm -rf /var/www && \
	mkdir -p www && \
	ln -s /srv/www /var/www && \
	yarn global add pm2