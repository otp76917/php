<?php
session_start();
if (!isset($_SESSION['flippedCards'])) {
    $_SESSION['flippedCards'] = [0, 0, 0, 0, 0, 0];
    $_SESSION['numFlipped'] = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jugar</title>
    <style>
        .card {
            width: 100px;
            height: 150px;
            border: 1px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            cursor: pointer;
        }
        .card[flipped="0"] {
            background-color: #000;
        }
    </style>
</head>
<body>
    <h1>Jugar</h1>
    <form method="post">
        <?php
            for ($i = 1; $i <= 6; $i++) {
                echo "<button name='flip' value='$i'>Voltear carta $i</button>";
            }
        ?>
    </form>
    <div style="display: flex; gap: 10px;">
        <?php
            if (!isset($_SESSION['cards'])) {
                $_SESSION['cards'] = [1, 1, 2, 2, 3, 3];
                shuffle($_SESSION['cards']);
            }
            for ($i = 1; $i <= 6; $i++) {
                echo "<div class='card' flipped='{$_SESSION['flippedCards'][$i-1]}' id='$i' value='{$_SESSION['cards'][$i-1]}'>{$_SESSION['cards'][$i-1]}</div>";
            }
        ?>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['flip'])) {
    $flip = $_POST['flip'];
    $_SESSION['numFlipped']++;
    $_SESSION['flippedCards'][$flip - 1] = 1;
    header("Location: " . "jugar.php");
    if ($_SESSION['numFlipped'] == 2) {
        sleep(2);
        $_SESSION['flippedCards'] = [0, 0, 0, 0, 0, 0];
        $_SESSION['numFlipped'] = 0;
    }
}
?>