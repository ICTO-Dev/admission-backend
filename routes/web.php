<?php

use App\Http\Controllers\PermitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Official CBSUA CAT Test Permit Routes (ADM-FR-005)
Route::get('/admission/pdf-permit/{application_no}', [PermitController::class, 'showPermit']);
Route::get('/permits/{application_no}', [PermitController::class, 'showPermit']);
