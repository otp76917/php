<?php
declare(strict_types=1);

/**
 * Establece y devuelve una conexión segura PDO con la base de datos AGENDA.
 *
 * @return PDO Instancia configurada de PDO.
 */
function conectarBD(): PDO
{
    $host = 'localhost';
    $db   = 'AGENDA';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $opciones);
    } catch (PDOException $e) {
        // Registramos el error internamente sin exponer credenciales ni detalles al usuario
        error_log('Error de conexión BD: ' . $e->getMessage());
        exit('Error interno: no se ha podido conectar con el servidor de base de datos.');
    }
}