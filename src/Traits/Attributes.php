<?php

namespace Wongyip\HTML\Traits;

use Throwable;

/**
 * Attributes manipulation trait.
 *
 * @todo Maybe need to normalize the key names of $__attrs.
 */
trait Attributes
{
    /**
     * Internal storage of attributes listed in $tagAttrs, which have value set
     * already (excluding attributes listed in $complexAttrs).
     *
     * @var array|string[]
     */
    protected array $__attrs = [];

    /**
     * Get or set a single tag attribute. Getter return null when attribute is
     * not set. Setter ignore unrecognized attribute, unless $forced is true.
     *
     * @param string $attribute
     * @param mixed|null $value
     * @param bool|null $forced
     * @return mixed|static
     */
    public function attribute(string $attribute, mixed $value = null, bool $forced = null): mixed
    {
        // Get
        if (is_null($value)) {
            if ($this->hasAttribute($attribute)) {
                return key_exists($attribute, $this->__attrs)
                    ? $this->__attrs[$attribute] // Plain, including custom attributes.
                    : $this->$attribute();       // Complex attributes.
            }
            return null;
        }
        // Set
        if ($this->hasAttribute($attribute, $isCustom)) {
            // Previously added with force.
            if ($isCustom) {
                $this->__attrs[$attribute] = $value;
            }
            else {
                $this->$attribute($value);
            }
        }
        elseif ($forced) {
            $this->__attrs[$attribute] = $value;
        }
        return $this;
    }

    /**
     * Get or set the entire tag attributes array.
     *
     * Notes:
     *  1. Not a direct get-setter.
     *  2. Unrecognized attributes are ignored (not in $tagAttrs, nor $complexAttrs).
     *  3. Tag "contents" is NOT an attribute.
     *  4. If $withDataAttrs is TRUE, the data attributes (dataset) is included (getter only).
     *
     * @param array|null $attributes
     * @param bool|null $withDataAttrs
     * @return array|static
     */
    public function attributes(array $attributes = null, bool $withDataAttrs = null) : array|static
    {
        // Get
        if (is_null($attributes)) {

            // Base
            $attributes = $this->__attrs;

            // Complex or dynamic attribute with its own getter.
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
        foreach ($attributes as $setter => $value) {
            try {
                // Only recognized.
                if ($this->hasAttribute($setter)) {
                    $this->$setter($value);
                }
            }
            catch (Throwable $e) {
                error_log(sprintf('TagAbstract.attributes() - Error: %s (%d)', $e->getMessage(), $e->getCode()));
            }
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
        if (in_array($name, $this->tagAttrs) || in_array($name, static::$complexAttrs)) {
            return true;
        }
        elseif (key_exists($name, $this->__attrs)) {
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
}