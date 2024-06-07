<?php

declare(strict_types=1);

namespace Apricity;

/**
 * A PHP class for parsing and executing various types of handlers, including strings in the format "Class@method",
 * callable functions, and arrays with class and method names.
 *
 * @see https://github.com/weroro-sk/apricity-handler/blob/main/README.md Documentation
 */
class Handler
{
    /**
     * Triggers the specified handler with the given variables.
     *
     * The handler can be a string, callable, or an array. It will be parsed
     * and executed with the provided variables.
     *
     * @param string|callable|array $handler The handler to trigger.
     * @param array $vars (optional) Variables to pass to the handler.
     *
     * @return mixed The result of the handler execution.
     * @throws HandlerException If the handler is not valid or cannot be executed.
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
    public static function trigger(string|callable|array $handler, array $vars = []): mixed
    {
        // Parse the handler to ensure it's in the correct format.
        $handler = self::parse($handler);

        // If the handler is a callable (single element), call it directly with the variables.
        if (count($handler) === 1) {
            if (false === ($result = call_user_func_array($handler[0], $vars))) {
                throw new HandlerException('Handler execution error: ' . json_encode($handler));
            }
            return $result;
        }

        // If the handler is a controller (class@method or [class, method]), attempt to call it.
        if (false === ($result = call_user_func_array([new $handler[0], $handler[1]], $vars))
            // If the first attempt fails, try the handler as a callable array.
            && false === ($result = call_user_func_array($handler, $vars))) {
            throw new HandlerException('Handler execution error: ' . json_encode($handler));
        }

        return $result;
    }

    /**
     * Parses the handler to ensure it's in a valid format and can be executed.
     *
     * The handler can be a string in the format "Class@method", a callable, or an array
     * with two elements where the first is a class name and the second is a method name.
     *
     *
     * @param string|callable|array $handler The handler to parse.
     *
     * @return array The parsed handler as an array.
     * @throws HandlerException If the handler is not valid or cannot be found.
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

        // If the handler is a string in the format 'Class@method', split it into an array.
        if (is_string($temp_handler) && strlen($temp_handler) > 2 && substr_count($temp_handler, '@') === 1) {
            $temp_handler = explode('@', $temp_handler, 2);
        } else if (
            // If the handler is not an array and is callable and exists as a function,
            (!is_array($temp_handler) && function_exists($temp_handler))
            // or if the handler is an array with a single element and that element is an existing function,
            || (is_array($temp_handler) && count($temp_handler) === 1 && function_exists($temp_handler = $temp_handler[0]))
        ) {

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

            return $temp_handler;
        }

        // If the handler format is invalid, throw an exception.
        throw new HandlerException('Handler ' . json_encode($handler) . ' not found.');
    }
}
