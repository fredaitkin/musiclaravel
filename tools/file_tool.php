<?php

// Change structure of names files
$handle1 = fopen("config/names.php", "r");
$handle2 = fopen("config/names_copy.php", "w");
if ($handle1) {
    while (($line = fgets($handle1)) !== false) {
        fwrite($handle2, str_replace(array(',', "\n", "\r"), array(' => ', '', ''), strtolower($line)) . ltrim($line));

    }
    fclose($handle2);
    fclose($handle1);
}