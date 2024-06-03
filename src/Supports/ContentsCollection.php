<?php

namespace Wongyip\HTML\Supports;

use Wongyip\HTML\Comment;
use Wongyip\HTML\RendererInterface;

class ContentsCollection implements RendererInterface
{
    /**
     * Enclosed contents.
     *
     * @var array|string[]|RendererInterface[]
     */
    protected array $contents = [];
    /**
     * @var array
     */
    protected array $errors = [];
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
     * Renderable Collection of Contents. N.B. The first argument can be an
     * RendererInterface as the parent of the ContentsCollection object to be
     * instantiated, insert null as no parent.
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
        foreach ($contents as $content) {
            if (!empty($content)) {
                if (is_array($content)) {
                    // Not doing push directly, for the empty check.
                    $this->append(...$content);
                } else {
                    $this->contents[] = $content;
                }
            }
            else {
                $this->errors[] = 'Attempted append empty content.';
            }
        }
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
     * Prepend contents to the collection.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function prepend(array|string|RendererInterface|null ...$contents): static
    {
        foreach ($contents as $content) {
            if (!empty($content)) {
                if (is_array($content)) {
                    // Not doing unshift directly, for the empty check.
                    $this->prepend(...$content);
                }
                else {
                    array_unshift($this->contents, $content);
                }
            }
            else {
                $this->errors[] = 'Attempted prepend empty content.';
            }
        }
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
}