<?php /** @noinspection ALL */

namespace Wongyip\HTML\Demo;

use Throwable;
use Wongyip\HTML\Anchor;
use Wongyip\HTML\Button;
use Wongyip\HTML\Comment;
use Wongyip\HTML\Div;
use Wongyip\HTML\Form;
use Wongyip\HTML\Input;
use Wongyip\HTML\Label;
use Wongyip\HTML\Option;
use Wongyip\HTML\RawHTML;
use Wongyip\HTML\Select;
use Wongyip\HTML\Supports\ContentsCollection;
use Wongyip\HTML\Table;
use Wongyip\HTML\Tag;
use Wongyip\HTML\TagAbstract;
use Wongyip\HTML\TBody;
use Wongyip\HTML\TD;
use Wongyip\HTML\Textarea;
use Wongyip\HTML\TH;
use Wongyip\HTML\THead;
use Wongyip\HTML\TR;
use Wongyip\HTML\Utils\Convert;
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
    public static function attribute(): void
    {
        $code = <<<CODE
        \$tag = Tag::make('div')->contents('I am a DIV.');

        // Basic
        \$tag->attribute('id', 'basic');

        // Complex (which is handle with specific setter).
        \$tag->attribute('class', 'class-added-with-attribute-method');

        // Simply ignored.
        \$tag->attribute('stranger', 'No recognized attribite are ignore.');

        // Make it custom with force.
        \$tag->attribute('Custom Attribute', 'Added with force.', true);

        // Force is no longer needed.
        \$tag->attribute('Custom Attribute', 'May the force be with you.');
        
        echo \$tag->render();
        CODE;

        $tag = Tag::make('div')->contents('I am a DIV.');

        // Basic
        // $tag->attribute('id', 'basic');

        // Complex (which is handle with specific setter).
        $tag->attribute('class', 'class-added-with-attribute-method');

        // Simply ignored.
        $tag->attribute('stranger', 'No recognized attribite are ignore.');

        // Make it custom with force.
        $tag->attribute('Custom Attribute', 'Added with force.', true);

        // Force is no longer needed.
        $tag->attribute('Custom Attribute', 'May the force be with you.');

        new Demo($code, $tag->render());
    }

    /**
     * @return void
     */
    public static function attribute2(): void
    {
        $code = <<<CODE
        \$tag = Tag::make('div')->contents('I am a DIV.');
        \$tag->attribute('class', ['added-via', 'attribute-method']);
        \$tag->attribute('style', ['no: no', 'no: way']);
        echo \$tag-=>render();
        CODE;

        $tag = Tag::make('div')->contents('I am a DIV.');
        $tag->attribute('class', ['added-via', 'attribute-method']);
        $tag->attribute('style', ['no: no', 'no: way']);
        new Demo($code, $tag->render());
        new Demo(
            '// DUMP',
            print_r(['$tag->class()' => $tag->class(), '$tag->attributes()' => $tag->attributes() ], true)
        );
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
        \$tag = Anchor::tag('Correct Link')->attributes(\$setAttributes);
        \$tag->attribute('custom attribute name', )
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
        $tag = Anchor::tag('Correct Link')->attributes($setAttributes);

        new Demo($code, $tag->render());
    }

    /**
     * @return void
     */
    public static function button(): void
    {
        $code = <<<CODE
        echo
            Div::tag(
                Button::create('OK', 'button1'),
                Button::submit('Go', 'button2'),
                Button::reset(
                    Tag::make('span')->contents('Cancel')->styleAdd('color: red;'),
                    'button3'
                )
            )->render()
        CODE;
        new Demo(
            $code,
            Div::tag(
                Button::create('OK', 'button1'),
                Button::submit('Go', 'button2'),
                Button::reset(
                    Tag::make('span')->contents('Cancel')->styleAdd('color: red;'),
                    'button3'
                )
            )->render()
        );
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
    public static function case(): void
    {
        $input = 'String case coversion';
        print_r([
            'input' => $input,
            'camel' => Convert::camel($input),
            'kebab' => Convert::kebab($input),
            'snake' => Convert::snake($input),
            'studly' => Convert::studly($input),
        ]);
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
        $a1 = Anchor::tag()->href('/go/1')->target('_blank')->contents('Go 1');

        // When there is structural data (e.g. a data model), input attributes array maybe a good choice.
        $a2 = Anchor::tag()->attributes(['href' => '/go/2', 'target' => '_blank'])->contents('Go 2');

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
            Comment::tag('Comment ignores attributes set.')
                ->class('ignored')
                ->contentsAppend(Tag::make('div')->contents('Nested tag is fine.'))
                ->contentsAppend(Comment::tag('Nested comment ending brace is escaped.'))
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
    public static function contentsOverride(): void
    {
        $code = <<<CODE
        \$tag = Tag::make('select')->name('color');
        \$tag->contentsPrefixed->append(Comment::make()->contents('This is not affected by the contents* method.'));
        \$tag->contentsSuffixed->append(Comment::make()->contents('This is also not affected.'));
        \$tag->contents('Empty selection (this content will be removed).')
            ->contents(Option::create('three', 'Third'))
            ->contentsAppend(Option::create('four', 'Fourth', true))
            ->contentsPrepend(Option::create('one', 'First', true), Option::create('two', 'Second', null, true));
        echo \$tag->render();
        CODE;

        $tag = Select::tag()
            ->name('rank')
            // Since contentsOverride() is taking the priority.
            ->contents('Main contents of the \<select\> tag is ignored')
            // Set option(s)
            ->options(Option::create('this', 'will be repalced by the next call'))
            // Replace all options
            ->options(Option::create('3', 'Third'), Option::create('4', 'Fourth', true))
            // Append ad prepend
            ->optionsAppend([Option::create('5', 'Fifth', true), Option::create('6', 'Sixth', null, true), 'Only option tags will be added.'])
            ->optionsPrepend(Option::create('1', 'First', true), Option::create('2', 'Second', null, true));
        $tag->contentsPrefixed->append(Comment::tag('Before the option tags.'));
        $tag->contentsSuffixed->append(Comment::tag('After the options tags.'));

        $output = $tag->render();
        new Demo($code, $output);
    }

    /**
     * @return void
     */
    public static function css(): void
    {

        $code = <<<CODE
        \$tag = Tag::make('select')->name('color');
        CODE;

        $tag = Tag::make('div')->contents('CSS Class');
        $tag->class('c1', 'c2 c3', 'c4', ['c5', ['c6', 'c7']]);
        $output = $tag->render();
        new Demo($code, $output);
    }

    /**
     * @return void
     */
    public static function data(): void
    {
        $code = <<<CODE
        \$data = [
            'Foo'         => 'bar',
            'someOption'  => 'Some Value',
            'some-places' => 'Here & there.',
            'truth'       => 'true'
        ];
        
        echo Div::make()->data(\$data)->render();
        CODE;

        $data = [
            'Foo'         => 'bar',
            'someOption'  => 'Some Value',
            'some-places' => 'Here & there.',
            'truth'       => 'true'
        ];
        $tag = Tag::make('div')->contents('I got data.')->dataset($data);

        print_r([
            'original' => $data,
            'stored' => $tag->dataset(),
            'attributes' => $tag->dataAttributes(),
            'json' => $tag->datasetJSON(),
        ]);

        new Demo(
            $code,
            $tag->render()
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
    public static function input(): void
    {
        new Demo(
            "Input::create('year', 'number', null, null, true)->disabled(false)->render();",
            Input::create('year', 'number', null, null, true)->disabled(true)->render(),
        );
    }

    /**
     * @return void
     */
    public static function inputAutoComplete(): void
    {
        $code = <<<CODE
        Input::create('username')->autocomplete('off')->render(),
        Input::create('password')->type('password')->autocomplete('new-password')->render(),
        CODE;
        new Demo(
            $code,
            implode(PHP_EOL, [
                $tag = Input::create('username')->autocomplete('off')->render(),
                $tag = Input::create('password')->type('password')->autocomplete('new-password')->render(),
            ])
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
            "Form::post('upload.php', 'upload_form', 'green-form big-font')->enableUpload()->inert(true)->render();",
            Form::post('upload.php', 'upload_form', 'green-form big-font')->enableUpload()->inert(true)->render()
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

    /**
     * @return void
     */
    public static function raw(): void
    {
        $tag = Tag::make('i')->contents(RawHTML::ZWNJ())->class('fas', 'fa-info-circle');
        new Demo(
            '',
            $tag->render()
        );
    }

    /**
     * @return void
     */
    public static function siblings(): void
    {
        $code = <<<CODE
        \$tag = Input::create('age', 'number');
        \$tag->siblingsBefore->append(Label::create('age')->contents('Age:'));
        \$tag->siblingsAfter->append(
            Input::create('', 'submit')->value('Submit')
        );
        echo \$tag->render();
        CODE;

        $tag = Input::create('age', 'number');
        $tag->siblingsBefore->append(Label::create('age')->contents('Age:'));
        $tag->siblingsAfter->append(
            Input::create('', 'submit')->value('Submit')
        );
        $output = $tag->render();
        new Demo($code, $output);
    }

    /**
     * @return void
     */
    public static function anatomy(): void
    {
        $code = <<<CODE
        \$tag = Tag::make('div');
        \$tag->contents->append(Tag::make('p')->contents('Content 1'), Tag::make('p')->contents('Content N'));
        \$tag->contentsPrefixed->append(Tag::make('h1')->contents('Prefixed 1'), Tag::make('h2')->contents('Prefixed N'));
        \$tag->contentsSuffixed->append(Tag::make('h3')->contents('Suffixed 1'), Tag::make('h4')->contents('Suffixed N'));
        \$tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before 1'));
        \$tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before N'));
        \$tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After 1'));
        \$tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After N'));
        echo \$tag->render();
        CODE;

        $tag = Tag::make('div');
        $tag->contents->append(Tag::make('p')->contents('Content 1'), Tag::make('p')->contents('Content N'));
        $tag->contentsPrefixed->append(Tag::make('h1')->contents('Prefixed 1'), Tag::make('h2')->contents('Prefixed N'));
        $tag->contentsSuffixed->append(Tag::make('h3')->contents('Suffixed 1'), Tag::make('h4')->contents('Suffixed N'));
        $tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before 1'));
        $tag->siblingsBefore->append(Tag::make('div')->contents('Sibling Before N'));
        $tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After 1'));
        $tag->siblingsAfter->append(Tag::make('div')->contents('Sibling After N'));
        $output = $tag->render();
        new Demo($code, $output);
    }

    /**
     * @return void
     */
    public static function select(): void
    {
        $code = <<<CODE
        \$options = [
            Option::create('R', 'Red is alternative.'),
            array(
                Option::create('G', 'Green is choosen.', true),
                Option::create('B', 'Blue is not allowed.', null, true),
            ),
            'This is not an options.',
            Option::create('Y', 'Yellow is the last option.'),
        ];
        \$select = Select::create(\$options)->render();
        CODE;

        $options = [
            Option::create('R', 'Red is alternative.'),
            array(
                Option::create('G', 'Green is choosen.', true),
                Option::create('B', 'Blue is not allowed.', null, true),
            ),
            'This is not an options.',
            Option::create('Y', 'Yellow is the last option.'),
        ];
        $select = Select::create($options);

        new Demo(
            $code,
            $select->render()
        );
    }

    /**
     * @return void
     */
    public static function textarea(): void
    {
        $code = <<<CODE
        Textarea::create(5)
            ->name('Address')
            ->placeholder('Address including postal code.')
            ->contents('Default input here...')
            ->render();
        CODE;

        new Demo(
            $code,
            Textarea::create(5)
                ->name('Address')
                ->placeholder('Address including postal code.')
                ->contents('Default input here...')
                ->render()
        );
    }

    public static function zen(): void
    {
        $code = <<<CODE
        \$zens = [
            'div#foo.c1',
            'div#foo.c1.c2',
            'div#foo.c1.c2#bar',
            'div#foo.c1.c2#bar.c3',
            '#foo.c1',
            '#foo.c1.c2',
            '#foo.c1.c2#bar',
            '#foo.c1.c2#bar.c3',
            '.c1#foo',
            '.c1.c2#foo',
            '.c1.c2#foo#bar',
            '.c1.c2#foo.c3#bar',
        ];
        
        array_walk(
            \$zens,
            function (\$zen) {
                echo Tag::make(\$zen)->render() . PHP_EOL;
            }
        );
        CODE;

        $zens = [
            'div#foo.c1', 'div#foo.c1.c2', 'div#foo.c1.c2#bar', 'div#foo.c1.c2#bar.c3',
            '#foo.c1', '#foo.c1.c2', '#foo.c1.c2#bar', '#foo.c1.c2#bar.c3',
            '.c1#foo', '.c1.c2#foo', '.c1.c2#foo#bar', '.c1.c2#foo.c3#bar',
        ];

        $results = '';
        array_walk(
            $zens,
            function ($zen) use (&$results) {
                $results .= Tag::make($zen)->render() . PHP_EOL;
            }
        );

        new Demo(
            $code,
            $results
        );

    }
}

