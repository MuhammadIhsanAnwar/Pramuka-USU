<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Boot the kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;

$argv = $_SERVER['argv'];
$id = $argv[1] ?? null;

if ($id) {
    $att = Attendance::find($id);
    if (! $att) {
        echo "Attendance not found: $id\n";
        exit(0);
    }
    echo "id: {$att->id}\n";
    echo "user_id: {$att->user_id}\n";
    echo "event_agenda_id: {$att->event_agenda_id}\n";
    echo "photo_path: {$att->photo_path}\n";
    echo "photo_url: " . ($att->photo_path ? asset('storage/'.$att->photo_path) : '-') . "\n";
    echo "latitude: {$att->latitude}\n";
    echo "longitude: {$att->longitude}\n";
    echo "scanned_at: {$att->scanned_at}\n";
    echo "status: {$att->status}\n";
    exit(0);
}

$latest = Attendance::latest('scanned_at')->take(10)->get();
foreach ($latest as $att) {
    echo "id: {$att->id} | event: {$att->event_agenda_id} | user: {$att->user_id} | scanned_at: {$att->scanned_at} | photo: " . ($att->photo_path ? $att->photo_path : '-') . " | latlon: " . ($att->latitude ? $att->latitude : '-') . "," . ($att->longitude ? $att->longitude : '-') . "\n";
}
