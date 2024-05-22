<?php

namespace Wongyip\HTML;

/**
 * A basic implementation of a "\<label>" tag.
 *
 * @method string|static for(string $set = null)
 */
class Label extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'label';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return ['for'];
    }

    /**
     * Create an \<label> tag.
     *
     * @param string $for
     * @return static
     */
    public static function create(string $for): static
    {
        return static::make()->attributes(compact('for'));
    }

}