<?php

namespace Wongyip\HTML\Supports;

use Wongyip\HTML\Comment;
use Wongyip\HTML\RendererInterface;

class ContentsCollection implements RendererInterface
{
    /**
     * Parent that initiate this collection, maybe useful for rendering contents
     * conditionally.
     *
     * Note, no setter as this is supposed read-only now.
     *
     * @var RendererInterface
     */
    protected RendererInterface $parent;
    /**
     * Enclosed contents.
     *
     * @var array|string[]|RendererInterface[]
     */
    protected array $contents = [];

    /**
     * Renderable Collection of Contents.
     *
     * @param RendererInterface|null $parent Optional parent tag.
     * @param array|string|RendererInterface ...$contents
     */
    public function __construct(RendererInterface $parent = null, array|string|RendererInterface ...$contents)
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
     * @param array|string|RendererInterface ...$contents
     * @return static
     */
    public function add(array|string|RendererInterface ...$contents): static
    {
        return $this->append(...$contents);
    }

    /**
     * Append contents to the collection.
     *
     * @param array|string|RendererInterface ...$contents
     * @return static
     */
    public function append(array|string|RendererInterface ...$contents): static
    {
        foreach ($contents as $content) {
            if (is_array($content)) {
                array_push($this->contents, ...$content);
            }
            else {
                $this->contents[] = $content;
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
     * @param array|string|RendererInterface ...$contents
     * @return string|static
     */
    public function contents(array|string|RendererInterface ...$contents): string|static
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
     * @param array|string|RendererInterface ...$contents
     * @return static
     */
    public function prepend(array|string|RendererInterface ...$contents): static
    {
        foreach ($contents as $content) {
            if (is_array($content)) {
                array_unshift($this->contents, ...$content);
            }
            else {
                array_unshift($this->contents, $content);
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
     * @param array|string|RendererInterface ...$contents
     * @return static
     */
    public function replace(array|string|RendererInterface ...$contents): static
    {
        return empty($contents)
            ? $this->empty()
            : $this->empty()->append(...$contents);
            // Infinite loop alert: DO NOT call the contents() method.
    }
}