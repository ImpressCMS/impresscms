ARG GITHUB_REPOSITORY
ARG CONTAINER_TAG
FROM docker.pkg.github.com/$GITHUB_REPOSITORY/nginx-php:$CONTAINER_TAG

COPY ./etc/ /etc/

COPY ./bin/ /usr/local/bin/

RUN apk add --no-cache \
	mysql \
	mysql-client && \
	mysql_install_db --user=root --datadir=/var/lib/mysql/ && \
	pm2 resurrect && \
	pm2 start --pid /var/run/mysql.pid --interpreter none --name mysql /usr/bin/mysqld -- --user=root && \
	pm2 save && \
	echo "DROP DATABASE IF EXISTS test;" > /tmp/install.sql && \
	echo "CREATE DATABASE IF NOT EXISTS impresscms;" >> /tmp/install.sql && \
	echo "CREATE USER 'icms'@'%' IDENTIFIED BY 'docker';" >> /tmp/install.sql && \
	echo "CREATE USER 'icms'@'localhost' IDENTIFIED BY 'docker';" >> /tmp/install.sql && \
	echo "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON impresscms.* TO 'icms'@'%';" >> /tmp/install.sql && \
	echo "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON impresscms.* TO 'icms'@'localhost';" >> /tmp/install.sql && \
	echo "FLUSH PRIVILEGES;" >> /tmp/install.sql && \
	echo "use mysql;" >> /tmp/install.sql && \
	mysql -vvv -e 'source /tmp/install.sql' && \
	mysql -vvv -e 'SELECT User, Host, Password FROM mysql.user;' && \
	rm -rf /tmp/install.sql
