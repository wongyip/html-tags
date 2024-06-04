<?php

namespace Wongyip\HTML\Traits;

use Throwable;

/**
 * CSS class attribute manipulation trait.
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
     * Get or set (replace) the class attribute. Setter accepts array or
     * space-seperated class list.
     *
     * @param string|array|null $classes
     * @return string|static
     */
    public function class(string|array ...$classes): string|static
    {
        // Set
        if (!empty($classes)) {
            $this->cssClasses = [];
            foreach ($classes as $c) {
                $this->cssClasses = array_merge($this->cssClasses, is_array($c) ? $c : explode(' ', $c));
            }
            return $this;
        }
        return implode(' ', $this->classes());
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
        $appended = $this->classParse($classes);
        if (!empty($appended)) {
            $this->classRemove(...$appended);
            array_push($this->cssClasses, ...$appended);
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
        $prepended = $this->classParse($classes);
        if (!empty($prepended)) {
            $this->classRemove(...$prepended);
            array_unshift($this->cssClasses, ...$prepended); // In
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
        $classes = $this->classParse($classes);
        if (!empty($classes)) {
            $this->cssClasses = array_diff($this->cssClasses, $classes);
        }
        return $this;
    }

    /**
     * Parse input into array of CSS classes.
     *
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