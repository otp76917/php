<?php
declare(strict_types=1);

require_once 'seguridad.php';
verificarAcceso(0);

require_once 'conexion.php';
require_once 'funciones.php';

$pdo = conectarBD();
$codigousu = (int)$_SESSION['codigo'];

// Leer configuración actual
$stmtConfig = $pdo->query('SELECT max_lote, num_emojis, max_contactos FROM configuracion WHERE id = 1');
$config = $stmtConfig->fetch();

$maxLote      = (int)$config['max_lote'];
$numEmojis    = (int)$config['num_emojis'];
$maxContactos = (int)$config['max_contactos'];

// Contar contactos actuales del usuario
$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM contactos WHERE codigousu = :codigousu');
$stmtCount->execute([':codigousu' => $codigousu]);
$actuales = (int)$stmtCount->fetchColumn();

$huecosDisponibles = max(0, $maxContactos - $actuales);
$topePermitido = min($maxLote, $huecosDisponibles);

$emojisDisp = emojisDisponibles($numEmojis);

// Si el usuario tiene huecos disponibles (> 0)
if ($topePermitido > 0) {
    // Inicializar lote en sesión si no existe o está vacío
    if (!isset($_SESSION['lote_emojis']) || !is_array($_SESSION['lote_emojis']) || count($_SESSION['lote_emojis']) === 0) {
        $_SESSION['lote_emojis'] = [emojiAleatorio($emojisDisp)];
        unset($_SESSION['lote_token']);
    }

    // Si por un cambio del administrador el lote actual supera el tope, recortamos
    if (count($_SESSION['lote_emojis']) > $topePermitido) {
        $_SESSION['lote_emojis'] = array_slice($_SESSION['lote_emojis'], 0, $topePermitido);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'incrementar') {
            if (count($_SESSION['lote_emojis']) < $topePermitido) {
                $_SESSION['lote_emojis'][] = emojiAleatorio($emojisDisp);
            }
            // Al llegar al tope se pasa directamente a agenda.php sin pulsar GRABAR
            if (count($_SESSION['lote_emojis']) >= $topePermitido) {
                header('Location: agenda.php');
                exit;
            }
        } elseif ($accion === 'grabar') {
            header('Location: agenda.php');
            exit;
        }
    } elseif (count($_SESSION['lote_emojis']) >= $topePermitido && $topePermitido === 1) {
        // Si solo queda 1 hueco en la agenda, ya está en el tope al empezar
        header('Location: agenda.php');
        exit;
    }
} else {
    unset($_SESSION['lote_emojis']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos - Inicio</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>AGENDA DE CONTACTOS</h1>
        <p class="barra-usuario">
            Usuario: <strong><?= e($_SESSION['nombre']) ?></strong> |
            <a href="totales.php">Ver totales</a> |
            <a href="salir.php">Cerrar sesión</a>
        </p>

        <?php if ($topePermitido <= 0): ?>
            <p class="mensaje-error">Has alcanzado el límite máximo de <?= $maxContactos ?> contactos en tu agenda.</p>
            <p><a href="totales.php">Ver totales</a></p>
        <?php else: ?>
            <p class="info-lote">
                Contactos a grabar: <strong><?= count($_SESSION['lote_emojis']) ?></strong>
                (máximo permitido en este lote: <?= $topePermitido ?>)
            </p>

            <div class="panel-emojis">
                <?php foreach ($_SESSION['lote_emojis'] as $emoji): ?>
                    <span class="emoji-grande"><?= e($emoji) ?></span>
                <?php endforeach; ?>
            </div>

            <form method="post" action="inicio.php" class="botones-centro">
                <button type="submit" name="accion" value="incrementar">INCREMENTAR</button>
                <button type="submit" name="accion" value="grabar">GRABAR</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>