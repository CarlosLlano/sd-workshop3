CREATE database database1;
USE database1;
CREATE TABLE WebServer(id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id),name VARCHAR(30));
INSERT INTO WebServer (name) VALUES ('Base de datos mysql');
GRANT ALL PRIVILEGES ON *.* to 'root'@'172.17.0.3' IDENTIFIED by 'my-secret-pw';
