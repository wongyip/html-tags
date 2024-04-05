[1mdiff --git a/README.md b/README.md[m
[1mindex d0a00c6..5c4ab74 100644[m
[1m--- a/README.md[m
[1m+++ b/README.md[m
[36m@@ -1,6 +1,6 @@[m
 # HTML Tags Renderer[m
 [m
[31m-An intuitive and simple HTML renderer for generic purpose.[m
[32m+[m[32mA simple HTML renderer with fluent interface for generic purpose.[m[41m[m
 [m
 ## Installation[m
 ```sh[m
[36m@@ -81,21 +81,43 @@[m [mOutput: `<div class="dialog-box"><h4>Notice</h4><p>Some message.</p><button>OK</[m
 [m
 ### HTML Comment[m
 ```php[m
[31m-$tag = Comment::make()[m
[31m-    ->contents('Comment tag ignores the tagName property & all other attributes. ')[m
[31m-    ->tagName('div')[m
[31m-    ->class('ignored-class')[m
[31m-    ->contentsAppend([m
[31m-        Tag::make('div')->contents('Nested tag is allowed in comment.')[m
[31m-    );[m
[31m-echo $tga->render();[m
[32m+[m[32mecho Comment::make()[m[41m[m
[32m+[m[32m    ->contents('Comment ignores attributes set.')[m[41m[m
[32m+[m[32m    ->class('ignored')[m[41m[m
[32m+[m[32m    ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))[m[41m[m
[32m+[m[32m    ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))[m[41m[m
[32m+[m[32m    ->render();[m[41m[m
 ```[m
[31m-Output: `<!-- Comment tag ignores the tagName property &amp; all other attributes. <div>Nested tag is allowed in comment.</div> -->`[m
[32m+[m[32mOutput: `<!-- Comment ignores attributes set.<div>Nested tag is fine.</div><!-- Nested comment ending brace is escaped. --&gt; -->`[m[41m[m
 [m
 ### More Examples[m
[31m-- See [`Demo::class`](src/Demo.php) for more examples.[m
[32m+[m[32m- See [`Demo::class`](src/Demo/Demo.php) for more examples.[m[41m[m
 - Run `php demo/demo.php` for demonstration in CLI.[m
 [m
[32m+[m[32m## Multiple Code Styles[m[41m[m
[32m+[m[41m[m
[32m+[m[32mA tag could be rendering in several ways to fit in different needs.[m[41m [m
[32m+[m[41m[m
[32m+[m[32m```php[m[41m[m
[32m+[m[32m// Spell out everything if you care about who read your code.[m[41m[m
[32m+[m[32m$a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');[m[41m[m
[32m+[m[41m[m
[32m+[m[32m// When there is structural data (e.g. a data model), input attributes array maybe a good choice.[m[41m[m
[32m+[m[32m$a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');[m[41m[m
[32m+[m[41m[m
[32m+[m[32m// To code a little less.[m[41m[m
[32m+[m[32m$a3 = Anchor::create('/go/3', 'Go 3', '_blank');[m[41m[m
[32m+[m[41m[m
[32m+[m[32mecho implode(PHP_EOL, [$a1->render(), $a2->render(), $a3->render()]);[m[41m[m
[32m+[m[32m```[m[41m[m
[32m+[m[32mOutput:[m[41m[m
[32m+[m[32m```html[m[41m[m
[32m+[m[32m<a href="/go/1" target="_blank">Go 1</a>[m[41m[m
[32m+[m[32m<a href="/go/2" target="_blank">Go  to fit in different needs>[m[41m[m
[32m+[m[32m<a href="/go/3" target="_blank">Go 3</a> in[m[41m[m
[32m+[m[32m``fferent needs`[m[41m[m
[32m+[m[32m to fit di[m[41m[m
[32m+[m[41m[m
 ## Limitations[m
 1. `<script>` tag is not supported, obviously because of the security concerns.[m
 2. `<style>` tag is neither supported as `htmlspecialchars()` might unintentionally break the styles.[m
[1mdiff --git a/src/Anchor.php b/src/Anchor.php[m
[1mindex 26a2e88..efc1962 100644[m
[1m--- a/src/Anchor.php[m
[1m+++ b/src/Anchor.php[m
[36m@@ -54,8 +54,25 @@[m [mclass Anchor extends TagAbstract[m
      *[m
      * @return array|string[][m
      */[m
[31m-    protected function addAttrs(): array[m
[32m+[m[32m    public function addAttrs(): array[m
     {[m
[31m-        return ['href', 'title', 'target'];[m
[32m+[m[32m        return ['href', 'target', 'title'];[m
     }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Create an Anchor tag.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @param string $href[m
[32m+[m[32m     * @param string $caption[m
[32m+[m[32m     * @param string|null $target[m
[32m+[m[32m     * @param string|null $title[m
[32m+[m[32m     * @return Anchor[m
[32m+[m[32m     */[m
[32m+[m[32m    public static function create(string $href, string $caption, string $target = null, string $title = null): Anchor[m
[32m+[m[32m    {[m
[32m+[m[32m        return Anchor::make()[m
[32m+[m[32m            ->attributes(compact('href', 'target', 'title'))[m
[32m+[m[32m            ->contents($caption);[m
[32m+[m[32m    }[m
[32m+[m
 }[m
\ No newline at end of file[m
[1mdiff --git a/src/Comment.php b/src/Comment.php[m
[1mindex f6f4841..d021f57 100644[m
[1m--- a/src/Comment.php[m
[1m+++ b/src/Comment.php[m
[36m@@ -23,7 +23,7 @@[m [mclass Comment extends TagAbstract[m
      * @return array|string[][m
      * @deprecated[m
      */[m
[31m-    protected function addAttrs(): array[m
[32m+[m[32m    public function addAttrs(): array[m
     {[m
         return [];[m
     }[m
[1mdiff --git a/src/Demo/Demo.php b/src/Demo/Demo.php[m
[1mindex c52cfec..70a72b8 100644[m
[1m--- a/src/Demo/Demo.php[m
[1m+++ b/src/Demo/Demo.php[m
[36m@@ -16,21 +16,39 @@[m [mclass Demo[m
     }[m
 [m
     /**[m
[31m-     * One-line syntax.[m
[31m-     *[m
      * @return void[m
      */[m
     public static function anchor(): void[m
     {[m
[32m+[m[32m        $code = <<<CODE[m
[32m+[m[32m        // Spell out everything if you care about who read your code.[m
[32m+[m[32m        \$a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');[m
[32m+[m
[32m+[m[32m        // When there is structural data (e.g. a data model), input attributes array maybe a good choice.[m
[32m+[m[32m        \$a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');[m
[32m+[m
[32m+[m[32m        // Code a little less.[m
[32m+[m[32m        \$a3 = Anchor::create('/go/3', 'Go 3', '_blank');[m
[32m+[m
[32m+[m[32m        echo implode(PHP_EOL, [\$a1->render(), \$a2->render(), \$a3->render()]);[m
[32m+[m[32m        CODE;[m
[32m+[m
[32m+[m[32m        // Spell out everything if you care about who read your code.[m
[32m+[m[32m        $a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');[m
[32m+[m
[32m+[m[32m        // When there is structural data (e.g. a data model), input attributes array maybe a good choice.[m
[32m+[m[32m        $a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');[m
[32m+[m
[32m+[m[32m        // To code a little less.[m
[32m+[m[32m        $a3 = Anchor::create('/go/3', 'Go 3', '_blank');[m
[32m+[m
         new Demo([m
[31m-            "Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents(Tag::make('span')->contents('Go')->style('color: green;'))->render()",[m
[31m-            Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents(Tag::make('span')->contents('Go')->style('color: green;'))->render()[m
[32m+[m[32m            $code,[m
[32m+[m[32m            implode(PHP_EOL, [$a1->render(), $a2->render(), $a3->render()])[m
         );[m
     }[m
 [m
     /**[m
[31m-     * Set multiple attributes at once.[m
[31m-     *[m
      * @return void[m
      */[m
     public static function attributes(): void[m
[36m@@ -64,8 +82,6 @@[m [mclass Demo[m
     }[m
 [m
     /**[m
[31m-     * Render a self-closing tag.[m
[31m-     *[m
      * @return void[m
      */[m
     public static function selfClosingTag(): void[m
[36m@@ -77,31 +93,45 @@[m [mclass Demo[m
     }[m
 [m
     /**[m
[31m-     * Contents manipulation.[m
[31m-     *[m
      * @return void[m
      */[m
     public static function comment(): void[m
     {[m
         $code = <<<CODE[m
[31m-        \$tag = Comment::make()->contents('Comment ignores attributes set.')->class('ignored');[m
[31m-        \$tag->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'));[m
[31m-        \$tag->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'));[m
[31m-        echo \$tag->render();[m
[32m+[m[32m        echo Comment::make()[m
[32m+[m[32m            ->contents('Comment ignores attributes set.')[m
[32m+[m[32m            ->class('ignored')[m
[32m+[m[32m            ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))[m
[32m+[m[32m            ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))[m
[32m+[m[32m            ->render();[m
         CODE;[m
[31m-[m
[31m-        $tag = Comment::make()->contents('Comment ignores attributes set.')->class('ignored');[m
[31m-        $tag->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'));[m
[31m-        $tag->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'));[m
[31m-[m
[31m-        new Demo($code, $tag->render());[m
[32m+[m[32m        new Demo([m
[32m+[m[32m            $code,[m
[32m+[m[32m            Comment::make()[m
[32m+[m[32m                ->contents('Comment ignores attributes set.')[m
[32m+[m[32m                ->class('ignored')[m
[32m+[m[32m                ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))[m
[32m+[m[32m                ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))[m
[32m+[m[32m                ->render()[m
[32m+[m[32m        );[m
     }[m
 [m
     /**[m
[31m-     * Contents manipulation.[m
[32m+[m[32m     * Compound Tag[m
      *[m
      * @return void[m
      */[m
[32m+[m[32m    public static function compound(): void[m
[32m+[m[32m    {[m
[32m+[m[32m        new Demo([m
[32m+[m[32m            "echo DialogBox::create('Some message.', 'Notice', 'OK')->render();",[m
[32m+[m[32m            DialogBox::create('Some message.', 'Notice', 'OK')->render()[m
[32m+[m[32m        );[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * @return void[m
[32m+[m[32m     */[m
     public static function contents(): void[m
     {[m
         new Demo([m
[36m@@ -111,21 +141,17 @@[m [mclass Demo[m
     }[m
 [m
     /**[m
[31m-     * Compound Tag[m
[31m-     *[m
      * @return void[m
      */[m
[31m-    public static function compound(): void[m
[32m+[m[32m    public static function innerHTML(): void[m
     {[m
         new Demo([m
[31m-            "echo DialogBox::create('Some message.', 'Notice', 'OK')->render();",[m
[31m-            DialogBox::create('Some message.', 'Notice', 'OK')->render()[m
[32m+[m[32m            "echo Tag::make('div')->contents(Anchor::create('/path/to/there', 'Go', 'There', '_blank'))->innerHTML;",[m
[32m+[m[32m            Tag::make('div')->contents(Anchor::create('/path/to/there', 'Go', 'There', '_blank'))->innerHTML[m
         );[m
     }[m
 [m
     /**[m
[31m-     * Errors[m
[31m-     *[m
      * @return void[m
      */[m
     public static function errors(): void[m
[36m@@ -161,8 +187,6 @@[m [mclass Demo[m
     }[m
 [m
     /**[m
[31m-     * Demo about tagName.[m
[31m-     *[m
      * @return void[m
      */[m
     public static function tagName(): void[m
[1mdiff --git a/src/Demo/DialogBox.php b/src/Demo/DialogBox.php[m
[1mindex a29c98f..e6c818b 100644[m
[1m--- a/src/Demo/DialogBox.php[m
[1m+++ b/src/Demo/DialogBox.php[m
[36m@@ -58,7 +58,7 @@[m [mclass DialogBox extends TagAbstract[m
         return [$this->button];[m
     }[m
 [m
[31m-    protected function addAttrs(): array[m
[32m+[m[32m    public function addAttrs(): array[m
     {[m
         return [];[m
     }[m
[1mdiff --git a/src/Tag.php b/src/Tag.php[m
[1mindex a4bbb4d..7160a01 100644[m
[1m--- a/src/Tag.php[m
[1m+++ b/src/Tag.php[m
[36m@@ -24,7 +24,7 @@[m [mclass Tag extends TagAbstract[m
      *[m
      * @return array|string[][m
      */[m
[31m-    protected function addAttrs(): array[m
[32m+[m[32m    public function addAttrs(): array[m
     {[m
         return [];[m
     }[m
[1mdiff --git a/src/TagAbstract.php b/src/TagAbstract.php[m
[1mindex 67ef0b2..853ab3a 100644[m
[1m--- a/src/TagAbstract.php[m
[1m+++ b/src/TagAbstract.php[m
[36m@@ -5,6 +5,7 @@[m [mnamespace Wongyip\HTML;[m
 use Exception;[m
 use ReflectionClass;[m
 use Throwable;[m
[32m+[m[32muse Wongyip\HTML\Traits\Attributes;[m
 use Wongyip\HTML\Traits\Contents;[m
 use Wongyip\HTML\Traits\CssClass;[m
 use Wongyip\HTML\Traits\CssStyle;[m
[36m@@ -17,11 +18,11 @@[m [muse Wongyip\HTML\Traits\CssStyle;[m
  * @method string|static name(string|null $value = null)[m
  *[m
  * Properties Get-setters[m
[31m- * -- None at the moment.[m
[32m+[m[32m * @property string $innerHTML[m
  */[m
 abstract class TagAbstract[m
 {[m
[31m-    use Contents, CssClass, CssStyle;[m
[32m+[m[32m    use Attributes, Contents, CssClass, CssStyle;[m
 [m
     /**[m
      * Ultimate default tagName.[m
[36m@@ -35,13 +36,6 @@[m [mabstract class TagAbstract[m
      * @var array|string[][m
      */[m
     protected array $__staticProps;[m
[31m-    /**[m
[31m-     * Internal storage of attributes listed in $tagAttrs, which have value set[m
[31m-     * already (excluding attributes listed in $complexAttrs).[m
[31m-     *[m
[31m-     * @var array|string[][m
[31m-     */[m
[31m-    protected array $attrsStore = [];[m
     /**[m
      * These are attributes present in all tags.[m
      *[m
[36m@@ -152,50 +146,25 @@[m [mabstract class TagAbstract[m
     }[m
 [m
     /**[m
[31m-     * Tag attributes in addition to common attributes. Every child tag object[m
[31m-     * should extend this method to provide a list of supported attributes.[m
[31m-     *[m
[31m-     * @return array|string[][m
[32m+[m[32m     * @param string $name[m
[32m+[m[32m     * @return string[m
[32m+[m[32m     * @throws Exception[m
      */[m
[31m-    abstract protected function addAttrs(): array;[m
[32m+[m[32m    public function __get(string $name)[m
[32m+[m[32m    {[m
[32m+[m[32m        if ($name === 'innerHTML') {[m
[32m+[m[32m            return $this->contentsRendered();[m
[32m+[m[32m        }[m
[32m+[m[32m        throw new Exception(sprintf('Undefined property %s.', $name));[m
[32m+[m[32m    }[m
 [m
     /**[m
[31m-     * Get or set all tag attributes.[m
[31m-     *[m
[31m-     * Notes:[m
[31m-     *  1. Not a direct get-setter.[m
[31m-     *  2. Unrecognized attributes are ignored (not in $tagAttrs, nor $complexAttrs).[m
[31m-     *  3. Tag "contents" is NOT an attribute.[m
[32m+[m[32m     * Tag attributes in addition to common attributes. Every child tag object[m
[32m+[m[32m     * should extend this method to provide a list of supported attributes.[m
      *[m
[31m-     * @param array|null $attributes[m
[31m-     * @return array|static[m
[32m+[m[32m     * @return array|string[][m
      */[m
[31m-    public function attributes(array $attributes = null) : array|static[m
[31m-    {[m
[31m-        if ($attributes) {[m
[31m-            foreach ($attributes as $setter => $val) {[m
[31m-                try {[m
[31m-                    if (in_array($setter, $this->tagAttrs) || in_array($setter, static::$complexAttrs)){[m
[31m-                        $this->$setter($val);[m
[31m-                    }[m
[31m-                    else {[m
[31m-                        error_log(sprintf('TagAbstract.attributes() - Unrecognized attribute "%s"', $setter));[m
[31m-                    }[m
[31m-                }[m
[31m-                catch (Throwable $e) {[m
[31m-                    error_log(sprintf('TagAbstract.attributes() - Error: %s (%d)', $e->getMessage(), $e->getCode()));[m
[31m-                }[m
[31m-            }[m
[31m-            return $this;[m
[31m-        }[m
[31m-        $attributes = $this->attrsStore;[m
[31m-        foreach (static::$complexAttrs as $getter) {[m
[31m-            if ($val = $this->$getter()) {[m
[31m-                $attributes[$getter] = $val;[m
[31m-            }[m
[31m-        }[m
[31m-        return $attributes;[m
[31m-    }[m
[32m+[m[32m    abstract public function addAttrs(): array;[m
 [m
     /**[m
      * Closing tag.[m
[36m@@ -227,7 +196,7 @@[m [mabstract class TagAbstract[m
      *[m
      * Notes:[m
      *  1. Overwrite class-defined tagName if $tagName is provided.[m
[31m-     *  2. Merge in to commonAttrs and addAttrs if $extraAttrs is provided.[m
[32m+[m[32m     *  2. Merge into commonAttrs and addAttrs if $extraAttrs is provided.[m
      *[m
      * @param string|null $tagName[m
      * @param array|null $extraAttrs[m
[1mdiff --git a/src/Traits/CssClass.php b/src/Traits/CssClass.php[m
[1mindex 89acc0e..3e152cd 100644[m
[1m--- a/src/Traits/CssClass.php[m
[1m+++ b/src/Traits/CssClass.php[m
[36m@@ -23,10 +23,13 @@[m [mtrait CssClass[m
      * @param string|array|null $class[m
      * @return string|static[m
      */[m
[31m-    public function class(string|array $class = null): string|static[m
[32m+[m[32m    public function class(string|array ...$class): string|static[m
     {[m
[31m-        if ($class) {[m
[31m-            $this->cssClasses = is_array($class) ? $class : explode(' ', $class);[m
[32m+[m[32m        if (!empty($class)) {[m
[32m+[m[32m            $this->cssClasses = [];[m
[32m+[m[32m            foreach ($class as $c) {[m
[32m+[m[32m                $this->cssClasses = array_merge($this->cssClasses, is_array($c) ? $c : explode(' ', $c));[m
[32m+[m[32m            }[m
             return $this;[m
         }[m
         return implode(' ', $this->classes());[m
