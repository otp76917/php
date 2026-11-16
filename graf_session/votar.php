<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION["azul"]) ) {
    $_SESSION["azul"] = 0;
}
if (!isset($_SESSION["rojo"]) ) {
    $_SESSION["rojo"] = 0;
}

// Process POST data and immediately redirect to prevent refresh duplication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['voto'])) {
        if ($_POST['voto'] == 'rojo') {
            $_SESSION['rojo']++;
        } elseif ($_POST['voto'] == 'azul') {
            $_SESSION['azul']++;
        } elseif ($_POST['voto'] == 'cero') {
            $_SESSION['rojo'] = 0;
            $_SESSION['azul'] = 0;
        }
    }
    
    // Redirect to the same page using GET
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<html>
<head>
    <title>Graf Session Counter</title>
    <style>
        .line-container {
            position: relative;
            height: 4px;
            background-color: #333;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <h1>Subir y bajar número</h1>
    <p>Haga click en los botones para modificar el valor:</p><br>
    <form method="post" action="">
        <button style="color: red;" type="submit" name="voto" value="rojo">✓</button>
        <a class="line-container" style="color: red; width: <?php echo $_SESSION['rojo'] * 10; ?>px;"></a><br>
        <button style="color: blue;" type="submit" name="voto" value="azul">✓</button>
        <a class="line-container" style="color: blue; width: <?php echo $_SESSION['azul'] * 10; ?>px;"></a>
        <br>
        <button type="submit" name="voto" value="cero">Establecer a cero</button>
    </form>
</body>
</html>