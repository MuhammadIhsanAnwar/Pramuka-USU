<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PresensiSession;
use App\Models\PresensiRecord;
use App\Models\Attendance;

function dumpModel($model) {
    echo "=== {$model} ===\n";
    $count = $model::count();
    echo "count: {$count}\n";
    if ($count > 0) {
        $items = $model::query()->limit(10)->get();
        foreach ($items as $item) {
            $props = [];
            foreach (['id','name','status','starts_at','ends_at','created_by','event_agenda_id','user_id','scanned_at'] as $field) {
                if (isset($item->{$field})) {
                    $props[] = $field.':'.(string)$item->{$field};
                }
            }
            echo implode(' | ', $props)."\n";
        }
    }
}

dumpModel('App\\Models\\PresensiSession');
dumpModel('App\\Models\\PresensiRecord');
dumpModel('App\\Models\\Attendance');
