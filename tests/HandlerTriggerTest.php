<?php

declare(strict_types=1);

namespace Apricity\tests;

use Apricity\Handler;
use Apricity\HandlerException;
use Apricity\tests\mocks\ExampleClass;
use PHPUnit\Framework\TestCase;

class HandlerTriggerTest extends TestCase
{
    public function testTriggerWithStringHandler()
    {
        $handler = 'Apricity\tests\mocks\ExampleClass@exampleMethod';
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::trigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example method executed with value1 and value2.', $result);
    }

    public function testTriggerWithCallableArrayHandler()
    {
        $handler = [ExampleClass::class, 'exampleMethod'];
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::trigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example method executed with value1 and value2.', $result);
    }

    public function testTriggerWithFunctionHandler()
    {
        $handler = 'Apricity\tests\mocks\exampleFunction';
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::trigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example function executed with value1 and value2.', $result);
    }

    public function testTriggerWithFunctionArrayHandler()
    {
        $handler = ['Apricity\tests\mocks\exampleFunction'];
        $vars = ['value1', 'value2'];
        try {
            $result = Handler::trigger($handler, $vars);
        } catch (HandlerException $e) {
            $this->fail($e->getMessage());
        }
        $this->assertEquals('Example function executed with value1 and value2.', $result);
    }

    public function testTriggerInvalidHandler()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler "InvalidHandler" not found.');
        Handler::trigger('InvalidHandler');
    }

    public function testTriggerWithNonCallableHandler()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler class "NonExistentClass" not found.');
        Handler::trigger(['NonExistentClass', 'nonExistentMethod']);
    }

    public function testTriggerNonCallableFunction()
    {
        $this->expectException(HandlerException::class);
        $this->expectExceptionMessage('Handler ["nonExistentFunction"] not found.');
        Handler::trigger(['nonExistentFunction']);
    }
}