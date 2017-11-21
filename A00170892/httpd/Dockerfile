# Se utiliza una imagen public de php
FROM php:7.0-apache

# Se instala pdo_mysql para la conexion remota. Para esto la imagen viene con un script para instalacion
# de paquetes llamado "docker-php-ext-install"
RUN docker-php-ext-install pdo pdo_mysql

# Se pasa el archivo con la pagina web "index.php"
ADD ./pages /var/www/html

# Se expone el puerto 80
EXPOSE 80

# Se inicia el servicio de apache2 para acceder a la pagina web desde un navegador
CMD service apache2 start && tail -f /var/log/apache2/access.log
