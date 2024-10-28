<?php

namespace Phpactor\ObjectRenderer\Tests;

use DOMDocument;
use Phpactor\ObjectRenderer\Model\Exception\CouldNotRenderObject;
use Phpactor\ObjectRenderer\ObjectRendererBuilder;
use Phpactor\ObjectRenderer\Tests\IntegrationTestCase;
use Psr\Log\LoggerInterface;
use stdClass;

class ObjectRendererBuilderTest extends IntegrationTestCase
{
    public function testRenderObject(): void
    {
        $renderer = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__ . '/Example')
            ->build();

        $dom = new DOMDocument();
        $child1 = $dom->createElement('child-1');
        $child1->setAttribute('foo', 'bar');
        $dom->appendChild($child1);
        $child2 = $dom->createElement('child-2');
        $child2->setAttribute('bar', 'foo');
        $dom->appendChild($child2);

        $rendered = $renderer->render($dom);

        self::assertEquals(<<<'EOT'
DOMDocument:
    - Element: "child-1"
      foo: bar
    - Element: "child-2"
      bar: foo

EOT
, $rendered);

    }

    public function testThrowsExceptionIfObjectTemplateNotFound(): void
    {
        $this->expectException(CouldNotRenderObject::class);

        $renderer = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__)
            ->build();

        $renderer->render(new stdClass());
    }

    public function testRendersEmptyStringTemplateNotFoundAndThatIsFine(): void
    {
        $renderer = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__)
            ->renderEmptyOnNotFound()
            ->build();

        self::assertEquals('', $renderer->render(new stdClass()));
    }

    public function testCanSetLogger(): void
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $renderer = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__)
            ->renderEmptyOnNotFound()
            ->setLogger($logger->reveal())
            ->build();

        self::assertEquals('', $renderer->render(new stdClass()));

        $logger->warning('Could not render object "stdClass" using templates "stdClass.twig"')->shouldHaveBeenCalled();
    }

    public function testCanSetAutoescapingStrategy(): void
    {
        $renderer = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__ . '/Example')
            ->setEscaping('html')
            ->build();

        self::assertEquals('I am a stdClass', trim($renderer->render(new stdClass())));
    }

    public function testBuilderIsImmutable(): void
    {
        $renderer1 = ObjectRendererBuilder::create()
            ->addTemplatePath(__DIR__ . '/Example');

        $renderer2 = $renderer1
            ->addTemplatePath(__DIR__ . '/Barfoo');

        self::assertNotEquals($renderer1, $renderer2);
    }

    public function testEnableInterfaceCandidates(): void
    {
        ObjectRendererBuilder::create()
            ->enableInterfaceCandidates()
            ->build();
        $this->addToAssertionCount(1);
    }

    public function testEnableAncestoralCandidates(): void
    {
        ObjectRendererBuilder::create()
            ->enableAncestoralCandidates()
            ->build();
        $this->addToAssertionCount(1);
    }
}
