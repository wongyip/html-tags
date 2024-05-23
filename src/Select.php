<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Traits\NoAddAttrs;

/**
 * A basic implementation of a "\<select>" tag, where \<optgroup> is not
 * supported, yet.
 */
class Select extends TagAbstract
{
    use NoAddAttrs;

    protected string $tagName = 'select';

    /**
     * @var array|Option[]
     */
    protected array $options = [];

    /**
     * @inheritdoc
     */
    protected function contentsBefore(): ContentsCollection
    {
        return new ContentsCollection($this->options);
    }

    /**
     * @inheritdoc
     */
    protected function contentsEmptyHook(): void
    {
        $this->options = [];
    }

    /**
     * Create \<select> tag with given options.
     *
     * @param Option ...$options
     * @return static
     */
    public static function create(Option ...$options): static
    {
        return static::make()->optionsAdd(...$options);
    }

    /**
     * Get option by index (array key).
     *
     * @param int|string $key
     * @return Option|null
     */
    public function option(int|string $key): Option|null
    {
        if (key_exists($key, $this->options)) {
            return $this->options[$key];
        }
        return null;
    }

    /**
     * Append option(s).
     *
     * @param Option ...$options
     * @return $this
     */
    public function optionsAdd(Option ...$options): static
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

}