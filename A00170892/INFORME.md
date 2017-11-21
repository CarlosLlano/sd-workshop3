# WORKSHOP 3 SISTEMAS DISTRIBUIDOS #


***1.Realice el despliegue de un repositorio de paquetes de pip***

Se crea un volumen que almacenara los paquetes:
```
docker volume create pypi_packages
```
Para llenar de paquetes este volumen, se utiliza un contenedor de centos auxiliar
```
docker run -it --name=centos -v pypi_packages:/lib/python2.7/site-packages/ centos bash
```
Este contenedor tiene los siguientes paquetes:

![5](https://user-images.githubusercontent.com/17281733/33055671-f921f0f2-ce4e-11e7-92cf-6e1a8bcf7294.png)


En este punto se puede crear un repositorio de paquetes local usando la imagen en el siguiente enlace:
https://hub.docker.com/r/janlo/pypi-mirror-nginx/

```bash
docker run -d -p 80:80 \
    -v pypi_packages:/web \
    -e PYPI_SERVER_NAME=pypi-mirror \
    janlo/pypi-mirror-nginx
```

Se puede ver como el contenedor contiene los paquetes antes mostrados:

![img1](https://user-images.githubusercontent.com/17281733/33055698-139efc7c-ce4f-11e7-9de3-c11a4026838c.png)


Para instalar mas paquetes, se debe ingresar al contenedor centos usando el comando "docker exec -it centos bash" y ejecutar los
siguientes comandos:
```
curl "https://bootstrap.pypa.io/get-pip.py" -o "get-pip.py"
python get-pip.py
pip install redis
pip install flask
```

Los nuevos paquetes instalados pueden ser vistos por el contenedor que sirve como repositorio:

![img3](https://user-images.githubusercontent.com/17281733/33055770-994d3dd4-ce4f-11e7-88aa-7af7b2f3c261.png)


***2.Escriba un archivo Dockerfile donde realice el despliegue de la aplicación.
Incluya comentarios donde explique las líneas del archivo Dockerfile***

Se utiliza la siguiente estructura de archivos

![estructura](https://user-images.githubusercontent.com/17281733/33054688-b738aed4-ce48-11e7-81c2-3d05026bfff4.png)

***SERVIDOR DE BASE DE DATOS***

Se escoge mysql como servidor de base de datos. El Dockerfile es:

```dockerfile
# Se utiliza una imagen public de mysql
FROM mysql

# Se asigna un valor para la variable de ambiente que hace referencia a la clave del usuario root
ENV MYSQL_ROOT_PASSWORD my-secret-pw

# Para crear el esquema de datos, se debe pasar el archivo .sql y almacenarlo en la carpeta "docker-entrypoint-initdb.d"
ADD ./conf/schema.sql /docker-entrypoint-initdb.d/schema.sql
```

Este Dockerfile crea una imagen de mysql con usuario "root" y clave "my-secret-pw". Estas Credenciales seran utilizadas para acceder a la
base de datos.
El esquema de datos utilizado es dado por el siguiente archivo:

```sql
CREATE database database1;
USE database1;
CREATE TABLE WebServer(id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),name VARCHAR(30));
INSERT INTO WebServer (name) VALUES ('Base de datos mysql');
GRANT ALL PRIVILEGES ON *.* to 'root'@'172.17.0.3' IDENTIFIED by 'my-secret-pw';
```
Con este script se crea una base de datos "database1" con una tabla "WebServer" que solo contiene un registro. 


***SERVIDOR WEB***

Se escoge apache+php como servidor web. El Dockerfile es:

```dockerfile
# Se utiliza una imagen publica de php
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
```

El servicio a consultar en el navegador muestra el registro almacenado en la base de datos mysql usando codigo php.

```php
<?php
    $con = new PDO('mysql:host=172.17.0.2;port=3306;dbname=database1;charset=utf8mb4', 'root', 'my-secret-pw');
    if (!$con)
    {
      die('No se pudo establecer la conexion');
    }
    foreach($con->query('SELECT * FROM WebServer') as $row) {
        echo "<h1>Solicitud atendida por <span class='color'>" . $row['name'] . "</span></h1>";
    }
 ?>
```



***3. Comandos utilizados***

Poner en funcionamiento Servidor mysql
```bash
docker build -t mysql-db .
docker run -d --name=mysql-db mysql-db
```

Verificar servidor mysql
```bash
mysql -u root -pmy-secret-pw
use database1;
select * from WebServer;
```


![1](https://user-images.githubusercontent.com/17281733/33055096-8609c94e-ce4b-11e7-920f-b710953f9729.png)


Poner en funcionamiento servidor web
```bash
docker build -t apache-php .
docker run -d -p 80:80 --name=apache-php apache-php
```

Verificar servidor web.

![nov -20-2017 23-42-11](https://user-images.githubusercontent.com/17281733/33055267-7e3bf754-ce4c-11e7-908e-0d2e371c7749.gif)

