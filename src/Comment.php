<?php

namespace Wongyip\HTML;

/**
 * Special implementation of a comment tag, which ignores the tagName property,
 * as well as other attributes set. Only the text contents will be output as a
 * comment block. e.g. "\<\!-- Example Comment Block -->".
 */
class Comment extends TagAbstract
{
    /**
     * Not used at all.
     *
     * @var string
     * @deprecated
     */
    protected string $tagName = '!--';

    /**
     * Implemented as required, but not used at all.
     *
     * @return array|string[]
     * @deprecated
     */
    protected function addAttrs(): array
    {
        return [];
    }

    /**
     * Override parent, closing a comment block, ignores tagName.
     *
     * @return string
     */
    public function close(): string
    {
        return ' -->';
    }

    /**
     * Override parent, open a comment block, ignores tagName and attributes.
     *
     * @param array|null $adHocAttrs
     * @inheritdoc
     */
    public function open(array $adHocAttrs = null): string
    {
        return '<!-- ';
    }
}