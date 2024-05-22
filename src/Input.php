<?php

namespace Wongyip\HTML;

/**
 * A basic implementation of an "\<input\>" tag.
 *
 * Attributes Get-setters.
 * @method bool|null|static disabled(bool|null $set = null)
 * @method string|null|static placeholder(string|null $set = null)
 * @method bool|null|static readonly(bool|null $set = null)
 * @method bool|null|static required(bool|null $set = null)
 * @method string|null|static type(string|null $set = null)
 * @method number|string|bool|null|static value(number|string|bool|null $set = null)
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
        return ['disabled', 'placeholder', 'readonly', 'required', 'type', 'value'];
    }

    /**
     * Create an \<input\> tag.
     *
     * @param string|null $name
     * @param string|null $type
     * @param string|null $id
     * @param bool|null $required
     * @param bool|null $disabled
     * @param bool|null $readonly
     * @param string|null $placeholder
     * @return static
     */
    public static function create(string $name = null, string $type = null, string $id = null, bool $required = null, bool $disabled = null, bool $readonly = null, string $placeholder = null): static
    {
        return static::make()->attributes(
            compact('name', 'type', 'id', 'disabled', 'readonly', 'required', 'placeholder')
        );
    }

}