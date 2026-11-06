<?php

$_user = "user";
$_pswd = "pswd";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve textarea value safely
    if (isset($_POST['user'])) if (isset($_POST['pswd'])) {
        // Trim whitespace and sanitize to prevent XSS
        if ($_POST['user'] == $_user && $_POST['pswd'] == $_pswd) {
            echo "<h3>You entered:</h3>";
            echo "<pre>{$_user}</pre>";
        } else {
            echo "<h3>You entered:</h3>";
        }

    } else {
        echo "Textarea value not found.";
    }
}

?>

<form method="post">
    <textarea name="user" id="user"></textarea>
    <textarea name="password" id="pswd"></textarea>
    <button type="submit">Entrar</button>
</form>