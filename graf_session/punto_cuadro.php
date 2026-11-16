<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION["x"]) ) {
    $_SESSION["x"] = 0;
    $_SESSION["y"] = 0;
}

// Process POST data and immediately redirect to prevent refresh duplication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['right'])) {
        $_SESSION['x']+=20;
        if ($_SESSION['x'] > 300) {
            $_SESSION['x'] -=600;
        }
    } elseif (isset($_POST['left'])) {
        $_SESSION['x']-=20;
        if ($_SESSION['x'] < -300) {
            $_SESSION['x'] +=600;
        }
    } elseif (isset($_POST['up'])) {
        $_SESSION['y']-=20;
        if ($_SESSION['y'] < -300) {
            $_SESSION['y'] +=600;
        }
    } elseif (isset($_POST['down'])) {
        $_SESSION['y']+=20;
        if ($_SESSION['y'] > 300) {
            $_SESSION['y'] -=600;
        }
    } elseif (isset($_POST['zero'])) {
        $_SESSION['x'] = 0;
        $_SESSION['y'] = 0;
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
        .container {
            position: relative;
            width: 600px;
            height: 600px;
            border: #333 solid 4px;
            margin: 60px 0 30px 0;
        }
        .red-dot {
            position: absolute;
            top: -8px;
            left: 0px;
            width: 20px;
            height: 20px;
            background-color: red;
            border-radius: 50%;
            margin-left: <?php echo ($_SESSION['x'] + 290); ?>px;
            margin-top: <?php echo ($_SESSION['y'] + 300); ?>px;
            transition: margin-left 0.2s ease, margin-top 0.2s ease;
        }
    </style>
</head>
<body>
    <h1>Mover punto izq der</h1>
    <p>Haga click en los botones para mover el punto:</p><br>
    <form method="post" action="">
        <button type="submit" name="up">↑</button>
        <button type="submit" name="down">↓</button>
        <button type="submit" name="left">←</button>
        <button type="submit" name="right">→</button>
        <div class="container">
            <div class="red-dot"></div>
        </div>
        <br>
        <button type="submit" name="zero" value="Establecer a cero">Establecer a cero</button>
    </form>
</body>
</html>