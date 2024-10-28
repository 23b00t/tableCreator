<?php

namespace Phpactor\ObjectRenderer\Tests;

use PHPUnit\Framework\TestCase;
use Phpactor\TestUtils\Workspace;

class IntegrationTestCase extends TestCase
{
    protected function setUp(): void
    {
        $this->workspace()->reset();
    }

    protected function loadStub(string $stub): object
    {
        $fname = uniqid();
        $this->workspace()->put($fname, $stub);
        return require($this->workspace()->path($fname));
    }

    protected function workspace(): Workspace
    {
        return Workspace::create(__DIR__ . '/Workspace');
    }
}
