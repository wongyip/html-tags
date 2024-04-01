<?php

namespace Wongyip\HTML;

use Exception;
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
     * @param string|null $tagName
     * @param array|null $tagAttrs
     */
    public function __construct(string $tagName = null, array $tagAttrs = null)
    {
        if ($tagName) {
            $this->tagName = $tagName;
        }
        $this->tagAttrs = array_merge(
            static::$commonAttrs,
            $this->addAttrs(),
            $tagAttrs ?? []
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
        // Attributes
        if (in_array($name, $this->tagAttrs)) {
            if (isset($arguments[0])) {
                $this->attributes[$name] = $arguments[0];
                return $this;
            }
            return $this->attributes[$name] ?? null;
        }
        /**
         * Properties without matching function.
         * @todo Some properties are not supposed to change were exposed now.
         */
        if (property_exists($this, $name)) {
            if (isset($arguments[0])) {
                $this->$name = $arguments[0];
                return $this;
            }
            return $this->$name ?? null;
        }
        throw new Exception(sprintf('Undefined method %s().', $name));
    }

    /**
     * Get all tag attributes with value set.
     *
     * @return array
     */
    public function attributes() : array
    {
        $attributes = $this->attributes;
        if ($class = $this->class()) {
            $attributes['class'] = $class;
        }
        if ($style = $this->style()) {
            $attributes['style'] = $style;
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
     * @param string|null $tagName
     * @param array|null $tagAttrs
     * @return static
     */
    public static function make(string $tagName = null, array $tagAttrs = null): static
    {
        return new static($tagName, $tagAttrs);
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