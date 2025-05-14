<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\Interfaces\ContentsOverride;
use Wongyip\HTML\Interfaces\RendererInterface;
use Wongyip\HTML\Supports\ContentsCollection;

/**
 * Contents manipulation trait.
 */
trait Contents
{
    /**
     * Collection of main inner contents.
     *
     * Note: if this class implements ContentsOverride, whether to render or how
     * to render thees contents is depending on the contentsOverride() method.
     *
     * @var ContentsCollection;
     * @see ContentsOverride::contentsOverride()
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
     * Getter return the RENDERED main contents, or contents returned by the
     * contentsOverride() method if this class implements ContentsOverride.
     *
     * Setter REPLACE the current main contents with inputs.
     *
     * Note: if this class implements ContentsOverride, whether to render or how
     * to render thees contents is depending on the contentsOverride() method.
     *
     * @param array|string|RendererInterface|null ...$contents
     * @return string|static
     */
    public function contents(array|string|RendererInterface|null ...$contents): string|static
    {
        // Get
        if (empty($contents)) {
            if ($this instanceof ContentsOverride) {
                return $this->contentsOverride()->render();
            }
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
     * [Shortcut] Same $this->contents->append(), but this method return the
     * current Tag instead of the ContentCollection object.
     *
     * Note: if this class implements ContentsOverride, whether to render or how
     * to render thees contents is depending on the contentsOverride() method.
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
     * [Shortcut] Same $this->contents->prepend(), but this method return the
     * current Tag instead of the ContentCollection object.
     *
     * Note: if this class implements ContentsOverride, whether to render or how
     * to render thees contents is depending on the contentsOverride() method.
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
     * Proxy to $this->contents() (without any arguments). Therefore, returns
     * the rendered string of $contents or contentsOverride().
     *
     * @return string
     */
    protected function contentsRendered(): string
    {
        return $this->contents();
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