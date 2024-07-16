<?php

namespace Wongyip\HTML;

use Throwable;
use Wongyip\HTML\Interfaces\ContentsOverride;
use Wongyip\HTML\Interfaces\DynamicTagName;
use Wongyip\HTML\Interfaces\RendererInterface;
use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Traits\Attributes;
use Wongyip\HTML\Traits\Contents;
use Wongyip\HTML\Traits\CssClass;
use Wongyip\HTML\Traits\CssStyle;
use Wongyip\HTML\Traits\DataAttributes;
use Wongyip\HTML\Traits\Overloading;
use Wongyip\HTML\Utils\Convert;

/**
 * Abstract class with most methods implements.
 *
 * IMPORTANT NOTE: Private or protected properties are exposed to read/write via
 * the __call() method.
 *
 * @method string|static id(string|null $value = null)
 * @method bool|static hidden(bool|null $value = null)
 * @method bool|static inert(bool|null $value = null)
 * @method string|static lang(string|null $value = null)
 * @method string|static name(string|null $value = null)
 * @method string|static role(string|null $value = null)
 * @method string|static tabindex(string|null $value = null)
 * @method string|static title(string|null $value = null)
 * @property string $innerHTML
 * @property string $innerText
 */
abstract class TagAbstract implements RendererInterface
{
    use Attributes, Contents, CssClass, CssStyle, DataAttributes, Overloading;

    /**
     * @see DataAttributes::dataset()
     */
    const DATASET_DEFAULT = 0;
    const DATASET_CAMEL   = 0;
    const DATASET_KEBAB   = 1;
    const DATASET_ATTRS   = 2;
    const DATASET_JSON    = 3;

    /**
     * Ultimate default tagName.
     *
     * @var string
     */
    const DEFAULT_TAG_NAME = 'span';
    /**
     * All static properties, which should be ignored by the __call() method.
     *
     * @var array|string[]
     */
    protected array $_staticProps;
    /**
     * Global HTML attributes present in all tags. N.B. This is far less than a
     * full list of all global attributes, only the most used are picked.
     *
     * @var array|string[]
     */
    protected static array $globalAttrs = ['hidden', 'id', 'inert', 'lang', 'name', 'role', 'tabindex', 'title'];
    /**
     * These are stored out of the $attributes array.
     *
     * @var array
     */
    protected static array $complexAttrs = ['class', 'style'];
    /**
     * HTML Tag Name in lower-case.
     *
     * @var string
     */
    protected string $tagName;

    /**
     * Instantiate a new Tag. If $extraAttrs is provided, it will be merge in to
     * commonAttrs and addAttrs.
     *
     * @param array|null $extraAttrs NAMES of extra attributes.
     */
    public function __construct(array $extraAttrs = null)
    {
        // Default
        if (!isset($this->tagName)) {
            $this->tagName = static::DEFAULT_TAG_NAME;
        }

        // Init. inner contents collections.
        $this->contents = ContentsCollection::of($this);
        $this->contentsPrefixed = ContentsCollection::of($this);
        $this->contentsSuffixed = ContentsCollection::of($this);

        // Init. sibling contents collections.
        // @todo Consider inject the parent of this tag.
        $this->siblingsAfter = new ContentsCollection();
        $this->siblingsBefore = new ContentsCollection();

        // Compile attributes list.
        $this->_attrsNames = array_diff(
            array_unique(
                array_merge(
                    static::$globalAttrs,
                    $this->addAttrs(),
                    $extraAttrs ?? []
                )
            ),
            static::$complexAttrs
        );
    }

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    abstract public function addAttrs(): array;

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
     * Opening tag. The $adHocAttrs are merged into tag attributes (after the
     * data attributes), overwrite existing attributes by names, and used to
     * render the tag for once only. Therefore, tag attributes are NOT updated
     * with $adHocAttrs.
     *
     * @param array|null $adHocAttrs
     * @return string
     */
    public function open(array $adHocAttrs = null): string
    {
        // Everything: attributes << data attributes << ad hoc attributes
        $attributes = array_merge($this->attributes(null, true), $adHocAttrs ?? []);
        $compiled = [];
        foreach ($attributes as $attr => $val) {
            try {
                if ($this->isBooleanAttribute($attr)) {
                    // No value, print its name if true, skip it for false.
                    if ($val) {
                        $compiled[] = $attr;
                    }
                }
                else {
                    // Name in kebab case, and value with proper escape of special chars.
                    if (!is_scalar($val)) {
                        /**
                         * Make it string/JSON in case of data attribute.
                         * @todo More documentation needed.
                         */
                        if (str_starts_with($attr, 'data-')) {
                            if (is_bool($val)) {
                                $val = $val ? 'true' : 'false';
                            }
                            else {
                                try {
                                    $val = json_encode($val);
                                }
                                catch (Throwable $e) {
                                    error_log('TagAbstract.open - invalid value of data attribute: ' . $attr);
                                    error_log('TagAbstract.open - [Exception] ' . $e->getMessage());
                                    $val = '';
                                }
                            }
                        }
                        else {
                            error_log('TagAbstract.open - non-scalar attribute value is not allowed for non-data attribute, output empty value for safety.');
                            $val = '';
                        }
                    }
                    $compiled[] = sprintf('%s="%s"', Convert::kebab($attr), htmlspecialchars($val, ENT_COMPAT));
                }
            }
            catch (Throwable $e) {
                error_log($e->getMessage()); // $attr, $val, $this, $attributes
            }
        }

        return empty($compiled)
            ? sprintf($this->isSelfClosing() ? '<%s />' : '<%s>', $this->tagName())
            : sprintf($this->isSelfClosing() ? '<%s %s />' : '<%s %s>', $this->tagName(), implode(' ', $compiled));
    }

