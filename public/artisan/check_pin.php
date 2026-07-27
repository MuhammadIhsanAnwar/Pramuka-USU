<?php
// public/artisan/check_pin.php
// Simple PIN check endpoint for the artisan UI.

if (PHP_SAPI === 'cli') {
    echo "This endpoint is for web use only.\n";
    exit;
}

require __DIR__ . '/session_init.php';

// accept GET or POST
$pin = $_REQUEST['pin'] ?? null;
if ($pin === null) {
    http_response_code(400);
    echo "Missing pin";
    exit;
}

// constant PIN; keep in sync with runner PIN
$PIN = '10982345';

if (hash_equals($PIN, $pin)) {
    // set session flag so UI can show
    $_SESSION['artisan_unlocked'] = true;
    http_response_code(200);
    echo "OK";
    exit;
}

http_response_code(403);
echo "Forbidden";
exit;
