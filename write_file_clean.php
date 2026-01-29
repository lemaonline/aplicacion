<?php
$file = $argv[1];
$content = stream_get_contents(STDIN);
file_put_contents($file, $content);
echo "Wrote " . strlen($content) . " bytes to $file\n";
