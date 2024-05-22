<?php

namespace Wongyip\HTML;

use Exception;

/**
 * A table caption.
 *
 * @method Caption sideBottom() Set caption-side CSS property's value to "bottom".
 * @method Caption sideTop() Set caption-side CSS property's value to "top".
 * @method Caption sideInherit() Set caption-side CSS property's value to "inherit".
 * @method Caption sideInitial() Set caption-side CSS property's value to "initial".
 *
 */
class Caption extends TagAbstract
{
    const DEFAULT_SIDE = 'top';

    /**
     * @var array|string[]
     */
    private static array $sides = ['top', 'bottom', 'initial', 'inherit'];
    /**
     * @var string
     */
    protected string $tagName = 'caption';

    /**
     * @param string $name
     * @param array $arguments
     * @return bool|Caption|TagAbstract|null
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (preg_match("/^side([A-Z][a-z]+)$/", $name, $matches)) {
            $setter = strtolower($matches[1]);
            if (in_array($setter, static::$sides)) {
                $this->styleProperty('caption-side', $setter);
                return $this;
            }
            // Let it go.
        }
        return parent::__call($name, $arguments);
    }

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

        $side = $side
            ? (in_array($side, static::$sides) ? $side : static::DEFAULT_SIDE)
            : static::DEFAULT_SIDE;

        return static::make()
            ->contents($contents)
            ->styleProperty('caption-side', $side);
    }

}