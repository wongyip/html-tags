<?php

use Wongyip\HTML\Supports\ContentsCollection;

require_once __DIR__ . '/../vendor/autoload.php';

print_r(ContentsCollection::flatten(
    1,
    [2, 3],
    [4, [5, 6]],
    7,
    [8, [9, [10]]]
));

function flatten(array $contents) {
    $flattened = [];
    array_walk_recursive($contents, function($a) use (&$flattened) { $flattened[] = $a; });
    return $flattened;
}

print_r(flatten([
    1,
    [2, 3],
    [4, [5, 6]],
    7,
    [8, [9, [10]]]
]));