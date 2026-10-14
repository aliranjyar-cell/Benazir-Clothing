<?php

require 'vendor/autoload.php';

try {
    $c = new ReflectionClass('App\Http\Controllers\CategoryController');
    echo 'Found: ' . $c->getName() . PHP_EOL;
} catch (ReflectionException $e) {
    echo 'Not found: ' . $e->getMessage() . PHP_EOL;
}
