<?php

namespace Wongyip\HTML\Utils;

class CSS
{
    /**
     * Convert a mixed set of CSS class string / array to plain array.
     *
     * @param array|string $classes
     * @return array
     */
    public static function classArray(array|string ...$classes): array
    {
        $classes = Convert::flatten(...$classes);
        return array_filter(array_map('trim', explode(' ', implode(' ', $classes))));
    }

    /**
     * Convert array of CSS declarations to CSS style string.
     *
     * e.g. ['width: 100%;', 'foo: bar;'] >>> 'width: 100%; foo: bar;'
     *
     * N.B. No validation and no error reporting.
     *
     * @param array $declarations
     * @return string
     */
    public static function parseDeclarationsStyle(array $declarations): string
    {
        return implode(' ', $declarations);
    }

    /**
     * Convert CSS style string to array of CSS declarations.
     *
     * e.g. 'width: 100%; foo: bar;' >>> ['width: 100%;', 'foo: bar;']
     *
     * N.B. No validation and no error reporting.
     *
     * @param string $style
     * @param array|null $errors
     * @return array
     */
    public static function parseStyleDeclarations(string $style, array &$errors = null): array
    {
        $rules = [];
        $errors = [];
        foreach (explode(';', $style) as $rule) {
            $rule = trim($rule, "; \n\r\t\v\0");
            if (!empty($rule)) {
                if (preg_match("/([^:]*):(.*)/", $rule, $matches)) {
                    $property = trim($matches[1]);
                    if (preg_match("/^[^ ]*$/", $property)) {
                        $value = trim($matches[2]);
                        if (!empty($value)) {
                            $rules[] = sprintf('%s: %s;', $property, $value);
                        }
                        else {
                            $errors[] = sprintf('Empty value in rule "%s".', $rule);
                        }
                    } else {
                        $errors[] = sprintf('Invalid property "%s" in rule "%s".', $property, $rule);
                    }
                } else {
                    $errors[] = sprintf('Mal-formatted rule "%s".', $rule);
                }
            }
        }
        return $rules;
    }

    /**
     * Dedupe by matching declaration.
     *
     * E.g. ['a: 1;', 'b: 2;', 'c: 3;', 'b: 2;']
     * Get: ['a: 1;', 'c: 3;', 'b: 2;']
     *
     * @param array $declarations
     * @return array
     */
    public static function dedupeDeclarations(array $declarations): array
    {
        $results = [];
        foreach (array_reverse($declarations) as $declaration) {
            if (!in_array($declaration, $results)) {
                $results[] = $declaration;
            }
        }
        return array_reverse($results);
    }

    /**
     * Dedupe by matching property.
     *
     * E.g. ['a: 1;', 'b: 2;', 'b: 3;', 'b: 4;']
     * Get: ['a: 1;', 'b: 4;']
     *
     * @param array $declarations
     * @return array
     */
    public static function dedupeProperties(array $declarations): array
    {
        $results = [];
        $properties = [];
        foreach (array_reverse($declarations) as $declaration) {
            if (str_contains($declaration, ':')) {
                list ($property) = explode(':', $declaration);
                if (!in_array($property, $properties)) {
                    $results[] = $declaration;
                    $properties[] = $property;
                }
            }
            else {
                // @todo Declaration is invalid and preserved now, change needed?
                $results[] = $declaration;
                $properties[] = $declaration;
            }
        }
        return array_reverse($results);
    }
}