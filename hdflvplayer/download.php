<?php
$filename = $_GET['f'];
header('Content-disposition: attachment; filename='.basename($filename));
readfile($filename);
?>