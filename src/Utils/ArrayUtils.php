<?php

namespace Wongyip\HTML\Utils;

use Wongyip\HTML\RendererInterface;

class ArrayUtils
{
    /**
     * @param mixed ...$contents
     * @return array
     */
    public static function flatten(mixed...$contents): array
    {
        $flattened = [];
        array_walk_recursive($contents, function($a) use (&$flattened) { $flattened[] = $a; });
        return $flattened;
    }
}