<?php
require 'vendor/autoload.php';
$reflection = new ReflectionClass('Filament\Resources\Resource');
foreach ($reflection->getProperties() as $prop) {
    if ($prop->getName() === 'navigationGroup') {
        echo 'Property: ' . $prop->getName() . "\n";
        echo 'Type: ' . $prop->getType() . "\n";
        echo 'Visibility: ' . ($prop->isPublic() ? 'public' : ($prop->isProtected() ? 'protected' : 'private')) . "\n";
        echo 'Static: ' . ($prop->isStatic() ? 'yes' : 'no') . "\n";
    }
}
