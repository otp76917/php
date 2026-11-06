<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <?php
            setcookie("color", "grey", [
                'expires' => time() + 86400,
                'path' => '/',
                'domain' => 'localhost',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            $color = $_COOKIE["color"];
        ?>
        <h1>Color:</h1>
        <?php
            if(isset($_POST['myButton'])) {
                setcookie("color", "red", [
                    'expires' => 86400,
                    'path' => '/',
                    'domain' => 'localhost',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
        ?>
        <form method="POST">
            <button type="submit" name="myButton">Guardar</button>
            <p>
                <?php
                    echo $color;
                ?>
            </p>
        </form>
    </body>
</html>