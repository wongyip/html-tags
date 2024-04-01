<?php

namespace Wongyip\HTML\Utils;

class CSS
{
    /**
     * Convert CSS Style string to rule-style associative array.
     * N.B. No validation and no error reporting.
     *
     * e.g. 'foo: bar; width: 100%;' -> ['foo' => 'bar', 'width' => '100%']
     *
     * @param string $input
     * @return array
     */
    static function parseStyleRules(string $input): array
    {
        // In case of multiple rules.
        $inputs = str_contains($input, ';') ? explode(';', trim($input, ';')) : [$input];

        // Parse
        $ruleStyles = [];
        foreach ($inputs as $rs) {
            if (str_contains($rs, ':')) {
                list($rule, $style) = explode(':', $rs);
                $rule = trim($rule);
                if (preg_match("/^a-z*$|^[a-z]*[a-z\-]*[a-z]*$/i", $rule)) {
                    $style = trim(trim($style), ';');
                    if (!empty($style)) {
                        $ruleStyles[$rule] = trim(trim($style), ';');
                    }
                    else {
                        // Log::warning(sprintf('CssUtils.toRuleStyleArray: empty style ignored in rule "%s".', $rs));
                    }
                }
                else {
                    // Log::warning(sprintf('CssUtils.toRuleStyleArray: syntax error in rule "%s" (invalid rule name).', $rs));
                }
            }
            else {
                // Log::warning(sprintf('CssUtils.toRuleStyleArray: syntax error in $rule "%s" (missing colon).', $rs));
            }
        }
        return $ruleStyles;
    }
}