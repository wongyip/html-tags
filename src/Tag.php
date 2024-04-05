<?php

namespace Wongyip\HTML;

/**
 * A minimal implementation of the TagAbstract with the following details:
 *
 *  1. It is a "\<span>" tag by default as defined in $tagName property.
 *  2. No additional attributes is supported, so the implementation of
 *     addAttrs() returns an empty array.
 */
class Tag extends TagAbstract
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
}