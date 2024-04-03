<?php

namespace Wongyip\HTML\Demo;

use Wongyip\HTML\Tag;
use Wongyip\HTML\TagAbstract;

class Section extends TagAbstract
{
    protected string $tagName = 'section';

    public Tag $heading;
    public Tag $footnote;

    public function __construct(string $tagName = null, array $extraAttrs = null)
    {
        parent::__construct($tagName, $extraAttrs);

        // Init named child(s).
        $this->heading = Tag::make('h1')->contents('Title Line');
        $this->footnote = Tag::make('div')->contents('Some notes here.');
    }

    public function contentsPrefixed(): array
    {
        // Prefix named child(s) to contents before render.
        return [$this->heading];
    }

    public function contentsSuffixed(): array
    {
        // Suffix named child(s) to contents before render.
        return [$this->footnote];
    }

    protected function addAttrs(): array
    {
        return [];
    }
}