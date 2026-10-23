<?php

// Run only against local dummy data: php tools/observer_experiment.php
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
if (!$app->environment('local') || config('database.default') !== 'sqlite') {
    throw new RuntimeException('Eksperimen hanya untuk SQLite lokal.');
}
$user = User::findOrFail('USR001');
$initial = $user->role;
$other = $initial === 'customer' ? 'staff' : 'customer';
$path = storage_path('logs/laravel.log');
$count = fn () => file_exists($path) ? substr_count(file_get_contents($path), 'local.INFO: user.role.changed') : 0;
$start = $count();
try {
    User::whereKey($user->getKey())->update(['role' => $other]);
    $bulk = ['role' => $user->fresh()->role, 'log_delta' => $count() - $start];
} finally {
    // Restore the dummy role by the same bulk path; no event is emitted.
    User::whereKey($user->getKey())->update(['role' => $initial]);
}
$restore = ['role' => $user->fresh()->role, 'log_delta' => $count() - $start];
$user->refresh();
$user->nama_lengkap = $user->nama_lengkap;
$user->save();
echo json_encode(['initial_role' => $initial, 'bulk_update' => $bulk,
    'restored' => $restore, 'unchanged_instance_save_log_delta' => $count() - $start], JSON_PRETTY_PRINT).PHP_EOL;
