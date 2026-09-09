<?php
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views';
$tmp = tempnam($dir, 'test');
file_put_contents($tmp, 'x');
$target = $dir . '/test-rename-' . uniqid() . '.php';
echo "tmp=$tmp\n";
echo "target=$target\n";
var_export(rename($tmp, $target));
echo "\n";
var_export(file_exists($target));
echo "\n";
if (file_exists($target)) unlink($target);
