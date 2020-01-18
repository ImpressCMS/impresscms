ARG GITHUB_REPOSITORY
ARG CONTAINER_TAG
FROM docker.pkg.github.com/$GITHUB_REPOSITORY/nginx-php:$CONTAINER_TAG

COPY ./etc/ /etc/

COPY ./bin/ /bin/

RUN apk add --no-cache \
	mysql \
	mysql-client && \
	echo "DELETE FROM mysql.user WHERE User='';" > /tmp/install.sql && \
	echo "DROP DATABASE IF EXISTS test;" >> /tmp/install.sql && \
	echo "CREATE DATABASE impresscms;" >> /tmp/install.sql && \
	echo "CREATE USER 'icms'@'127.0.0.1' IDENTIFIED BY 'docker';" >> /tmp/install.sql && \
	echo "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON impresscms.* TO 'icms'@'docker';" >> /tmp/install.sql && \
	mysqld --bootstrap --verbose=0 < /tmp/install.sql && \
	rn -rf /tmp/install.sql