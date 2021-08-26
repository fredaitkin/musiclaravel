<?php

// Change structure of names files
$handle1 = fopen("config/capitalized.php", "r");
$handle2 = fopen("config/capitalized2.php", "w");
if ($handle1) {
    while (($line = fgets($handle1)) !== false) {
        fwrite($handle2, str_pad(str_replace(array(',', "\n", "\r"), array('', '', ''), strtolower($line)), 24) . '=> ' . ltrim($line));

    }
    fclose($handle2);
    fclose($handle1);
}

// Pad arrays to same length
// mephistopheles = 14 + 4 + 4 but make it divisible by 4 - tab length
/*
$handle1 = fopen("config/names.php", "r");
$handle2 = fopen("config/names_copy.php", "w");
if ($handle1) {
    while (($line = fgets($handle1)) !== false) {
        $equals_pos = strpos($line, '=>');
        fwrite($handle2, str_pad(substr($line, 0, $equals_pos), 24) . substr($line, $equals_pos));
    }
    fclose($handle2);
    fclose($handle1);
}
*/

