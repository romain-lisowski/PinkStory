FROM nginx:1.18

# ssl certificates
COPY ./certificate/localhost.crt /etc/ssl/certs/
COPY ./certificate/localhost.key /etc/ssl/private/

# php ini configuration
COPY ./conf.d/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/app
EXPOSE 443