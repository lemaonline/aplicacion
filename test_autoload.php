<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Step 1: Require autoloader ===\n";
require_once 'vendor/autoload.php';
echo "Autoloader loaded successfully\n";

echo "\n=== Step 2: Check if class exists ===\n";
$class = 'App\\Filament\\Resources\\Clientes\\ClienteResource';
try {
    $exists = class_exists($class);
    echo "Class exists: " . ($exists ? 'yes' : 'no') . "\n";

    if ($exists) {
        echo "\n=== Step 3: Reflection ===\n";
        $reflection = new ReflectionClass($class);
        echo "Class loaded from: " . $reflection->getFileName() . "\n";

        // Check navigationGroup property
        if ($reflection->hasProperty('navigationGroup')) {
            $prop = $reflection->getProperty('navigationGroup');
            echo "navigationGroup type: " . $prop->getType() . "\n";
        }
    }
} catch (Error $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "In: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
