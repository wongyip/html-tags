<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Utils\CSS;

/**
 * CSS style (complex attribute) manipulation trait.
 *
 * @note This is an extremely rough implementation.
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
     * [Internal] Used by the attribute() method.
     *
     * @param mixed|null $set
     * @return string|static
     * @see Attributes::attribute()
     */
    protected function _style(mixed $set = null): string|static
    {
        if (is_null($set)) {
            return $this->style();
        }
        return is_string($set) ? $this->style($set) : $this;
    }

    /**
     * Get or set (replace) the CSS style. Setter take one or two arguments,
     * e.g. style('color: brown') and style('color', 'brown') yield identical
     * result.
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
     * Alias to styleAppend().
     *
     * @param string ...$declarations
     * @return static
     */
    public function styleAdd(string ...$declarations): static
    {
        return $this->styleAppend(...$declarations);
    }

    /**
     * Append CSS declaration(s), accepts one or more semicolon separated
     * declarations string, .e.g 'color: red; width: 10px'.
     *
     * @param string ...$declarations
     * @return static
     */
    public function styleAppend(string ...$declarations): static
    {
        $appends = [];
        foreach ($declarations as $declaration) {
            $appends = array_merge($appends, CSS::parseStyleDeclarations($declaration));
        }
        $this->cssDeclarations = array_merge($this->cssDeclarations, $appends);
        return $this;
    }

    /**
     * Alias to stylesEmpty().
     *
     * @return static
     */
    public function styleEmpty(): static
    {
        return $this->stylesEmpty();
    }

    /**
     * Prepend CSS declaration(s), accepts one or more semicolon separated
     * declarations string, .e.g 'color: red; width: 10px'.
     *
     * @param string ...$declarations
     * @return static
     */
    public function stylePrepend(string ...$declarations): static
    {
        $prepends = [];
        foreach ($declarations as $declaration) {
            $prepends = array_merge($prepends, CSS::parseStyleDeclarations($declaration));
        }
        $this->cssDeclarations = array_merge($prepends, $this->cssDeclarations);
        return $this;
    }

    /**
     * Get of set the value of particular CSS property. Getter returns the last
     * value if multiple declarations is found, return null if CSS property has
     * no declaration. Setter default append declaration, replace existing(s) if
     * $unsetExisting is TRUE.
     *
     * @param string $property
     * @param string|null $value
     * @param bool $unsetExisting Unset existing declarations of the property.
     * @return string|null|static
     */
    function styleProperty(string $property, string $value = null, bool $unsetExisting = false): string|null|static
    {
        // Get
        if (is_null($value)) {
            foreach (array_reverse($this->cssDeclarations) as $declaration) {
                if (str_starts_with($declaration, "$property:")) {
                    return trim(preg_replace("/.*:/", '', trim(trim($declaration), ';')));
                }
            }
            return null;
        }
        // Set
        if ($unsetExisting) {
            $this->styleUnset($property);
        }
        $this->styleAppend(sprintf('%s: %s;', $property, $value));
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
     * Clear all CSS declarations.
     *
     * @return static
     */
    public function stylesEmpty(): static
    {
        $this->cssDeclarations = [];
        return $this;
    }

    /**
     * [Extension] Modify the CSS declarations array before output.
     *
     * @param string[]|array $declarations
     * @return string[]|array
     */
    protected function stylesHook(array $declarations): array
    {
        return $declarations;
        /* e.g.
        $customizations = ['color: brown;', 'width: 100%;'];
        return array_merge($declarations, $customizations);
        */
    }

    /**
     * Unset all declarations of the CSS property.
     *
     * @param string $property
     * @return static
     */
    public function styleUnset(string $property): static
    {
        $modified = [];
        foreach ($this->cssDeclarations as $declaration) {
            if (!str_starts_with($declaration, "$property:")) {
                $modified[] = $declaration;
            }
        }
        $this->cssDeclarations = $modified;
        return $this;
    }
}