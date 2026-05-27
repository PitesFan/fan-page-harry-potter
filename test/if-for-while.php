<?php
$sir = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$sImpar = 0;
$sPar = 0;

for ($i = 0; $i < 10; $i++) {
if($sir[$i] % 2 == 0) {
    $sPar++;
} else {
    $sImpar++;
}}

echo "
<h3>Total numere pare: " . $sPar . "</h3>
<h3>Total numere impare: " . $sImpar . "</h3>";

?>
