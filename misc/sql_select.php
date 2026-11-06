<?php 
require_once 'conexion.php'; // Obtenemos la conexión $pdo 
$notaMinima = 5.0; // Supongamos que viene de un filtro de búsqueda 
try { 
// 1. Preparar la consulta con marcador nominal :notaMin 
$sql = "SELECT Codigo, Nombre, Clave FROM usuarios;"; 
$stmt = $pdo->prepare($sql);
$stmt->execute();
// 2. Ejecutar pasando los valores en un array asociativo 
//$stmt->execute([':notaMin' => $notaMinima]); 
echo "<h3>Listado de Alumnos Aprobados:</h3>"; 
// 3. Recorrer los resultados fila a fila con fetch() 
while ($fila = $stmt->fetch()) { 
// htmlspecialchars evita inyecciones de código HTML/JavaScript (XSS) 
$nombre = htmlspecialchars($fila['Nombre']);  
$clave   = htmlspecialchars((string)$fila['Clave']); 
  
        echo "ID: {$fila['Codigo']} | Nombre: $nombre | Clave: $clave <br>"; 
    } 
  
} catch (PDOException $e) { 
    error_log("Error en SELECT: " . $e->getMessage()); 
    echo "No se pudieron obtener los alumnos."; 
} finally { 
    $stmt = null; // Liberamos el cursor 
    $pdo  = null; // Cerramos la conexión 
} 
?>
