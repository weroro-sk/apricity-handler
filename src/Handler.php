<?php

declare(strict_types=1);

namespace Apricity;

/**
 * The Handler class provides functionality to parse and execute various types of handlers.
 *
 * Handlers can be in the form of strings, callables, or arrays, and this class ensures that they are properly formatted
 * and executed. It includes methods to parse a handler, trigger the execution of a handler with optional parameters,
 * and handle errors effectively.
 *
 * @see https://github.com/weroro-sk/apricity-handler/blob/main/README.md Documentation
 */
class Handler
{
    /**
     * Executes a given handler with provided variables.
     *
     * This method can handle multiple types of handlers: an array with a class and method,
     * a callable, or a string. It attempts to execute the handler with the provided variables
     * and returns the result. If the handler is invalid or execution fails, it throws a
     * HandlerException.
     *
     * @param array|callable|string $handler The handler to be executed. It can be:
     *
     *                                       - A callable/closure function.
     *                                       - A string representing a function name.
     *                                       - An array containing a single string element representing a function name.
     *                                       - An array containing a class and method.
     *                                       - An array containing a class name and method as strings.
     *
     * @param array $vars [optional] The variables to pass to the handler. Default is an empty array.
     *
     * @return mixed The result of the handler execution.
     * @throws HandlerException If the handler is invalid or execution fails.
     *
     * ```
     * try {
     *      $result1 = Handler::simpleTrigger(function($a, $b) { return $a + $b; }, [5, 3]);
     *      echo $result1; // Outputs: 8
     *
     *      $result2 = Handler::simpleTrigger("strtolower", ["HELLO"]);
     *      echo $result2; // Outputs: hello
     *
     *      // Using callable array [Class, "method"]
     *      $result3 = Handler::simpleTrigger([ExampleClass::class, "exampleMethod"], ["value1", "value2"]);
     *      echo $result3; // Outputs: Example method executed with value1 and value2
     *
     *      // Using callable array ["function"]
     *      $result4 = Handler::simpleTrigger(["exampleFunction"], ["value1", "value2"]);
     *      echo $result4; // Outputs: Example function executed with value1 and value2
     * } catch (HandlerException $e) {
     *      echo "Error: " . $e->getMessage();
     * }
     * ```
     */
    public static function simpleTrigger(array|callable|string $handler, array $vars = []): mixed
    {
        // If the handler is empty, throw an exception.
        if (empty($handler)) {
            throw new HandlerException('Invalid handler.');
        }

        if (!is_array($handler)) {
            if (false === ($result = call_user_func_array($handler, $vars))) {
                throw new HandlerException('Handler execution error: ' . json_encode($handler));
            }
        } else if (count($handler) === 1) {
            if (false === ($result = call_user_func_array($handler[0], $vars))) {
                throw new HandlerException('Handler execution error: ' . json_encode($handler));
            }
        } else if (
            false === ($result = call_user_func_array([new $handler[0], $handler[1]], $vars))
            // If the first attempt fails, try the handler as a callable array or array contains static class and method.
            && false === ($result = call_user_func_array($handler, $vars))
        ) {
            throw new HandlerException('Handler execution error: ' . json_encode($handler));
        }

        return $result;
    }

    /**
     * Triggers the execution of a given handler with provided variables.
     *
     * This method first parses the handler to ensure it's in the correct format,
     * and then executes it using the simpleTrigger method. The handler can be a string,
     * callable, or an array. It returns the result of the handler execution.
     *
     * @param array|callable|string $handler The handler to be executed. It can be:
     *
     *                                       - A string in the format "Class@method".
     *                                       - A string representing a function name.
     *                                       - An array containing a single string element representing a function name.
     *                                       - An array containing a class and method.
     *                                       - An array containing a class name and method as strings.
     *
     * @param array $vars [optional] The variables to pass to the handler. Default is an empty array.
     *
     * @return mixed The result of the handler execution.
     * @throws HandlerException If the handler is invalid or execution fails.
     *
     * ```
     * // Example 1: Using "Class@method" string format
     * $result1 = Handler::trigger("ExampleClass@exampleMethod", ["value1", "value2"]);
     * echo $result1; // Outputs: Example method executed with value1 and value2.
     *
     * // Example 2: Using callable array [Class, method]
     * $result2 = Handler::trigger([ExampleClass::class, "exampleMethod"], ["value1", "value2"]);
     * echo $result2; // Outputs: Example method executed with value1 and value2.
     *
     * // Example 3: Using callable "function"
     * $result3 = Handler::trigger("exampleFunction", ["value1", "value2"]);
     * echo $result3; // Outputs: Example function executed with value1 and value2.
     *
     * // Example 4: Using callable array ["function"]
     * $result4 = Handler::trigger(["exampleFunction"], ["value1", "value2"]);
     * echo $result4; // Outputs: Example function executed with value1 and value2.
     * ```
     */
    public static function trigger(array|callable|string $handler, array $vars = []): mixed
    {
        // Parse the handler to ensure it's in the correct format.
        $handler = self::parse($handler);

        return self::simpleTrigger($handler, $vars);
    }

