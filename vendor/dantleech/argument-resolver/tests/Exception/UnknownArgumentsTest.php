<?php

namespace DTL\ArgumentResolver\Tests\Exception;

use DTL\ArgumentResolver\Exception\UnknownArguments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class UnknownArgumentsTest extends TestCase
{
    public function exampleMethod()
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
        $unknownArguments = [ 'one', 'two' ];
        $exception = new UnknownArguments($this->reflectionMethod, $unknownArguments);

        $this->assertSame($this->reflectionMethod, $exception->method());
        $this->assertSame($unknownArguments, $exception->unknownArgumentNames());
        $this->assertEquals($this->reflectionClass, $exception->class());
    }
}
