<?php
declare(strict_types=1);

require_once 'conexion.php';

$pdo = conectarBD();

try {
    // Iniciamos transacción para asegurar que todo se inserte correctamente o nada
    $pdo->beginTransaction();

    // 1. Insertar un nuevo usuario (rol: 0 = Usuario, 1 = Administrador)
    $nombreUsuario = 'juan';
    $clavePlana    = 'juan123';
    $claveHash     = password_hash($clavePlana, PASSWORD_ARGON2ID);
    $rol           = 0;

    $stmtUsuario = $pdo->prepare(
        'INSERT INTO usuarios (nombre, clave, rol) 
         VALUES (:nombre, :clave, :rol)'
    );
    $stmtUsuario->execute([
        ':nombre' => $nombreUsuario,
        ':clave'  => $claveHash,
        ':rol'    => $rol,
    ]);

    // Obtenemos el ID (codigo) autogenerado del usuario recién creado
    $codigoUsuario = (int)$pdo->lastInsertId();

    // 2. Insertar un contacto asociado a ese usuario
    $stmtContacto = $pdo->prepare(
        'INSERT INTO contactos (codigousu, nombre, apellidos, telefono, email, emoji, fecha)
         VALUES (:codigousu, :nombre, :apellidos, :telefono, :email, :emoji, NOW())'
    );

    $stmtContacto->execute([
        ':codigousu' => $codigoUsuario,
        ':nombre'    => 'Elena',
        ':apellidos' => 'Martínez López',
        ':telefono'  => '622333444', // Debe tener exactamente 9 caracteres (CHAR(9))
        ':email'     => 'elena@ejemplo.es',
        ':emoji'     => '🌟',
    ]);

    // Confirmamos los cambios en la base de datos
    $pdo->commit();

    echo "Inserción completada: usuario '{$nombreUsuario}' (ID: {$codigoUsuario}) y su contacto creados correctamente.";

} catch (PDOException $e) {
    // Si ocurre algún error (ej. usuario duplicado), revertimos los cambios
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error al insertar en la base de datos: " . $e->getMessage();
}