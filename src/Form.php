<?php

namespace Wongyip\HTML;

/**
 * Basic HTTP &lt;form&gt; tag.
 *
 * @method string|static action(string|null $value = null) Path or URL.
 * @method string|static enctype(string|null $value = null) Default empty for 'application/x-www-form-urlencoded', set 'multipart/form-data' for upload form, set 'text/plain' for no encoding (not recommended)..
 * @method string|static method(string|null $value = null) Either 'GET' or 'POST'.
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
        return ['action', 'enctype', 'method'];
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
     * **IMPORTANT**: All arguments are ignored, an empty POST form is returned.
     * It is suggested to use the Form::get() or Form::post() methods.
     *
     * @param string|null $tagName
     * @param array|null $extraAttrs
     * @return static
     */
    public static function make(string $tagName = null, array $extraAttrs = null): static
    {
        return static::post();
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