<?php

namespace Phpactor\ObjectRenderer\Tests\Model\TemplateProvider;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Model\TemplateProvider\ClassNameTemplateProvider;
use Phpactor\ObjectRenderer\Tests\IntegrationTestCase;
use stdClass;

class ClassNameTemplateProviderTest extends IntegrationTestCase
{
    /**
     * @dataProvider provideResolveFromObject
     */
    public function testResolveFromObject(string $stub, array $expected): void
    {
        $object = $this->loadStub($stub);
        self::assertEquals($expected, (new ClassNameTemplateProvider())->resolveFor(get_class($object)));
    }

    /**
     * @return Generator<array>
     */
    public function provideResolveFromObject(): Generator
    {
        yield [
            '<?php return new stdClass();',
            [
                'stdClass'
            ]
        ];

        yield [
            '<?php namespace Test\Object; class TestObject{} return new TestObject();',
            [
                'Test/Object/TestObject'
            ]
        ];
    }
}
