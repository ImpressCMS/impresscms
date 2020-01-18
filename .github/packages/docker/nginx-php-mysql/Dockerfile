ARG GITHUB_REPOSITORY
ARG CONTAINER_TAG
FROM docker.pkg.github.com/$GITHUB_REPOSITORY/nginx-php:$CONTAINER_TAG

COPY ./etc/ /etc/

COPY ./bin/ /bin/

RUN apk add --no-cache \
	mysql \
	mysql-client && \
	mkdir -p /srv/mysql && \
	mysql_install_db --user=root --datadir=/srv/mysql && \
	echo "DROP DATABASE IF EXISTS test;" > /tmp/install.sql && \
	echo "CREATE DATABASE IF NOT EXISTS impresscms;" >> /tmp/install.sql && \
	echo "use mysql;" >> /tmp/install.sql && \
	echo "ALTER USER 'root'@'localhost' IDENTIFIED BY 'icmsdocker';" >> /tmp/install.sql && \
	echo "FLUSH PRIVILEGES;" >> /tmp/install.sql && \
	mysqld --datadir=/srv/mysql --bootstrap --verbose=0 < /tmp/install.sql && \
	rn -rf /tmp/install.sql