<?php

$_user = "user";
$_pswd = password_hash("pswd", PASSWORD_DEFAULT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if both fields are submitted
    if (isset($_POST['user']) && isset($_POST['pswd'])) {
        
        // Compare the submitted values with our credentials
        if ($_POST['user'] === $_user && password_verify($_POST['pswd'], $_pswd)) {
            echo "<h3>Welcome! You entered the correct credentials.</h3>";
            echo "<pre>" . htmlspecialchars($_POST['user']) . "</pre>";
        } else {
            echo "<h3>Incorrect username or password:".$_pswd."</h3>";
        }
        
    } else {
        echo "Please fill in all fields.";
    }
}

?>

<form method="post">
    <label for="user">User:</label><br>
    <textarea name="user" id="user"></textarea><br>
    
    <label for="pswd">Password:</label><br>
    <!-- Fixed: name changed from 'password' to 'pswd' to match PHP -->
    <textarea name="pswd" id="pswd"></textarea><br>
    
    <button type="submit">Entrar</button>
</form>