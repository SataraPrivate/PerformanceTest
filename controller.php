<?php
/*
 * $ php controller.php [testfunction] [repeats] [outputfile.csv]
 * testfunction:    führt eine bestimmte Funktion aus
 * repeats:         Wiederholungen
 * outputfile.csv   Angabe des Outputfiles
 */
define("PERF_DIR", "perf/");
/*
 * Erwartet eine Ausgabe der Befehle in Form von "\d+(\.\d*)?\[micros\]"
 * $0 gibt das Position des Files an
 * $1 gibt die Position der Argumente an
 */
$languages = [
    "C" => [
        "exec" => "gcc $0 -o c && ./c $1",
        "extension" => "c"
    ],
    "C++" => [
        "exec" => "g++ $0 -o cpp && ./cpp $1",
        "extension" => "cpp"
    ],
    "Java" => [
        "exec" => "java $0 $1",
        "extension" => "java"
    ],
    "JS" => [
        "exec" => "node $0 $1",
        "extension" => "js"
    ],
    "Python2" => [
        "exec" => "python2 $0 $1",
        "extension" => "py"
    ],
    "Python3" => [
        "exec" => "python3 $0 $1",
        "extension" => "py"
    ],
    "PHP" => [
        "exec" => "php $0 $1",
        "extension" => "php"
    ],
];
if (posix_getuid() == 0) {
    $languages["PHP"] = [
        "exec" => "sudo phpdismod xdebug && php $0 $1",
        "extension" => "php"
    ];
    $languages["PHP with xDebug"] = [
        "exec" => "sudo phpenmod xdebug && php $0 $1",
        "extension" => "php"
    ];
}

/*
 * Configuration
 * Name must be equals filename
 */
$testConfs = [
    "bubble" => [
        "firstElementSize" => 500,
        "lastElementSize" => 10000,
        "step" => 500
    ]
];


$testfunction = $argv[1] ?? "";
$controller = new TestController($languages, $testConfs, $argv[2] ?? 1);

$tests = empty($testfunction) ? $controller->getTests() : [$testfunction];
foreach ($tests as $test) {
    $outputFile = $argv[3] ?? $test . "-result.csv";
    saveResultAsCSV($controller->test($test), $outputFile);
}
@unlink("c");
@unlink("cpp");

class TestController
{
    protected $languages;
    protected $confs;
    protected $repeats;

    public function __construct(array $languages, array $confs, int $repeats)
    {
        $this->languages = $languages;
        $this->confs = $confs;
        $this->repeats = $repeats;
        $this->init();
    }

    protected function init()
    {
        foreach ($this->languages as $lang => &$langConf) {
            $langConf["extension"] = $langConf["extension"] ?? $langConf["extension"] ?? $lang;
            $files = glob(PERF_DIR . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*" . $langConf["extension"]);
            foreach ($files as $file) {
                $langConf["files"][pathinfo($file)["filename"]] = $file;
            }
        }
    }

    public function getTests()
    {
        $tests = [];
        foreach ($this->languages as $langConf) {
            foreach ($langConf["files"] as $file) {
                $tests[] = pathinfo($file)["filename"] ?? null;
            }
        }
        return array_unique($tests);
    }

    public function test(string $test)
    {
        $callable = [$this, "test_" . $test];
        printf("Running Test [%s]...\n", $test);
        [$result, $addColumns] = is_callable($callable) ? call_user_func($callable) : $this->test_default($test);
        printf("Tests closed.\n");
        $size = count($result);
        printf("%d Results.\n", $size);
        if ($size > 0) {
            printf("%d Languages.\n", count(array_keys(current($result))) - $addColumns);
        }

        return $result;

    }

    private function test_bubble()
    {
        $firstElementSize = $this->confs["bubble"]["firstElementSize"] ?? 500;
        $lastElementSize = $this->confs["bubble"]["lastElementSize"] ?? 5000;
        $step = $this->confs["bubble"]["step"] ?? 500;

        $results = [];
        for ($count = $firstElementSize; $count <= $lastElementSize; $count = $count + $step) {
            $results[] = array_merge(["elements" => $count], $this->test_default("bubble", [$count])[0]);
        }
        return [$results, 1];
    }

    private function exec(string $exec, string $file, array $args = [], callable $logger = null)
    {
        $args = implode(" ", $args);
        $command = str_replace(["$0", "$1"], [$file, $args], $exec);
        $result = 0;
        $repeats = $this->repeats > 0 ? $this->repeats : 1;

        for ($s = 0; $s < $repeats; $s++) {
            $return = exec($command . ' ' . $args);
            if (is_callable($logger)) $logger($args . "\t|\t" . $return);
            preg_match('/^(\d*\.?\d*)\[micros\]/', $return, $match);
            //Durchschnitt berechnen
            $result = ($result * $s + intval($match[1])) / ($s + 1);
        }
        $result = intval($result);
        if ($repeats > 1) {
            if (is_callable($logger)) $logger("AVG:\t|\t" . $result . "[micros]");
        }
        return $result;
    }

    private function test_default(string $test, array $args = [])
    {
        $result = [];
        foreach ($this->languages as $lang => $langConf) {
            if (is_file($langConf["files"][$test] ?? "")) {
                $result[$lang] = $this->exec(
                    $langConf["exec"],
                    $langConf["files"][$test],
                    $args,
                    function ($msg) use ($lang) {
                        echo $lang . "\t" . $msg . PHP_EOL;
                    });
            }
        }
        return [$result, 0];
    }
}

function saveResultAsCSV(array $results, string $file)
{
    if (empty($results)) return;
    $csvHead = array_keys(current($results));
    $csv = implode(";", $csvHead) . "\n";
    foreach ($results as $result) {
        $csv .= implode(";", $result) . "\n";
    }
    file_put_contents($file, $csv);
}
