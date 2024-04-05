<?php

namespace Wongyip\HTML;

/**
 * Table Head
 */
class THead extends TagAbstract
{
    protected string $tagName = 'thead';

    /**
     * @var array|TR[]|TagAbstract[]
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
     * @return array
     */
    public function contentsPrefixed(): array
    {
        return $this->rows;
    }

    /**
     * @param TR|TagAbstract ...$rows
     * @return static
     */
    public function addRows(TR|TagAbstract ...$rows): static
    {
        $this->rows = array_merge($this->rows, $rows);
        return $this;
    }

    /**
     * @param TR|TagAbstract ...$rows
     * @return static
     */
    public static function create(TR|TagAbstract ...$rows): static
    {
        return static::make()->addRows(...$rows);
    }
}