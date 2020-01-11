<?php
/*
 * Configuration
 */
$firstElementSize = 500;
$lastElementSize = 5000;
$step = 500;
/*
 * Erwartet eine Ausgabe der Befehle in Form von "\d+(\.\d*)?\[micros\]"
 */
$commands = [
    "php" => "php perfphp.php",
    "c" => "gcc perfc.c -o c && ./c",
    "cpp" => "g++ perfcpp.cpp -o cpp && ./cpp",
    "js" => "node perfjs.js",
    "python" => "python perfpy.py",
    "java" => "java perfjava.java"
];
/*
 * Command
 * php controllwe.php [repeats] [outputfile.csv]
 * Wiederholungen $repeats
 * Ausgabefile $outputfile
 * Gibt an wie oft der Test wiederholt wird
 */
$repeats = $argv[1];
$outputfile = $argv[2] ?? "result.csv";


//Controlling
$results = [];
$repeats = $repeats > 0 ? $repeats : 1;
for ($count = $firstElementSize; $count <= $lastElementSize; $count = $count + $step) {
    $result = ["elements" => $count];
    foreach ($commands as $lang => $command) {
        for ($s = 0; $s < $repeats; $s++) {
            $return = exec($command . ' ' . $count);
            echo $lang . "\t" . $count . "\t" . $return . PHP_EOL;
            preg_match('/^(.*?)\[micros\]$/', $return, $match);
            //Durchschnitt berechnen
            $result[$lang] = (($result[$lang] ?? 0) * $s + intval($match[1])) / ($s + 1);
        }
        $result[$lang] = intval($result[$lang]);
        if($repeats>1){
            echo "AVG:\t" . $result[$lang]."[micros]" . PHP_EOL;
        }

    }
    $results[] = $result;
}

$csvHead = array_keys(current($results));
$csv = implode(";", $csvHead) . "\n";
foreach ($results as $result) {
    $csv .= implode(";", $result) . "\n";
}
file_put_contents($outputfile, $csv);