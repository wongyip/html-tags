<?php

namespace Wongyip\HTML\Utils;

class CSS
{
    /**
     * Convert CSS style string to array of rules ('property: value;' strings).
     *
     * e.g. ['width: 100%;', 'foo: bar;'] >>> 'width: 100%; foo: bar;'
     *
     * N.B. No validation and no error reporting.
     *
     * @param array $rules
     * @return string
     */
    static function parseRulesStyle(array $rules): string
    {
        return implode(' ', $rules);
    }

    /**
     * Convert CSS style string to array of rules ('property: value;' strings).
     *
     * e.g. 'width: 100%; foo: bar;' >>> ['width: 100%;', 'foo: bar;']
     *
     * N.B. No validation and no error reporting.
     *
     * @param string $style
     * @param array|null $errors
     * @return array
     */
    static function parseStyleRules(string $style, array &$errors = null): array
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
}