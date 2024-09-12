<?php

namespace Wongyip\HTML\Supports;

use Wongyip\HTML\Comment;
use Wongyip\HTML\Interfaces\RendererInterface;
use Wongyip\HTML\Utils\Convert;

class ContentsCollection implements RendererInterface
{
    /**
     * Enclosed contents.
     *
     * @var array|string[]|RendererInterface[]
     */
    protected array $contents = [];
    /**
     * Parent that initiate this collection, maybe useful for rendering contents
     * conditionally.
     *
     * Note, no setter as this is supposed to be read-only.
     *
     * @var RendererInterface
     */
    protected RendererInterface $parent;

    /**
     *
     * Renderable Collection of Contents. N.B. The first argument can be an
     * RendererInterface as the parent of the ContentsCollection object to be
     * instantiated, insert null as no parent.
     *
     * @note Use the static of() and with() constructors are recommended for
     * @note less confusing.
     *
     * @param RendererInterface|null $parent Optional parent tag.
     * @param array|string|RendererInterface|null ...$contents
     */
    public function __construct(RendererInterface $parent = null, array|string|RendererInterface|null ...$contents)
    {
        if ($parent) {
            $this->parent = $parent;
        }
        if (!empty($contents)) {
            $this->append(...$contents);
        }
    }

    /**
     * Alias to append().
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function add(array|string|RendererInterface|null ...$contents): static
    {
        return $this->append(...$contents);
    }

    /**
     * Append contents to the collection.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function append(array|string|RendererInterface|null ...$contents): static
    {
        $appends = array_filter(Convert::flatten($contents));
        array_push($this->contents, ...$appends);
        return $this;
    }

    /**
     * Get existing contents rendered as string, or set (replace) the existing
     * contents with the input $contents (maybe string, RendererInterface, or an
     * array mixed with both types).
     *
     * Notes
     *  1. Getter returns rendered contents, use the get() method instead to get
     *     the current $contents array.
     *  2. Setter REPLACE ALL existing contents.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return string|static
     */
    public function contents(array|string|RendererInterface|null ...$contents): string|static
    {
        // Get
        if (empty($contents)) {
            return $this->render();
        }
        // Set
        return $this->replace(...$contents);
    }

    /**
     * Get number of contents in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->contents);
    }

    /**
     * Remove all contents form the collection.
     *
     * @return static
     */
    public function empty(): static
    {
        $this->contents = [];
        return $this;
    }

    /**
     * Get all contents in the collection.
     *
     * @return array|string[]|RendererInterface[]
     */
    public function get(): array
    {
        return $this->contents;
    }

    /**
     * Indicates that there are contents in the collection.
     *
     * @return bool
     */
    public function hasContents(): bool
    {
        return !empty($this->contents);
    }

    /**
     * Indicates that there is no contents in the collection.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->contents);
    }

    /**
     * Instantiate with parent.
     *
     * @param RendererInterface $parent
     * @return static
     */
    public static function of(RendererInterface $parent): static
    {
        return (new static)->parent($parent);
    }

    /**
     * Get or set parent tag/renderer.
     *
     * @param RendererInterface|null $parent
     * @return RendererInterface|$this|null
     */
    public function parent(RendererInterface $parent = null): RendererInterface|null|static
    {
        // Get
        if (is_null($parent)) {
            return $this->parent ?? null;
        }
        // Set
        $this->parent = $parent;
        return $this;
    }

    /**
     * Prepend contents to the collection.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function prepend(array|string|RendererInterface|null ...$contents): static
    {
        /**
         * As input accepts nested contents array, we shall flatten it before
         * contents are prepended.
         */
        $contents = array_filter(Convert::flatten($contents));
        array_unshift($this->contents, ...$contents);
        return $this;
    }

    /**
     * Render all contents in the collection, which is properly escaped and safe
     * to output as raw HTML.
     *
     * @inheritdoc
     */
    public function render(array $adHocAttrs = null, array $adHocOptions = null): string
    {
        // Conditional Rendering
        $inComment = isset($this->parent) && is_a($this->parent, Comment::class);
        $rendered = '';
        foreach ($this->contents as $content) {
            // Escape text
            $rendered .= is_string($content) ? htmlspecialchars($content)
                // Escape ending brace in case of nested comment.
                : ($inComment && is_a($content, Comment::class) ? preg_replace("/-->$/", '--&gt;', $content->render())
                    // Render nested tag.
                    : (is_a($content, RendererInterface::class) ? $content->render() : '')
                );
        }
        return $rendered;
    }

    /**
     * Replace the contents in the collection, empty input yield same result
     * with the empty() method.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function replace(array|string|RendererInterface|null ...$contents): static
    {
        return empty($contents)
            ? $this->empty()
            : $this->empty()->append(...$contents);
        // Infinite loop alert: DO NOT call the contents() method.
    }

    /**
     * Instantiate with contents.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public static function with(array|string|RendererInterface|null ...$contents): static
    {
        return (new static)->append(...$contents);
    }
}