<?php
$x = date('Y-m-d');

$y = strtotime($x);
var_dump($y);
$z = $y + 86400;
$a = date('Y-m-d',$z);
var_dump($a);
?>