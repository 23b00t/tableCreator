<?php

namespace DTL\ArgumentResolver\Tests;

use DTL\ArgumentResolver\ArgumentConverter;
use DTL\ArgumentResolver\ArgumentResolver;
use DTL\ArgumentResolver\Exception\MissingRequiredArguments;
use DTL\ArgumentResolver\Exception\UnknownArguments;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;
use RuntimeException;
use stdClass;

class ArgumentResolverTest extends TestCase
{
    public function createResolver(array $converters = [], int $options = 0)
    {
        return new ArgumentResolver($converters, $options);
    }

    public function testThrowsExceptionIfClassDoesNotImplementMethod()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Expected "stdClass" to implement a method named "foobar"');
        $this->createResolver()->resolveArguments('stdClass', 'foobar', []);
    }

    public function testThrowsExceptionIfRequiredParameterNotPresent()
    {
        $this->expectException(MissingRequiredArguments::class);
        $this->expectExceptionMessage('Argument(s) "foobar" are required in method "__invoke" of class');
        $class = new class {
            public function __invoke($foobar)
            {
            }
        };
        $this->createResolver()->resolveArguments(get_class($class), '__invoke', []);
    }

    public function testThrowsExceptionOnUnknownParameter()
    {
        $this->expectException(UnknownArguments::class);
        $this->expectExceptionMessage('Parameter(s) "arg" do not exist in method "__invoke", valid parameter(s): "foobar", "barfoo"');

        $class = new class {
            public function __invoke($foobar = null, $barfoo = null)
            {
            }
        };
        $this->createResolver()->resolveArguments(get_class($class), '__invoke', [
            'arg' => 123,
        ]);
    }

    public function testToleratesMissingArgumentsWhenOptionIsGiven()
    {
        $class = new class {
            public function __invoke($foobar = null, $barfoo = null)
            {
            }
        };
        $args = $this->createResolver([], ArgumentResolver::ALLOW_UNKNOWN_ARGUMENTS)->resolveArguments(get_class($class), '__invoke', [
            'arg' => 123,
        ]);
        $this->assertEquals([null, null], $args);
    }

    public function testUsesArgumentConverter()
    {
        $object = new class {
            public function __invoke(string $message)
            {
                return $message;
            }
        };

        $converter = new class implements ArgumentConverter {
            public function canConvert(ReflectionParameter $parameter, $argument): bool
            {
                return $parameter->getName() === 'message' && $argument == 'hello';
            }

            public function convert(ArgumentResolver $resolver, ReflectionParameter $parameter, $argument)
            {
                return 'right';
            }
        };

        $expected = 'right';
        $input = ['message' => 'hello'];

        $resolver = $this->createResolver([ $converter ]);
        $this->assertResolution($resolver, $object, $input, $expected);
    }

    public function testSkipsConvertersThatCannotPerformConversion()
    {
        $object = new class {
            public function __invoke(string $message)
            {
                return $message;
            }
        };

        $converter1 = new class implements ArgumentConverter {
            public function canConvert(ReflectionParameter $parameter, $argument): bool
            {
                return false;
            }

            public function convert(ArgumentResolver $resolver, ReflectionParameter $parameter, $argument)
            {
                return 'wrong';
            }
        };

        $converter2 = new class implements ArgumentConverter {
            public function canConvert(ReflectionParameter $parameter, $argument): bool
            {
                return true;
            }

            public function convert(ArgumentResolver $resolver, ReflectionParameter $parameter, $argument)
            {
                return 'right';
            }
        };

        $resolver = $this->createResolver([ $converter1, $converter2 ]);
        $this->assertResolution($resolver, $object, [ 'message' => 'hello' ], 'right');
    }

    public function testConvertOneOfMultipleParameters()
    {
        $object = new class {
            public function __invoke(string $message = 'hello', int $foobar)
            {
                return [ $message, $foobar ];
            }
        };

        $converter = new class implements ArgumentConverter {
            public function canConvert(ReflectionParameter $parameter, $argument): bool
            {
                return $parameter->getName() === 'foobar';
            }

            public function convert(ArgumentResolver $resolver, ReflectionParameter $parameter, $argument)
            {
                return 66;
            }
        };

        $resolver = $this->createResolver([ $converter ]);
        $this->assertResolution($resolver, $object, [ 'foobar' => 123 ], [ 'hello', 66 ]);
    }

    /**
     * @dataProvider provideResolvesArguments
     */
    public function testResolvesArguments($object, array $arguments, $expected)
    {
        $resolver = $this->createResolver([],
            ArgumentResolver::ALLOW_UNKNOWN_ARGUMENTS | ArgumentResolver::MATCH_TYPE
        );
        $this->assertResolution($resolver, $object, $arguments, $expected);
    }

    public function provideResolvesArguments()
    {
        yield 'null value' => [
            new class {
                public function __invoke($foobar)
                {
                    return $foobar;
                }
            },
            [ 'foobar' => null ],
            null
        ];

        yield 'scalar value' => [
            new class {
                public function __invoke($foobar)
                {
                    return $foobar;
                }
            },
            [ 'foobar' => 12 ],
            12
        ];

        yield 'uses defaults' => [
            new class {
                public function __invoke($foobar = 'barfoo')
                {
                    return $foobar;
                }
            },
            [],
            'barfoo',
        ];

        yield 'multiple arguments' => [
            new class {
                public function __invoke($foobar = 'barfoo', $null = null, $barfoo)
                {
                    return [$foobar, $null, $barfoo ];
                }
            },
            [
                'foobar' => '456',
                'barfoo' => '123',
            ],
            [ 456, null, '123' ],
        ];

        yield 'typed argument' => [
            new class {
                public function __invoke(\stdClass $foo)
                {
                    return [$foo ];
                }
            },
            [
                new stdClass(),
            ],
            [ new stdClass() ],
        ];

        yield 'typed argument in mixed bag' => [
            new class {
                public function __invoke($foobar, \stdClass $foo, bool $bag)
                {
                    return [
                        $foobar,
                        $foo,
                        $bag
                    ];
                }
            },
            [
                'foobar' => 'foobar',
                'bag' => false,
                new stdClass(),
            ],
            [ 'foobar', new stdClass(), false ],
        ];

        yield 'instanceof argument' => [
            new class {
                public function __invoke(ExampleInterface $foo)
                {
                    return [$foo];
                }
            },
            [
                new ExampleClass(),
            ],
            [ new ExampleClass() ],
        ];
    }

    private function assertResolution(ArgumentResolver $resolver, $object, array $arguments, $expected)
    {
        $arguments = $resolver->resolveArguments(get_class($object), '__invoke', $arguments);
        $return = $object->__invoke(...$arguments);

        $this->assertEquals($expected, $return);
    }
}

interface ExampleInterface
{
}

class ExampleClass implements ExampleInterface
{
}
