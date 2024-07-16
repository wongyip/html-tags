<?php

namespace Wongyip\HTML\Traits;

/**
 * [Experimental]
 *
 * Given the complexity of enumerated attributes, it's not ideal to overload
 * their get/setters. Each **global** enumerated attribute should have its own
 * get/setter method.
 */
trait EnumeratedAttributes
{
    /**
     * Get or set the hidden state. Valid values are 'hidden', 'until-found' or
     * '' (empty string), input true for 'hidden', false to unset the attribute.
     *
     * Note that the hidden state maybe override by CSS.
     *
     * @see https://html.spec.whatwg.org/multipage/interaction.html#the-hidden-attribute
     * The attribute's missing value default is the not hidden state, and its
     * invalid value default is the hidden state.
     *
     * @param string|bool|null $set
     * @return string|null|static
     *
     */
    public function hidden(string|bool $set = null)
    {
        // Get
        if (is_null($set)) {
            return $this->_attributes['hidden'] ?? null;
        }

        // Set
        if ($set === false) {
            unset($this->_attributes['hidden']);
        }
        else {
            $set = $set === true ? 'hidden' : $set;
            $this->_attributes['hidden'] = $set;
        }
        return $this;
    }

    /**
     * @todo In progress.
     * @see https://github.com/iandevlin/html-attributes/blob/master/enumerated-attributes.json
     *
     * @param string $attribute
     * @return bool
     */
    protected function isEnumeratedAttribute(string $attribute): bool
    {
        return in_array(
            strtolower($attribute), [
                'hidden',
            ]
        );
    }
}
