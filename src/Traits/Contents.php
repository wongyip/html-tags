<?php

namespace Wongyip\HTML\Traits;

/**
 * Inner contents manipulation trait, the input contents are stored in the
 * $contents array, the output for rendering is composed by the contentText()
 * method.
 *
 * @see Tag::contentsText() for the default implementation.
 */
trait Contents
{
    /**
     * Inner text-content.
     *
     * @var array
     */
    protected array $contents = [];

    /**
     * @param string|array|null $contents
     * @return array|$this
     */
    public function contents(string|array $contents = null): array|static
    {
        if ($contents) {
            $this->contents = is_array($contents) ? $contents : [$contents];
            return $this;
        }
        return $this->contents;
    }

    /**
     * @param array|string $contents
     * @return $this
     */
    public function contentsAdd(array|string $contents): static
    {
        return $this->contentsAppend($contents);
    }

    /**
     * @param array|string $contents
     * @return $this
     */
    public function contentsAppend(array|string $contents): static
    {
        $this->contents = array_merge(
            $this->contents,
            is_array($contents) ? $contents : [$contents]
        );
        return $this;
    }

    /**
     * @param array|string $contents
     * @return $this
     */
    public function contentsPrepend(array|string $contents): static
    {
        $this->contents = array_merge(
            is_array($contents) ? $contents : [$contents],
            $this->contents
        );
        return $this;
    }
}