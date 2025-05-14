<?php

namespace Wongyip\HTML\Interfaces;

use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Tag;
use Wongyip\HTML\TagAbstract;

interface ContentsOverride
{
    /**
     * Compose and return the override ContentsCollection, which will supersede
     * the main ContentsCollection ($this->content) on render.
     *
     * Implementation note, do not call the contents() method as it will result
     * infinite recursive call.
     *
     * @return ContentsCollection
     * @see TagAbstract::render()
     * @see Tag::$contents
     */
    public function contentsOverride(): ContentsCollection;
}