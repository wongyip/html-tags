<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * Inner contents manipulation trait, the input contents are stored in the
 * $contents array, the output for rendering is composed by the contentText()
 * method.
 *
 * @see ContentsText::contentsText() for the default implementation.
 */
trait Contents
{
    /**
     * Inner text contents.
     *
     * @var array
     */
    protected array $contents = [];

    /**
     * Get existing contents (as string), or set (replace) the existing
     * $contents (string or array).
     *
     * @param string|array|null $contents
     * @return string|static
     */
    public function contents(string|array $contents = null): string|static
    {
        if ($contents) {
            $this->contents = is_array($contents) ? $contents : [$contents];
            return $this;
        }
        return $this->contentsText();
    }

    /**
     * Alias to contentsAppend().
     *
     * @param string ...$contents
     * @return static
     */
    public function contentsAdd(string ...$contents): static
    {
        return $this->contentsAppend(...$contents);
    }

    /**
     * Append contents to the $contents array.
     *
     * @param string ...$contents
     * @return static
     */
    public function contentsAppend(string ...$contents): static
    {
        $this->contents = array_merge($this->contents, $contents);
        return $this;
    }

    /**
     * Prepend contents to the $contents array.
     *
     * @param string ...$contents
     * @return static
     */
    public function contentsPrepend(string ...$contents): static
    {
        $this->contents = array_merge($contents, $this->contents);
        return $this;
    }
}