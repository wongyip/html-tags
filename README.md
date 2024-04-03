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
$div->class('c1 c2')
$div->contents('Example <div> tag with t1 & t2 CSS classes.');
echo $div->render();
```
Output: `<div class="c1 c2">Example &lt;div&gt; tag with t1 &amp; t2 CSS classes.</div>`

### One-liner
```php
echo Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render();
```
Output: `<a href="/path/to/go" target="_blank" class="btn btn-primary">Go</a>`

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
Output: `<div class="parent"><p id="child1">Regular</p><p id="child2"><span><strong>Bold Face</strong></span></p></div>`

### Compound Tags with Using Contents Extension 

```php
class DialogBox extends TagAbstract
{
    protected string $tagName = 'div';
    public Tag $heading;
    public Tag $button;

    public function __construct(string $tagName = null, array $extraAttrs = null)
    {
        parent::__construct($tagName, $extraAttrs);
        // Init named child(s).
        $this->heading = Tag::make('h4');
        $this->button = Tag::make('button');
    }
    public static function create(Tag|string $message, string $title, string $buttonCaption): static
    {
        $tag = static::make();
        $tag->contents(Tag::make('p')->contents($message))->class('dialog-box');
        $tag->heading->contents($title);
        $tag->button->contents($buttonCaption);
        return $tag;
    }
    public function contentsPrefixed(): array
    {
        // Prefix named child(s) to contents before render.
        return [$this->heading];
    }
    public function contentsSuffixed(): array
    {
        // Suffix named child(s) to contents before render.
        return [$this->button];
    }
    ...
}

echo DialogBox::create('Some message.', 'Notice', 'OK')->render();
```
Expected output: `<div class="dialog-box"><h4>Notice</h4><p>Some message.</p><button>OK</button></div>`

### More Examples
- See [`Demo::class`](src/Demo.php) for more examples.
- Run `php demo/demo.php` for demonstration in CLI.

## Limitations
1. `<script>` tag is not supported, obviously because of the security concerns.
2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
3. `<!doctype>` tag is not supported also. 
4. `<!-- comment -->` tag must be rendered with `Comment::class`.
5. Web content accessibility is not addressed, yet.
