<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Table Row
 */
class TR extends TagAbstract
{
    protected string $tagName = 'tr';

    /**
     * Table cells.
     *
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
     * Append table cells (TD/TH).
     *
     * @param TH|TD|TagAbstract ...$cells
     * @return static
     */
    public function addCells(TH|TD|TagAbstract ...$cells): static
    {
        $this->cells = array_merge($this->cells, $cells);
        return $this;
    }

    /**
     * Get table cell by index (array key).
     *
     * @param int|string $key
     * @return TD|TH|TagAbstract|null
     */
    public function cell(int|string $key): TD|TH|TagAbstract|null
    {
        if (key_exists($key, $this->cells)) {
            return $this->cells[$key];
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function contentsBefore(): ContentsCollection
    {
        return new ContentsCollection($this->cells);
    }

    /**
     * @inheritdoc
     */
    protected function contentsEmptyHook(): void
    {
        $this->cells = [];
    }

    /**
     * Create a table row (TR) with cells (TD/TH).
     *
     * @param TH|TD|TagAbstract ...$cells
     * @return static
     */
    public static function create(TD|TH|TagAbstract ...$cells): static
    {
        return static::make()->addCells(...$cells);
    }
}