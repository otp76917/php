<?php

function recursiveFile($n, $fileName) {
    if ($file = fopen($fileName,"x")) {
        fwrite($file, file_get_contents($fileName));
        fclose($file);
    } else {
        $n++;
        $pos = strpos($fileName, "_");
        if ($pos !== false) {
            $fileName = substr($fileName, 0, $pos);
        }
        recursiveFile($n, $fileName . "_" . $n);
    }
}

recursiveFile(0, "./txts/recursion.txt");

?>