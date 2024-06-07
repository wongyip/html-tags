<?php

namespace Wongyip\HTML;

/**
 * A basic (incomplete) implementation of a "\<textarea>" tag.
 *
 * @method int|static cols(int $set = null)
 * @method bool|null|static disabled(bool|null $set = null) Disabled input is not editable, and is NOT transmitted when the form is submitted.
 * @method int|static maxlength(int $set = null)
 * @method string|null|static placeholder(string|null $set = null)
 * @method bool|null|static readonly(bool|null $set = null) Read-only input is not editable, but it is transmitted when the form is submitted.
 * @method bool|null|static required(bool|null $set = null)
 * @method int|static rows(int $set = null)
 */
class Textarea extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'textarea';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [
            'autofocus',
            'cols',
            'dirname',
            'disabled',
            'form',
            'maxlength',
            'placeholder',
            'readonly',
            'required',
            'rows',
            'wrap'
        ];
    }

    /**
     * Create an \<textarea> tag.
     *
     * @param int|null $rows
     * @param int|null $cols
     * @return static
     */
    public static function create(int $rows = null, int $cols = null): static
    {
        return static::tag()->attributes(compact('cols', 'rows'));
    }
}