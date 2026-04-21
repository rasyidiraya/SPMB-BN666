<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/register/send-otp', 'POST', [
    'nama' => 'Rasyid',
    'email' => 'rasyidiraya2007@gmail.com',
    'hp' => '081234567890',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);
$response = $kernel->handle($request);
echo "STATUS: " . $response->getStatusCode() . "\n";
echo "CONTENT: " . $response->getContent() . "\n";
