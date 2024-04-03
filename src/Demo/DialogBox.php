<?php

namespace Wongyip\HTML\Demo;

use Wongyip\HTML\Tag;
use Wongyip\HTML\TagAbstract;

class DialogBox extends TagAbstract
{
    protected string $tagName = 'div';

    public Tag $heading;
    public Tag $button;

    public function __construct(string $tagName = null, array $extraAttrs = null)
    {
        parent::__construct($tagName, $extraAttrs);

        // Init named child(s).
        $this->heading = Tag::make('h4');
        $this->button = Tag::make('button');
    }

    public static function create(Tag|string $message, string $title, string $buttonCaption): static
    {
        $tag = static::make();
        $tag->contents(Tag::make('p')->contents($message))->class('dialog-box');
        $tag->heading->contents($title);
        $tag->button->contents($buttonCaption);
        return $tag;
    }

    public function contentsPrefixed(): array
    {
        // Prefix named child(s) to contents before render.
        return [$this->heading];
    }

    public function contentsSuffixed(): array
    {
        // Suffix named child(s) to contents before render.
        return [$this->button];
    }

    protected function addAttrs(): array
    {
        return [];
    }
}