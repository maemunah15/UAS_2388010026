FROM php:8.1-apache
RUN echo "ServerName _" >> /etc/apache2/apache2.conf
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
# Set landing page as default
RUN sed -i 's/DirectoryIndex index.html index.cgi index.pl index.php/DirectoryIndex landing.php index.php/' /etc/apache2/mods-enabled/dir.conf
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
EXPOSE 80
