<?php
$host = 'localhost:3307';
$dbname = 'dbsimon';
$usuario = 'root';
$clave = 'root';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdo = null;

try {
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);
    echo "Conexión establecida correctamente.<br>";
} catch (PDOException $e) {
    error_log("Error de conexión PDO: " . $e->getMessage());
    echo "<p style='color:red;'>No se pudo conectar a la base de datos. Inténtelo más tarde.</p>";
} finally {
}
?>
<!DOCTYPE html>
<html>

<head></head>

<body>
    <form method="post">
        <div style="display: flex; flex-direction: row;">
            <label>Usuario: </label>
            <textarea name="user"></textarea>
        </div>
        <div style="display: flex; flex-direction: row;">
            <label>Contraseña: </label>
            <textarea name="pswd"></textarea>
        </div>
        <div style="display: flex; flex-direction: row;">
            <button type="submit" name="login" value="enter">Entrar</button>
            <button type="submit" name="login" value="register">Registarse</button>
        </div>
    </form>
</body>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["user"];
    $pswd = $_POST["pswd"];
    $stmt = $pdo->query("SELECT Codigo, Nombre, Clave FROM usuarios");
    $user_exists = false;
    $id = 0;
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) { 
        if ($fila["Nombre"] == $user && $fila["Clave"] == $pswd) {
            $user_exists = true;
            $id = $fila["Codigo"];
        }
    } 

    if ($_POST["login"] === "enter") {
        if ($user_exists) {
            header("Location: jugar.php?id={$id}");
        }
    }
    else {
        $user_exists = !$user_exists;
        if ($user_exists) {
            $stmt = $pdo->prepare("INSERT INTO usuarios (Nombre, Clave) VALUES (:nombre, :clave)");
            $stmt->bindParam(':nombre', $user);
            $stmt->bindParam(':clave', $pswd);
            $stmt->execute();
            echo "<p style='color:green;'>Usuario registrado correctamente.</p>";
        } else {
            echo "<p style='color:red;'>El usuario ya existe.</p>";
        }
    }
}

?>

</html>