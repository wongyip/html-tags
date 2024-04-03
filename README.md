# HTML Tags Renderer

This package is aimed to provide a fast and simple way to render "some" HTML tags for generic purpose.

## Installation
```sh
composer require wongyip/html-tags
```

## Usage

### Example 1
```php
use Wongyip\HTML\Tag;
$div = new Tag('div');
$div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');
echo $div->render();
```
Expected output: `<div class="c1 c2">Example &lt;div&gt; tag with t1 &amp; t2 CSS classes.</div>`

### Example 2
```php
use Wongyip\HTML\Anchor;
echo Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render(),
```
Expected output: `<a href="/path/to/go" target="_blank" class="btn btn-primary">Go</a>`

*See [`Demo::class`](src/Demo.php) for more examples.*

## Limitations
1. `<script>` tag is not supported, obviously because of the security concerns.
2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.
3. `<!doctype>` tag is not supported also. 
4. `<!-- comment -->` tag must be rendered with `Comment::class`.
5. Web content accessibility is not addressed, yet.
