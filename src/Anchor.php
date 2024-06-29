<?php

namespace Wongyip\HTML;

use Exception;

/**
 * A minimal implementation an \<a\> (anchor) tag with the following details:
 *
 *  1. Additional attributes is supported via the addAttrs() method.
 *  2. Add. attributes' get-setters are annotated with @-method for code-hint.
 *  3. Additional helper target[Blank|Parent|Self|Top] methods.
 *
 * @method string|static href(string|null $value = null)
 * @method string|static target(string|null $value = null)
 *
 * @method static targetBlank() Opens the linked document in a new window or tab.
 * @method static targetParent() Opens the linked document in the parent frame.
 * @method static targetSelf() Opens the linked document in the same frame as it was clicked (this is default).
 * @method static targetTop() pens the linked document in the full body of the window.
 */
class Anchor extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'a';

    /**
     * @param string $name
     * @param array $arguments
     * @return $this|bool|string|Anchor|TagAbstract|null
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        // Target attribute setters.
        if (preg_match("/^target([A-Z][a-z]+)$/", $name, $matches)) {
            $target = strtolower($matches[1]);
            if (in_array($target, ['blank', 'parent', 'self', 'top'])) {
                return $this->target('_' . $target);
            }
        }
        // Fallback
        return parent::__call($name, $arguments);
    }

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    public function addAttrs(): array
    {
        return ['href', 'target'];
    }

    /**
     * Create an Anchor tag.
     *
     * @param string $href
     * @param string $caption
     * @param string|null $target
     * @param string|null $title
     * @return Anchor
     */
    public static function create(string $href, string $caption, string $target = null, string $title = null): Anchor
    {
        return static::tag()
            ->attributes(compact('href', 'target', 'title'))
            ->contents($caption);
    }

}