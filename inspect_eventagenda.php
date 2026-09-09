<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventAgenda;
use App\Models\Attendance;
use App\Models\PresensiSession;

$agendas = EventAgenda::query()->select('id','name','status','starts_at','ends_at','qr_token')->get();
echo "EventAgenda count: ".count($agendas)."\n";
foreach ($agendas as $agenda) {
    echo implode(' | ', [
        $agenda->id,
        $agenda->name,
        $agenda->status,
        $agenda->starts_at,
        $agenda->ends_at,
        $agenda->qr_token,
    ])."\n";
}

$att = Attendance::query()->select('id','user_id','event_agenda_id','status','starts_at','ends_at')->get();
echo "Attendance count: ".count($att)."\n";
foreach ($att as $row) {
    echo implode(' | ', [$row->id, $row->user_id, $row->event_agenda_id, $row->status, $row->starts_at, $row->ends_at])."\n";
}

$ps = PresensiSession::query()->select('id','name','status','starts_at','ends_at','event_agenda_id')->get();
echo "PresensiSession count: ".count($ps)."\n";
foreach ($ps as $row) {
    echo implode(' | ', [$row->id, $row->name, $row->status, $row->starts_at, $row->ends_at, $row->event_agenda_id])."\n";
}
