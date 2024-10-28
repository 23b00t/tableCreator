<?php

namespace Phpactor\ObjectRenderer\Tests\Model\TemplateProvider;

use Generator;
use PHPUnit\Framework\TestCase;
use Phpactor\ObjectRenderer\Model\TemplateProvider\AncestoralClassTemplateProvider;
use Phpactor\ObjectRenderer\Model\TemplateProvider\ClassNameTemplateProvider;
use Phpactor\ObjectRenderer\Tests\IntegrationTestCase;
use stdClass;

class AncestoralClassProviderTest extends IntegrationTestCase
{
    /**
     * @dataProvider provideResolveFromObject
     */
    public function testResolveFromObject(string $stub, array $expected): void
    {
        $object = $this->loadStub($stub);
        $innerProvider = new ClassNameTemplateProvider();
        self::assertEquals($expected, (new AncestoralClassTemplateProvider($innerProvider))->resolveFor(get_class($object)));
    }

    /**
     * @return Generator<array>
     */
    public function provideResolveFromObject(): Generator
    {
        yield [
            <<<'EOT'
<?php

class One
{
}

class Two extends One
{
}

return new Two();
EOT
            ,
            [
                'Two',
                'One',
            ],
        ];
    }
}

