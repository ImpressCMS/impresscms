ARG GITHUB_USER=MekDrop
ARG GITHUB_REPO_NAME=impresscms
FROM docker.pkg.github.com/$GITHUB_USER/$GITHUB_REPO_NAME/nginx-php:$VERSION

COPY ./ecosystem.pm2.yml /etc/

RUN apk add --no-cache mysql mysql-client