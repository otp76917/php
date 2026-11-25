<!DOCTYPE html>
<!--
  MAQUETA ESTÁTICA DE jugar.php (solo como referencia de diseño).
  En jugar.php:
   - El nombre del usuario (de la sesión), los créditos, las tiradas, las frutas,
     el mensaje de premio y la tabla de premios se generan con PHP.
   - La tabla de premios se genera con foreach a partir de un array asociativo
     (no se admite copiarla de esta maqueta).
   - Los créditos y las tiradas de la partida se guardan en variables de sesión.
   - Los rodillos solo se muestran después de tirar.
-->

<?php

$premios["cereza"] = 2;
$premios["limon"] = 4;
$premios["uva"] = 6;
$premios["sandia"] = 10;
$premios["iguales"] = 1;

$imagen["cereza"] = "<img src=\"img/cereza.png\" alt=\"cereza\">";
$imagen["limon"] = "<img src=\"img/limon.png\" alt=\"limon\">";
$imagen["uva"] = "<img src=\"img/uva.png\" alt=\"uva\">";
$imagen["sandia"] = "<img src=\"img/sandia.png\" alt=\"sandia\">";

?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tragaperras - Partida</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .contenedor { display: flex; gap: 60px; align-items: flex-start; }
        .creditos { color: #1565c0; font-size: 20px; font-weight: bold; margin: 0; }
        .maquina { background: #263238; border-radius: 14px; padding: 16px; display: inline-flex; gap: 18px; }
        .rodillo { background: #fff; border-radius: 8px; width: 140px; height: 136px;
                   display: flex; align-items: center; justify-content: center; }
        .rodillo img { width: 110px; height: 110px; }
        .premios { border-collapse: collapse; }
        .premios td { border: 1px solid #888; padding: 4px 12px; }
        .premios img { width: 32px; height: 32px; }
        .gana { color: #2e7d32; } .pareja { color: #ef6c00; } .nada { color: #c62828; }
        form { display: inline; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div>
            <h1>Bienvenido/a, Ana García</h1>

            <div class="maquina">
                <?php
                $start = "<div class=\"rodillo\">";
                $image = "";
                $end = "</div>";
                ?>
                <div class="rodillo"><img src="img/uva.png" alt="uva"></div>
                <div class="rodillo"><img src="img/uva.png" alt="uva"></div>
                <div class="rodillo"><img src="img/uva.png" alt="uva"></div>
            </div>
            <h2 class="gana">¡TRES IGUALES! Ganas 6 créditos</h2>

            <form method="post" action="jugar.php">
                <input type="submit" name="tirar" value="TIRAR">
            </form>
            <form method="post" action="guardar.php">
                <input type="submit" name="cobrar" value="COBRAR">
            </form>
        </div>

        <div>
            <p class="creditos">Créditos: 12</p>
            <p>Tiradas: 5</p>
            <h3>Tabla de premios</h3>
            <table class="premios">
                <?php
                
                ?>
                <tr><td><img src="img/cereza.png" alt="cereza"> x 3</td><td>2</td></tr>
                <tr><td><img src="img/limon.png" alt="limon"> x 3</td><td>4</td></tr>
                <tr><td><img src="img/uva.png" alt="uva"> x 3</td><td>6</td></tr>
                <tr><td><img src="img/sandia.png" alt="sandia"> x 3</td><td>10</td></tr>
                <tr><td>Dos iguales</td><td>1</td></tr>
            </table>
        </div>
    </div>
</body>
</html>
