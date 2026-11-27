<?php
declare(strict_types=1);

require_once 'seguridad.php';
verificarAcceso(null); // Accede cualquier usuario conectado

require_once 'conexion.php';
require_once 'funciones.php';

$pdo = conectarBD();
$codigousuActual = (int)$_SESSION['codigo'];
$rolActual       = (int)$_SESSION['rol'];

// Única consulta agregada con LEFT JOIN y GROUP BY
$sql = 'SELECT u.codigo, u.nombre, COUNT(c.id) AS num_contactos, MAX(c.fecha) AS ultimo_alta
        FROM usuarios u
        LEFT JOIN contactos c ON u.codigo = c.codigousu
        GROUP BY u.codigo, u.nombre
        ORDER BY num_contactos DESC, u.codigo ASC';

$stmt = $pdo->query($sql);
$filas = $stmt->fetchAll();

// Calcular el total global de contactos en PHP
$totalGlobal = 0;
foreach ($filas as $f) {
    $totalGlobal += (int)$f['num_contactos'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda - Totales</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main class="contenedor">
        <h1>AGENDA - Totales</h1>
        <p><?= e($_SESSION['nombre']) ?>, estos son los contactos guardados:</p>

        <table class="tabla-datos">
            <thead>
                <tr>
                    <th>Cód.</th>
                    <th>Nombre</th>
                    <th>Cont.</th>
                    <th>%</th>
                    <th>Último alta</th>
                    <th>Gráfica</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $f): ?>
                    <?php
                        $codigoFila = (int)$f['codigo'];
                        $num        = (int)$f['num_contactos'];
                        $porcentaje = $totalGlobal > 0 ? ($num * 100) / $totalGlobal : 0.0;
                        $porcTexto  = number_format($porcentaje, 1, ',', '');
                        $anchoBarra = (int)round($porcentaje);

                        $fechaFormateada = '';
                        if ($f['ultimo_alta'] !== null) {
                            $dt = new DateTime($f['ultimo_alta']);
                            $fechaFormateada = $dt->format('d/m/Y');
                        }

                        $esUsuarioConectado = ($codigoFila === $codigousuActual);
                    ?>
                    <tr class="<?= $esUsuarioConectado ? 'fila-destacada' : '' ?>">
                        <td><?= $codigoFila ?></td>
                        <td><?= e($f['nombre']) ?></td>
                        <td><?= $num ?></td>
                        <td><?= e($porcTexto) ?></td>
                        <td><?= e($fechaFormateada) ?></td>
                        <td class="celda-grafica">
                            <div class="barra-fondo">
                                <div class="barra-relleno" style="width: <?= $anchoBarra ?>%;"></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav class="enlaces-pie">
            <?php if ($rolActual === 0): ?>
                <a href="inicio.php">Grabar más contactos</a>
            <?php else: ?>
                <a href="admin.php">Panel de administración</a>
            <?php endif; ?>
            <a href="salir.php">Cerrar sesión</a>
        </nav>
    </main>
</body>
</html>