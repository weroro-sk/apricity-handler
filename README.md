# Handler

The `Handler` class is responsible for triggering and parsing handlers. Handlers can be strings in the format
`Class@method`, `callable` functions, or `array`s containing a class and method name. The class ensures the handler is
valid and can be executed with the provided variables.

## Installation

```shell
composer require apricity/handler
```

## Table of contents

1. Methods (API)
    - [Handler::trigger](#handlertrigger)
        - [example](#example)
    - [Handler::parse](#handlerparse)
        - [example](#example-1)
2. [Code Details](#code-details)
    - [trigger Method](#trigger-method)
    - [parse Method](#parse-method)
3. [Tests](#run-tests)
4. [License](#license)

---

### Handler::trigger

Triggers the specified handler with the given variables.

The handler can be a string, callable, or an
array. It will be parsed and executed with the provided variables.

```php
public static function trigger(string|callable|array $handler, array $vars = []): mixed
```

**Parameters:**

- `string|callable|array $handler`: The handler to trigger.
- `array $vars`: [optional] Variables to pass to the handler.

**Returns:** `mixed` - The result of the handler execution.

**Throws:** `HandlerException` - If the handler is not valid or cannot be executed.

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

Parses the handler to ensure it's in a valid format and can be executed.

The handler can be a string in the
format `Class@method`, a `callable`, or an `array` with two elements where the first is a class name and the second is a
method name.

```php
public static function parse(string|callable|array $handler): array
```

**Parameters:**

- `string|callable|array $handler`: The handler to parse.

**Returns**: `array` - The parsed handler as an array.

**Throws**: `HandlerException` - If the handler is not valid or cannot be found.

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

## Code Details

### trigger Method

1. **Parse the handler**:
    - Ensures the handler is in the correct format by calling the `parse` method.
2. **Check if the handler is callable**:
    - If the handler is a single-element array, it calls the handler directly with the provided variables.
3. **Check if the handler is a controller**:
    - If the handler is a controller (e.g., `Class@method`), it attempts to call the method on the class instance.
    - If the first attempt fails, it tries the handler as a callable array.
    - Throws an `HandlerException` if both attempts fail.

### parse Method

1. **Check for empty handler**:
    - Throws an `HandlerException` if the handler is empty.
2. **Check for string handler**:
    - If the handler is a string in the format `Class@method`, it splits it into an array.
3. **Check for callable function**:
    - If the handler is a callable function, it returns it as a single-element array.
    - If the handler is an array with a single callable function element, it returns it as a single-element array.
4. **Check for class and method array**:
    - If the handler is an array with two elements (class and method), it validates the class and method.
    - Throws an `HandlerException` if the class or method does not exist.
5. **Invalid handler format**:
    - Throws an `HandlerException` if the handler format is invalid.

---

### Run tests

```shell
composer test
```

---

## License

This project is licensed under the BSD 3-Clause License - see the [LICENSE](LICENSE) file for details.

---

The repository has been migrated from GitLab.