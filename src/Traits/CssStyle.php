<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Utils\CSS;

/**
 * CSS style attribute manipulation trait
 */
trait CssStyle
{
    /**
     * CSS rules array, where rules are CSS style in"css-property: css-style;"
     * format.
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
        return CSS::parseRulesStyle($this->styles());
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
        $customRules = ['color: brown;', 'width: 100%;'];
        return array_merge($rules, $customRules);
        */
    }

    /**
     * Alias to styleAppend().
     *
     * @param string ...$rules
     * @return static
     */
    public function styleAdd(string ...$rules): static
    {
        return $this->styleAppend(...$rules);
    }

    /**
     * Append rules to current $rules array, input accepts string(s) in
     * 'rule: style;' format (semicolon is optional).
     *
     * @param string ...$rules
     * @return static
     */
    public function styleAppend(string ...$rules): static
    {
        $appends = [];
        foreach ($rules as $rule) {
            $appends = array_merge($appends, CSS::parseStyleRules($rule));
        }
        $this->cssRules = array_merge($this->cssRules, $appends);
        return $this;
    }

    /**
     * Prepend rules to current $rules array, input accepts string(s) in
     * 'rule: style;' format (semicolon is optional).
     *
     * @param string ...$rules
     * @return static
     */
    public function stylePrepend(string ...$rules): static
    {
        $prepends = [];
        foreach ($rules as $rule) {
            $prepends = array_merge($prepends, CSS::parseStyleRules($rule));
        }
        $this->cssRules = array_merge($prepends, $this->cssRules);
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
     * Unset all rules of the given property. E.g. styleUnset('border') removes
     * all elements in $cssRules that start with 'border:'.
     *
     * @param string $property
     * @return static
     */
    public function styleUnset(string $property): static
    {
        $modified = [];
        foreach ($this->cssRules as $rule) {
            if (!str_starts_with($rule, "$property:")) {
                $modified[] = $rule;
            }
        }
        $this->cssRules = $modified;
        return $this;
    }
}