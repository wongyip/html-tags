<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\ContentsOverride;
use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Table Body
 */
class TBody extends TagAbstract implements ContentsOverride
{
    protected string $tagName = 'tbody';

    /**
     * Table Rows
     *
     * @var array|TR[]
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
    public function contentsOverride(): ContentsCollection
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
     * @param TR ...$rows
     * @return static
     */
    public function addRows(TR ...$rows): static
    {
        $this->rows = array_merge($this->rows, $rows);
        return $this;
    }

    /**
     * Create table body (or head) with rows (TR).
     *
     * @param TR ...$rows
     * @return static
     */
    public static function create(TR ...$rows): static
    {
        return static::tag()->addRows(...$rows);
    }

    /**
     * Get table row (TR) by index (array key).
     *
     * @param int|string $key
     * @return TR|null
     */
    public function row(int|string $key): TR|null
    {
        if (key_exists($key, $this->rows)) {
            return $this->rows[$key];
        }
        return null;
    }
}