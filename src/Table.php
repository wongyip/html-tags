<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\ContentsOverride;
use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Traits\NoAddAttrs;

/**
 * Table (Single set of caption + head + body only.).
 */
class Table extends TagAbstract implements ContentsOverride
{
    use NoAddAttrs;

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
     * @inheritdoc
     */
    public function contentsOverride(): ContentsCollection
    {
        return
            ContentsCollection::of($this)
            ->contents(
                $this->caption ?? '',
                $this->head ?? '',
                $this->body ?? ''
            );
    }

    /**
     * @param THead|TagAbstract|null $thead
     * @param TBody|TagAbstract|null $tbody
     * @param TagAbstract|String|null $caption
     * @return static
     */
    public static function create(THead|TagAbstract $thead = null, TBody|TagAbstract $tbody = null, TagAbstract|String $caption = null): static
    {
        $table = new static();
        if ($thead) {
            $table->head = $thead;
        }
        if ($tbody) {
            $table->body = $tbody;
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