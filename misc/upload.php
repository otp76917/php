<!DOCTYPE html>
<html>

<head>

</head>

<body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Seleccionar un fichero:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <div class="image-row-container">
        <?php
        // Define the path to your images folder relative to this script
        $folder_path = "emojis/";

        // Allowed image extensions
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');

        // Check if the directory exists
        if (is_dir($folder_path)) {
            // Scan directory for files
            $files = scandir($folder_path);

            foreach ($files as $file) {
                // Get the file extension
                $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // Check if file is a valid image
                if (in_array($file_ext, $allowed_extensions)) {
                    $image_url = $folder_path . $file;
                    // Output the image tag
                    echo '<img src="' . htmlspecialchars($image_url) . '" alt="' . htmlspecialchars($file) . '" style="width: 200px; height: 200px; margin: 5px;">';
                }
            }
        } else {
            echo '<p>The specified folder does not exist.</p>';
        }
        ?>
    </div>

</body>

</html>

<?php

if (isset($_POST['submit'])) {
    $target_dir = "emojis/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Comprobamos si es una imagen real o falsa  
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if (file_exists($target_file)) {
        echo "El fichero existe.";
        $uploadOk = 0;
    } else if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Solo se permiten ficheros JPG, JPEG, PNG y GIF.";
        $uploadOk = 0;
    } else if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "El fichero es demasiado grande.";
        $uploadOk = 0;
    } else if ($check !== false) {
        echo "El fichero es una imagen - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "El fichero no es una imagen.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "<br>Tu archivo no fue subido.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // header("Location: " . $_SERVER['PHP_SELF']);
            echo "<br>El archivo " . htmlspecialchars(basename($_FILES["fileToUpload"]["tmp_name"])) . " ha sido subido correctamente.";
        } else {
            echo "<br>Hubo un error al subir tu archivo.";
            echo "<br>El archivo " . htmlspecialchars(basename($_FILES["fileToUpload"]["tmp_name"])) . " no ha sido subido a: " . $target_file;
        }
    }
}



?>