<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * Special implementation of a comment tag, which ignores the tagName property,
 * as well as other attributes set. Only the text contents will be output as a
 * comment block. e.g. "\<\!-- Example Comment Block -->".
 */
class Comment extends TagAbstract
{
    use ContentsText;

    /**
     * @var string
     */
    protected string $tagName = '!--';

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

    /**
     * @inheritdoc
     */
    public function close(): string
    {
        return ' -->';
    }

    /**
     * @inheritdoc
     */
    public function open(): string
    {
        return '<!-- ';
    }
}