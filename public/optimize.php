<?php
// public/optimize.php
// Run from the public directory via CLI: php optimize.php
// Clears and rebuilds Laravel caches, attempts composer dump-autoload.

if (PHP_SAPI !== 'cli') {
    echo "This script must be run from the command line (CLI)\n";
    http_response_code(400);
    exit(1);
}

$start = new DateTimeImmutable();
$root = realpath(__DIR__ . '/..');
if ($root === false) {
    fwrite(STDERR, "Unable to resolve project root.\n");
    exit(1);
}

$php = PHP_BINARY;
$artisan = $root . DIRECTORY_SEPARATOR . 'artisan';
if (!file_exists($artisan)) {
    fwrite(STDERR, "artisan not found at: $artisan\n");
    exit(1);
}

function runCommand(string $cmd): int
{
    echo "\n> $cmd\n";
    $output = [];
    $exit = 0;
    exec($cmd . ' 2>&1', $output, $exit);
    foreach ($output as $line) {
        echo $line . "\n";
    }
    return $exit;
}

chdir($root);

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
    $rc = runCommand($cmd);
    if ($rc !== 0) {
        // Specific helpful message for route:cache failure
        if (strpos($cmd, 'route:cache') !== false) {
            echo "Note: route:cache failed — you may have Closure routes. This is expected in many apps.\n";
            // continue
        } else {
            echo "Command exited with code $rc. Continuing to next step.\n";
        }
    }
}

// Try composer dump-autoload
if (file_exists($root . DIRECTORY_SEPARATOR . 'composer.json')) {
    $composerAvailable = false;
    // Check for composer in PATH
    exec('composer --version 2>&1', $cOut, $cRc);
    if ($cRc === 0) {
        $composerAvailable = true;
        $rc = runCommand('composer dump-autoload -o');
        if ($rc !== 0) {
            echo "composer dump-autoload failed with code $rc\n";
        }
    } elseif (file_exists($root . DIRECTORY_SEPARATOR . 'composer.phar')) {
        $composerPhar = $root . DIRECTORY_SEPARATOR . 'composer.phar';
        $rc = runCommand("$php \"$composerPhar\" dump-autoload -o");
        if ($rc !== 0) {
            echo "php composer.phar dump-autoload failed with code $rc\n";
        }
    } else {
        echo "Composer not found in PATH and composer.phar missing; skipping dump-autoload.\n";
    }
}

$end = new DateTimeImmutable();
$diff = $end->getTimestamp() - $start->getTimestamp();
echo "\nFinished in {$diff} seconds.\n";
exit(0);
