<?php

use Wongyip\HTML\Demo\Demo;

require_once __DIR__ . '/../vendor/autoload.php';

$demos = array_diff(
    array_column((new ReflectionClass(Demo::class))->getMethods(), 'name'),
    ['__construct']
);

if ($run = $argv[1]) {
    if ($run !== '__construct') {
        if ($run === '--all') {
            foreach ($demos as $demo) {
                echo PHP_EOL . "Demo: $demo" .PHP_EOL . str_repeat('=', 80) . PHP_EOL;
                Demo::$demo();
            }
        }
        elseif (method_exists(Demo::class, $run)) {
            Demo::$run();
        }
        else {
            error_log("Undefined Demo::$run() method.");
        }
        exit;
    }
}


echo PHP_EOL . 'Usage: php demo.php <demo>|--all';
echo PHP_EOL . PHP_EOL . 'Available demos:' .PHP_EOL;
foreach ($demos as $demo) {
    echo "  - $demo\n";
}
echo PHP_EOL;
exit;