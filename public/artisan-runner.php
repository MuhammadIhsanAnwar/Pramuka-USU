<?php
// public/artisan-runner.php
// Web-accessible runner for a set of artisan commands.
// Access control: use ?pin=10982345 or header X-RUN-PIN: 10982345
// The script continues executing all commands even if some fail.

if (PHP_SAPI === 'cli') {
    echo "This script is intended for web use. Use php public/optimize.php for CLI.\n";
    exit;
}

// allow session-authenticated UI as well as PIN header/param
require __DIR__ . '/artisan/session_init.php';
$authenticated = false;
if (!empty($_SESSION['artisan_unlocked'])) {
    $authenticated = true;
}

// simple pin check fallback
$pin = $_GET['pin'] ?? null;
if ($pin === null) {
    $headers = getallheaders();
    $pin = $headers['X-RUN-PIN'] ?? $headers['X-Run-Pin'] ?? null;
}

if (!$authenticated) {
    if ($pin !== '10982345') {
        http_response_code(403);
        echo "Forbidden: invalid pin.";
        exit;
    }
}

// Determine project root and artisan
$root = realpath(__DIR__ . '/..');
if ($root === false) {
    http_response_code(500);
    echo "Unable to resolve project root.";
    exit;
}

$artisan = $root . DIRECTORY_SEPARATOR . 'artisan';
if (!file_exists($artisan)) {
    http_response_code(500);
    echo "artisan not found at $artisan";
    exit;
}

// Commands to run (non-destructive defaults). Runs all even if some fail.
$php = PHP_BINARY;
$commands = [
    "$php \"$artisan\" optimize:clear",
    "$php \"$artisan\" cache:clear",
    "$php \"$artisan\" config:clear",
    "$php \"$artisan\" route:clear",
    "$php \"$artisan\" view:clear",
    "$php \"$artisan\" config:cache",
    "$php \"$artisan\" route:cache",
    "$php \"$artisan\" view:cache",
    "$php \"$artisan\" optimize",
];

// Allow optional single artisan command via GET 'cmd' but only simple allowed chars
if (!empty($_GET['cmd'])) {
    $raw = $_GET['cmd'];
    // allow =, /, \\ and dot for options and class names
    if (preg_match('/^[A-Za-z0-9_\\-:=\/\\\\., ]+$/', $raw)) {
        $safe = escapeshellcmd($raw);
        $commands = [ $php . ' ' . escapeshellarg($artisan) . ' ' . $safe ];
    } else {
        echo "Invalid cmd parameter.\n";
    }
}

// Set response headers and disable timeouts
header('Content-Type: text/plain; charset=utf-8');
// Debug info to help diagnose empty-client output
echo "DEBUG: authenticated=" . ($authenticated ? '1' : '0') . "\n";
echo "DEBUG: REQUEST_URI=" . ($_SERVER['REQUEST_URI'] ?? '') . "\n\n";
ignore_user_abort(true);
set_time_limit(0);

echo "Artisan runner started\n";
echo "Project root: $root\n";
echo "Running as: $php\n\n";
flush();

foreach ($commands as $cmd) {
    echo "\n> $cmd\n\n";
    flush();
    $exit = 0;
    passthru($cmd . ' 2>&1', $exit);
    echo "-- exit code: $exit\n";
    flush();
    // continue regardless of exit code
}

// try composer if present
if (file_exists($root . DIRECTORY_SEPARATOR . 'composer.json')) {
    echo "\n> composer dump-autoload -o\n\n";
    flush();

    $composerHome = getenv('COMPOSER_HOME');
    if (!$composerHome) {
        $composerHome = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'composer-home';
        if (!is_dir($composerHome)) {
            @mkdir($composerHome, 0755, true);
        }
        putenv('COMPOSER_HOME=' . $composerHome);
        $_ENV['COMPOSER_HOME'] = $composerHome;
    }

    $rc = null;
    exec('composer --version 2>&1', $out, $rc);
    if ($rc === 0) {
        passthru('composer dump-autoload -o 2>&1', $rc2);
        echo "-- composer exit code: $rc2\n";
    } elseif (file_exists($root . DIRECTORY_SEPARATOR . 'composer.phar')) {
        $cmd = "$php \"$root/composer.phar\" dump-autoload -o";
        passthru($cmd . ' 2>&1', $rc2);
        echo "-- composer.phar exit code: $rc2\n";
    } else {
        echo "Composer not found; skipped.\n";
    }
    flush();
}

echo "\nAll done.\n";
exit;
