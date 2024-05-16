<?php

namespace Wongyip\HTML;

/**
 * A basic implementation of an "\<input\>" tag.
 *
 * Attributes Get-setters.
 * @method bool|static disabled(bool|null $value = null)
 * @method bool|static readonly(bool|null $value = null)
 */
class Input extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'input';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return ['disabled', 'readonly', 'type', 'value'];
    }

    /**
     * Create an \<input\> tag.
     *
     * @param string|null $name
     * @param string|null $type
     * @param string|null $id
     * @param bool|null $disabled
     * @param bool|null $readonly
     * @return static
     */
    public static function create(string $name = null, string $type = null, string $id = null, bool $disabled = null, bool $readonly = null): static
    {
        return Input::make()->attributes(compact('name', 'type', 'id', 'disabled', 'readonly'));
    }

}