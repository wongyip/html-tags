<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\NoAddAttrs;

/**
 * A \<div\> tag.
 */
class Div extends TagAbstract
{
    use NoAddAttrs;

    /**
     * HTML Tag Name.
     *
     * @var string
     */
    protected string $tagName = 'div';
}