<?php

namespace Wongyip\HTML\Traits;

/**
 * Hook methods for child classes.
 */
trait ContentsExtensions
{
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
     * Return contents to be rendered before the contents attached to the tag.
     *
     * @return array
     */
    protected function contentsPrefixed(): array
    {
        return [];
    }

    /**
     * Return contents to be rendered after the contents attached to the tag.
     *
     * @return array
     */
    protected function contentsSuffixed(): array
    {
        return [];
    }
}