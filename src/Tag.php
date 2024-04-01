<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * A minimal implemented of the \<span\> tag.
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