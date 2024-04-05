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
     * @param string|array|null $class
     * @return string|static
     */
    public function class(string|array ...$class): string|static
    {
        if (!empty($class)) {
            $this->cssClasses = [];
            foreach ($class as $c) {
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
     * Add (append) a list of CSS classes to the classes array (space=-separated
     * classes list is supported).
     *
     * @param string ...$classes
     * @return static
     */
    public function classAdd(string ...$classes): static
    {
        $classes = $this->classParse($classes);
        // @todo is array_diff() necessary?
        $this->cssClasses = array_merge($this->cssClasses, $classes);
        return $this;
    }

    /**
     * Add (append) a list of CSS classes to the classes array(space=-separated
     * classes list is supported).
     *
     * @param string ...$classes
     * @return static
     */
    public function classAppend(string ...$classes): static
    {
        return $this->classAdd(...$classes);
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
     * Prepend a list of CSS classes to the classes array (space=-separated
     * classes list is supported).
     *
     * @param string ...$classes
     * @return static
     * @todo is array_diff() necessary?
     */
    public function classPrepend(string ...$classes): static
    {
        $classes = $this->classParse($classes);
        $this->cssClasses = array_merge($classes, array_diff($this->cssClasses, $classes));
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
            return array_map('trim', explode(' ', implode(' ', is_array($classes) ? $classes : [$classes])));
        }
        catch (Throwable $e) {
            error_log(sprintf('CssClass.classParse() - Error: %s (%d).', $e->getMessage(), $e->getCode()));
            return [];
        }
    }
}