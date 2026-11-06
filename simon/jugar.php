<!DOCTYPE html>
<?php
session_start();

if (!isset($_POST['color'])) {
    $_SESSION['combinacion'] = array();
    for ($i = 0; $i < 4 ; $i++) {
        $_SESSION['combinacion'][] = 'black';
    };
    $_SESSION['pulsaciones'] = 0;
} else if ($_SESSION['pulsaciones'] < count($_SESSION['combinacion'])) {
    $_SESSION['combinacion'][$_SESSION['pulsaciones']] = $_POST['color'];
    $_SESSION['pulsaciones']++;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Combinación de Colores</title>
</head>
<body>
    <div style="height: 90%; width: 100%; display: flex; flex-direction: row; gap: 10px;">
        <?php
        foreach ($_SESSION['combinacion'] as $color) {
            echo '<div class="color-circle" style="background-color: ' . htmlspecialchars($color) . '; height: 50px; width: 50px; border-radius: 50%;"></div>';
        }
        ?>
    </div>
    
    <form method="POST" style="margin-top: 20px;">
        <button type="submit" name="color" value="red">Rojo</button>
        <button type="submit" name="color" value="green">Verde</button>
    </form>
</body>
</html>