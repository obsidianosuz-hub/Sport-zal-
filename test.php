<?php

$request = Illuminate\Http\Request::create('/clients', 'POST', [
    'name' => 'Test Client',
    'phone' => '+998901234567',
    'subscription_type' => 'oddiy',
    'subscription_expires_at' => ''
]);
$controller = app(\App\Http\Controllers\ClientController::class);
try {
    $controller->store($request);
    echo "Client stored successfully.\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
