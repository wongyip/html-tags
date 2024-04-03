<?php

namespace Wongyip\HTML\Traits;

use Wongyip\HTML\TagAbstract;

/**
 */
trait Contents
{
    /**
     * Inner text contents.
     *
     * @var array|string[]|TagAbstract[]
     */
    protected array $contents = [];

    /**
     * Get existing contents rendered as string, or set (replace) the existing
     * $contents (string or TabAbstract, or array mixed of both types).
     *
     * @param string|TagAbstract ...$contents
     * @return string|static
     */
    public function contents(string|TagAbstract ...$contents): string|static
    {
        if (!empty($contents)) {
            array_push($this->contents, ...$contents);
            return $this;
        }
        return $this->contentsRendered();
    }

    /**
     * Alias to contentsAppend().
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsAdd(string|TagAbstract ...$contents): static
    {
        return $this->contentsAppend(...$contents);
    }

    /**
     * Append contents to the $contents array.
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsAppend(string|TagAbstract ...$contents): static
    {
        $this->contents = array_merge($this->contents, $contents);
        return $this;
    }

    /**
     * Prepend contents to the $contents array.
     *
     * @param string|TagAbstract ...$contents
     * @return static
     */
    public function contentsPrepend(string|TagAbstract ...$contents): static
    {
        $this->contents = array_merge($contents, $this->contents);
        return $this;
    }

    /**
     * @return string
     */
    private function contentsRendered(): string
    {
        $contents = '';
        foreach ($this->contents as $content) {
            $contents .= (is_string($content) ?  htmlspecialchars($content) : $content->render());
        }
        return $contents;
    }
}