<?php

use Wongyip\HTML\Demo\Demo;
use Wongyip\HTML\Utils\Output;

require_once __DIR__ . '/../vendor/autoload.php';

if ($demo = $argv[1]) {
    if (method_exists(Demo::class, $demo)) {
        Demo::$demo();
    }
    else {
        error_log("Undefined Demo::$demo() method.");
    }
    exit;
}

$demo = array_column((new ReflectionClass(Demo::class))->getMethods(), 'name');
Output::header('Available Demonstrations.');
foreach ($demo as $d) {
    echo "  - $d\n";
}
exit;