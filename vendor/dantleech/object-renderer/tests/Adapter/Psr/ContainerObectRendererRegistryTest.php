<?php

namespace Phpactor\ObjectRenderer\Tests\Adapter\Psr;

use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Adapter\Psr\ContainerObectRendererRegistry;
use Phpactor\ObjectRenderer\Model\Exception\ObjectRendererNotFound;
use Phpactor\ObjectRenderer\Model\ObjectRenderer;
use Psr\Container\ContainerInterface;

class ContainerObectRendererRegistryTest extends TestCase
{
    public function testThrowsExceptionIfNamedRendererNotFound(): void
    {
        $this->expectException(ObjectRendererNotFound::class);
        $container = $this->prophesize(ContainerInterface::class);
        $registry = new ContainerObectRendererRegistry($container->reveal(), [
            'foobar' => 'foobar.service'
        ]);
        $registry->get('barfoo');
    }

    public function testReturnsObjectRendererByName(): void
    {
        $renderer = $this->prophesize(ObjectRenderer::class);
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('foobar.service')->willReturn($renderer->reveal());

        $registry = new ContainerObectRendererRegistry($container->reveal(), [
            'foobar' => 'foobar.service'
        ]);

        $found = $registry->get('foobar');

        self::assertSame($found, $renderer->reveal());
    }

}
