<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * A minimal implemented of the \<a\> tag.
 *
 * Attributes Get-setters
 * @method string|static href(string|null $value = null)
 * @method string|static title(string|null $value = null)
 * @method string|static target(string|null $value = null)
 */
class Anchor extends TagAbstract
{
    use ContentsText;

    /**
     * @var string
     */
    protected string $tagName = 'a';

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    protected function addAttrs(): array
    {
        return ['href', 'title', 'target'];
    }

    /**
     * @return static
     */
    public function targetBlank(): static
    {
        return $this->target('_blank');
    }
}