<?php

namespace Wongyip\HTML\Utils;

use Exception;
use Throwable;

class Output
{
    /**
     * @param string|null $char
     * @param int|null $length
     * @return void
     */
    public static function line(string $char = null, int $length = null): void
    {
        $char = $char ?? '=';
        $length = $length ?? 80;
        echo str_repeat($char, $length) . PHP_EOL;
    }

    /**
     * @param Throwable|Exception $e
     * @return void
     */
    public static function error(Throwable|Exception $e): void
    {
        echo sprintf('Error: %s (%d)', $e->getMessage(), $e->getCode()) . PHP_EOL;
    }

    /**
     * @param string $header
     * @param string|null $char
     * @param int|null $length
     * @return void
     */
    public static function header(string $header, string $char = null, int $length = null): void
    {
        $char = $char ?? '=';
        $length = $length ?? 80;
        echo PHP_EOL . $header . PHP_EOL .str_repeat($char, $length) . PHP_EOL;
    }
}