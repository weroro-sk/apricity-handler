<?php

declare(strict_types=1);

namespace Apricity\tests;

use Apricity\Handler;
use Apricity\HandlerException;
use Apricity\tests\mocks\ExampleClass;
use PHPUnit\Framework\TestCase;

class HandlerParseTest extends TestCase
{

    public function testParseStringHandler()
    {
        $handler = 'Apricity\tests\mocks\ExampleClass@exampleMethod';
        try {
            $parsedHandler = Handler::parse($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals(['Apricity\tests\mocks\ExampleClass', 'exampleMethod'], $parsedHandler);
    }

    public function testParseCallableArrayHandler()
    {
        $handler = [ExampleClass::class, 'exampleMethod'];
        try {
            $parsedHandler = Handler::parse($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals([ExampleClass::class, 'exampleMethod'], $parsedHandler);
    }

    public function testParseFunctionHandler()
    {
        $handler = 'Apricity\tests\mocks\exampleFunction';
        try {
            $parsedHandler = Handler::parse($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals(['Apricity\tests\mocks\exampleFunction'], $parsedHandler);
    }

    public function testParseArrayFunctionHandler()
    {
        $handler = ['Apricity\tests\mocks\exampleFunction'];
        try {
            $parsedHandler = Handler::parse($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals(['Apricity\tests\mocks\exampleFunction'], $parsedHandler);
    }

    public function testParseInvalidHandler()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler "InvalidHandler" not found.');
        Handler::parse('InvalidHandler');
    }

    public function testParseNonExistentClass()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler class "NonExistentClass" not found.');
        Handler::parse(['NonExistentClass', 'nonExistentMethod']);
    }

    public function testParseNonExistentMethod()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler method "nonExistentMethod" in class "Apricity\tests\mocks\ExampleClass" not found.');
        Handler::parse([ExampleClass::class, 'nonExistentMethod']);
    }
}