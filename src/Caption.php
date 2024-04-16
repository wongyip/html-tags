<?php

namespace Wongyip\HTML;

/**
 * A table caption.
 */
class Caption extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'caption';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * Create a caption tag.
     *
     * @param string|TagAbstract $contents
     * @param string|null $side
     * @return Caption
     */
    public static function create(string|TagAbstract $contents, string $side = null): Caption
    {
        // @todo validate $side.

        return Caption::make()
            ->contents($contents)
            ->styleAppend("caption-side: $side");
    }

}