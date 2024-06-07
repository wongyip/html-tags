<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\RendererInterface;

class RawHTML implements RendererInterface
{
    /**
     * @var string
     */
    protected string $html = '';

    /**
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html = $html;
    }

    /**
     * Override parent, output raw HTML directly.
     *
     * CAUTION: beware of XSS attack, always sanitized before output.
     *
     * @param array|null $adHocAttrs
     * @param array|null $adHocOptions
     * @return string
     */
    public function render(array $adHocAttrs = null, array $adHocOptions = null): string
    {
        return $this->html;
    }

    /**
     * Create an RawHTML tag object.
     *
     * @param string $html
     * @return static
     */
    public static function create(string $html): static
    {
        return new static($html);
    }

    /**
     * non-breaking space: \&nbsp;
     *
     * @return static
     */
    public static function NBSP(): static
    {
        return new static('&nbsp;');
    }

    /**
     * zero-width non-joiner: \&zwnj;
     *
     * @return static
     */
    public static function ZWNJ(): static
    {
        return new static('&zwnj;');
    }
}