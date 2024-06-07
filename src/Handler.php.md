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
