<?php

namespace Wongyip\HTML\Traits;

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
     * [Shortcut] Getter return $this->contents->render(), setter replace all
     * contents in the contents collection and returns the current Tag.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return string|static
     */
    public function contents(array|string|RendererInterface|null ...$contents): string|static
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
     * [Extension] Return collection of contents to be rendered inside the tag,
     * right after the tag's contents.
     *
     * @return ContentsCollection
     */
    protected function contentsAfter(): ContentsCollection
    {
        // Empty
        return new ContentsCollection();
    }

    /**
     * [Shortcut] Like $this->contents->append(), but returns the current Tag
     * instead of the ContentCollection object.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function contentsAppend(array|string|RendererInterface|null ...$contents): static
    {
        $this->contents->append(...$contents);
        return $this;
    }

    /**
     * [Extension] Return collection of contents to be rendered inside the tag,
     * right before the tag's contents.
     *
     * @return ContentsCollection
     */
    protected function contentsBefore(): ContentsCollection
    {
        // Empty
        return new ContentsCollection();
    }

    /**
     * Remove all contents in contents collection, then invoke the emptyHook()
     * method.
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
     * [Extension] Will be called by the contentsEmpty() method. Replace this
     * method to add routine after content emptied, e.g. clear child contents.
     *
     * @return void
     */
    protected function contentsEmptyHook(): void
    {
        // For child class only.
    }

    /**
     * [Shortcut] Like $this->contents->prepend(), but returns the current Tag
     * instead of the ContentCollection object.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return static
     */
    public function contentsPrepend(array|string|RendererInterface|null ...$contents): static
    {
        $this->contents->prepend(...$contents);
        return $this;
    }

    /**
     * [Shortcut] Same as $this->contents->render().
     *
     * @return string
     */
    protected function contentsRendered(): string
    {
        return $this->contents->render();
    }

    /**
     * [Mixed Usage] Give no input to get rendered contents of $siblingsAfter.
     * Otherwise, replaces all contents in $siblingAfter and return current Tag.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return string|static
     */
    public function siblingsAfter(array|string|RendererInterface|null ...$contents): string|static
    {
        if (empty($contents)) {
            return $this->siblingsAfter->render();
        }
        $this->siblingsAfter->empty()->contents(...$contents);
        return $this;
    }

    /**
     * [Mixed Usage] Give no input to get rendered contents of $siblingsBefore.
     * Otherwise, replaces all contents in $siblingsBefore and return current Tag.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return string|static
     */
    public function siblingsBefore(array|string|RendererInterface|null ...$contents): string|static
    {
        if (empty($contents)) {
            return $this->siblingsBefore->render();
        }
        $this->siblingsBefore->empty()->contents(...$contents);
        return $this;
    }
}