    /**
     * Compile the object into HTML.
     *
     * Additional attributes and options may be supplied for use in the rendering
     * process, but neither of them will be stored. Therefore, the object will
     * keep intact after rendering.
     *
     * @param array|null $adHocAttrs
     * @param array|null $adHocOptions
     * @return string
     */
    public function render(array $adHocAttrs = null, array $adHocOptions = null): string
    {
        $beforeTag = $this->siblingsBefore->render();
        $afterTag  = $this->siblingsAfter->render();
        // Render opening tag and siblings only for self-closing tags.
        if ($this->isSelfClosing()) {
            return $beforeTag . $this->open($adHocAttrs) . $afterTag;
        }

        // Main contents override.
        $main = $this instanceof ContentsOverride ? $this->contentsOverride() : $this->contents;

        // Render everything for the rest.
        $prefixed = $this->contentsPrefixed->render();
        $before   = $this->contentsBefore()->render(null, ['parent' => static::tagName()]);
        $contents = $main->render(null, ['parent' => static::tagName()]);
        $after    = $this->contentsAfter()->render(null, ['parent' => static::tagName()]);
        $suffixed = $this->contentsSuffixed->render(null, ['parent' => static::tagName()]);
        return $beforeTag. $this->open($adHocAttrs) . $prefixed . $before . $contents . $after . $suffixed . $this->close() . $afterTag;
    }

    /**
     * Instantiate a new Tag with the provided contents.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public static function tag(array|null|string|RendererInterface ...$contents): static
    {
        $tag = new static();
        return empty($contents) ? $tag : $tag->contents(...$contents);
    }

    /**
     * Get all available attributes (except complex attributes). There is no
     * setter as change of available attributes is not sensible.
     *
     * @todo Change might needed.
     * @return array
     */
    public function tagAttrs(): array
    {
        return $this->_attrsNames;
    }

    /**
     * Get or set the tagName property. Note that script and style tags are not
     * allowed, while !doctype tag is not supported.
     *
     * @param string|null $tagName
     * @return string|TagAbstract|static
     */
    public function tagName(string $tagName = null): string|TagAbstract|static
    {
        $tagName = trim(strtolower($tagName));
        // Get
        if (empty($tagName)) {
            return $this->tagName;
        }

        // Set
        // Allowed
        $class = preg_replace("/.*\\\\/", '', get_class($this));
        if (!($this instanceof DynamicTagName)) {
            error_log(sprintf('%s.tagName() - Tag does not implement DynamicTagName interface.', $class));
        }
        elseif (in_array($tagName, ['script', 'style'])) {
            error_log(sprintf('%s.tagName() - Error: tagName "%s" is invalid.', $class, $tagName));
        }
        else{
            // Standard
            if (preg_match("/^[a-z][a-z1-6]*\$/", $tagName)) {
                $this->tagName = $tagName;
            }
            // Custom
            elseif (!preg_match("/\s/", $tagName)) {
                $this->tagName = $tagName;
            }
            // Invalid
            else {
                error_log(sprintf('TagAbstract.tagName() - Error: tagName "%s" is invalid.', $tagName));
            }
        }
        return $this;
    }
}