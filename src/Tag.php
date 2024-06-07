<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\DynamicTagName;

/**
 * A minimal implementation of the TagAbstract with the following details:
 *
 *  1. It is a \<span\> tag by default as defined in $tagName property.
 *  2. No additional attributes is supported, so the implementation of
 *     addAttrs() returns an empty array.
 */
class Tag extends TagAbstract implements DynamicTagName
{
    /**
     * HTML Tag Name.
     *
     * @var string
     */
    protected string $tagName = 'span';

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    public function addAttrs(): array
    {
        return [];
    }
    /**
     * Shorthand instantiate.
     *
     * Notes:
     *  1. Overwrite class-defined tagName if $tagName is provided.
     *  2. Merge into commonAttrs and addAttrs if $extraAttrs is provided.
     *
     * @param string|null $tagName
     * @param array|null $extraAttrs
     * @return static
     */
    public static function make(string $tagName = null, array $extraAttrs = null): static
    {
        $tag = new static($extraAttrs);
        return $tagName ? $tag->tagName($tagName) : $tag;
    }
}