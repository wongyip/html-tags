<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Utils\CSS;

trait CssStyle
{
    /**
     * Associative array of CSS rule => style.
     *
     * @var array
     */
    protected array $cssRules = [];

    /**
     * Get or set (replace) the style attribute.
     *
     * @param string|null $style
     * @return string|static
     */
    public function style(string $style = null): string|static
    {
        if ($style) {
            $this->cssRules = CSS::parseStyleRules($style);
            return $this;
        }
        $rules = [];
        foreach ($this->styles() as $rule => $style) {
            $rules[] = sprintf('%s: %s;', trim($rule), trim(rtrim(trim($style), ';')));
        }
        return implode(' ', $rules);
    }

    /**
     * Output all CSS style as a rules array.
     *
     * @return string[]|array
     */
    public function styles(): array
    {
        return $this->stylesHook($this->cssRules);
    }

    /**
     * [Extension] Modify the CSS styles array before output.
     *
     * @param string[]|array $rules
     * @return string[]|array
     */
    protected function stylesHook(array $rules): array
    {
        return $rules;
        /* e.g.
        $custom = ['color', 'brown'];
        return array_unique(array_merge($styles, $custom));
        */
    }

    /**
     * @param string $rule
     * @param string|null $style
     * @return static
     */
    public function styleAdd(string $rule, string $style = null): static
    {
        foreach (self::parseCssStyleRules($rule, $style) as $r => $s) {
            // Because order's matter for CSS rules.
            $this->styleRemove($r);
            $this->cssRules[$r] = $s;
        }
        return $this;
    }

    /**
     * Remove ALL rules.
     *
     * @return static
     */
    public function styleEmpty(): static
    {
        $this->cssRules = [];
        return $this;
    }

    /**
     * @param string $rule
     * @return static
     */
    public function styleRemove(string $rule): static
    {
        if (key_exists($rule, $this->cssRules)) {
            unset($this->cssRules[$rule]);
        }
        else {
            // Just in case a rule-colon-style string is passed in.
            $rules = array_keys(self::parseCssStyleRules($rule));
            foreach ($rules as $r) {
                if (key_exists($r, $this->cssRules)) {
                    unset($this->cssRules[$rule]);
                }
            }
        }
        return $this;
    }

    /**
     * @param string $rule
     * @param string|null $style
     * @return array
     */
    private static function parseCssStyleRules(string $rule, string $style = null): array
    {
        if (empty($style)) {
            return CSS::parseStyleRules($rule);
        }
        return CSS::parseStyleRules("$rule: $style");
    }
}