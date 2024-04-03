<?php

namespace Wongyip\HTML;

use Exception;
use ReflectionClass;
use Throwable;
use Wongyip\HTML\Traits\Contents;
use Wongyip\HTML\Traits\CssClass;
use Wongyip\HTML\Traits\CssStyle;

/**
 * Abstract class with most methods implements.
 *
 * Attributes Get-setters
 * @method string|static id(string|null $value = null)
 * @method string|static name(string|null $value = null)
 *
 * Properties Get-setters
 * -- None at the moment.
 */
abstract class TagAbstract
{
    use Contents, CssClass, CssStyle;

    /**
     * Ultimate default tagName.
     *
     * @var string
     */
    const DEFAULT_TAG_NAME = 'span';
    /**
     * List of all static properties, which should be ignored by __call().
     *
     * @var array|string[]
     */
    protected array $__staticProperties;
    /**
     * Internal storage of attributes listed in $tagAttrs, which have value set
     * already (excluding attributes listed in $complexAttrs).
     *
     * @var array|string[]
     */
    protected array $attrsStore = [];
    /**
     * These are attributes present in all tags.
     *
     * @var array|string[]
     */
    protected static array $commonAttrs = ['id', 'name'];
    /**
     * These are stored out of the $attributes array.
     *
     * @var array
     */
    protected static array $complexAttrs = ['class', 'style'];
    /**
     * These attributes are get or set as in the $attributes array.
     *
     * @var array
     */
    protected array $tagAttrs;
    /**
     * HTML Tag Name in lower-case.
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
        // Override
        if ($tagName) {
            // Might not success if not accepted.
            $this->tagName($tagName);
        }

        // Normalize
        $this->tagName = isset($this->tagName) ? strtolower($this->tagName) : static::DEFAULT_TAG_NAME;

        // Patch
        if (in_array($this->tagName, ['script', 'style'])) {
            $this->tagName(static::DEFAULT_TAG_NAME);
        }

        // Compile attributes list.
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

        /**
         * List out static properties so the __call() method will ignore them.
         * @see TagAbstract::__call()
         */
        $this->__staticProperties = array_keys((new ReflectionClass($this))->getStaticProperties() ?? []);
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
                $this->attrsStore[$name] = $arguments[0];
                return $this;
            }
            return $this->attrsStore[$name] ?? null;
        }
        /**
         * Get or set property if it exists.
         * @todo Some properties are not supposed to change may be exposed.
         */
        if (property_exists($this, $name) && !in_array($name, $this->__staticProperties)) {
            if (isset($arguments[0])) {
                $this->$name = $arguments[0];
                return $this;
            }
            return $this->$name;
        }
        throw new Exception(sprintf('Undefined method %s() called.', $name));
    }

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    abstract protected function addAttrs(): array;

    /**
     * Get or set all tag attributes.
     *
     * Notes:
     *  1. Not a direct get-setter.
     *  2. Unrecognized attributes are ignored (not in $tagAttrs, nor $complexAttrs).
     *  4. Tag "contents" is NOT an attribute.
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
     * Closing tag.
     *
     * @return string
     */
    public function close(): string
    {
        return $this->isSelfClosing() ? '' : sprintf('</%s>', $this->tagName);
    }

    /**
     * Get composed text contents.
     *
     * @return string
     */
    abstract public function contentsText(): string;

    /**
     * Determine if this is a self-closing tag.
     *
     * @return bool
     */
    protected function isSelfClosing(): bool
    {
        return in_array($this->tagName, [
            // HTML5
            'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr',
            // Obsolete
            'command', 'frame', 'keygen', 'menuitem'
        ]);
    }

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
     * Opening tag. The $adHocAttrs are merged into tag attributes, overwrite
     * existing attributes by names, and used to render the tag for once only.
     * Therefore, tag attributes are NOT updated with $adHocAttrs.
     *
     * @param array|null $adHocAttrs
     * @return string
     */
    public function open(array $adHocAttrs = null): string
    {
        $compiled = [];
        $attributes = array_merge($this->attributes(), $adHocAttrs ?? []);
        foreach ($attributes as $attr => $val) {
            $compiled[] = sprintf('%s="%s"', $attr, htmlspecialchars($val, ENT_COMPAT));
        }
        return empty($compiled)
            ? sprintf($this->isSelfClosing() ? '<%s />' : '<%s>', $this->tagName())
            : sprintf($this->isSelfClosing() ? '<%s %s />' : '<%s %s>', $this->tagName(), implode(' ', $compiled));
    }

    /**
     * The main rendering method if the tag. The $adHocAttrs are merged into tag
     * attributes, overwrite existing attributes by names, and used to render
     * the opening tag for once only. Therefore, tag attributes are NOT updated
     * with $adHocAttrs.
     *
     * @param array|null $adHocAttrs
     * @return string
     */
    public function render(array $adHocAttrs = null): string
    {
        return $this->isSelfClosing()
            ? $this->open($adHocAttrs)
            : $this->open($adHocAttrs) . htmlspecialchars($this->contentsText()) . $this->close();
    }

    /**
     * Get all available attributes (except complex attributes). There is no
     * setter as change of available attributes is not sensible.
     *
     * @return array
     */
    public function tagAttrs(): array
    {
        return $this->tagAttrs;
    }

    /**
     * Get or set the tagName property. Note that script and style tags are not
     * allowed, while !doctype tag is not supported.
     *
     * @param string|null $tagName
     * @return string|$this
     */
    public function tagName(string $tagName = null): string|static
    {
        $tagName = strtolower($tagName);
        // Setter
        if (!empty($tagName)) {

            // Allowed
            if (!in_array($tagName, ['script', 'style'])) {
                if (preg_match("/^[a-z]+$/", $tagName)) {
                    $this->tagName = $tagName;
                }
                else {
                    error_log(sprintf('TagAbstract.tagName() - Error: tagName may contains alphabets only, input "%s" is invalid.', $tagName));
                }
            }
            else {
                error_log(sprintf('TagAbstract.tagName() - Error: <%s> tag is not support.', $tagName));
            }

            // Ultimate default
            if (!isset($this->tagName)) {
                $this->tagName = static::DEFAULT_TAG_NAME;
            }
            return $this;
        }
        // Getter
        return $this->tagName;
    }
}