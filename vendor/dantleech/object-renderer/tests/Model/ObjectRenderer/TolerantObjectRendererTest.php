<?php

namespace Phpactor\ObjectRenderer\Tests\Model\ObjectRenderer;

use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Model\Exception\CouldNotRenderObject;
use Phpactor\ObjectRenderer\Model\ObjectRenderer;
use Phpactor\ObjectRenderer\Model\ObjectRenderer\TolerantObjectRenderer;
use Psr\Log\LoggerInterface;

class TolerantObjectRendererTest extends TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $innerRenderer;
    /**
     * @var ObjectProphecy
     */
    private $logger;
    /**
     * @var TolerantObjectRenderer
     */
    private $renderer;

    protected function setUp(): void
    {
        $this->innerRenderer = $this->prophesize(ObjectRenderer::class);
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->renderer = new TolerantObjectRenderer(
            $this->innerRenderer->reveal(),
            $this->logger->reveal()
        );
    }

    public function testCatchesAndLogsCouldNotRenderObjectErrors(): void
    {
        $object = new \stdClass();
        $this->innerRenderer->render($object)->willThrow(new CouldNotRenderObject('soz'));

        $rendered = $this->renderer->render($object);

        $this->logger->warning('soz')->shouldHaveBeenCalled();
        self::assertEquals('', $rendered, 'Renders empty string');
    }
}
