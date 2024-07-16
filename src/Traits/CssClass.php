<?php

namespace Wongyip\HTML\Traits;

use Throwable;
use Wongyip\HTML\Utils\Convert;
use Wongyip\HTML\Utils\CSS;

/**
 * CSS class (complex attribute) manipulation trait.
 */
trait CssClass
{
    /**
     * Internal storage of CSS classes added.
     *
     * @var array
     */
    protected array $cssClasses = [];

    /**
     * [Internal] Used by the attribute() method.
     *
     * @param mixed|null $set
     * @return string|static
     * @see Attributes::attribute()
     */
    protected function _class(mixed $set = null): string|static
    {
        //Get
        if (is_null($set)) {
            return $this->class();
        }
        // Set
        $classes = is_array($set) ? $set : (is_scalar($set) ? [$set] : []);
        return $this->class(...$classes);
    }

    /**
     * Get or set (replace) the class attribute. Setter accepts array or
     * space-seperated class list.
     *
     * @param string|array|null $classes
     * @return string|static
     */
    public function class(string|array ...$classes): string|static
    {
        // Get
        if (empty($classes)) {
            return implode(' ', $this->classes());
        }
        // Set
        $this->cssClasses = Convert::flatten(...$classes);
        return $this;
    }

    /**
     * Extract all CSS classes as an array.
     *
     * @return string[]|array
     */
    public function classes(): array
    {
        return $this->classesHook($this->cssClasses);
    }

    /**
     * [Extension] Modify the CSS classes array before output.
     *
     * @param string[]|array $classes
     */
    protected function classesHook(array $classes): array
    {
        return $classes;
    }

    /**
     * Alias to append().
     *
     * Push a list of CSS classes onto the end of the classes array, if an input
     * class is already in the classes array, it will be removed and then push
     * again onto the end of the array.
     *
     * E.g. When appending 'c2' and 'c4' to existing classes ['c1', 'c2', 'c3'],
     * the outcome will be ['c1', 'c3', 'c2', 'c4'].
     *
     * Space-separated classes list is supported.
     *
     * @param string ...$classes
     * @return static
     */
    public function classAdd(string ...$classes): static
    {
        return $this->classAppend(...$classes);
    }

    /**
     * Push a list of CSS classes onto the end of the classes array, if an input
     * class is already in the classes array, it will be removed and then push
     * again onto the end of the array.
     *
     * E.g. When appending 'c2' and 'c4' to existing classes ['c1', 'c2', 'c3'],
     * the outcome will be ['c1', 'c3', 'c2', 'c4'].
     *
     * Space-separated classes list is supported.
     *
     * @param string ...$classes
     * @return static
     */
    public function classAppend(string ...$classes): static
    {
        $appends = CSS::classArray(...$classes);
        if (!empty($appends)) {
            $this->classRemove(...$appends);
            array_push($this->cssClasses, ...$appends);
        }
        return $this;
    }

    /**
     * Remove ALL classes).
     *
     * @return static
     */
    public function classEmpty(): static
    {
        $this->cssClasses = [];
        return $this;
    }

    /**
     * Append the input $class if $condition is TRUE, or remove it if $condition
     * is FALSE.
     *
     * @param string $class
     * @param bool $condition
     * @return $this
     */
    public function classIf(string $class, bool $condition): static
    {
        if ($condition) {
            $this->classAppend($class);
        }
        else {
            $this->classRemove($class);
        }
        return $this;
    }

    /**
     * Prepend a list of CSS classes to the CSS classes array, if an input class
     * is already in the classes array, it will be removed from the array before
     * the prepend operation.
     *
     * E.g. When prepending 'c2' and 'c4' to existing classes ['c1', 'c2', 'c3'],
     * the outcome will be ['c2', 'c4', 'c1', 'c3'].
     *
     * Space-separated classes list is supported.
     *
     * @param string ...$classes
     * @return static
     * @todo is array_diff() necessary?
     */
    public function classPrepend(string ...$classes): static
    {
        $prepends = CSS::classArray(...$classes);
        if (!empty($prepends)) {
            $this->classRemove(...$prepends);
            array_unshift($this->cssClasses, ...$prepends); // In
        }
        return $this;
    }

    /**
     * Remove a list of CSS classes from the classes array (space=-separated
     * classes list is supported).
     *
     * @param string|array|string[] $classes
     * @return static
     */
    public function classRemove(string ...$classes): static
    {
        $removes = CSS::classArray(...$classes);
        if (!empty($removes)) {
            $this->cssClasses = array_diff($this->cssClasses, $removes);
        }
        return $this;
    }

    /**
     * [To be removed].
     *
     * Parse input into array of CSS classes.
     *
     * @deprecated
     * @param $classes
     * @return array
     */
    private function classParse($classes): array
    {
        try {
            return array_filter(array_map('trim', explode(' ', implode(' ', is_array($classes) ? $classes : [$classes]))));
        }
        catch (Throwable $e) {
            error_log(sprintf('CssClass.classParse() - Error: %s (%d).', $e->getMessage(), $e->getCode()));
            return [];
        }
    }
}