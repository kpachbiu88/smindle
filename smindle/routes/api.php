
<?php

use App\Http\Controllers\OrderController;

Route::post('/order', [OrderController::class, 'create']);