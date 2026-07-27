<?php
// public/artisan/artisan-runner.php
// Local wrapper of the root artisan-runner for UI under /artisan

// include session helper from same folder
require __DIR__ . '/session_init.php';

// include and run the root runner
$rootRunner = realpath(__DIR__ . '/../artisan-runner.php');
if ($rootRunner && file_exists($rootRunner)) {
    require $rootRunner;
    exit;
}

http_response_code(500);
echo "artisan-runner not found";
exit;
