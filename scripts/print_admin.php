<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::where('username', 'admin@gmail.com')
    ->orWhere('email', 'admin@gmail.com')
    ->first();

if (!$u) {
    echo "NOT_FOUND\n";
    exit(0);
}

echo json_encode($u->only(['id', 'username', 'email', 'role', 'status']), JSON_PRETTY_PRINT) . "\n";

