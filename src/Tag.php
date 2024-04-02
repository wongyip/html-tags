<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * A minimal implemented of the TagAbstract with the following details:
 *
 *  1. It is a "\<span>" tag by default as defined in $tagName property.
 *  2. No additional attributes is supported, so the implementation of
 *     addAttrs() returns an empty array.
 *  3. Uses default ContentsText method to compose text contents.
 */
class Tag extends TagAbstract
{
    use ContentsText;

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
    protected function addAttrs(): array
    {
        return [];
    }
}