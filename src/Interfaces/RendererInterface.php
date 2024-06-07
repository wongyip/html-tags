<?php

namespace Wongyip\HTML\Interfaces;

interface RendererInterface
{
    /**
     * Compile the object into HTML.
     *
     * Additional attributes and options may be supplied for use in the rendering
     * process, but neither of them will be stored. Therefore, the object will
     * keep intact after rendering.
     *
     * @param array|null $adHocAttrs
     * @param array|null $adHocOptions
     * @return string
     */
    public function render(array $adHocAttrs = null, array $adHocOptions = null): string;
}