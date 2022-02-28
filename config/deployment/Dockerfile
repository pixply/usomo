FROM php:7.4-apache

# persistent dependencies
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
	# Ghostscript is required for rendering PDF previews
	ghostscript \
	; \
	rm -rf /var/lib/apt/lists/*

# Use this for new building the image
# ADD https://www.google.com /time.now

# install the PHP extensions we need (https://make.wordpress.org/hosting/handbook/handbook/server-environment/#php-extensions)
RUN set -ex; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get update; \
	apt-get install -y --no-install-recommends \
	libfreetype6-dev \
	libjpeg-dev \
	libmagickwand-dev \
	libpng-dev \
	libzip-dev \
	; \
	\
	docker-php-ext-configure gd \
	--with-freetype \
	--with-jpeg \
	; \
	docker-php-ext-install -j "$(nproc)" \
	bcmath \
	exif \
	gd \
	mysqli \
	zip \
	; \
	pecl install imagick-3.4.4; \
	docker-php-ext-enable imagick; \
	rm -r /tmp/pear; \
	\
	# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
	apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
	| awk '/=>/ { print $3 }' \
	| sort -u \
	| xargs -r dpkg-query -S \
	| cut -d: -f1 \
	| sort -u \
	| xargs -rt apt-mark manual; \
	\
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN set -eux; \
	docker-php-ext-enable opcache; \
	{ \
	echo 'opcache.memory_consumption=128'; \
	echo 'opcache.interned_strings_buffer=8'; \
	echo 'opcache.max_accelerated_files=4000'; \
	echo 'opcache.revalidate_freq=2'; \
	echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini
# https://wordpress.org/support/article/editing-wp-config-php/#configure-error-logging
RUN { \
	# https://www.php.net/manual/en/errorfunc.constants.php
	# https://github.com/docker-library/wordpress/issues/420#issuecomment-517839670
	echo 'error_reporting = E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_RECOVERABLE_ERROR'; \
	echo 'display_errors = Off'; \
	echo 'display_startup_errors = Off'; \
	echo 'log_errors = On'; \
	echo 'error_log = /dev/stderr'; \
	echo 'log_errors_max_len = 1024'; \
	echo 'ignore_repeated_errors = On'; \
	echo 'ignore_repeated_source = Off'; \
	echo 'html_errors = Off'; \
	} > /usr/local/etc/php/conf.d/error-logging.ini

RUN set -eux; \
	a2enmod rewrite expires; \
	\
	# https://httpd.apache.org/docs/2.4/mod/mod_remoteip.html
	a2enmod remoteip; \
	{ \
	echo 'RemoteIPHeader X-Forwarded-For'; \
	# these IP ranges are reserved for "private" use and should thus *usually* be safe inside Docker
	echo 'RemoteIPTrustedProxy 10.0.0.0/8'; \
	echo 'RemoteIPTrustedProxy 172.16.0.0/12'; \
	echo 'RemoteIPTrustedProxy 192.168.0.0/16'; \
	echo 'RemoteIPTrustedProxy 169.254.0.0/16'; \
	echo 'RemoteIPTrustedProxy 127.0.0.0/8'; \
	} > /etc/apache2/conf-available/remoteip.conf; \
	a2enconf remoteip; \
	# https://github.com/docker-library/wordpress/issues/383#issuecomment-507886512
	# (replace all instances of "%h" with "%a" in LogFormat)
	find /etc/apache2 -type f -name '*.conf' -exec sed -ri 's/([[:space:]]*LogFormat[[:space:]]+"[^"]*)%h([^"]*")/\1%a\2/g' '{}' +

RUN apt-get update && apt-get install -y \
	git \
	unzip \
	vim

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
	php composer-setup.php --quiet; \
	rm -f composer-setup.php; \
	chmod +x composer.phar; \
	mv composer.phar /usr/bin/composer

COPY . /usr/src/build

# Run composer
WORKDIR /usr/src/build
RUN cp auth.json /root/.composer
RUN composer global config repositories.delicious-brains '{ "type": "composer", "url": "https://composer.deliciousbrains.com" }'
RUN composer global require "deliciousbrains-plugin/wp-migrate-db-pro"
RUN composer global require "deliciousbrains-plugin/wp-migrate-db-pro-media-files"
RUN composer global require "deliciousbrains-plugin/wp-migrate-db-pro-theme-plugin-files"
RUN composer install

ARG WORDPRESS_DB_NAME=${WORDPRESS_DB_NAME}
ARG WORDPRESS_DB_USER=${WORDPRESS_DB_USER}
ARG WORDPRESS_DB_PASSWORD=${WORDPRESS_DB_PASSWORD}
ARG WORDPRESS_DB_HOST=${WORDPRESS_DB_HOST}
ARG WORDPRESS_TABLE_PREFIX=${WORDPRESS_TABLE_PREFIX}
ARG ENVIRONMENT=${ENVIRONMENT}

# Insert database credentials from caprover environment
WORKDIR /usr/src/build
RUN cp config/wordpress/base-config.php public/$ENVIRONMENT-config.php; \
	sed -i -e "s/dbhost/$WORDPRESS_DB_HOST/g" public/$ENVIRONMENT-config.php; \
	sed -i -e "s/dbname/$WORDPRESS_DB_NAME/g" public/$ENVIRONMENT-config.php; \
	sed -i -e "s/dbuser/$WORDPRESS_DB_USER/g" public/$ENVIRONMENT-config.php; \
	sed -i -e "s/dbpass/$WORDPRESS_DB_PASSWORD/g" public/$ENVIRONMENT-config.php

# Install Node, Npm
RUN apt-get update; \
	curl -sL https://deb.nodesource.com/setup_16.x | bash -; \
	apt-get install -y --no-install-recommends nodejs

# Install node_modules in theme and generate prod styles/scripts
WORKDIR /usr/src/build/public/wp-content/themes/usomo
RUN npm install
RUN npm run build

# Move public directory to /var/www/html
RUN cp -a public/. /var/www/html/; \
	chown -R www-data:www-data /var/www/html

RUN usermod -a -G users www-data

VOLUME /var/www/html

CMD ["apache2-foreground"]
