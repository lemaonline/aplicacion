<?php
$file = 'app/Filament/Resources/Clientes/ClienteResource.php';

echo "=== File Info ===\n";
echo "Exists: " . (file_exists($file) ? 'yes' : 'no') . "\n";
echo "Size: " . filesize($file) . " bytes\n";
echo "Modified: " . date('Y-m-d H:i:s', filemtime($file)) . "\n";

echo "\n=== File Content (first 500 chars) ===\n";
$content = file_get_contents($file);
echo substr($content, 0, 500);
echo "\n\n=== Content Length ===\n";
echo strlen($content) . " characters\n";

echo "\n=== Contains $navigationGroup? ===\n";
echo (strpos($content, '$navigationGroup') !== false ? 'YES' : 'NO') . "\n";

echo "\n=== Content starts with <?php? ===\n";
echo (str_starts_with(trim($content), '<?php') ? 'YES' : 'NO') . "\n";

echo "\n=== First 20 bytes hex ===\n";
for ($i = 0; $i < min(20, strlen($content)); $i++) {
    echo sprintf("%02x ", ord($content[$i]));
}
echo "\n";
