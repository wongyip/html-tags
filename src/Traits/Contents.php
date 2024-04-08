<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Comment;
use Wongyip\HTML\TagAbstract;

/**
 * Contents manipulation trait.
 */
trait Contents
{
    use ContentsExtensions;

    /**
     * Enclosed contents.
     *
     * @var array|string[]|TagAbstract[]
     */
    protected array $contents = [];

    /**
     * Get existing contents rendered as string, or set (replace) the existing
     * $contents (string or TabAbstract, or array mixed of both types).
     *
     * @param string|TagAbstract ...$contents
     * @return string|static
     */
    public function contents(string|TagAbstract ...$contents): string|static
    {
        // Setter
        if (!empty($contents)) {
            array_push($this->contents, ...$contents);
            return $this;
        }
        // Get them rendered.
        return $this->contentsRendered();
    }

    /**
     * Alias to contentsAppend().
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsAdd(string|TagAbstract ...$contents): static
    {
        return $this->contentsAppend(...$contents);
    }

    /**
     * Append contents to the $contents array.
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsAppend(string|TagAbstract ...$contents): static
    {
        $this->contents = array_merge($this->contents, $contents);
        return $this;
    }

    /**
     * Remove all attached contents.
     *
     * N.B. This method should be extended if
     *
     * @return static
     */
    public function contentsEmpty(): static
    {
        $this->contents = [];
        $this->contentsEmptyHook();
        return $this;
    }

    /**
     * Prepend contents to the $contents array.
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsPrepend(string|TagAbstract ...$contents): static
    {
        $this->contents = array_merge($contents, $this->contents);
        return $this;
    }

    /**
     * Get all contents combined, which is properly escaped and safe to output
     * as raw HTML.
     *
     * @return string
     */
    protected function contentsRendered(): string
    {
        // Grab all render contents.
        $contents = array_merge(
            $this->contentsPrefixed(),
            $this->contents,
            $this->contentsSuffixed()
        );
        $rendered = '';
        foreach ($contents as $content) {
            // Escape text
            $rendered .= is_string($content) ? htmlspecialchars($content)
                // Escape comment's ending brace.
                : (is_a($content, Comment::class) ? preg_replace("/-->$/", '--&gt;', $content->render())
                    // Render nested tag.
                    : (is_a($content, TagAbstract::class) ? $content->render() : '')
                );
        }
        return $rendered;
    }
}