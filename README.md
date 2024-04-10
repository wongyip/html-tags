# HTML Tags Renderer

A simple HTML tags renderer with fluent interface.
- NOT a rendering engine.
- Targeted to work with dynamic components that require programmatic customizations.

## Use Case
For example, you may use this to generate an ad hoc notice above the usual content of your website,
where the notice is not always needed, and something else could also be there for other purposes.
With this package, there is no need to bloat too much of logic into the template for some most likely
not rendered components. This keep you template clean and also keep certain components free from the
template engine. That could be a big help when you're needed to switch your rendering engine.

## Read This First
This package doesn't take care of security at all, if your application generates HTML from dynamic
data, especially user-contributed contents, be very careful to avoid something like XSS attack.
You should employ your own favour of HTML filtering tool, e.g. [HTML Purifier](https://github.com/ezyang/htmlpurifier).

**The World is Dangerous, Always Sanitize Generated HTML.**

## Installation
```sh
composer require wongyip/html-tags
```

## Usage

### Basic
```php
$div = new Tag('div');
$div->id('some-css-class')
$div->style('font-size: 2em;')
$div->contents('Example <div> tag with class & style attributes.');
echo $div->render();
```
```
<div id="some-css-class" style="font-size: 2em;">Example &lt;div&gt; tag with class &amp; style attributes.</div>
```
*The above output are not syntax highlighted to properly display the contents escaped by the `htmlspecialchars` function.*

### Various Coding Style

Tags may be rendered in different ways to fit into different scenarios.

```php
// Spell out everything if you care about who read your code.
$a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');

// When working with structural data like a data model.
$a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');

// Code a little less with tailor-made creator-function.
$a3 = Anchor::create('/go/3', 'Go 3', '_blank');

echo implode(PHP_EOL, [$a1->render(), $a2->render(), $a3->render()]);
```
```html
<a href="/go/1" target="_blank">Go 1</a>
<a href="/go/2" target="_blank">Go 2</a>
<a href="/go/3" target="_blank">Go 3</a>
```

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
```html
<div class="parent"><p id="child1">Regular</p><p id="child2"><span><strong>Bold Face</strong></span></p></div>
```

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
```html
<div class="dialog-box"><h4>Notice</h4><p>Some message.</p><button>OK</button></div>
```

### Comment
```php
echo Comment::make()
    ->contents('Comment ignores attributes set.')
    ->class('ignored')
    ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))
    ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))
    ->render();
```
```
<!-- Comment ignores attributes set.<div>Nested tag is fine.</div><!-- Nested comment ending brace is escaped. --&gt; -->
```
*The above output are not syntax highlighted to properly display the contents escaped by the `htmlspecialchars` function.*

### More Examples
- See [`Demo::class`](src/Demo/Demo.php) for more examples.
- Run `php demo/demo.php` for demonstration in CLI.

## Limitations
1. `<script>` tag is not supported, obviously because of the security concerns.
2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
3. `<!doctype>` tag is not supported also.
4. Web content accessibility is not addressed, yet.

## Format or Pretty-print HTML Tags
The package output everything in a single line, if formatted HTML is needed, you may utilize the
[HTML Beautify](https://github.com/wongyip/html-beautify) package to format the output of this
package.

---
**Always Sanitize Generated HTML.**