<?php

namespace Phpactor\ObjectRenderer\Tests\Model\TemplateProvider;

use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Model\TemplateProvider\ClassNameTemplateProvider;
use Phpactor\ObjectRenderer\Model\TemplateProvider\SuffixAppendingTemplateProvider;
use stdClass;

class SuffixAppendingTemplateProviderTest extends TestCase
{
    public function testAppendsSuffix()
    {
        $inner = $this->getMockBuilder(ClassNameTemplateProvider::class)
            ->getMock();
        $inner->expects($this->once())
              ->method('resolveFor')
              ->willReturn(['Foobar']);

        $resolver = new SuffixAppendingTemplateProvider($inner, '.php.twig');
        self::assertEquals(['Foobar.php.twig'], $resolver->resolveFor(stdClass::class));
    }

}
