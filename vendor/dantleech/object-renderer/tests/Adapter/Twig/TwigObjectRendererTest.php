<?php

namespace Phpactor\ObjectRenderer\Tests\Adapter\Twig;

use Closure;
use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Adapter\Twig\TwigObjectRenderer;
use Phpactor\ObjectRenderer\Model\Exception\CouldNotRenderObject;
use Phpactor\ObjectRenderer\Model\TemplateProvider\ClassNameTemplateProvider;
use Phpactor\ObjectRenderer\Model\TemplateProvider\TestTemplateProvider;
use Phpactor\ObjectRenderer\Model\TemplateResolver\FirstExistingFileTemplateResolver;
use Phpactor\ObjectRenderer\Tests\IntegrationTestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use stdClass;

class TwigObjectRendererTest extends IntegrationTestCase
{
    /**
     * @dataProvider provideRender
     * @param array<string, string> $templates
     * @param array<string, string> $templateCandidates
     */
    public function testRender(string $stub, array $templateCandidates, array $templates, string $expected): void
    {
        $oect = $this->loadStub($stub);
        $loader = new ArrayLoader($templates);
        $provider = new TestTemplateProvider($templateCandidates);
        $renderer = new TwigObjectRenderer(new Environment($loader), $provider);
        self::assertEquals($expected, $renderer->render($oect));
    }

    /**
     * @return Generator<array>
     */
    public function provideRender(): Generator
    {
        yield 'render simple oect' => [
            '<?php $o = new stdClass(); $o->foobar = "Barfoo"; return $o;',
            [
                'stdClass',
            ],
            [
                'stdClass' => '{{ object.foobar }}'
            ],
            'Barfoo'
        ];

        yield 'render a sub-object from in the template' => [
            '<?php $o = new stdClass(); $o->hello = "hello";$o->foobar = new stdClass();$o->foobar->hello="goodbye"; return $o;',
            [
                'stdClass',
            ],
            [
                'stdClass' => '{{ object.hello }}{{ render(object.foobar) }}'
            ],
            'hellogoodbye'
        ];

        yield 'falls back to existing template' => [
            '<?php $o = new stdClass(); $o->hello = "hello";$o->foobar = new stdClass();$o->foobar->hello="goodbye"; return $o;',
            [
                'NotExistingTemplate',
                'stdClass',
            ],
            [
                'stdClass' => '{{ object.hello }}{{ render(object.foobar) }}'
            ],
            'hellogoodbye'
        ];
    }

    public function testThrowsExceptionWhenNoTemplatesAvailable(): void
    {
        $this->expectException(CouldNotRenderObject::class);
        $loader = new ArrayLoader([]);
        $provider = new TestTemplateProvider([]);
        $renderer = new TwigObjectRenderer(new Environment($loader), $provider);
        $renderer->render(new stdClass());
    }
}
