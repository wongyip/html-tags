<?php

namespace Wongyip\HTML\Utils;

class Convert
{
    /**
     * convertInputToCamelCase
     *
     * @param string $input
     * @return string
     */
    public static function camel(string $input): string
    {
        return lcfirst(static::studly($input));
    }

    /**
     * @param mixed ...$elements
     * @return array
     */
    public static function flatten(mixed ...$elements): array
    {
        $flattened = [];
        array_walk_recursive($elements, function($a) use (&$flattened) { $flattened[] = $a; });
        return $flattened;
    }

    /**
     * convert-input-to-kebab-case
     *
     * @param string $input
     * @return string
     */
    public static function kebab(string $input): string
    {
        return static::snake($input, '-');
    }

    /**
     * Convert the array keys naming scheme.
     *
     * @param array $array
     * @param string $case
     * @param string|null $prefix
     * @param string|null $suffix
     * @return array
     */
    public static function keysCase(array $array, string $case, string $prefix = null, string $suffix = null): array
    {
        $values = array_values($array);
        $keys  = array_keys($array);
        $keys  = array_map(fn($k) => $prefix . Convert::$case($k) . $suffix, $keys);
        return array_combine($keys, $values);
    }

    /**
     * convert_input_to_snake_case
     *
     * @param string $input
     * @param string $delimiter
     * @return string
     */
    public static function snake(string $input, string $delimiter = '_'): string
    {
        // Nothing to convert.
        if (ctype_lower($input)) {
            return $input;
        }
        $input = preg_replace('/\s+/u', '', ucwords($input));
        $input = preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $input);
        return mb_strtolower($input, 'UTF-8');
    }

    /**
     * ConvertInputToStudlyCase
     *
     * @param string $input
     * @return string
     */
    public static function studly(string $input)
    {
        $words = explode(' ', str_replace(['-', '_'], ' ', $input));
        $studlyWords = array_map(fn ($word) => ucfirst($word), $words);
        return implode($studlyWords);
    }
}