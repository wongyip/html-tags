<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Utils\CSS;

/**
 * CSS style attribute manipulation trait.
 */
trait CssStyle
{
    /**
     * CSS rules array, where rules are CSS style in"css-property: css-style;"
     * format.
     *
     * @var array
     */
    protected array $cssDeclarations = [];

    /**
     * Get or set (replace) the style attribute. Setter take one or two arguments,
     * where for example, style('color: brown;') and style('color', 'brown'), are
     * identical in function.
     *
     * @param string|null $style Or CSS property.
     * @param string|null $value Effective for setter only.
     * @return string|static
     */
    public function style(string $style = null, string $value = null): string|static
    {
        // Get
        if (is_null($style)) {
            // Take declarations processed by stylesHook().
            return CSS::parseDeclarationsStyle($this->styles());
        }
        $this->cssDeclarations = CSS::parseStyleDeclarations($value ? "$style: $value;" : $style);
        return $this;
    }

    /**
     * Output all style as an array of CSS declarations.
     *
     * @return string[]|array
     */
    public function styles(): array
    {
        return $this->stylesHook($this->cssDeclarations);
    }

    /**
     * [Extension] Modify the CSS declarations array before output.
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
     * Append CSS declarations, accepts one or more semicolon separated
     * declarations string, .e.g 'color: red; width: 10px'.
     *
     * @param string ...$rules
     * @return static
     */
    public function styleAppend(string ...$rules): static
    {
        $appends = [];
        foreach ($rules as $rule) {
            $appends = array_merge($appends, CSS::parseStyleDeclarations($rule));
        }
        $this->cssDeclarations = array_merge($this->cssDeclarations, $appends);
        return $this;
    }

    /**
     * Prepend CSS declarations, accepts one or more semicolon separated
     * declarations string, .e.g 'color: red; width: 10px'.
     *
     * @param string ...$rules
     * @return static
     */
    public function stylePrepend(string ...$rules): static
    {
        $prepends = [];
        foreach ($rules as $rule) {
            $prepends = array_merge($prepends, CSS::parseStyleDeclarations($rule));
        }
        $this->cssDeclarations = array_merge($prepends, $this->cssDeclarations);
        return $this;
    }

    /**
     * Remove ALL rules.
     *
     * @return static
     */
    public function styleEmpty(): static
    {
        $this->cssDeclarations = [];
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
        foreach ($this->cssDeclarations as $rule) {
            if (!str_starts_with($rule, "$property:")) {
                $modified[] = $rule;
            }
        }
        $this->cssDeclarations = $modified;
        return $this;
    }
}