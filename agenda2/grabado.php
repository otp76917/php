<?php
declare(strict_types=1);

require_once 'seguridad.php';
verificarAcceso(0);

require_once 'conexion.php';
require_once 'funciones.php';

$pdo = conectarBD();
$codigousu = (int)$_SESSION['codigo'];

// Si existen datos validados pendientes de grabar, los insertamos dentro de una transacción
if (
    isset($_SESSION['lote_validado'], $_SESSION['lote_emojis'], $_SESSION['lote_token']) &&
    is_array($_SESSION['lote_validado']) &&
    count($_SESSION['lote_validado']) === count($_SESSION['lote_emojis'])
) {
    $contactos = $_SESSION['lote_validado'];
    $emojis    = $_SESSION['lote_emojis'];

    try {
        $pdo->beginTransaction();

        $stmtInsert = $pdo->prepare(
            'INSERT INTO contactos (codigousu, nombre, apellidos, telefono, email, emoji, fecha)
             VALUES (:codigousu, :nombre, :apellidos, :telefono, :email, :emoji, NOW())'
        );

        foreach ($contactos as $i => $c) {
            $stmtInsert->execute([
                ':codigousu' => $codigousu,
                ':nombre'    => $c['nombre'],
                ':apellidos' => $c['apellidos'],
                ':telefono'  => $c['telefono'],
                ':email'     => $c['email'],
                ':emoji'     => $emojis[$i],
            ]);
        }

        $pdo->commit();

        // Guardamos el resumen en sesión para evitar duplicados al recargar (F5)
        $_SESSION['ultimo_grabado_cantidad'] = count($contactos);

        // Borramos de la sesión los datos del lote sin cerrar la sesión del usuario
        unset($_SESSION['lote_validado'], $_SESSION['lote_emojis'], $_SESSION['lote_token']);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Error al grabar lote de contactos: ' . $e->getMessage());
        exit('Error interno al guardar los contactos. No se ha grabado ningún registro.');
    }
}

// Si se entra directamente sin haber grabado nada reciente, redirigimos a inicio.php
if (!isset($_SESSION['ultimo_grabado_cantidad'])) {
    header('Location: inicio.php');
    exit;
}

$grabadosEnLote = (int)$_SESSION['ultimo_grabado_cantidad'];

// Consultar el total actual de contactos del usuario en su agenda
$stmtTotal = $pdo->prepare('SELECT COUNT(*) FROM contactos WHERE codigousu = :codigousu');
$stmtTotal->execute([':codigousu' => $codigousu]);
$totalAgenda = (int)$stmtTotal->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos - Grabado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>AGENDA DE CONTACTOS</h1>
        <p class="barra-usuario">Usuario: <strong><?= e($_SESSION['nombre']) ?></strong></p>

        <div class="caja-confirmacion">
            <p>Se han grabado <strong><?= $grabadosEnLote ?></strong> contactos en tu agenda</p>
            <p>Tu agenda tiene ahora <strong><?= $totalAgenda ?></strong> contactos.</p>
        </div>

        <nav class="enlaces-pie">
            <a href="inicio.php">Grabar más contactos</a>
            <a href="totales.php">Ver totales</a>
            <a href="salir.php">Cerrar sesión</a>
        </nav>
    </main>
</body>
</html>