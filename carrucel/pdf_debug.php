<?php
$bytes = file_get_contents('test.pdf');
$text = $bytes;
$lines = preg_split('/\r\n|\r|\n/', $text);
for ($i = 0; $i < min(40, count($lines)); $i++) {
    echo $i . ': [' . $lines[$i] . ']\n';
}
?>
