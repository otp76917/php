<?php
/* 1. Cadena de conexión (DSN) e instanciación directa de PDO */

$dsn = "mysql:host=localhost:3307;dbname=agenda;charset=utf8mb4"; 
$pdo = new PDO($dsn, "root", "root"); 
  
/* 2. Ejecución directa de la consulta SQL */

$stmt = $pdo->query("SELECT Nombre FROM usuarios"); 
  
/* 3. Recorrido y visualización de resultados en pantalla */

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) { 
    echo $fila['Nombre']  . "<br>"; 
} 
  
/* 4. Cierre explícito de cursor y conexión */

$stmt = null; 
$pdo  = null; 
?> 
