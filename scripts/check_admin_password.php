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

$hash = (string) $u->getAuthPassword();
echo "hash_prefix=" . substr($hash, 0, 4) . "\n";
echo "hash_len=" . strlen($hash) . "\n";
echo "check_smilecareLTD=" . (Illuminate\Support\Facades\Hash::check('smilecareLTD', $hash) ? 'true' : 'false') . "\n";

