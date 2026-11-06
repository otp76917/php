<!DOCTYPE html>
<?php
session_start();
if (!isset($_POST['color'])) {
    $_SESSION['combinacion'] = array("negro","negro","negro","negro");
    $_SESSION['pulsaciones'] = 0;
} else if ($_SESSION['pulsaciones']<count($_SESSION['combinacion'])) {
    $_SESSION['combinacion'][$_SESSION['pulsaciones']] = $_POST['color'];
    $_SESSION['pulsaciones']++;
}

//var_dump($_SESSION['combinacion']);

?>
<body>
    <row style="height: 90%; width: 100%">
        <?php
        foreach ($_SESSION['combinacion'] as $color) {
    // Mapeo de nombres en español a colores CSS válidos
    $cssColor = match ($color) {
        'rojo' => 'red',
        'verde' => 'green',
        default => 'black',
    };
            
    echo '<div class="color-circle" style="background-color: ' . $cssColor . '; height: 5%; width: 100%;"></div>';
}
?>
    </row>
    
    
    <form method="POST">
        <button type="submit" name="color" value="rojo">Rojo</button>
        <button type="submit" name="color" value="verde">Verde</button>
    </form>
</body>
