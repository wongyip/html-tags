# HTML Tags Renderer
A simple HTML renderer with fluent interface to generate dynamic components. Those generated tags are
self-rendered, so it is not necessary to build extra logic into templates to handle them, which helps
to reduce the complexity of templates and also make certain components independent of template
engines.  

## Read This First
This package doesn't take cares of security at all, if your application generates HTML from dynamic
data, especially user-contributed contents, be very careful to avoid something like XSS attack. You
should always employ your own favour of HTML filtering tool, e.g. [HTML Purifier](https://github.com/ezyang/htmlpurifier).

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

### Nesting
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


### Contents Collections

Tags extending the `TagAbstract` will have a several `ContentsCollection` properties, which are empty on instantiate,
and accessible in public scope. You may make use of them to manipulate all the contents of a tag.

- `TagAbstract::$siblingsBefore` - sibling tags with the same nesting level of the tag, rendered before the tag. 
- `TagAbstract::$contentsPrefixed` - inner contents on top of the tag.
- `TagAbstract::$contents` - inner contents of the tag.
- `TagAbstract::$contentsSuffixed` - inner contents at the bottom of the tag.
- `TagAbstract::$siblingsAfter` - sibling tags with the same nesting level of the tag, rendered after the tag.


### Extensible Contents

For scenario of tags with conditional inner contents, you may extend the following methods to handle the needs:
- `TagAbstract::contentsBefore()`, that output a `ContentsCollection` for render right before the tag's contents.
- `TagAbstract::contentsAfter()` that output a `ContentsCollection` for render right after the tag's contents.

For example, the build-in [`Table`](/src/Table.php) tag extended the `contentsBefore()` method to insert its
`caption`, `thead` and `tbody` when they're set.

### Contents Anatomy, Concluded
- Siblings Before the Tag
  - Prefixed Contents
  - Extensible Contents (Before)
  - Contents
  - Extensible Contents (After)
  - Suffixed Contents
- Siblings After the Tag

The following example illustrates the above anatomy excepts of the Extensible Contents (Before and After):

```php
$tag = Tag::make('div');
$tag->contents->append(Tag::make('p')->contents('Content 1'), Tag::make('p')->contents('Content N'));
$tag->contentsPrefixed->append(Tag::make('h1')->contents('Prefixed 1'), Tag::make('h2')->contents('Prefixed N'));
$tag->contentsSuffixed->append(Tag::make('h3')->contents('Suffixed 1'), Tag::make('h4')->contents('Suffixed N'));
$tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before 1'));
$tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before N'));
$tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After 1'));
$tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After N'));
echo $tag->render();
```
```html
<div>Sibling Before 1</div>
<div>Sibling Before N</div>
<div>
    <h1>Prefixed 1</h1>
    <h2>Prefixed N</h2>
    <p>Content 1</p>
    <p>Content N</p>
    <h3>Suffixed 1</h3>
    <h4>Suffixed N</h4>
</div>
<div>Sibling After 1</div>
<div>Sibling After N</div>
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
1. This is NOT a rendering engine.
2. `<script>` tag is not supported, obviously because of the security concerns.
3. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
4. `<!doctype>` tag is not supported also.
5. Web content accessibility is not addressed, yet.
6. Tags are rendered into a single line, if formatted HTML is needed, give [HTML Beautify](https://github.com/wongyip/html-beautify) a try.

---
**Always Sanitize Generated HTML.**