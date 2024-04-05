<?php

namespace Wongyip\HTML;

/**
 * Table Data Cell
 */
class TD extends TagAbstract
{
    protected string $tagName = 'td';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return ['colspan', 'rowspan'];
    }

    /**
     * @param TagAbstract|string ...$contents
     * @return static
     */
    public static function create(TagAbstract|string ...$contents): static
    {
        return static::make()->contents(...$contents);
    }
}