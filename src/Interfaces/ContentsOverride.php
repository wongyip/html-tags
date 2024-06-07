<?php

namespace Wongyip\HTML\Interfaces;

use Wongyip\HTML\Supports\ContentsCollection;

interface ContentsOverride
{
    /**
     * Compose and return the override ContentsCollection, which will supersede
     * the main ContentsCollection ($this->content) on render.
     *
     * @return ContentsCollection
     */
    public function contentsOverride(): ContentsCollection;
}