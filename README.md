# HTML Tags Renderer

An intuitive and simple HTML renderer for generic purpose.

## Installation
```sh
composer require wongyip/html-tags
```

## Usage

### Basic
```php
$div = new Tag('div');
$div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');
echo $div->render();
```
Expected output: `<div class="c1 c2">Example &lt;div&gt; tag with t1 &amp; t2 CSS classes.</div>`

### One-liner
```php
echo Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render();
```
Expected output: `<a href="/path/to/go" target="_blank" class="btn btn-primary">Go</a>`

### Nested
```php
$tag = Tag::make('div')->class('parent')->contents(
    Tag::make('p')->id('child1')->contents('Regular'),
    Tag::make('p')->id('child2')->contents(
        Tag::make('span')->contents(
            Tag::make('strong')->contents('Bold Face')
        )
    )
);
echo $tag->render();
```
Expected output: `<div class="parent"><p id="child1">Regular</p><p id="child2"><span><strong>Bold Face</strong></span></p></div>`

### Contents Extension

```php

class Section extends TagAbstract
{
    protected string $tagName = 'section';

    public Tag $heading;
    public Tag $footnote;

    public function __construct(string $tagName = null, array $extraAttrs = null)
    {
        parent::__construct($tagName, $extraAttrs);
        
        // Init named child(s).
        $this->heading = Tag::make('h1')->contents('Title Line');
        $this->footnote = Tag::make('div')->contents('Some notes here.');
    }

    public function contentsPrefixed(): array
    {
        // Prefix named child(s) to contents before render.
        return [$this->heading];
    }

    public function contentsSuffixed(): array
    {
        // Suffix named child(s) to contents before render.
        return [$this->footnote];
    }

    ...
}

echo Section::make()->contents(Tag::make('p')->contents('Paragraph 1'), Tag::make('p')->contents('Paragraph 2'))->render();
```
Expected output: `<section><h1>Title Line</h1><p>Paragraph 1</p><p>Paragraph 2</p><div>Some notes here.</div></section`

*See [`Demo::class`](src/Demo.php) for more examples.*

## Limitations
1. `<script>` tag is not supported, obviously because of the security concerns.
2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
3. `<!doctype>` tag is not supported also. 
4. `<!-- comment -->` tag must be rendered with `Comment::class`.
5. Web content accessibility is not addressed, yet.
