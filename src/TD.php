<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\RendererInterface;

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
     * @param RendererInterface|string|null ...$contents
     * @return static
     */
    public static function create(RendererInterface|string|null ...$contents): static
    {
        return static::tag(...$contents);
    }
}