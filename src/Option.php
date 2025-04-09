<?php

namespace Wongyip\HTML;

/**
 * A generic option tag inside the select tag.
 *
 * @method bool|null|static selected(bool|null $set = null) Set the selected attribute (boolean).
 *
 * @todo Since only text content is allowed in an option tag, the contents() or render() method might need to update to prevent adding of HTML content.
 * @todo Additional 'label' attribute is supported by a datalist option tag, which render an alternative description of the option's value.
 */
class Option extends TagAbstract
{
    protected string $tagName = 'option';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return ['disabled', 'selected', 'value',];
    }

    /**
     * @inheritdoc
     */
    protected array $emptyValueAttributes = ['value'];

    /**
     * Create an Option tag.
     *
     * @param string $value Required, empty string is accepted.
     * @param string|null $text Optional, take $text when omitted.
     * @param bool|null $selected Optional, default FALSE.
     * @param bool|null $disabled Optional, default FALSE.
     * @return static
     */
    public static function create(string $value, string $text = null, bool $selected = null, bool $disabled = null): static
    {
        return static::tag()
            ->attributes(compact('value', 'selected', 'disabled'))
            ->contents($text ?? $value);
    }
}