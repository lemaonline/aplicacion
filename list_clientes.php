<?php
$dir = 'app/Filament/Resources/Clientes';
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
);
foreach ($iterator as $file) {
    echo $file->getPathname() . ' (' . $file->getSize() . " bytes)\n";
}
