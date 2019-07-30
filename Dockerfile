FROM php:cli

RUN docker-php-ext-install pcntl
COPY ./ /app
WORKDIR /app
CMD ["php", "/app/start.php", "start", "-d"]