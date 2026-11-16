<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION["count"]) ) {
    $_SESSION["count"] = 0;
}

// Process POST data and immediately redirect to prevent refresh duplication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['increment'])) {
        $_SESSION['count']++;
    } elseif (isset($_POST['decrement'])) {
        $_SESSION['count']--;
    } elseif (isset($_POST['zero'])) {
        $_SESSION['count'] = 0;
    }
    
    // Redirect to the same page using GET
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<html>
<head>
    <title>Graf Session Counter</title>
</head>
<body>
    <h1>Subir y bajar número</h1>
    <p>Haga click en los botones para modificar el valor:</p><br>
    <form method="post" action="">
        <button type="submit" name="decrement" value="-">-</button>
        <a><?php echo $_SESSION['count']; ?></a>
        <button type="submit" name="increment" value="+">+</button>
        <br>
        <button type="submit" name="zero" value="Establecer a cero">Establecer a cero</button>
    </form>
</body>
</html>