<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dashboard
Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// Patients
Route::get('/patients', function () {
    return view('patients.index');
});

Route::get('/patients/create', function () {
    return view('patients.create');
});

Route::get('/patients/{id}', function ($id) {
    $patient = \App\Models\Patient::findOrFail($id);
    return view('patients.show', ['patient' => $patient]);
});

// Predictions
Route::get('/predict', function () {
    return view('predict.index');
});

// Models
Route::get('/models', function () {
    return view('models.index');
});

// Welcome page (original Laravel page)
Route::get('/welcome', function () {
    return view('welcome');
});
