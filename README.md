# HTML Tags Renderer

A simple HTML renderer with fluent interface for generic purpose.

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

### Compound Tag Using Contents Extensions

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
Output: `<div class="dialog-box"><h4>Notice</h4><p>Some message.</p><button>OK</button></div>`

### HTML Comment
```php
echo Comment::make()
    ->contents('Comment ignores attributes set.')
    ->class('ignored')
    ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))
    ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))
    ->render();
```
Output: `<!-- Comment ignores attributes set.<div>Nested tag is fine.</div><!-- Nested comment ending brace is escaped. --&gt; -->`

### More Examples
- See [`Demo::class`](src/Demo/Demo.php) for more examples.
- Run `php demo/demo.php` for demonstration in CLI.

## Multiple Code Styles

A tag could be rendering in several ways to fit in different needs. 

```php
// Spell out everything if you care about who read your code.
$a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');

// When there is structural data (e.g. a data model), input attributes array maybe a good choice.
$a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');

// To code a little less.
$a3 = Anchor::create('/go/3', 'Go 3', '_blank');

echo implode(PHP_EOL, [$a1->render(), $a2->render(), $a3->render()]);
```
Output:
```html
<a href="/go/1" target="_blank">Go 1</a>
<a href="/go/2" target="_blank">Go  to fit in different needs>
<a href="/go/3" target="_blank">Go 3</a> in
``fferent needs`
 to fit di

## Limitations
1. `<script>` tag is not supported, obviously because of the security concerns.
2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
3. `<!doctype>` tag is not supported also.
4. Web content accessibility is not addressed, yet.
