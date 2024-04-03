<?php

namespace Wongyip\HTML\Demo;

use Throwable;
use Wongyip\HTML\Anchor;
use Wongyip\HTML\Comment;
use Wongyip\HTML\Tag;
use Wongyip\HTML\Utils\Output;

class Demo
{
    /**
     * One-line syntax.
     *
     * @return void
     */
    public static function anchor(): void
    {
        echo sprintf(
            "Code: %s\nOutput: %s\n",
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
        print_r([
            '$setAttributes' => $setAttributes,
            'Anchor::make()->attributes($setAttributes)->contents(\'Correct Link\')' => $tag,
            '$tag->attributes()' => $tag->attributes(),
            '$tag->render()' => $tag->render(),
        ]);
    }

    /**
     * Render a self-closing tag.
     *
     * @return void
     */
    public static function selfClosingTag(): void
    {
        print_r([
            'code' => "Tag::make()->tagName('HR')->style('margin-bottom: 1rem;')->render()",
            'output' => Tag::make()->tagName('HR')->style('margin-bottom: 1rem;')->render(),
        ]);
    }

    /**
     * Contents manipulation.
     *
     * @return void
     */
    public static function comment(): void
    {
        $tag = Comment::make()->contents('Comment tag ignores the tagName property & all other attributes. ')->tagName('div')->class('skipped-class');
        $tag->contentsAppend(Tag::make('div')->contents('Nested tag is allowed in comment.'));
        print_r([
            'line 1' => "\$tag = Comment::make()->contents('Comment tag ignores the tagName property & all other attributes. ')->tagName('div')->class('skipped-class')",
            'line 2' => "\$tag->contentsAppend(Tag::make('div')->contents('Nested tag is allowed in comment.'));",
            '$tag->render()' => $tag->render(),
        ]);
    }

    /**
     * Contents manipulation.
     *
     * @return void
     */
    public static function contents(): void
    {
        echo sprintf(
            "Code: %s\nOutput: %s\n",
            "Tag::make('div')->contents('C3 ', Tag::make('p')->contents('C4 '))->contentsAppend(Tag::make('p')->contents('C5 '))->contentsPrepend('C1 ', 'C2 ')->render()",
            Tag::make('div')->contents('C3 ', Tag::make('p')->contents('C4 '))->contentsAppend(Tag::make('p')->contents('C5 '))->contentsPrepend('C1 ', 'C2 ')->render()
        );
    }

    /**
     * Compound Tag
     *
     * @return void
     */
    public static function compound(): void
    {
        echo sprintf(
            "Code:\n\n%s\n\nOutput:\n\n%s\n\n",
            "Section::make()->contents(Tag::make('p')->contents('Paragraph 1'), Tag::make('p')->contents('Paragraph 2'))->render()",
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
        print_r([
            'code' => "Tag::make('p')->tagName('DIV')->tagName('script')->tagName('style')->tagName('wrong tag')->contents('A div tag is rendered finally.')->render();",
            'output' => Tag::make('p')->tagName('DIV')->tagName('script')->tagName('style')->tagName('wrong tag')->contents('A div tag is rendered finally.')->render(),
        ]);
    }

    /**
     * Basic usage.
     *
     * @return void
     */
    public static function basic(): void
    {
        $div = new Tag('div');
        $div->class('c1 c2')->contents('Example <div> tag with t1 & t2 CSS classes.');

        print_r([
            'code' => '$div = new Tag(\'div\'); $div->class(\'c1, c2\')->style(\'width: 50%\')->contents(\'Example <div> tag with t1 & t2 CSS classes.\')->render();',
            'output' => $div->render(),
        ]);

    }

    /**
     * One-line syntax.
     *
     * @return void
     */
    public static function example2(): void
    {
        print_r([
            'code' => "Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render();",
            'output' => Anchor::make()->href('/path/to/go')->targetBlank()->classAdd('btn', 'btn-primary')->contents('Go')->render(),
        ]);
    }

    /**
     * @return void
     */
    public static function cssStyle(): void
    {
        $tag = Tag::make()->contents('Testing');
        $tag->style('margin: 10px; font-size: 2em; color: green; --empty-rule: ; wrong rule: ignored;');
        $tag->stylePrepend('font-size: 1em; color: red');
        $tag->styleAppend('font-size: 3rem;', 'color: blue;');
        $tag->styleUnset('margin');

        echo "Code:" . PHP_EOL;
        echo "\$tag = Tag::make()->contents('Testing');" . PHP_EOL;
        echo "\$tag->style('margin: 10px; font-size: 2em; color: green; --empty-rule: ; wrong rule: ignored;');" . PHP_EOL;
        echo "\$tag->stylePrepend('font-size: 1em; color: red');" . PHP_EOL;
        echo "\$tag->styleAppend('font-size: 3rem;', 'color: blue;');" . PHP_EOL;
        echo "\$tag->styleUnset('margin');" . PHP_EOL;
        echo "echo \$tag->style();" . PHP_EOL;
        echo "echo \$tag->render();" . PHP_EOL;

        echo PHP_EOL ;

        echo "Output:" . PHP_EOL;
        echo $tag->style() . PHP_EOL;
        echo $tag->render() . PHP_EOL;
        echo PHP_EOL;
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
        echo sprintf(
            "Code:\n\n%s\n\nOutput:\n\n%s\n\n",
            $code,
            $tag->render()
        );
    }
}

