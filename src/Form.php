<?php

namespace Wongyip\HTML;

/**
 * Basic HTTP \<form\> tag.
 *
 * @method string|static action(string|null $value = null) Path or URL.
 * @method string|static autocomplete(string|null $value = null) Get or set the autocomplete attribute. Set 'off' to disable to auto-complete all fields (whether to comply or not is up to the browser).
 * @method string|static enctype(string|null $value = null) Default empty for 'application/x-www-form-urlencoded', set 'multipart/form-data' for upload form, set 'text/plain' for no encoding (not recommended)..
 * @method string|static method(string|null $value = null) Either 'GET' or 'POST'.
 * @method string|static target(string|null $value = null) Submission target.
 */
class Form extends TagAbstract
{
    /**
     * @inheritdoc
     */
    protected string $tagName = 'form';

    /**
     * Instantiate a &lt;form&gt; tag. Note that changing of tagName and setting
     * of $extraAttrs are not supported.
     *
     * @param string|null $action Default '', which will submit to the current URL.
     * @param string|null $method Default POST.
     * @param string|null $id Tag ID.
     * @param string|null $class Single CSS class or space-separated classes list.
     */
    public function __construct(string $action = null, string $method = null, string $id = null, string $class = null)
    {
        // Basic init.
        parent::__construct();
        $this->action($action ?? '');
        $this->method($method ?? 'POST');
        if ($id) {
            $this->id($id) ;
        }
        if ($class) {
            $this->classAppend($class);
        }
    }

    /**
     * Tag attributes in addition to common attributes. Every child tag object
     * should extend this method to provide a list of supported attributes.
     *
     * @return array|string[]
     */
    public function addAttrs(): array
    {
        // @todo Incomplete
        return ['action', 'autocomplete', 'enctype', 'method', 'target'];
    }

    /**
     * Enable or disable upload by switching the enctype attribute.
     * N.B. will also change method attribute to 'POST' while switching on.
     *
     * @param bool $switch
     * @return static
     */
    public function enableUpload(bool $switch = true): static
    {
        $this->enctype($switch ? 'multipart/form-data' : '');
        $this->method('POST');
        return $this;
    }

    /**
     * Make a GET form.
     *
     * @param string|null $action Default '', which will submit to the current URL.
     * @param string|null $id Tag ID.
     * @param string|null $class Single CSS class or space-separated classes list.
     * @return static
     */
    public static function get(string $action = null, string $id = null, string $class = null): static
    {
        return new static($action, 'GET', $id, $class);
    }

    /**
     * Make a POST form.
     *
     * @param string|null $action Default '', which will submit to the current URL.
     * @param string|null $id Tag ID.
     * @param string|null $class Single CSS class or space-separated classes list.
     * @return static
     */
    public static function post(string $action = null, string $id = null, string $class = null): static
    {
        return new static($action, 'POST', $id, $class);
    }
}