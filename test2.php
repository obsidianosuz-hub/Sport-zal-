<?php

$user = \App\Models\User::first();
if($user) auth()->login($user);

$request = Illuminate\Http\Request::create('/kitchen', 'POST', [
    'product_id' => 1,
    'quantity' => 1,
]);
$controller = app(\App\Http\Controllers\KitchenController::class);
try {
    $controller->store($request);
    echo "Sale stored successfully.\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: " . json_encode($e->errors()) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
