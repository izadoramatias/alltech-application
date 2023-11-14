# Use a imagem oficial do PHP com Apache
FROM php:8.1-apache

# Atualize os pacotes e instale ferramentas necessárias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Instale o Composer globalmente
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Instale o Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure o Xdebug
ARG XDEBUG_ENABLED=false
ARG XDEBUG_PORT=9000
RUN if [ "$XDEBUG_ENABLED" = "true" ]; then \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=$XDEBUG_PORT" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi

# Crie um diretório de trabalho para seus projetos PHP
WORKDIR /var/www/html

RUN composer install
RUN php bin/console doctrine:migrations:migrate
RUN php bin/console doctrine:fixtures:load

# Exponha a porta 80 para acesso via navegador
EXPOSE 80

# Inicie o servidor Apache
CMD ["apache2-foreground"]
