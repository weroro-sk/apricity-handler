# Handler

The `Handler` class is responsible for triggering and parsing handlers. Handlers can be strings in the format
`Class@method`, `callable` functions, `array`s containing a class and method name or `array`s containing `callable`
function. The class ensures the handler is valid and can be executed with the provided variables.

## Installation

```shell
composer require apricity/handler
```

## Table of contents

1. Usage
    - API
        - [Handler::simpleTrigger](#handlersimpletrigger)
        - [Handler::trigger](#handlertrigger)
        - [Handler::parse](#handlerparse)
2. [Tests](#run-tests)
3. [Contributing](#contributing)
4. [Changelog](CHANGELOG.md)
5. [License](#license)

---

### Handler::simpleTrigger

Executes a given handler with provided variables.

This method can handle multiple types of handlers: an array with a class and method, a callable, or a string. It
attempts to execute the handler with the provided variables and returns the result. If the handler is invalid or
execution fails, it throws a HandlerException.

```php
public static function simpleTrigger(array|callable|string $handler, array $vars = []): mixed
```

**Parameters:**

- `array|callable|string $handler`: The handler to be executed.
    - **It can be**:
        - A callable/closure function.
        - A string representing a function name.
        - An array containing a single string element representing a function name.
        - An array containing a class and method.
        - An array containing a class name and method as strings.
- `array $vars`: [optional] The variables to pass to the handler. Default is an empty array.

**Returns:** `mixed` - The result of the handler execution.

**Throws:** `HandlerException` - If the handler is invalid or execution fails.

#### Example

```php
namespace Apricity;

use HandlerException;

class ExampleClass
{
    public function exampleMethod($param1, $param2)
    {
        return "Example method executed with $param1 and $param2.";
    }
}

// Define a standalone function to use as a handler
function exampleFunction($param1, $param2)
{
    return "Example function executed with $param1 and $param2.";
}

try {
    $result = Handler::simpleTrigger(function($a, $b) { return $a + $b; }, [5, 3]);
    echo $result; // Outputs: 8

    $result = Handler::simpleTrigger('strtolower', ['HELLO']);
    echo $result; // Outputs: hello

    // Using callable array [Class, method]
    $result = Handler::simpleTrigger([ExampleClass::class, 'exampleMethod'], ['value1', 'value2']);
    echo $result; // Outputs: Example method executed with value1 and value2.

    // Using callable array ["function"]
    $result = Handler::simpleTrigger(['exampleFunction'], ['value1', 'value2']);
    echo $result; // Outputs: Example function executed with value1 and value2.

} catch (HandlerException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

### Handler::trigger

Triggers the execution of a given handler with provided variables.

This method first parses the handler to ensure it's in the correct format, and then executes it using the simpleTrigger
method. The handler can be a string, callable, or an array. It returns the result of the handler execution.

```php
public static function trigger(array|callable|string $handler, array $vars = []): mixed
```

**Parameters:**

- `array|callable|string $handler`: The handler to be executed.
    - **It can be**:
        - A string in the format "Class@method".
        - A string representing a function name.
        - An array containing a single string element representing a function name.
        - An array containing a class and method.
        - An array containing a class name and method as strings.
- `array $vars`: [optional] The variables to pass to the handler. Default is an empty array.

**Returns:** `mixed` - The result of the handler execution.

**Throws:** `HandlerException` - If the handler is invalid or execution fails.

#### Example

```php
namespace Apricity;

use HandlerException;

class ExampleClass
{
    public function exampleMethod($param1, $param2)
    {
        return "Example method executed with $param1 and $param2.";
    }
}

// Define a standalone function to use as a handler
function exampleFunction($param1, $param2)
{
    return "Example function executed with $param1 and $param2.";
}

try {
    // Example 1: Using 'Class@method' string format
    $result1 = Handler::trigger('ExampleClass@exampleMethod', ['value1', 'value2']);
    echo $result1; // Outputs: Example method executed with value1 and value2.

    // Example 2: Using callable array [Class, method]
    $result2 = Handler::trigger([ExampleClass::class, 'exampleMethod'], ['value1', 'value2']);
    echo $result2; // Outputs: Example method executed with value1 and value2.

    // Example 3: Using callable "function"
    $result3 = Handler::trigger('exampleFunction', ['value1', 'value2']);
    echo $result3; // Outputs: Example function executed with value1 and value2.
    
    // Example 4: Using callable array ["function"]
    $result4 = Handler::trigger(['exampleFunction'], ['value1', 'value2']);
    echo $result4; // Outputs: Example function executed with value1 and value2.

} catch (HandlerException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

### Handler::parse

Parses a given handler into a standardized array format.

This method accepts a handler in various formats and returns it as an array. It handles strings, callables, and arrays,
caching the result for future use. If the handler is invalid, it throws a HandlerException.

```php
public static function parse(array|callable|string $handler): array
```

**Parameters:**

- `array|callable|string $handler`: The handler to be executed.
    - **It can be**:
        - A string in the format "Class@method".
        - A string representing a function name.
        - An array containing a single string element representing a function name.
        - An array containing a class and method.
        - An array containing a class name and method as strings.

**Returns**: `array` - The parsed handler as an array.

**Throws**: `HandlerException` - If the handler is invalid or not found.

#### Example

```php
namespace Apricity;

use HandlerException;

class ExampleClass
{
    public function exampleMethod()
    {
        return "Example method executed.";
    }
}

// Define a standalone function to use as a handler
function exampleFunction() {
    return "Example function executed.";
}

try {
    // Example 1: Using 'Class@method' string format
    $parsedHandler1 = Handler::parse('ExampleClass@exampleMethod');
    print_r($parsedHandler1); // Outputs: Array ( [0] => ExampleClass [1] => exampleMethod )

    // Example 2: Using callable array [Class, method]
    $parsedHandler2 = Handler::parse([ExampleClass::class, 'exampleMethod']);
    print_r($parsedHandler2); // Outputs: Array ( [0] => ExampleClass [1] => exampleMethod )

    // Example 3: Using callable function
    $parsedHandler3 = Handler::parse('exampleFunction');
    print_r($parsedHandler3); // Outputs: Array ( [0] => exampleFunction )
    
    // Example 4: Using callable ["function"]
    $parsedHandler4 = Handler::parse(['exampleFunction']);
    print_r($parsedHandler4); // Outputs: Array ( [0] => exampleFunction )

} catch (HandlerException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

### Run tests

```shell
composer test
```

---

## Contributing

We welcome contributions from the community! For guidelines on how to contribute, please refer to
the [CONTRIBUTING.md](CONTRIBUTING.md) file.

---

## License

This project is licensed under the BSD 3-Clause License - see the [LICENSE](LICENSE) file for details.

---

The repository has been migrated from GitLab.
