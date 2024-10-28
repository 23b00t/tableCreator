<?php

namespace Phpactor\ObjectRenderer\Adapter\Twig\Extension;

use Phpactor\ObjectRenderer\Model\ObjectRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ObjectRendererExtension extends AbstractExtension
{
    /**
     * @var ObjectRenderer
     */
    private $renderer;

    public function __construct(ObjectRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('render', function (?object $object) {
                if (null === $object) {
                    return '';
                }

                return $this->renderer->render($object);
            })
        ];
    }
}
