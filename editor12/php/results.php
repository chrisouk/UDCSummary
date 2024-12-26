<?php

$myFile = "testFile.txt";
$fh = fopen($myFile, 'r');
$theData = fgets($fh);
fclose($fh);

$lines = explode("\n", $theData);

echo "There are " . count($lines) . " lines in the file";

?>