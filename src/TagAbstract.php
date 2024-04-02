<?php

namespace Wongyip\HTML;

use Exception;
use Throwable;
use Wongyip\HTML\Traits\Contents;
use Wongyip\HTML\Traits\CssClass;
use Wongyip\HTML\Traits\CssStyle;

/**
 * A basic tag to be implemented.
 *
 * Attributes Get-setters
 * @method string|static id(string|null $value = null)
 * @method string|static name(string|null $value = null)
 *
 * Properties Get-setters
 * @method string|static tagName(string|null $setter = null)
 */
abstract class TagAbstract
{
    use Contents, CssClass, CssStyle;

    /**
     * Tag attributes (with value set only).
     *
     * @var array|string[]
     */
    protected array $attributes = [];
    /**
     * @var array|string[]
     */
    protected static array $commonAttrs = ['id', 'name'];
    /**
     * These are NOT stored directly in the $attributes array.
     *
     * @var array
     */
    protected static array $complexAttrs = ['class', 'style'];
    /**
     * These are attributes allowed to store in the $attributes array.
     *
     * @var array
     */
    protected array $tagAttrs;
    /**
     * HTML Tag Name.
     *
     * @var string
     */
    protected string $tagName;

    /**
     * Instantiate a new Tag.
     *
     * Notes:
     *  1. Overwrite class-defined tagName if $tagName is provided.
     *  2. Merge in to commonAttrs and addAttrs if $extraAttrs is provided.
     *
     * @param string|null $tagName
     * @param array|null $extraAttrs
     */
    public function __construct(string $tagName = null, array $extraAttrs = null)
    {
        if ($tagName) {
            $this->tagName = $tagName;
        }

        $this->tagAttrs = array_diff(
            array_unique(
                array_merge(
                    static::$commonAttrs,
                    $this->addAttrs(),
                    $extraAttrs ?? []
                )
            ),
            static::$complexAttrs
        );
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return bool|TagAbstract|null
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        /**
         * Get or set attribute if it is present in $tagAttrs.
         */
        if (in_array($name, $this->tagAttrs)) {
            if (isset($arguments[0])) {
                $this->attributes[$name] = $arguments[0];
                return $this;
            }
            return $this->attributes[$name] ?? null;
        }
        /**
         * Get or set property if it exists.
         * @todo Some properties are not supposed to change may exposed.
         */
        if (property_exists($this, $name)) {
            if (isset($arguments[0])) {
                $this->$name = $arguments[0];
                return $this;
            }
            return $this->$name;
        }
        throw new Exception(sprintf('Undefined method %s() called.', $name));
    }

    /**
     * Get or set all tag attributes.
     *
     * Notes:
     *  1. Unrecognized attributes are ignored.
     *  2. Tag "contents" is NOT an attribute.
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
        $attributes = $this->attributes;
        foreach (static::$complexAttrs as $getter) {
            if ($val = $this->$getter()) {
                $attributes[$getter] = $val;
            }
        }
        return $attributes;
    }

    /**
     * Closing tag.
     *
     * @return string
     */
    public function close(): string
    {
        return sprintf('</%s>', $this->tagName());
    }

    /**
     * Get composed text contents.
     *
     * @return string
     */
    abstract public function contentsText(): string;

    /**
     * Shorthand instantiate.
     *
     * Notes:
     *  1. Overwrite class-defined tagName if $tagName is provided.
     *  2. Merge in to commonAttrs and addAttrs if $extraAttrs is provided.
     *
     * @param string|null $tagName
     * @param array|null $extraAttrs
     * @return static
     */
    public static function make(string $tagName = null, array $extraAttrs = null): static
    {
        return new static($tagName, $extraAttrs);
    }

    /**
     * Opening tag.
     *
     * @return string
     */
    public function open(): string
    {
        $attrs = [];
        foreach ($this->attributes() as $attr => $val) {
            $attrs[] = sprintf('%s="%s"', $attr, htmlspecialchars($val, ENT_COMPAT));
        }
        return empty($attrs)
            ? sprintf('<%s>', $this->tagName())
            : sprintf('<%s %s>', $this->tagName(), implode(' ', $attrs));
    }

    /**
     * The main rendering method if the tag.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->open() . htmlspecialchars($this->contentsText()) . $this->close();
    }

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    abstract protected function addAttrs(): array;

    /**
     * Get all available attributes.
     *
     * @return array
     */
    public function tagAttrs(): array
    {
        return $this->tagAttrs;
    }
}