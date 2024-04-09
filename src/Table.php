<?php

namespace Wongyip\HTML;

/**
 * Table (Single set of caption + head + body only.).
 */
class Table extends TagAbstract
{
    protected string $tagName = 'table';

    /**
     * Table Body.
     *
     * @var TBody|TagAbstract
     */
    public TBody|TagAbstract $body;
    /**
     * Table Caption
     *
     * @var TagAbstract
     */
    public TagAbstract $caption;
    /**
     * Table Head
     *
     * @var THead|TagAbstract
     */
    public THead|TagAbstract $head;

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * Get $caption tag, or set its content text if $caption not empty.
     *
     * @param TagAbstract|string|null $caption
     * @return TagAbstract|null|static
     */
    public function caption(TagAbstract|string $caption = null): Tag|null|static
    {
        // Setter
        if (!empty($caption)) {
            $this->caption = Tag::make('caption')->contents($caption);
            return $this;
        }
        return $this->caption ?? null;
    }

    /**
     * @return static
     */
    public function captionRemove(): static
    {
        unset($this->caption);
        return $this;
    }

    /**
     * @return array
     */
    public function contentsPrefixed(): array
    {
        // In rendering order.
        $prefixed = [];
        if (isset($this->caption)) $prefixed[] = $this->caption;
        if (isset($this->head)) $prefixed[] = $this->head;
        if (isset($this->body)) $prefixed[] = $this->body;
        return $prefixed;
    }

    /**
     * @param THead|TagAbstract|null $thead
     * @param TBody|TagAbstract|null $tbody
     * @param TagAbstract|String|null $caption
     * @return static
     */
    public static function create(THead|TagAbstract $thead = null, TBody|TagAbstract $tbody = null, TagAbstract|String $caption = null): static
    {
        $table = static::make();
        if ($thead) {
            $table->body = $tbody;
        }
        if ($tbody) {
            $table->head = $thead;
        }
        if ($caption) {
            $table->caption($caption);
        }
        return $table;
    }

    /**
     * @return bool
     */
    public function hasCaption(): bool
    {
        return isset($this->caption);
    }
}