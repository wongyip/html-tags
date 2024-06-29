<?php

namespace Wongyip\HTML;

use Wongyip\HTML\Interfaces\RendererInterface;

/**
 * A basic implementation of an \<button\> tag.
 *
 * @method bool|null|static disabled(bool|null $set = null) Disabled input is not editable, and is NOT transmitted when the form is submitted.
 * @method string|null|static type(string|null $set = null)
 * @see https://www.w3schools.com/tags/tag_button.asp
 */
class Button extends TagAbstract
{
    /**
     * @var string
     */
    protected string $tagName = 'button';

    /**
     * @inheritdoc
     */
    public function addAttrs(): array
    {
        return [
            'autofocus', 'disabled',
            'form', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget',
            'popovertarget', 'popovertargetaction', 'ype', 'value',
        ];
    }

    /**
     * Create an \<button type="button"\>...\</button\> tag.
     *
     * @param string|RendererInterface|null $contents
     * @param string|null $id
     * @param string|null $name
     * @return static
     */
    public static function button(string|RendererInterface $contents = null, string $id = null, string $name = null): static
    {
        return static::create($contents, $id, $name, 'button');
    }

    /**
     * Create an \<button type="..."\>...\</button\> tag.
     *
     * @param string|RendererInterface|null $contents
     * @param string|null $id
     * @param string|null $name
     * @param string|null $type
     * @return static
     */
    public static function create(string|RendererInterface $contents = null, string $id = null, string $name = null, string $type = null): static
    {
        $type = $type ?? 'button';
        $button = static::tag()->attributes(compact('id', 'name', 'type'));
        return $contents ? $button->contents($contents) : $button;
    }

    /**
     * Create an \<button type="reset"\>...\</button\> tag.
     *
     * @param string|RendererInterface|null $contents
     * @param string|null $id
     * @param string|null $name
     * @return static
     */
    public static function reset(string|RendererInterface $contents = null, string $id = null, string $name = null): static
    {
        return static::create($contents, $id, $name, 'reset');
    }

    /**
     * Create an \<button type="submit"\>...\</button\> tag.
     *
     * @param string|RendererInterface|null $contents
     * @param string|null $id
     * @param string|null $name
     * @return static
     */
    public static function submit(string|RendererInterface $contents = null, string $id = null, string $name = null): static
    {
        return static::create($contents, $id, $name, 'submit');
    }
}