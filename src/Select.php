<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Utils\Convert;

/**
 * A basic implementation of a "\<select>" tag, where \<optgroup> is not
 * supported, yet.
 */
class Select extends TagAbstract
{
    protected string $tagName = 'select';

    /**
     * @var array|Option[]
     */
    protected array $options = [];

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return ['autofocus', 'disabled', 'form', 'multiple', 'required', 'size'];
    }

    /**
     * @inheritdoc
     */
    protected function contentsBefore(): ContentsCollection
    {
        return new ContentsCollection($this, $this->options);
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
     * @param array|Option ...$options
     * @return static
     */
    public static function create(array|Option ...$options): static
    {
        return static::make()->optionsAppend(...$options);
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
     * Get or set (replace) all options.
     *
     * @param array|Option[] $options
     * @return array|Option[]|static
     */
    public function options(array $options = null): array|static
    {
        if (is_null($options)) {
            return $this->options;
        }
        // Set
        return $this
            ->optionsEmpty()
            ->optionsAppend(...$options);
    }

    /**
     * Alias to append().
     *
     * @param array|Option ...$options
     * @return static
     */
    public function optionsAdd(array|Option ...$options): static
    {
        return $this->optionsAppend(...$options);
    }

    /**
     * Append option(s).
     *
     * @param array|Option ...$options
     * @return static
     */
    public function optionsAppend(array|Option ...$options): static
    {
        $flattened = Convert::flatten(...$options);
        $appends = array_filter($flattened, fn($option) => $option instanceof Option);
        array_push($this->options, ...$appends);
        return $this;
    }

    /**
     * Remove all options.
     *
     * @return static
     */
    public function optionsEmpty(): static
    {
        $this->options = [];
        return $this;
    }
}