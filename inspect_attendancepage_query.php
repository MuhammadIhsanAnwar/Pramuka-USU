<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Filament\User\Pages\AttendancePage;
use Filament\Facades\Filament;

$auth = app('auth');
// simulate Filament auth user 5 if available
$user = App\Models\User::find(5);
if ($user) {
    auth()->login($user);
}

$page = new AttendancePage();
$builder = $page->getTableQuery();
$count = $builder->count();
echo 'count: '.$count."\n";
$rows = $builder->get();
foreach ($rows as $row) {
    echo get_class($row).': '.$row->id.' '.$row->name.' starts_at='.$row->starts_at.' ends_at='.$row->ends_at.'\n';
}
