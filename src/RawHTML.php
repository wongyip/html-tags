<?php

namespace Wongyip\HTML;

class RawHTML extends TagAbstract
{
    /**
     * @var string
     */
    protected string $rawHTML = '';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [];
    }

    /**
     * Override parent, output raw HTML directly.
     *
     * CAUTION: beware of XSS attack, always sanitized before output.
     *
     * @param array|null $adHocAttrs
     * @return string
     */
    public function render(array $adHocAttrs = null): string
    {
        return $this->rawHTML;
    }

    /**
     * Create an RawHTML tag object.
     *
     * @param string $html
     * @return static
     */
    public static function create(string $html): static
    {
        $tag = static::make();
        $tag->rawHTML = $html;
        return $tag;
    }

    /**
     * non-breaking space: \&nbsp;
     *
     * @return static
     */
    public static function NBSP(): static
    {
        $tag = static::make();
        $tag->rawHTML = '&nbsp;';
        return $tag;
    }

    /**
     * zero-width non-joiner: \&zwnj;
     *
     * @return static
     */
    public static function ZWNJ(): static
    {
        $tag = static::make();
        $tag->rawHTML = '&zwnj;';
        return $tag;
    }
}