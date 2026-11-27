<?php
declare(strict_types=1);

session_start();
require_once 'conexion.php';
require_once 'funciones.php';

// Si ya hay sesión activa, redirigimos a su pantalla correspondiente
if (isset($_SESSION['codigo'], $_SESSION['rol'])) {
    header('Location: ' . ((int)$_SESSION['rol'] === 1 ? 'admin.php' : 'inicio.php'));
    exit;
}

$error = '';
$usuarioInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrarse') {
        header('Location: registro.php');
        exit;
    }

    $usuarioInput = trim((string)($_POST['usuario'] ?? ''));
    $claveInput   = (string)($_POST['clave'] ?? '');

    if ($usuarioInput === '' || $claveInput === '') {
        $error = 'Debes introducir usuario y clave.';
    } else {
        $pdo = conectarBD();
        $stmt = $pdo->prepare('SELECT codigo, nombre, clave, rol FROM usuarios WHERE nombre = :nombre LIMIT 1');
        $stmt->execute([':nombre' => $usuarioInput]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($claveInput, $usuario['clave'])) {
            session_regenerate_id(true);
            $_SESSION['codigo'] = (int)$usuario['codigo'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol']    = (int)$usuario['rol'];

            if ((int)$usuario['rol'] === 1) {
                header('Location: admin.php');
            } else {
                header('Location: inicio.php');
            }
            exit;
        } else {
            $error = 'Usuario o clave incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos - Acceso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>AGENDA DE CONTACTOS</h1>

        <?php if ($error !== ''): ?>
            <p class="mensaje-error"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="post" action="index.php" class="formulario-login">
            <div class="fila-login">
                <label for="usuario">USUARIO:</label>
                <input type="text" id="usuario" name="usuario" value="<?= e($usuarioInput) ?>" maxlength="20">
            </div>
            <div class="fila-login">
                <label for="clave">CLAVE:</label>
                <input type="password" id="clave" name="clave">
            </div>
            <div class="botones-centro">
                <button type="submit" name="accion" value="entrar">Entrar</button>
                <button type="submit" name="accion" value="registrarse">Registrarse</button>
            </div>
        </form>
    </main>
</body>
</html>