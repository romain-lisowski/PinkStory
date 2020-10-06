FROM node:12.2.0

WORKDIR /var/www/app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install and cache app dependencies
RUN npm install @vue/cli@3.7.0 -g

COPY . .
EXPOSE 8080
