# Se utiliza una imagen public de mysql
FROM mysql

# Se asigna un valor para la variable de ambiente que hace referencia a la clave del usuario root
ENV MYSQL_ROOT_PASSWORD my-secret-pw

# Para crear el esquema de datos, se debe pasar el archivo .sql y almacenarlo en la carpeta "docker-entrypoint-initdb.d"
ADD ./conf/schema.sql /docker-entrypoint-initdb.d/schema.sql
