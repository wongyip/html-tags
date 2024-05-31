<?php

namespace Wongyip\HTML;

/**
 * A basic implementation of an \<input\> tag.
 *
 * @method bool|null|static disabled(bool|null $set = null) Disabled input is not editable, and is NOT transmitted when the form is submitted.
 * @method string|null|static placeholder(string|null $set = null)
 * @method bool|null|static readonly(bool|null $set = null) Read-only input is not editable, but it is transmitted when the form is submitted.
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
        return [
            'type', 'value',
            'checked', 'disabled', 'readonly', 'required',
            'placeholder'
        ];
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
     * @param bool|null $checked
     * @param string|null $placeholder
     * @return static
     */
    public static function create(string $name = null, string $type = null, string $id = null, bool $required = null, bool $disabled = null, bool $readonly = null, bool $checked = null, string $placeholder = null): static
    {
        return static::make()->attributes(
            compact('name', 'type', 'id', 'checked', 'disabled', 'readonly', 'required', 'placeholder')
        );
    }

}