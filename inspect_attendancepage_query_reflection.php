<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\User\Pages\AttendancePage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::find(5);
if (! $user) {
    echo 'no user 5';
    exit(1);
}
Auth::login($user);

$page = new AttendancePage();

$ref = new ReflectionMethod(AttendancePage::class, 'getTableQuery');
$ref->setAccessible(true);
$builder = $ref->invoke($page);

if (method_exists($builder, 'toSql')) {
    echo 'sql: '.$builder->toSql()."\n";
}
if (method_exists($builder, 'getBindings')) {
    echo 'bindings: '.json_encode($builder->getBindings())."\n";
}
$count = $builder->count();
echo 'count: '.$count."\n";
$rows = $builder->get();
foreach ($rows as $row) {
    echo get_class($row).': '.$row->id.' '.$row->name.' status='.$row->status."\n";
    echo 'attendances: '.count($row->attendances)."\n";
}
