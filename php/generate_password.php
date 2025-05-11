<?php
$pattern = "GA0alb1|cBdN@efg2hCiH3jkD#1P40mM%n^]Vo\Q5pRE_<q=)SIK9rs6&tT+uUF, LL-(vZ/ 7wWx+8yXzY";
$length = strlen($pattern);
$password = [];

for ($i = 0; $i < 8; $i++) {
    $index = rand(0, $length - 1); // Adjusted to $length - 1
    $password[] = $pattern[$index];
}

echo implode($password);
?>
