<?php

declare(strict_types=1);

namespace Apricity\tests;

use Apricity\Handler;
use Apricity\HandlerException;
use Apricity\tests\mocks\ExampleClass;
use PHPUnit\Framework\TestCase;

class HandlerSimpleTriggerTest extends TestCase
{
    public function testSimpleTriggerErrorWithStringHandlerAndNoVars()
    {
        $handler = 'Apricity\tests\mocks\ExampleClass@exampleMethodNoVars';
        $this->expectException(\TypeError::class);
        try {
            Handler::simpleTrigger($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSimpleTriggerErrorWithStringHandler()
    {
        $handler = 'Apricity\tests\mocks\ExampleClass@exampleMethod';
        $vars = ['value1', 'value2'];
        $this->expectException(\TypeError::class);
        try {
            Handler::simpleTrigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testSimpleTriggerWithCallableArrayHandler()
    {
        $handler = [ExampleClass::class, 'exampleMethod'];
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::simpleTrigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example method executed with value1 and value2.', $result);
    }

    public function testSimpleTriggerWithFunctionHandlerAndNoVars()
    {
        $handler = 'Apricity\tests\mocks\exampleFunctionNoVars';
        try {
            $result = Handler::simpleTrigger($handler);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example function executed.', $result);
    }

    public function testSimpleTriggerWithFunctionHandler()
    {
        $handler = 'Apricity\tests\mocks\exampleFunction';
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::simpleTrigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example function executed with value1 and value2.', $result);
    }

    public function testSimpleTriggerWithFunctionArrayHandler()
    {
        $handler = ['Apricity\tests\mocks\exampleFunction'];
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::simpleTrigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example function executed with value1 and value2.', $result);
    }

    public function testSimpleTriggerExceptionWithClosureHandler()
    {
        $handler = function ($value1, $value2) {
            return "Example closure executed with $value1 and $value2.";
        };
        $vars = ['value1', 'value2'];

        //$this->expectException(MicroCacheException::class);
        //$this->expectExceptionMessage("A cache key must be provided, cannot be empty, and cannot be callable.");

        try {
            $result = Handler::simpleTrigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example closure executed with value1 and value2.', $result);
    }

    public function testSimpleTriggerInvalidHandler()
    {
        $this->expectException(\TypeError::class);
        try {
            Handler::simpleTrigger('InvalidHandler');
        } catch (HandlerException $e) {
        }
    }

    public function testSimpleTriggerWithNonCallableHandler()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Class "NonExistentClass" not found');
        try {
            Handler::simpleTrigger(['NonExistentClass', 'nonExistentMethod']);
        } catch (HandlerException $e) {
        }
    }

    public function testSimpleTriggerNonCallableFunction()
    {
        $this->expectException(\TypeError::class);
        try {
            Handler::simpleTrigger(['nonExistentFunction']);
        } catch (HandlerException $e) {
        }
    }
}