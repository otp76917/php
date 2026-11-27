<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Comprueba que el usuario haya iniciado sesión y tenga el rol permitido.
 *
 * @param int|null $rolRequerido 1 para admin, 0 para usuario normal, null para cualquier usuario autenticado.
 * @return void
 */
function verificarAcceso(?int $rolRequerido = null): void
{
    if (!isset($_SESSION['codigo'], $_SESSION['nombre'], $_SESSION['rol'])) {
        header('Location: index.php');
        exit;
    }

    $rolActual = (int)$_SESSION['rol'];

    if ($rolRequerido !== null && $rolActual !== $rolRequerido) {
        if ($rolActual === 1) {
            header('Location: admin.php');
        } else {
            header('Location: inicio.php');
        }
        exit;
    }
}