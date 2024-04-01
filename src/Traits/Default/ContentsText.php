<?php

namespace Wongyip\HTML\Traits\Default;

/**
 * Default implementation of contentsText() method.
 */
trait ContentsText
{
    /**
     * Default implementation of contentsText() method, which joins all values
     * of the $contents array and returns it as a string. Tags with needs of
     * extra pre-processing of contents may extend this method with their own
     * implementation.
     *
     * @return string
     */
    public function contentsText(): string
    {
        return $this->contents ? implode(PHP_EOL, $this->contents) : '';
    }
}