    /**
     * Parses a given handler into a standardized array format.
     *
     * This method accepts a handler in various formats and returns it as an array.
     * It handles strings, callables, and arrays, caching the result for future use.
     * If the handler is invalid, it throws a HandlerException.
     *
     * @param string|callable|array $handler The handler to be parsed. It can be:
     *
     *                                      - A string in the format "Class@method".
     *                                      - A string representing a function name.
     *                                      - An array containing a single string element representing a function name.
     *                                      - An array containing a class and method.
     *                                      - An array containing a class name and method as strings.
     *
     * @return array The parsed handler as an array.
     * @throws HandlerException If the handler is invalid or not found.
     *
     * ```
     * // Example 1: Using "Class@method" string format
     * $parsedHandler1 = Handler::parse("ExampleClass@exampleMethod");
     * print_r($parsedHandler1); // Outputs: Array ( [0] => ExampleClass [1] => exampleMethod )
     *
     * // Example 2: Using callable array [Class, method]
     * $parsedHandler2 = Handler::parse([ExampleClass::class, "exampleMethod"]);
     * print_r($parsedHandler2); // Outputs: Array ( [0] => ExampleClass [1] => exampleMethod )
     *
     * // Example 3: Using callable "function"
     * $parsedHandler3 = Handler::parse("exampleFunction");
     * print_r($parsedHandler3); // Outputs: Array ( [0] => exampleFunction )
     *
     * // Example 4: Using callable as array ["function"]
     * $parsedHandler4 = Handler::parse(["exampleFunction"]);
     * print_r($parsedHandler4); // Outputs: Array ( [0] => exampleFunction )
     * ```
     */
    public static function parse(string|callable|array $handler): array
    {
        // If the handler is empty, throw an exception.
        if (empty($temp_handler = $handler)) {
            throw new HandlerException('Invalid handler.');
        }

        if (null !== ($value = HandlerCache::get($handler))) {
            return $value;
        }

        // If the handler is a string in the format 'Class@method', split it into an array.
        if (is_string($temp_handler) && strlen($temp_handler) > 2 && substr_count($temp_handler, '@') === 1) {
            $temp_handler = explode('@', $temp_handler, 2);
        } else if (
            // If the handler is not an array and is callable and exists as a function,
            (!is_array($temp_handler) && function_exists($temp_handler))
            // or if the handler is an array with a single element and that element is an existing function,
            || (is_array($temp_handler) && count($temp_handler) === 1 && function_exists($temp_handler = $temp_handler[0]))
        ) {

            HandlerCache::set($handler, [$temp_handler]);
            // return the handler as a single-element array.
            return [$temp_handler];
        }

        // If the handler is an array with two elements (class and method), validate it.
        if (is_array($temp_handler) && count($temp_handler) === 2) {
            [$class, $method] = $temp_handler;

            // Check if the class exists.
            if (!class_exists($class)) {
                throw new HandlerException('Handler class "' . $class . '" not found.');
            }
            // Check if the method exists in the class.
            if (!method_exists($class, $method)) {
                throw new HandlerException('Handler method "' . $method . '" in class "' . $class . '" not found.');
            }

            HandlerCache::set($handler, $temp_handler);
            return $temp_handler;
        }

        // If the handler format is invalid, throw an exception.
        throw new HandlerException('Handler ' . json_encode($handler) . ' not found.');
    }
}
