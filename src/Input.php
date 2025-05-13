<?php

namespace Wongyip\HTML;

/**
 * A basic implementation of an \<input\> tag.
 *
 * @method string|null|static autocomplete(string|null $set = null) Get or set the autocomplete attribute  Set 'off' to disable or set 'new-password' in case of requesting new password (whether to comply or not is up to the browser).
 * @method bool|null|static checked(bool|null $set = null) Only for input with type of 'checkbox' and 'radio'.
 * @method bool|null|static disabled(bool|null $set = null) Disabled input is not editable, and is NOT transmitted when the form is submitted.
 * @method string|null|static pattern(string|null $set = null) A regular expression that an <input> element's value is checked against.
 * @method string|null|static placeholder(string|null $set = null) A short hint that describes the expected value of an <input> element
 * @method bool|null|static readonly(bool|null $set = null) [N.B.] Read-only input is not editable, but it is transmitted when the form is submitted.
 * @method bool|null|static required(bool|null $set = null)
 * @method string|null|static type(string|null $set = null)
 * @method number|string|bool|null|static value(number|string|bool|null $set = null)
 *
 * @see https://www.w3schools.com/tags/tag_input.asp
 * @see https://www.w3schools.com/tags/att_input_autocomplete.asp
 * @see https://developer.mozilla.org/en-US/docs/Web/Security/Practical_implementation_guides/Turning_off_form_autocompletion
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
            'pattern', 'placeholder', 'autocomplete',
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
     * @param string|null $pattern
     * @return static
     */
    public static function create(string $name = null, string $type = null, string $id = null, bool $required = null, bool $disabled = null, bool $readonly = null, bool $checked = null, string $placeholder = null, string $pattern = null): static
    {
        return static::tag()->attributes(
            compact('name', 'type', 'id', 'checked', 'disabled', 'readonly', 'required', 'placeholder', 'pattern')
        );
    }

}