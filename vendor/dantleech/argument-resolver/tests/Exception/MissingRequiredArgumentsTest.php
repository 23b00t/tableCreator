<?php

namespace DTL\ArgumentResolver\Tests\Exception;

use DTL\ArgumentResolver\Exception\MissingRequiredArguments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;

class MissingRequiredArgumentsTest extends TestCase
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var ReflectionMethod
     */
    private $reflectionMethod;

    /**
     * @var array
     */
    private $reflectionParameters;

    public function exampleMethod(string $foobar)
    {
    }

    public function setUp()
    {
        $this->reflectionClass = new ReflectionClass(__CLASS__);
        $this->reflectionMethod = $this->reflectionClass->getMethod('exampleMethod');
        $this->reflectionParameters = $this->reflectionMethod->getParameters();
    }

    public function testAccessors()
    {
        $exception = new MissingRequiredArguments($this->reflectionMethod, $this->reflectionParameters);

        $this->assertSame($this->reflectionMethod, $exception->method());
        $this->assertSame($this->reflectionParameters, $exception->missingParameters());
        $this->assertEquals($this->reflectionClass, $exception->class());
    }
}
