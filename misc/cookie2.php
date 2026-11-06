<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php
            if (isset($_POST['myButton'])) {
                setcookie("color", "red", [
                    'expires' => time() + 86400,
                    'path' => '/',
                    'domain' => 'localhost',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
                $color = "red";
            } else {
                $color = $_COOKIE["color"] ?? "grey"; 
                
                if (!isset($_COOKIE["color"])) {
                    setcookie("color", "grey", [
                        'expires' => time() + 86400,
                        'path' => '/',
                        'domain' => 'localhost',
                        'secure' => false,
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                }
            }
        ?>
        <h1>Color:</h1>
        <form method="POST">
            <button type="submit" name="myButton">Guardar</button>
            <p style="color: <?php echo $color ?>;">
                <?php
                    echo htmlspecialchars($color);
                ?>
            </p>
        </form>
    </body>
</html>