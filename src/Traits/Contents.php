<?php

namespace Wongyip\HTML\Traits;

use Exception;
use Wongyip\HTML\RendererInterface;
use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Contents manipulation trait.
 */
trait Contents
{
    /**
     * Collection of inner contents.
     *
     * @var ContentsCollection;
     */
    public ContentsCollection $contents;
    /**
     * Collection of contents prefixed to the inner contents.
     *
     * @var ContentsCollection;
     */
    public ContentsCollection $contentsPrefixed;
    /**
     * Collection of contents suffixed to the inner contents.
     *
     * @var ContentsCollection;
     */
    public ContentsCollection $contentsSuffixed;
    /**
     * Collection of sibling contents to be rendered after the tag, at the same
     * nesting level.
     *
     * @var ContentsCollection;
     */
    public ContentsCollection $siblingsAfter;
    /**
     * Collection of sibling contents to be rendered before the tag, at the same
     * nesting level.
     *
     * @var ContentsCollection;
     */
    public ContentsCollection $siblingsBefore;

    /**
     * Proxy method, getter return $this->contents->render(), setter replace
     * all contents in contents collection and returns the current Tag.
     *
     * @param string|RendererInterface ...$contents
     * @return string|static
     */
    public function contents(string|RendererInterface ...$contents): string|static
    {
        // Get
        if (empty($contents)) {
            return $this->contents->render();
        }
        // Set
        $this->contentsEmpty()->contentsAppend(...$contents);
        return $this;
    }

    /**
     * Return collection of contents to be rendered inside the tag, right after
     * the tag's contents.
     *
     * @return ContentsCollection
     */
    protected function contentsAfter(): ContentsCollection
    {
        // Empty
        return new ContentsCollection();
    }

    /**
     * Proxy of $this->contents->append(), but returns the current Tag instead
     * of the ContentCollection object.
     *
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function contentsAppend(string|RendererInterface ...$contents): static
    {
        $this->contents->append(...$contents);
        return $this;
    }

    /**
     * Return collection of contents to be rendered inside the tag, right before
     * the tag's contents.
     *
     * @return ContentsCollection
     */
    protected function contentsBefore(): ContentsCollection
    {
        // Empty
        return new ContentsCollection();
    }

    /**
     * Remove all contents in contents collection.
     *
     * @return static
     */
    public function contentsEmpty(): static
    {
        $this->contents->empty();
        $this->contentsEmptyHook();
        return $this;
    }

    /**
     * Will be called by the contentsEmpty() method. Replace this method to
     * clear customized contents.
     *
     * @return void
     */
    protected function contentsEmptyHook(): void
    {
        // For child class only.
    }

    /**
     * Proxy of $this->contents->prepend(), but returns the current Tag instead
     * of the ContentCollection object.
     *
     * @param string|RendererInterface ...$contents
     * @return static
     */
    public function contentsPrepend(string|RendererInterface ...$contents): static
    {
        $this->contents->prepend(...$contents);
        return $this;
    }

    /**
     * Proxy of $this->contents->render()
     *
     * @return string
     */
    protected function contentsRendered(): string
    {
        return $this->contents->render();
    }

    /**
     * @return array
     * @throws Exception
     * @deprecated
     */
    protected function contentsPrefixed(): array
    {
        throw new Exception('Deprecated and replaced with contentsBefore() method.');
    }

    /**
     * @return array
     * @throws Exception
     * @deprecated
     */
    protected function contentsSuffix(): array
    {
        throw new Exception('Deprecated and replaced with contentsAfter() method.');
    }
}