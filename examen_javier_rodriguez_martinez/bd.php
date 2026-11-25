<?php
/**
 * bd.php - Funciones de acceso a la base de datos TRAGAPERRAS.
 * Se facilita la función conectar(). El alumno añadirá validarUsuario(),
 * guardarPartida() y obtenerEstadisticas().
 */
require_once 'config.php';

/**
 * Abre una conexión con la base de datos usando las constantes de config.php.
 *
 * @return PDO Conexión abierta con juego de caracteres utf8mb4.
 */
function conectar() : PDO
{
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $con = new PDO($dsn, DB_USER, DB_PASS, $opciones);
    
    return $con;
}
