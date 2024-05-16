<?php

namespace Wongyip\HTML\Traits;

use Throwable;

/**
 * Contents manipulation trait.
 */
trait Attributes
{
    /**
     * Internal storage of attributes listed in $tagAttrs, which have value set
     * already (excluding attributes listed in $complexAttrs).
     *
     * @var array|string[]
     */
    protected array $attrsStore = [];

    /**
     * Get or set a single tag attributes. In case of non-exist attribute, setter
     * simply ignore it, while get will return null.
     * 
     * @param string $attribute
     * @param mixed|null $value
     * @return mixed|static
     */
    public function attribute(string $attribute, mixed $value = null): mixed
    {
        // Set
        if ($value) {
            if ($this->hasAttribute($attribute)) {
                $this->attrsStore[$attribute] = $value;
            }
            return $this;
        }
        // Get
        if (key_exists($attribute, $this->attrsStore)) {
            return $this->attrsStore[$attribute];
        }
        return null;
    }

    /**
     * Get or set all tag attributes.
     *
     * Notes:
     *  1. Not a direct get-setter.
     *  2. Unrecognized attributes are ignored (not in $tagAttrs, nor $complexAttrs).
     *  3. Tag "contents" is NOT an attribute.
     *
     * @param array|null $attributes
     * @return array|static
     */
    public function attributes(array $attributes = null) : array|static
    {
        if ($attributes) {
            foreach ($attributes as $setter => $val) {
                try {
                    if (in_array($setter, $this->tagAttrs) || in_array($setter, static::$complexAttrs)){
                        $this->$setter($val);
                    }
                    else {
                        error_log(sprintf('TagAbstract.attributes() - Unrecognized attribute "%s"', $setter));
                    }
                }
                catch (Throwable $e) {
                    error_log(sprintf('TagAbstract.attributes() - Error: %s (%d)', $e->getMessage(), $e->getCode()));
                }
            }
            return $this;
        }
        $attributes = $this->attrsStore;
        foreach (static::$complexAttrs as $getter) {
            if ($val = $this->$getter()) {
                $attributes[$getter] = $val;
            }
        }
        return $attributes;
    }

    /**
     * Check if the tag has the given attribute.
     *
     * @param string $attribute
     * @return bool
     */
    public function hasAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->tagAttrs);
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