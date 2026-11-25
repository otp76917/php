<?php
require_once "bd.php";
?>

<!DOCTYPE html>
<html>
<style></style>

<body>
    <table border="1">
        <tr>
            <th>Jugador</th>
            <th>Partidas</th>
            <th>Tiradas</th>
            <th>Balance</th>
        </tr>
        <?php
        $connection = conectar();
        $query = $connection->query("SELECT nombre, partidas, tiradas, balance FROM jugadores ORDER BY balance;");
        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            $nombre = $fila["nombre"];
            $partidas = $fila["partidas"];
            $tiradas = $fila["tiradas"];
            $balance = $fila["balance"];
            echo "
                <tr>
                    <td>$nombre</td>
                    <td>$partidas</td>
                    <td>$tiradas</td>
                    <td>$balance</td>
                </tr>
            ";
        }
        ?>
    </table>
</body>

</html>