<?php
$file = 'app/Filament/Resources/Clientes/ClienteResource.php';

// Get what PHP sees
$content = file_get_contents($file);
echo "PHP file_get_contents length: " . strlen($content) . "\n";
echo "PHP MD5: " . md5($content) . "\n";

// Check for BOM or other hidden characters
$firstBytes = substr($content, 0, 10);
echo "First 10 bytes hex: ";
for ($i = 0; $i < strlen($firstBytes); $i++) {
    echo sprintf("%02x ", ord($firstBytes[$i]));
}
echo "\n";

// Read using SplFileObject
$spl = new SplFileObject($file, 'r');
$splContent = '';
while (!$spl->eof()) {
    $splContent .= $spl->fgets();
}
echo "SplFileObject length: " . strlen($splContent) . "\n";
echo "SplFileObject MD5: " . md5($splContent) . "\n";

// Check if they match
if ($content === $splContent) {
    echo "Content matches: YES\n";
} else {
    echo "Content matches: NO\n";
}

// Try to require the file without autoloading
echo "\nTrying to require directly...\n";
try {
    // First check if syntax is valid
    $result = shell_exec('php -l ' . escapeshellarg($file) . ' 2>&1');
    echo "Syntax check: " . $result;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
