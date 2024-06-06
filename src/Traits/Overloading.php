<?php

namespace Wongyip\HTML\Traits;

use Exception;
use ReflectionClass;
use Wongyip\HTML\TagAbstract;

trait Overloading
{
    /**
     * @param string $name
     * @param array $arguments
     * @return bool|static|TagAbstract|null
     * @throws Exception
     * @see https://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call(string $name, array $arguments)
    {
        /**
         * Get or set attribute if it is present in $tagAttrs.
         */
        if (in_array($name, $this->_attrsNames)) {
            return $this->attribute($name, $arguments[0] ?? null);
        }
        /**
         * Get or set property if 1. it is defined, 2. its name is NOT starting
         * with _ (underscore), and 3. it's not static.
         */
        $error = '';
        if (!property_exists($this, $name)) {
            $error = 'undefined property';
        }
        elseif (str_starts_with($name, '_')) {
            $error = 'property with name starting with underscore';
        }
        elseif ($this->isStaticProp($name)) {
            $error = 'static property';
        }

        $class = preg_replace("/.*\\\\/", '', get_class($this));
        if ($error) {
            throw new Exception(sprintf('Undefined method %s.%s() called (note: %s).', $class, $name, $error));
        }

        if (!in_array($name, $this->overloading())) {
            error_log("*** DEPRECATED *** Get or set $class.$name without explicitly stated overloading!");
        }

        // Set property.
        if (isset($arguments[0])) {
            $this->$name = $arguments[0];
            return $this;
        }
        // Get property.
        return $this->$name ?? null;
    }

    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'innerHTML':
                return $this->contentsRendered();
            case 'innerText':
                return strip_tags($this->contentsRendered());
        }
        throw new Exception(sprintf('Undefined property %s.', $name));
    }

    /**
     * Check if the given property is static.
     *
     * @param string $property
     * @return bool
     */
    protected function isStaticProp(string $property): bool
    {
        if (!isset($this->_staticProps)) {
            $this->_staticProps = array_keys((new ReflectionClass($this))->getStaticProperties() ?? []);
        }
        return in_array($property, $this->_staticProps);
    }

    /**
     * [For extension]
     *
     * Returns a list of properties exposed for read/write via the __call()
     * method overloading.
     *
     * @return array
     */
    protected function overloading(): array
    {
        return [];
    }
}