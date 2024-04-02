<?php

namespace Wongyip\HTML;

class Demo
{
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
    public static function contents(): void
    {
        print_r([
            'code' => "Tag::make()->contents('contents')->contentsAdd('contentsAdd1', 'contentAdd2')->contentsPrepend('contentsPrepend')->render();",
            'output' => Tag::make()->contents('contents')->contentsAdd('contentsAdd1', 'contentAdd2')->contentsPrepend('contentsPrepend')->render(),
        ]);
    }

    /**
     * Basic usage.
     *
     * @return void
     */
    public static function usage1(): void
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
    public static function usage2(): void
    {
        print_r([
            'code' => "Anchor::make()->classAdd('btn', 'btn-primary')->contents('OK')->render();",
            'output' => Anchor::make()->classAdd('btn', 'btn-primary')->contents('OK')->render(),
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
}