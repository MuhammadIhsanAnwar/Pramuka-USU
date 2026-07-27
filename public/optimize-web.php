<?php
// public/optimize-web.php
// Web-accessible optimize helper. Use only in secured environments.
// Usage: https://your-site/optimize-web.php?key=YOUR_SECRET_KEY

// Security: the script checks the key against OPTIMIZE_KEY in the project's .env.
// If OPTIMIZE_KEY is not set, the script refuses to run.

// NOTE: Running artisan commands via a web endpoint is potentially dangerous.
// Only enable and use this temporarily on trusted / internal networks.

if (PHP_SAPI === 'cli') {
    // If called from CLI, delegate to optimize.php for consistent behavior.
    passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/optimize.php'));
    exit;
}

function envValueFromDotEnv(string $root, string $name): ?string
{
    $envFile = $root . DIRECTORY_SEPARATOR . '.env';
    if (!is_readable($envFile)) {
        return null;
    }
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v);
            if ($k === $name) {
                // remove optional surrounding quotes
                if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
                    $v = substr($v, 1, -1);
                }
                return $v;
            }
        }
    }
    return null;
}

$root = realpath(__DIR__ . '/..');
if ($root === false) {
    http_response_code(500);
    echo "Unable to resolve project root.";
    exit;
}

// Get key from GET or header
$providedKey = $_GET['key'] ?? null;
if ($providedKey === null) {
    // allow header X-OPTIMIZE-KEY
    $headers = getallheaders();
    $providedKey = $headers['X-OPTIMIZE-KEY'] ?? $headers['X-Optimize-Key'] ?? null;
}

$expectedKey = envValueFromDotEnv($root, 'OPTIMIZE_KEY');
if ($expectedKey === null || $expectedKey === '') {
    http_response_code(403);
    echo "OPTIMIZE_KEY is not set in .env. Set OPTIMIZE_KEY and try again.";
    exit;
}

if (!hash_equals((string)$expectedKey, (string)$providedKey)) {
    http_response_code(403);
    echo "Forbidden: invalid key.";
    exit;
}

// Allowed. Run commands and stream output.
set_time_limit(0);
ignore_user_abort(true);
header('Content-Type: text/plain; charset=utf-8');

$php = PHP_BINARY;
$artisan = $root . DIRECTORY_SEPARATOR . 'artisan';
if (!file_exists($artisan)) {
    http_response_code(500);
    echo "artisan not found at $artisan\n";
    exit;
}

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

foreach ($commands as $cmd) {
    echo "\n> $cmd\n\n";
    flush();
    // use passthru to stream
    $ret = 0;
    passthru($cmd . ' 2>&1', $ret);
    if ($ret !== 0 && str_contains($cmd, 'route:cache')) {
        echo "Note: route:cache failed (Closure routes?). Continuing.\n";
        flush();
    }
}

if (file_exists($root . DIRECTORY_SEPARATOR . 'composer.json')) {
    // try composer
    ob_start();
    $composerAvailable = false;
    exec('composer --version 2>&1', $out, $rc);
    if ($rc === 0) {
        $composerAvailable = true;
        echo "\n> composer dump-autoload -o\n\n";
        flush();
        passthru('composer dump-autoload -o 2>&1', $rc2);
        if ($rc2 !== 0) {
            echo "composer dump-autoload failed with code $rc2\n";
        }
    } elseif (file_exists($root . DIRECTORY_SEPARATOR . 'composer.phar')) {
        $phpComposer = "$php \"$root/composer.phar\" dump-autoload -o";
        echo "\n> $phpComposer\n\n";
        passthru($phpComposer . ' 2>&1', $rc2);
        if ($rc2 !== 0) {
            echo "php composer.phar dump-autoload failed with code $rc2\n";
        }
    } else {
        echo "\nComposer not found; skipping dump-autoload\n";
    }
}

echo "\nDone.\n";
exit;
