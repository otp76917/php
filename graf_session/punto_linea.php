<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION["count"]) ) {
    $_SESSION["count"] = 0;
}

// Process POST data and immediately redirect to prevent refresh duplication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['increment'])) {
        $_SESSION['count']+=20;
        if ($_SESSION['count'] > 300) {
            $_SESSION['count'] -=600;
        }
    } elseif (isset($_POST['decrement'])) {
        $_SESSION['count']-=20;
        if ($_SESSION['count'] < -300) {
            $_SESSION['count'] +=600;
        }
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
    <style>
        .line-container {
            position: relative;
            width: 600px;
            height: 4px;
            background-color: #333;
            margin: 60px 0 30px 0;
        }
        .red-dot {
            position: absolute;
            top: -8px; /* Centers the dot vertically on the 4px line */
            left: 0px;
            width: 20px;
            height: 20px;
            background-color: red;
            border-radius: 50%;
            /* Dynamically set the left offset using PHP */
            margin-left: <?php echo ($_SESSION['count'] + 300); ?>px;
            transition: margin-left 0.2s ease; /* Smooth visual transition */
        }
    </style>
</head>
<body>
    <h1>Mover punto izq der</h1>
    <p>Haga click en los botones para mover el punto:</p><br>
    <form method="post" action="">
        <button type="submit" name="decrement" value="-">-</button>
        <button type="submit" name="increment" value="+">+</button>
        <div class="line-container">
            <div class="red-dot"></div>
        </div>
        <br>
        <button type="submit" name="zero" value="Establecer a cero">Establecer a cero</button>
    </form>
</body>
</html>