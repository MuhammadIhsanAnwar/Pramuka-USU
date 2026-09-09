<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventAgenda;
use Filament\Facades\Filament;

$id = 5;
$events = EventAgenda::query()
    ->published()
    ->with(['attendances' => fn ($query) => $query->where('user_id', $id)])
    ->orderBy('starts_at')
    ->get();

echo 'count: '.count($events)."\n";
foreach ($events as $event) {
    echo 'id='.$event->id.' name='.$event->name.' status='.$event->status.' starts_at='.$event->starts_at.' ends_at='.$event->ends_at.'\n';
    echo 'attendances: '.count($event->attendances)."\n";
    foreach ($event->attendances as $att) {
        echo '  att '.$att->id.' status='.$att->status.' scanned_at='.$att->scanned_at."\n";
    }
}
