<?php
// Ejercicio 1.a: comentario explicativo (RA1).

// Ejercicio 1.c: lógica de acceso (validación, consulta preparada, sesión y redirección).

require_once "config.php";
require_once "bd.php";

$connection = conectar();
$display = false;

?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST["login"];
    $pswd = $_POST["clave"];
    $user_exists = false;
    $query = $connection->query("SELECT login, clave FROM jugadores;");
    $id = "";
    while ($fila = $query->fetch(PDO::FETCH_ASSOC)) { 
        if ($fila["login"] == $user && $fila["clave"] == $pswd) {
            $user_exists = true;
            $id = $fila["login"];
            header("Location: jugar.php?id={$id}");
        }
        else $display = true;
    }


    
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tragaperras - Acceso</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        label { display: inline-block; width: 70px; }
        p { margin: 10px 0; }
        .error { color: #c62828; display: "none";}
    </style>
</head>
<body>
    <h1>Tragaperras – Acceso</h1>
    <form method="post" action="">
        <p><label for="login">Login:</label> <input type="text" name="login" id="login"></p>
        <p><label for="clave">Clave:</label> <input type="password" name="clave" id="clave"></p>
        <p><label></label> <input type="submit" name="entrar" value="ENTRAR"></p>
    </form>
    <!-- Mensaje de error si el usuario o la clave no son correctos -->
    <p class="error" <?php if ($_SERVER["REQUEST_METHOD"] === "POST") if ($display) echo "style=\"display: block;\"" ?>>Usuario o clave incorrectos. Vuelva a intentarlo.</p>
</body>
</html>

