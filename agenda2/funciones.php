<?php
declare(strict_types=1);

const EMOJIS = ['🐱', '🚀', '🍕', '🌵', '🎸', '🦊', '🍩', '🛸', '🐼', '⚡'];

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function emojisDisponibles(int $num): array
{
    return array_slice(EMOJIS, 0, $num);
}

function emojiAleatorio(array $emojis): string
{
    $indice = array_rand($emojis);
    return $emojis[$indice];
}

function validarContacto(array $c): array
{
    $errores = [];

    $nombre    = trim((string)($c['nombre'] ?? ''));
    $apellidos = trim((string)($c['apellidos'] ?? ''));
    $telefono  = trim((string)($c['telefono'] ?? ''));
    $email     = trim((string)($c['email'] ?? ''));

    if ($nombre === '') {
        $errores['nombre'] = 'El nombre es obligatorio';
    } elseif (mb_strlen($nombre) > 30) {
        $errores['nombre'] = 'Máximo 30 caracteres';
    }

    if ($apellidos === '') {
        $errores['apellidos'] = 'Los apellidos son obligatorios';
    } elseif (mb_strlen($apellidos) > 50) {
        $errores['apellidos'] = 'Máximo 50 caracteres';
    }

    if ($telefono === '') {
        $errores['telefono'] = 'El teléfono es obligatorio';
    } elseif (!preg_match('/^\d{9}$/', $telefono)) {
        $errores['telefono'] = 'Debe tener 9 dígitos';
    }

    if ($email === '') {
        $errores['email'] = 'El email es obligatorio';
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errores['email'] = 'Email no válido';
    } elseif (mb_strlen($email) > 80) {
        $errores['email'] = 'Máximo 80 caracteres';
    }

    return $errores;
}

function pintarFormularioContacto(int $i, string $emoji, array $valores, array $errores): string
{
    $nombre    = e((string)($valores['nombre'] ?? ''));
    $apellidos = e((string)($valores['apellidos'] ?? ''));
    $telefono  = e((string)($valores['telefono'] ?? ''));
    $email     = e((string)($valores['email'] ?? ''));
    $emojiSafe = e($emoji);

    $html  = "<div class='tarjeta-contacto'>";
    $html .= "<div class='emoji-contacto'>{$emojiSafe}</div>";
    $html .= "<div class='campos-contacto'>";

    $html .= "<div class='fila-campos'>";
    $html .= "<div class='campo'>";
    $html .= "<label for='nombre_{$i}'>Nombre</label>";
    $html .= "<input type='text' id='nombre_{$i}' name='contactos[{$i}][nombre]' value='{$nombre}' maxlength='30'>";
    if (isset($errores['nombre'])) {
        $html .= "<span class='error-campo'>" . e($errores['nombre']) . "</span>";
    }
    $html .= "</div>";

    $html .= "<div class='campo'>";
    $html .= "<label for='apellidos_{$i}'>Apellidos</label>";
    $html .= "<input type='text' id='apellidos_{$i}' name='contactos[{$i}][apellidos]' value='{$apellidos}' maxlength='50'>";
    if (isset($errores['apellidos'])) {
        $html .= "<span class='error-campo'>" . e($errores['apellidos']) . "</span>";
    }
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<div class='fila-campos'>";
    $html .= "<div class='campo'>";
    $html .= "<label for='telefono_{$i}'>Teléfono</label>";
    $html .= "<input type='text' id='telefono_{$i}' name='contactos[{$i}][telefono]' value='{$telefono}' maxlength='9'>";
    if (isset($errores['telefono'])) {
        $html .= "<span class='error-campo'>" . e($errores['telefono']) . "</span>";
    }
    $html .= "</div>";

    $html .= "<div class='campo'>";
    $html .= "<label for='email_{$i}'>Email</label>";
    $html .= "<input type='text' id='email_{$i}' name='contactos[{$i}][email]' value='{$email}' maxlength='80'>";
    if (isset($errores['email'])) {
        $html .= "<span class='error-campo'>" . e($errores['email']) . "</span>";
    }
    $html .= "</div>";
    $html .= "</div>";

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}