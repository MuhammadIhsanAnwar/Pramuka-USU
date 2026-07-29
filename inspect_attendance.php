<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;
use App\Models\User;

$users = User::limit(20)->get();
echo 'USER ATTENDANCE COUNTS\n';
foreach ($users as $user) {
    $count = Attendance::where('user_id', $user->id)->count();
    if ($count) {
        echo sprintf('USER %s %s => %s\n', $user->id, $user->name, $count);
    }
}

echo '\nSAMPLE ATTENDANCES\n';
$attendances = Attendance::with('user', 'agenda')->limit(10)->get();
foreach ($attendances as $attendance) {
    echo sprintf('id=%s user_id=%s (%s) agenda_id=%s (%s) status=%s scanned_at=%s\n',
        $attendance->id,
        $attendance->user_id,
        $attendance->user?->name ?? 'UNKNOWN',
        $attendance->event_agenda_id,
        $attendance->agenda?->name ?? 'UNKNOWN',
        $attendance->status,
        $attendance->scanned_at?->toDateTimeString() ?? 'NULL'
    );
}
