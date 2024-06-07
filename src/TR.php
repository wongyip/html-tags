<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\ContentsOverride;
use Wongyip\HTML\Interfaces\RendererInterface;
use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Table Row
 */
class TR extends TagAbstract implements ContentsOverride
{
    protected string $tagName = 'tr';

    /**
     * Table cells.
     *
     * @var array|TD[]|TH[]
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
     * @param TH|TD|RendererInterface ...$cells
     * @return static
     */
    public function addCells(TH|TD|RendererInterface ...$cells): static
    {
        $this->cells = array_merge($this->cells, $cells);
        return $this;
    }

    /**
     * Get table cell by index (array key).
     *
     * @param int|string $key
     * @return TD|TH|RendererInterface|null
     */
    public function cell(int|string $key): TD|TH|RendererInterface|null
    {
        if (key_exists($key, $this->cells)) {
            return $this->cells[$key];
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function contentsOverride(): ContentsCollection
    {
        return new ContentsCollection($this, ...$this->cells);
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
     * @param TD|TH|RendererInterface ...$cells
     * @return static
     */
    public static function create(TD|TH|RendererInterface ...$cells): static
    {
        return static::tag()->addCells(...$cells);
    }
}