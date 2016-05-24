FROM php:7-cli

RUN apt-get update && apt-get install -y  libxml2-dev zlib1g-dev \
	&& docker-php-ext-install soap \
	&& docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin

WORKDIR /src

RUN /usr/local/bin/composer.phar require wsdl2phpgenerator/wsdl2phpgenerator
RUN /usr/local/bin/composer.phar require corneltek/getoptionkit
ADD files/generate.php /src

ENTRYPOINT ["php", "generate.php"]
CMD ["-h"]

