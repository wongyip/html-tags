<?php /** @noinspection ALL */

namespace Wongyip\HTML\Demo;

use Throwable;
use Wongyip\HTML\Anchor;
use Wongyip\HTML\Comment;
use Wongyip\HTML\Tag;
use Wongyip\HTML\Utils\Output;

class Demo
{
    public function __construct(string $code, string $output)
    {
        echo sprintf("\nCode: \n\n%s\n\nOutput:\n\n%s\n", $code, $output);
    }

    /**
     * One-line syntax.
     *
     * @return void
     */
    public static function anchor(): void
    {
        new Demo(
            "Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents(Tag::make('span')->contents('Go')->style('color: green;'))->render()",
            Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents(Tag::make('span')->contents('Go')->style('color: green;'))->render()
        );
    }

    /**
     * Set multiple attributes at once.
     *
     * @return void
     */
    public static function attributes(): void
    {
        $code = <<<CODE
        \$setAttributes = [
            'href' => 'https://www.google.com',
            'class' => 'c1 c2 c3',
            'style' => 'color: red; font-weight: bold;',
            'contents' => 'Contents is not an attribute, this will be ignored.',
            'whatever-not-recognized' => 'Will be ignored, too.',
            'id' => 'tag1',
            'name' => 'anchor1',
        ];
        \$tag = Anchor::make()->attributes(\$setAttributes)->contents('Correct Link');
        echo \$tag->render()
        CODE;

        $setAttributes = [
            'href' => 'https://www.google.com',
            'class' => 'c1 c2 c3',
            'style' => 'color: red; font-weight: bold;',
            'contents' => 'Contents is not an attribute, this will be ignored.',
            'whatever-not-recognized' => 'Will be ignored, too.',
            'id' => 'tag1',
            'name' => 'anchor1',
        ];
        $tag = Anchor::make()->attributes($setAttributes)->contents('Correct Link');

        new Demo($code, $tag->render());
    }

    /**
     * Render a self-closing tag.
     *
     * @return void
     */
    public static function selfClosingTag(): void
    {
        new Demo(
            "echo Tag::make()->tagName('HR')->style('margin-bottom: 1rem;')->render();",
            Tag::make()->tagName('HR')->style('margin-bottom: 1rem;')->render()
        );
    }

    /**
     * Contents manipulation.
     *
     * @return void
     */
    public static function comment(): void
    {
        $code = <<<CODE
        \$tag = Comment::make()->contents('Comment ignores attributes set.')->class('ignored');
        \$tag->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'));
        \$tag->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'));
        echo \$tag->render();
        CODE;

        $tag = Comment::make()->contents('Comment ignores attributes set.')->class('ignored');
        $tag->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'));
        $tag->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'));

        new Demo($code, $tag->render());
    }

    /**
     * Contents manipulation.
     *
     * @return void
     */
    public static function contents(): void
    {
        new Demo(
            "echo Tag::make('div')->contents('C3', Tag::make('p')->contents('C4'))->contentsAppend(Tag::make('p')->contents('C5'))->contentsPrepend('C1', 'C2')->render();",
            Tag::make('div')->contents('C3', Tag::make('p')->contents('C4'))->contentsAppend(Tag::make('p')->contents('C5'))->contentsPrepend('C1', 'C2')->render()
        );
    }

    /**
     * Compound Tag
     *
     * @return void
     */
    public static function compound(): void
    {
        new Demo(
            "echo DialogBox::create('Some message.', 'Notice', 'OK')->render();",
            DialogBox::create('Some message.', 'Notice', 'OK')->render()
        );
    }

    /**
     * Errors
     *
     * @return void
     */
    public static function errors(): void
    {
        Output::header('Test Error 1: setting of static prop. via the __call() method.');
        try {
            echo "Core: \$tag = Tag::make()->commonAttrs(['mo', 'la']);" . PHP_EOL;
            $tag = Tag::make()->commonAttrs(['mo', 'la']);
        }
        catch (Throwable $e) {
            Output::error($e);
        }

        Output::header('Test Error 2: setting of prop. with name started with underscore.');
        try {
            echo "Core: \$tag = Tag::make()->__staticProps(['mo', 'la']);" . PHP_EOL;
            $tag = Tag::make()->__staticProps(['mo', 'la']);
        }
        catch (Throwable $e) {
            Output::error($e);
        }

        Output::header('Test Error 3: setting of non-existing property.');
        try {
            echo "Core: \$tag = Tag::make()->something('not exists');" . PHP_EOL;
            $tag = Tag::make()->something('not exists');
        }
        catch (Throwable $e) {
            Output::error($e);
        }

        echo PHP_EOL;
    }

    /**
     * Demo about tagName.
     *
     * @return void
     */
    public static function tagName(): void
    {
        new Demo(
            "echo Tag::make('p')->tagName('DIV')->tagName('script')->tagName('style')->tagName('wrong tag')->contents('A div tag is rendered finally.')->render();",
            Tag::make('p')->tagName('DIV')->tagName('script')->tagName('style')->tagName('wrong tag')->contents('A div tag is rendered finally.')->render()
        );
    }

    /**
     * Basic usage.
     *
     * @return void
     */
    public static function basic1(): void
    {
        $code = <<<CODE
        \$div = new Tag('div');
        \$div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');
        echo \$div->render();
        CODE;
        $div = new Tag('div');
        $div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');
        new Demo($code, $div->render());
    }

    /**
     * One-line syntax.
     *
     * @return void
     */
    public static function basic2(): void
    {
        new Demo(
            "echo Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render();",
            Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render()
        );
    }

    /**
     * @return void
     */
    public static function style(): void
    {
        $code = <<<CODE
        \$tag = Tag::make()->contents('Testing');
        \$tag->style('margin: 10px; font-size: 2em; color: green; --empty-rule: ; wrong rule: ignored;');
        \$tag->stylePrepend('font-size: 1em; color: red');
        \$tag->styleAppend('font-size: 3rem;', 'color: blue;');
        \$tag->styleUnset('margin');
        echo \$tag->render();
        CODE;

        $tag = Tag::make()->contents('Testing');
        $tag->style('margin: 10px; font-size: 2em; color: green; --empty-rule: ; wrong rule: ignored;');
        $tag->stylePrepend('font-size: 1em; color: red');
        $tag->styleAppend('font-size: 3rem;', 'color: blue;');
        $tag->styleUnset('margin');

        new Demo(
            $code,
            $tag->render()
        );
    }

    /**
     * @return void
     */
    public static function nested(): void
    {
        $code = <<<CODE
        \$tag = Tag::make('div')->class('parent')->contents(
            Tag::make('p')->id('child1')->contents('Regular'),
            Tag::make('p')->id('child2')->contents(
                Tag::make('span')->contents(
                    Tag::make('strong')->contents('Bold Face')
                )
            )
        );
        echo \$tag->render();
        CODE;

        $tag = Tag::make('div')->class('parent')->contents(
            Tag::make('p')->id('child1')->contents('Regular'),
            Tag::make('p')->id('child2')->contents(
                Tag::make('span')->contents(
                    Tag::make('strong')->contents('Bold Face')
                )
            )
        );

        new Demo(
            $code,
            $tag->render()
        );
    }
}

