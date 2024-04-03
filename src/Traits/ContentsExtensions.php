<?php

namespace Wongyip\HTML\Traits;

/**
 * Hook methods for child classes.
 */
trait ContentsExtensions
{
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