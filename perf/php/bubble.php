<?php

function test(array $array, int $length)
{
    for ($n = $length; $n > 1; --$n) {
        for ($i = 0; $i < $n - 1; ++$i) {
            if ($array[$i] > $array[$i + 1]) {
                $tmp = $array[$i];
                $array[$i] = $array[$i + 1];
                $array[$i + 1] = $tmp;
            }
        }
    }
}

$size = intval($argv[1]);
$array = [];
for ($i = 0; $i < $size; $i++) {
    $array[$i] = $size - $i;
}

$start = microtime(true);
test($array, $size);
$end = microtime(true);

echo (($end - $start) * 1000000) . '[micros]' . PHP_EOL;
