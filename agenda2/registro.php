<?php
declare(strict_types=1);

session_start();
require_once 'conexion.php';
require_once 'funciones.php';

if (isset($_SESSION['codigo'], $_SESSION['rol'])) {
    header('Location: ' . ((int)$_SESSION['rol'] === 1 ? 'admin.php' : 'inicio.php'));
    exit;
}

$errores = [];
$nombreInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreInput = trim((string)($_POST['usuario'] ?? ''));
    $claveInput  = (string)($_POST['clave'] ?? '');
    $clave2Input = (string)($_POST['clave2'] ?? '');

    if ($nombreInput === '') {
        $errores[] = 'El nombre de usuario no puede estar vacío.';
    } elseif (mb_strlen($nombreInput) > 20) {
        $errores[] = 'El nombre de usuario no puede superar los 20 caracteres.';
    }

    if ($claveInput === '') {
        $errores[] = 'La clave no puede estar vacía.';
    } elseif ($claveInput !== $clave2Input) {
        $errores[] = 'Las claves no coinciden.';
    }

    if (empty($errores)) {
        $pdo = conectarBD();

        // Comprobar si el nombre ya existe
        $stmtCheck = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE nombre = :nombre');
        $stmtCheck->execute([':nombre' => $nombreInput]);

        if ((int)$stmtCheck->fetchColumn() > 0) {
            $errores[] = 'El nombre de usuario ya está registrado.';
        } else {
            $hash = password_hash($claveInput, PASSWORD_ARGON2ID);
            $stmtInsert = $pdo->prepare('INSERT INTO usuarios (nombre, clave, rol) VALUES (:nombre, :clave, 0)');
            $stmtInsert->execute([
                ':nombre' => $nombreInput,
                ':clave'  => $hash,
            ]);

            $nuevoCodigo = (int)$pdo->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['codigo'] = $nuevoCodigo;
            $_SESSION['nombre'] = $nombreInput;
            $_SESSION['rol']    = 0;

            header('Location: inicio.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos - Registro</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>REGISTRO DE USUARIO</h1>

        <?php if (!empty($errores)): ?>
            <div class="mensaje-error">
                <ul>
                    <?php foreach ($errores as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="registro.php" class="formulario-login">
            <div class="fila-login">
                <label for="usuario">USUARIO:</label>
                <input type="text" id="usuario" name="usuario" value="<?= e($nombreInput) ?>" maxlength="20">
            </div>
            <div class="fila-login">
                <label for="clave">CLAVE:</label>
                <input type="password" id="clave" name="clave">
            </div>
            <div class="fila-login">
                <label for="clave2">REPETIR CLAVE:</label>
                <input type="password" id="clave2" name="clave2">
            </div>
            <div class="botones-centro">
                <button type="submit">Crear cuenta</button>
                <a href="index.php" class="boton-enlace">Volver</a>
            </div>
        </form>
    </main>
</body>
</html>