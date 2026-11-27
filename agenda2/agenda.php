<?php
declare(strict_types=1);

require_once 'seguridad.php';
verificarAcceso(0);

require_once 'funciones.php';

// No se puede entrar en agenda.php sin un lote iniciado en la sesión
if (!isset($_SESSION['lote_emojis']) || !is_array($_SESSION['lote_emojis']) || count($_SESSION['lote_emojis']) === 0) {
    header('Location: inicio.php');
    exit;
}

$emojisLote = $_SESSION['lote_emojis'];
$totalFormularios = count($emojisLote);

$valores = [];
$erroresPorContacto = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postContactos = $_POST['contactos'] ?? [];
    $hayErrores = false;

    for ($i = 0; $i < $totalFormularios; $i++) {
        $datosContacto = is_array($postContactos[$i] ?? null) ? $postContactos[$i] : [];
        $valores[$i] = [
            'nombre'    => trim((string)($datosContacto['nombre'] ?? '')),
            'apellidos' => trim((string)($datosContacto['apellidos'] ?? '')),
            'telefono'  => trim((string)($datosContacto['telefono'] ?? '')),
            'email'     => trim((string)($datosContacto['email'] ?? '')),
        ];

        $errores = validarContacto($valores[$i]);
        if (!empty($errores)) {
            $hayErrores = true;
            $erroresPorContacto[$i] = $errores;
        }
    }

    if (!$hayErrores) {
        // Guardamos los datos validados en sesión y generamos un token único anti-duplicados
        $_SESSION['lote_validado'] = $valores;
        $_SESSION['lote_token']    = bin2hex(random_bytes(16));
        header('Location: grabado.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda de Contactos - Rellenar Contactos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>AGENDA DE CONTACTOS</h1>
        <p class="barra-usuario">
            Usuario: <strong><?= e($_SESSION['nombre']) ?></strong> |
            <a href="inicio.php">Cambiar número de contactos</a> |
            <a href="salir.php">Cerrar sesión</a>
        </p>

        <form method="post" action="agenda.php">
            <?php for ($i = 0; $i < $totalFormularios; $i++): ?>
                <?= pintarFormularioContacto(
                    $i,
                    $emojisLote[$i],
                    $valores[$i] ?? [],
                    $erroresPorContacto[$i] ?? []
                ) ?>
            <?php endfor; ?>

            <div class="botones-centro">
                <button type="submit">Grabar contactos</button>
            </div>
        </form>
    </main>
</body>
</html>