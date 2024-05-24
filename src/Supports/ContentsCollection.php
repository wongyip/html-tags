<?php

namespace Wongyip\HTML\Supports;

use Wongyip\HTML\Comment;
use Wongyip\HTML\RendererInterface;

class ContentsCollection implements RendererInterface
{
    /**
     * Parent that initiate this collection, supposed read-only, maybe useful
     * for rendering contents conditionally.
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
     * @param string|array|string[]|RendererInterface|RendererInterface[] ...$contents
     */
    public function __construct(RendererInterface $parent = null, string|RendererInterface|array $contents = null)
    {
        if ($parent) {
            $this->parent = $parent;
        }
        $input = empty($contents) ? [] : (is_array($contents) ? $contents : [$contents]);
        foreach ($input as $content) {
            $this->append($content);
        }
    }

    /**
     * Alias to append().
     *
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function add(string|RendererInterface ...$contents): static
    {
        return $this->append(...$contents);
    }

    /**
     * Append contents to the collection.
     *
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function append(string|RendererInterface ...$contents): static
    {
        array_push($this->contents, ...$contents);
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
     * @param string|RendererInterface ...$contents
     * @return string[]|RendererInterface[]|static
     */
    public function contents(string|RendererInterface ...$contents): string|static
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
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function prepend(string|RendererInterface ...$contents): static
    {
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
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function replace(string|RendererInterface ...$contents): static
    {
        return empty($contents)
            ? $this->empty()
            : $this->contents(...$contents);
    }
}