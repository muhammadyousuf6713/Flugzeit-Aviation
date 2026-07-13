<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = preg_replace('/lengthMenu:\s*\[\s*\[.*?\]\s*,\s*\[.*?\]\s*\]/s', 'lengthMenu: [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]]', $content);
    $newContent = preg_replace('/"lengthMenu":\s*\[\s*\[.*?\]\s*,\s*\[.*?\]\s*\]/s', '"lengthMenu": [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]]', $newContent);
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated " . $path . PHP_EOL;
    }
}
