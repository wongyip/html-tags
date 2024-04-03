<?php

use Wongyip\HTML\Demo;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Throw Exception
 *
$tag = \Wongyip\HTML\Tag::make()->commonAttrs(['ho', 'la']);
print_r($tag);
exit;
 */

if ($demo = $argv[1]) {
    if (method_exists(Demo::class, $demo)) {
        Demo::$demo();
    }
    else {
        error_log("Undefined Demo::$demo() method.");
    }
    exit;
}

echo PHP_EOL . 'Demo::attributes();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::attributes();

echo PHP_EOL . 'Demo::contents();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::contents();

echo PHP_EOL . 'Demo::cssStyle();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::cssStyle();

echo PHP_EOL . 'Demo::usage1();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::example1();

echo PHP_EOL . 'Demo::usage2();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::example2();

echo PHP_EOL . 'Demo::selfClosingTag();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::selfClosingTag();

echo PHP_EOL . 'Demo::tagName();' . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
Demo::tagName();