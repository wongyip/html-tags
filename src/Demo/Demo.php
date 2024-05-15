<?php /** @noinspection ALL */

namespace Wongyip\HTML\Demo;

use Throwable;
use Wongyip\HTML\Anchor;
use Wongyip\HTML\Comment;
use Wongyip\HTML\Form;
use Wongyip\HTML\Table;
use Wongyip\HTML\Tag;
use Wongyip\HTML\TBody;
use Wongyip\HTML\TD;
use Wongyip\HTML\TH;
use Wongyip\HTML\THead;
use Wongyip\HTML\TR;
use Wongyip\HTML\Utils\Output;

class Demo
{
    public function __construct(string $code, string $output)
    {
        echo sprintf("\nCode: \n\n%s\n\nOutput:\n\n%s\n", $code, $output);
    }

    /**
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
     * @return void
     */
    public static function codingStyles(): void
    {
        $code = <<<CODE
        // Spell out everything if you care about who read your code.
        \$a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');

        // When working with structural data like a data model.
        \$a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');

        // Code a little less with tailor-made creator-function.
        \$a3 = Anchor::create('/go/3', 'Go 3', '_blank');

        echo implode(PHP_EOL, [\$a1->render(), \$a2->render(), \$a3->render()]);
        CODE;

        // Spell out everything if you care about who read your code.
        $a1 = Anchor::make()->href('/go/1')->target('_blank')->contents('Go 1');

        // When there is structural data (e.g. a data model), input attributes array maybe a good choice.
        $a2 = Anchor::make()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');

        // To code a little less.
        $a3 = Anchor::create('/go/3', 'Go 3', '_blank');

        new Demo(
            $code,
            implode(PHP_EOL, [$a1->render(), $a2->render(), $a3->render()])
        );
    }

    /**
     * @return void
     */
    public static function comment(): void
    {
        $code = <<<CODE
        echo Comment::make()
            ->contents('Comment ignores attributes set.')
            ->class('ignored')
            ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))
            ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))
            ->render();
        CODE;
        new Demo(
            $code,
            Comment::make()
                ->contents('Comment ignores attributes set.')
                ->class('ignored')
                ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))
                ->contentsAppend(Comment::make()->contents('Nested comment ending brace is escaped.'))
                ->render()
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
     * @return void
     */
    public static function innerHTML(): void
    {
        new Demo(
            "echo Tag::make('div')->contents(Anchor::create('/path/to/there', 'Go', 'There', '_blank'))->innerHTML;",
            Tag::make('div')->contents(Anchor::create('/path/to/there', 'Go', 'There', '_blank'))->innerHTML
        );
    }

    /**
     * @return void
     */
    public static function innerText(): void
    {
        new Demo(
            "echo Tag::make('div')->contents('C3', Tag::make('p')->contents('C4'))->contentsAppend(Tag::make('p')->contents('C5'))->contentsPrepend('C1', 'C2')->innerHTML;",
            Tag::make('div')
                ->contents('C3', Tag::make('p')->contents('C4'))
                ->contentsAppend(Tag::make('p')->contents('C5'))
                ->contentsPrepend('C1', 'C2')->innerText
        );
    }

    /**
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
     * @return void
     */
    public static function form(): void
    {
        new Demo(
            "Form::post('upload.php', 'upload_form', 'green-form big-font')->enableUpload()->render();",
            Form::post('upload.php', 'upload_form', 'green-form big-font')->enableUpload()->render()
        );
    }

    /**
     * @return void
     */
    public static function table(): void
    {
        $code = <<<CODE
        EDIT...
        CODE;

        $tag = Table::create(
            THead::create(TR::create(TH::create('Header 1'), TH::create('Headee 2'))),
            TBody::create(TR::create(TD::create('Data 1'), TD::create('Data 2'))),
            'Test Table'
        );


        new Demo(
            $code,
            $tag->render()
        );
    }


    /**
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
    public static function basic(): void
    {
        $code = <<<CODE
        \$div = new Tag('div');
        \$div->id('some-css-class')
        \$div->style('font-size: 2em;')
        \$div->contents('Example <div> tag with class & style attributes.');
        echo \$div->render();
        CODE;
        $div = new Tag('div');
        $div->id('some-css-class');
        $div->style('font-size: 2em;');
        $div->contents('Example <div> tag with class & style attributes.');
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

