<?php

namespace Wongyip\HTML;

/**
 * Table Row
 */
class TR extends TagAbstract
{
    protected string $tagName = 'tr';

    /**
     * @var array|TD[]|TH|TagAbstract[]
     */
    protected array $cells = [];

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * @param TH|TD|TagAbstract ...$cells
     * @return static
     */
    public function addCells(TH|TD|TagAbstract ...$cells): static
    {
        $this->cells = array_merge($this->cells, $cells);
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function contentsPrefixed(): array
    {
        return $this->cells;
    }

    /**
     * @param TH|TD|TagAbstract ...$cells
     * @return static
     */
    public static function create(TD|TH|TagAbstract ...$cells): static
    {
        return static::make()->addCells(...$cells);
    }
}