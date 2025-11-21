<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Patient Routes
Route::apiResource('patients', PatientController::class);

// Examination Routes
Route::apiResource('examinations', ExaminationController::class);
Route::get('patients/{patient}/examinations', [ExaminationController::class, 'byPatient']);

// Dataset Version Routes
Route::apiResource('datasets', DatasetController::class);
Route::get('datasets/{dataset}/training-runs', [DatasetController::class, 'trainingRuns']);

// Model Routes
Route::apiResource('models', ModelController::class);
Route::get('models/{model}/training-runs', [ModelController::class, 'trainingRuns']);
Route::get('models/{model}/predictions', [ModelController::class, 'predictions']);

// Training Run Routes
Route::apiResource('training-runs', TrainingController::class);
Route::post('training/start', [TrainingController::class, 'startTraining']);
Route::post('training-runs/{trainingRun}/finish', [TrainingController::class, 'finishTraining']);

// Prediction Routes
Route::apiResource('predictions', PredictionController::class);
Route::post('predict', [PredictionController::class, 'predict']);
Route::get('patients/{patientId}/predictions', [PredictionController::class, 'byPatient']);
Route::get('training-runs/{trainingRun}/predictions', [PredictionController::class, 'byTrainingRun']);

// Dashboard Routes
Route::get('dashboard/stats', [DashboardController::class, 'stats']);
Route::get('dashboard/system-stats', [DashboardController::class, 'systemStats']);
Route::get('patients/{patientId}/stats', [DashboardController::class, 'patientStats']);
Route::get('models/{modelId}/stats', [DashboardController::class, 'modelStats']);
