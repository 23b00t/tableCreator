<?php

namespace DTL\ArgumentResolver\Tests\ParamConverter;

use DTL\ArgumentResolver\ArgumentResolver;
use DTL\ArgumentResolver\ParamConverter\RecursiveInstantiator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RecursiveInstantiatorTest extends TestCase
{
    /**
     * @var RecursiveInstantiator
     */
    private $converter;

    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;


    public function setUp()
    {
        $this->converter = new RecursiveInstantiator();
        $this->argumentResolver = new ArgumentResolver([$this->converter]);
    }

    /**
     * @dataProvider provideCanConvert
     */
    public function testCanConvert(string $methodName, $argument, bool $expected)
    {
        $parameter = $this->getParameterAt($methodName);
        $result = $this->converter->canConvert($parameter, $argument);
        $this->assertEquals($expected, $result);
    }

    public function provideCanConvert()
    {
        yield 'not scalar type' => [
            'methodWithScalarType',
            [],
            false
        ];

        yield 'not non-array argument' => [
            'methodWithEmptyObject',
            'non-array',
            false
        ];

        yield 'not array argument' => [
            'methodWithEmptyObject',
            [],
            true
        ];
    }

    /**
     * @dataProvider provideInstantiatesObjectFromArray
     */
    public function testInstantiatesObjectFromArray($methodName, $argument, $expected)
    {
        $parameter = $this->getParameterAt($methodName);
        $result = $this->converter->convert($this->argumentResolver, $parameter, $argument);
        $this->assertEquals($expected, $result);
    }

    public function provideInstantiatesObjectFromArray()
    {
        yield 'for type with no parameters' => [
            'methodWithEmptyObject',
            [],
            new EmptyObject(),
        ];

        yield 'for type with argument' => [
            'methodWithObject',
            [
                'message' => 'hello',
            ],
            new NoSubObject('hello'),
        ];

        yield 'for sub-objects' => [
            'methodWithObjectWithSubObjects',
            [
                'message' => [
                    'message' => 'hello'
                ]
            ],
            new SubObject(new Message('hello')),
        ];
    }

    public function getParameterAt(string $methodName)
    {
        $reflectionClass = new \ReflectionClass($this);
        $method = $reflectionClass->getMethod($methodName);
        return $method->getParameters()[0];
    }

    public function methodWithScalarType(string $scalar)
    {
    }

    public function methodWithEmptyObject(EmptyObject $empty)
    {
    }

    public function methodWithObject(NoSubObject $object)
    {
    }

    public function methodWithObjectWithSubObjects(SubObject $object)
    {
    }
}

class EmptyObject
{
}

class NoSubObject
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function sayHello()
    {
        return $this->message;
    }
}

class SubObject
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}

class Message
{
    public $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
