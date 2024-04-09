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
     * @var Caption|TagAbstract
     */
    public Caption|TagAbstract $caption;
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
     * Get or set caption tag. Setter replace the current if input is a Caption
     * tag, otherwise a Caption tab will be created with $content as contents.
     *
     * @param Caption|TagAbstract|string|null $contents
     * @return Caption|null|static
     */
    public function caption(Caption|TagAbstract|string $contents = null, string $captionSide = null): Tag|null|static
    {
        // Setter
        if (!empty($contents)) {
            $this->caption = is_a($contents, Caption::class)
                ? $contents
                : Caption::create($contents, $captionSide);
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
        return array_filter([$this->caption ?? '', $this->head ?? '', $this->body ?? '',]);
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