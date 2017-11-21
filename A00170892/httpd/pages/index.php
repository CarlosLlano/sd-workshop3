<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>EJEMPLO</title>
  <style media="screen">
  h1 {
    font-size: 35px;
    text-align: center;
    margin-top: 100px;
    padding-left: 30px;
  }
  .color{
    color: #1d9fe8;
    font-weight: bold;
  }
  </style>
</head>
<body>
    <?php
    $con = new PDO('mysql:host=172.17.0.2;port=3306;dbname=database1;charset=utf8mb4', 'root', 'my-secret-pw');
    if (!$con)
    {
      die('No se pudo establecer la coneccion');
    }
    foreach($con->query('SELECT * FROM WebServer') as $row) {
        echo "<h1>Solicitud atendida por <span class='color'>" . $row['name'] . "</span></h1>";
    }
    ?>
</body>
</html>
