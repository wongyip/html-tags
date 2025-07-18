<?php

namespace Wongyip\HTML\Traits;

use Wongyip\PHPHelpers\Format;

/**
 * Attributes manipulation trait.
 *
 * @todo Maybe need to normalize the key names of $__attrs.
 */
trait Attributes
{
    use EnumeratedAttributes;

    /**
     * Internal storage of recognized and force-added attributes with value set
     * already (excluding attributes listed in $complexAttrs).
     *
     * @var array|string[]
     */
    protected array $_attributes = [];
    /**
     * Supported attributes list. These attributes are get or set as in the
     * $attributes array.
     *
     * @var array
     * @see Overloading::__call()
     */
    protected array $_attrsNames = [];
    /**
     * Blank attribute value (empty string) is not store and not render unless
     * specified here.
     *
     * @var array
     */
    protected array $emptyValueAttributes = [];

    /**
     * Add one or more attributes to the supported attributes list.
     *
     * @param string|array ...$names
     * @return $this
     */
    public function addAttributes(string|array ...$names): static
    {
        foreach (Format::flatten($names) as $name) {
            if (!$this->hasAttribute($name) && !in_array($name, static::$complexAttrs)) {
                $this->_attrsNames[] = $name;
            }
        }
        return $this;
    }

    /**
     * Get or set a single tag attribute. Getter return null when attribute is
     * not set. Setter ignore unrecognized attribute, unless $forced is true.
     * Setter unset the attribute if the input $value is an empty string.
     *
     * Avoid using this method to read/write any complex attributes like class
     * and style, as they have more complex structure that this method is not
     * capable to handle.
     *
     * Input empty value to unset the attribute, unless the attribute is in the
     * $emptyValueAttributes array,
     *
     * @param string $attribute
     * @param mixed|null $value
     * @param bool|null $forced
     * @return mixed|static
     */
    public function attribute(string $attribute, mixed $value = null, bool $forced = null): mixed
    {
        /**
         * Proxy to the complex attribute get/setter.
         * @see CssClass::_class()
         * @see CssStyle::_style()
         */
        if ($this->isComplexAttribute($attribute)) {
            $method = '_' . $attribute;
            return $this->$method($value);
        }

        /**
         * Let the corresponding get/setter handle it.
         * @see EnumeratedAttributes
         */
        if ($this->isEnumeratedAttribute($attribute)) {
            return $this->$attribute($value);
        }

        // [Pinned] Complex attributes are handled before this line.

        // Get
        if (is_null($value)) {
            return $this->_attributes[$attribute] ?? null;
        }

        // Set
        if ($this->hasAttribute($attribute, $isCustom)) {
            // Previously added with force.
            if ($isCustom) {
                $this->_attributes[$attribute] = $value;
            }
            else {
                // Empty string to unset, unless attribute allows empty value.
                if (is_string($value) && (strlen($value) === 0) && !in_array($attribute, $this->emptyValueAttributes)) {
                    unset($this->_attributes[$attribute]);
                }
                else {
                    $this->_attributes[$attribute] = $value;
                }
            }
        }
        elseif ($forced) {
            $this->_attributes[$attribute] = $value;
        }
        return $this;
    }

    /**
     * Get or set multiple tag attributes.
     *
     * Getter:
     *  - Only set attributes are returned.
     *  - Include data attributes if $withDataAttrs is true.
     *
     * Setter:
     *  - Setting of unrecognized attributes are ignored, use the attribute()
     *    method with $force:true instead.
     *  - Ignores $withDataAttrs argument.
     *
     * Extra note: tag "contents" is NOT an attribute.
     *
     * @param array|null $attributes
     * @param bool|null $withDataAttrs
     * @return array|static
     */
    public function attributes(array $attributes = null, bool $withDataAttrs = null) : array|static
    {
        // Get
        if (is_null($attributes)) {

            // Generic and custom attributes.
            $attributes = $this->_attributes;

            // Complex attributes with their own getter.
            foreach (static::$complexAttrs as $getter) {
                if ($value = $this->$getter()) {
                    $attributes[$getter] = $value;
                }
            }

            // Take data attributes when necessary.
            return $withDataAttrs
                ? array_merge($attributes, $this->dataAttributes())
                : $attributes;
        }

        // Set
        foreach ($attributes as $name => $value) {
            $this->attribute($name, $value);
        }
        return $this;
    }

    /**
     * Check if the tag has the given attribute, either plain, complex or custom
     * attribute added with force (indicated with the $isCustom reference).
     *
     * @param string $name
     * @param bool|null $isCustom
     * @return bool
     */
    public function hasAttribute(string $name, bool &$isCustom = null): bool
    {
        $isCustom = false;
        if (in_array($name, $this->_attrsNames) || in_array($name, static::$complexAttrs)) {
            return true;
        }
        elseif (key_exists($name, $this->_attributes)) {
            $isCustom = true;
            return true;
        }
        return false;
    }

    /**
     * Check if the given attribute  is a Boolean attribute.
     *
     * @param string $attribute
     * @return bool
     * @see https://developer.mozilla.org/en-US/docs/Glossary/Boolean/HTML
     * @see https://meiert.com/en/blog/boolean-attributes-of-html/
     */
    protected function isBooleanAttribute(string $attribute): bool
    {
        return in_array(
            strtolower($attribute), [
                'allowfullscreen', 'async', 'autofocus', 'autoplay', 'checked', 'controls',
                'default', 'defer', 'disabled', 'formnovalidate', 'inert', 'ismap', 'itemscope',
                'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline',
                'readonly', 'required', 'reversed', 'selected',
            ]
        );
    }

    /**
     * Whether the given attribute is a complex attribute (having tailor-made
     * get/setter).
     *
     * @param string $attribute
     * @return bool
     */
    protected function isComplexAttribute(string $attribute): bool
    {
        return in_array($attribute, static::$complexAttrs);
    }
}