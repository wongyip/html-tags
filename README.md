# HTML Tags Renderer

This package is aimed to provide a fast and simple way to render "some" HTML tags for generic purpose.

## Installation
```sh
composer require wongyip/html-tags
```

## Examples

### Usage 1
```php
use Wongyip\HTNL\Tag;
$div = new Tag('div');
$div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');
echo $div->render();
```
Expected output: `<div class="c1 c2">Example &lt;div&gt; tag with t1 &amp; t2 CSS classes.</div>`

### Usage 2
```php
use Wongyip\HTNL\Anchor;
echo Anchor::make()->classAdd('btn', 'btn-primary')->contents('OK')->render();
```
Expected output: `<a class="btn btn-primary">OK</a>`

---
More examples in [`Demo::class`](src/Demo.php).