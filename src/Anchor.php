<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Traits\Default\ContentsText;

/**
 * A minimal implemented of a "\<a>" (anchor) tag with the following details:
 *
 *  1. Additional attributes is supported via the addAttrs() method.
 *  2. Add. attributes' get-setters are annotated with @-method for code-hint.
 *  3. Additional helper method: targetBlank() is added.
 *  4. Uses default ContentsText method to compose text contents.
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
     * Set the target attribute to '_blank'.
     * @return static
     */
    public function targetBlank(): static
    {
        return $this->target('_blank');
    }
}