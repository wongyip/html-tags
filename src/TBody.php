<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Table Body
 */
class TBody extends TagAbstract
{
    protected string $tagName = 'tbody';

    /**
     * Table Rows
     *
     * @var array|TR[]|RendererInterface[]
     */
    protected array $rows = [];

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function contentsBefore(): ContentsCollection
    {
        return new ContentsCollection($this, $this->rows);
    }

    /**
     * @inheritdoc
     */
    protected function contentsEmptyHook(): void
    {
        $this->rows = [];
    }

    /**
     * Append table rows (TR).
     *
     * @todo rename to rowsAdd()
     *
     * @param TR|TagAbstract ...$rows
     * @return static
     */
    public function addRows(TR|TagAbstract ...$rows): static
    {
        $this->rows = array_merge($this->rows, $rows);
        return $this;
    }

    /**
     * Create table body (or head) with rows (TR).
     *
     * @param TR|TagAbstract ...$rows
     * @return static
     */
    public static function create(TR|TagAbstract ...$rows): static
    {
        return static::make()->addRows(...$rows);
    }

    /**
     * Get table row (TR) by index (array key).
     *
     * @param int|string $key
     * @return TR|TagAbstract|null
     */
    public function row(int|string $key): TR|TagAbstract|null
    {
        if (key_exists($key, $this->rows)) {
            return $this->rows[$key];
        }
        return null;
    }
}