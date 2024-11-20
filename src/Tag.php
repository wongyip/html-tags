<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\DynamicTagName;
use Wongyip\HTML\Interfaces\RendererInterface;
use Wongyip\HTML\Traits\Attributes;

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
     * Note: not to confuse with the addAttributes() methods.
     *
     * @return array|string[]
     * @see TagAbstract::__construct()
     * @see Attributes::addAttributes()
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * Shorthand instantiation or a Tag object, with a little bit of zen coding.
     *
     * Notes:
     *  1. Input NULL or simple tag name of $tagNameOrEmmet for generic usage,
     *     overwrite class-defined tagName if a simple tag name is provided.
     *  2. Input a zen-code to $tagNameOrEmmet for Emmet-inspired, experimental
     *     usage. e.g. '#foo.bar' for `<div id="foo" class="bar"></div>`.
     *
     * @param string|null $tagNameOrEmmet
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public static function make(string $tagNameOrEmmet = null, array|string|null|RendererInterface ...$contents): static
    {
        // Zen mode.
        if ($tagNameOrEmmet && preg_match("/[.#]/", $tagNameOrEmmet)) {
            $zen = trim($tagNameOrEmmet);
            $tagName = preg_match("/^([^.#]+)[.#]|(h[1-6])[.#]/i", $zen, $matchesTagName) ? $matchesTagName[1] : 'div';
            $classes = preg_match_all("/\.([^.#]+)/", $zen, $matchesClasses) ? $matchesClasses[1] : [];
            $id = preg_match_all("/#([^.#]+)/", $zen, $matchesID) ? trim($matchesID[1][0], '#') : '';
            return $id
                ? Tag::make($tagName, ...$contents)->classAppend(...$classes)->id($id)
                : Tag::make($tagName, ...$contents)->classAppend(...$classes);
        }
        // Generic tag.
        return $tagNameOrEmmet
            ? (new static())->contentsAppend(...$contents)->tagName($tagNameOrEmmet)
            : (new static())->contentsAppend(...$contents);
    }
}