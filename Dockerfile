  FROM php:8.2-fpm

  RUN apt-get update && apt-get install -y \
      libonig-dev \
      libzip-dev \
      unzip \
      zip \
      curl \
      procps \
      net-tools \
      && docker-php-ext-install pdo pdo_mysql zip

  COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

  WORKDIR /var/www/html

  CMD ["php-fpm", "-F"]

  EXPOSE 9000