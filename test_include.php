<?php
$classmap = require 'vendor/composer/autoload_classmap.php';
$class = 'App\\Filament\\Resources\\Clientes\\ClienteResource';
echo "ClienteResource path: " . $classmap[$class] . "\n";
echo "File exists: " . (file_exists($classmap[$class]) ? 'yes' : 'no') . "\n";

// Try to include it directly
echo "\nTrying direct include...\n";
try {
    include_once $classmap[$class];
    echo "Include successful!\n";
    echo "Class exists: " . (class_exists($class, false) ? 'yes' : 'no') . "\n";
} catch (Error $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
}
