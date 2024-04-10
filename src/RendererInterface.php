<?php

namespace Wongyip\HTML;

interface RendererInterface
{
    /**
     * Compile the object into HTML tag(s) for rendering.
     *
     * Additional attributes or options may be supplied, and they should be used
     * once only during the rendering process, neither of them should be stored.
     * Therefore, the object should be keep intact after rendering.
     *
     * @param array|null $adHocAttrs
     * @param array|null $adHocOptions
     * @return string
     */
    public function render(array $adHocAttrs = null, array $adHocOptions = null): string;
}