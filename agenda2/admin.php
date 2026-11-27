<?php
declare(strict_types=1);

require_once 'seguridad.php';
verificarAcceso(1);

require_once 'conexion.php';
require_once 'funciones.php';

$pdo = conectarBD();
$errores = [];
$mensajeOk = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maxLoteRaw      = filter_input(INPUT_POST, 'max_lote', FILTER_VALIDATE_INT);
    $numEmojisRaw    = filter_input(INPUT_POST, 'num_emojis', FILTER_VALIDATE_INT);
    $maxContactosRaw = filter_input(INPUT_POST, 'max_contactos', FILTER_VALIDATE_INT);

    if ($maxLoteRaw === false || $maxLoteRaw === null || $maxLoteRaw < 3 || $maxLoteRaw > 8) {
        $errores[] = 'Contactos por lote debe ser un entero entre 3 y 8.';
    }
    if ($numEmojisRaw === false || $numEmojisRaw === null || $numEmojisRaw < 4 || $numEmojisRaw > 10) {
        $errores[] = 'Emojis disponibles debe ser un entero entre 4 y 10.';
    }
    if ($maxContactosRaw === false || $maxContactosRaw === null || $maxContactosRaw < 10 || $maxContactosRaw > 100) {
        $errores[] = 'Máximo de contactos por usuario debe ser un entero entre 10 y 100.';
    }

    if (empty($errores)) {
        $stmtUpdate = $pdo->prepare(
            'UPDATE configuracion 
             SET max_lote = :max_lote, num_emojis = :num_emojis, max_contactos = :max_contactos 
             WHERE id = 1'
        );
        $stmtUpdate->execute([
            ':max_lote'      => $maxLoteRaw,
            ':num_emojis'    => $numEmojisRaw,
            ':max_contactos' => $maxContactosRaw,
        ]);
        $mensajeOk = 'Configuración actualizada correctamente.';
    }
}

// Obtener configuración actual
$stmtConfig = $pdo->query('SELECT max_lote, num_emojis, max_contactos FROM configuracion WHERE id = 1');
$config = $stmtConfig->fetch();

// Si hubo error de validación, mantenemos los valores enviados en el formulario
$valMaxLote      = isset($_POST['max_lote']) && !empty($errores) ? (string)$_POST['max_lote'] : (string)$config['max_lote'];
$valNumEmojis    = isset($_POST['num_emojis']) && !empty($errores) ? (string)$_POST['num_emojis'] : (string)$config['num_emojis'];
$valMaxContactos = isset($_POST['max_contactos']) && !empty($errores) ? (string)$_POST['max_contactos'] : (string)$config['max_contactos'];

// Listar usuarios registrados y su número de contactos
$stmtUsuarios = $pdo->query(
    'SELECT u.codigo, u.nombre, u.rol, COUNT(c.id) AS total_contactos
     FROM usuarios u
     LEFT JOIN contactos c ON u.codigo = c.codigousu
     GROUP BY u.codigo, u.nombre, u.rol
     ORDER BY u.codigo ASC'
);
$usuarios = $stmtUsuarios->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>Panel de Administración</h1>
        <p class="barra-usuario">
            Conectado como: <strong><?= e($_SESSION['nombre']) ?></strong> (rol 1) |
            <a href="salir.php">Cerrar sesión</a>
        </p>

        <?php if ($mensajeOk !== ''): ?>
            <p class="mensaje-ok"><?= e($mensajeOk) ?></p>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="mensaje-error">
                <ul>
                    <?php foreach ($errores as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="admin.php" class="formulario-config">
            <div class="fila-config">
                <label for="max_lote">Contactos por lote (3-8):</label>
                <input type="number" id="max_lote" name="max_lote" value="<?= e($valMaxLote) ?>" min="3" max="8">
            </div>
            <div class="fila-config">
                <label for="num_emojis">Emojis disponibles (4-10):</label>
                <input type="number" id="num_emojis" name="num_emojis" value="<?= e($valNumEmojis) ?>" min="4" max="10">
            </div>
            <div class="fila-config">
                <label for="max_contactos">Máx. contactos por usuario (10-100):</label>
                <input type="number" id="max_contactos" name="max_contactos" value="<?= e($valMaxContactos) ?>" min="10" max="100">
            </div>
            <div class="botones-centro">
                <button type="submit">Guardar configuración</button>
            </div>
        </form>

        <h2>Usuarios registrados</h2>
        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Contactos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= (int)$u['codigo'] ?></td>
                        <td><?= e($u['nombre']) ?></td>
                        <td><?= (int)$u['rol'] ?></td>
                        <td><?= (int)$u['total_contactos'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>