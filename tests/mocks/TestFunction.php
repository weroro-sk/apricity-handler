<?php

namespace Apricity\tests\mocks;

if (!function_exists('exampleFunction')) {
    function exampleFunction($param1, $param2): string
    {
        return "Example function executed with $param1 and $param2.";
    }
}
