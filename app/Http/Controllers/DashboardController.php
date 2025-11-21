<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Examination;
use App\Models\MlModel;
use App\Models\Prediction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $totalPatients = Patient::count();
        $totalExaminations = Examination::count();
        $totalModels = MlModel::count();
        $totalPredictions = Prediction::count();

        // Get prediction statistics - use predicted_label as fallback if prediction_result is null
        $diabetesCount = Prediction::where(function($query) {
            $query->where('prediction_result', 'diabetes')
                  ->orWhere(function($q) {
                      $q->whereNull('prediction_result')
                        ->where('predicted_label', 1);
                  });
        })->count();
        
        $normalCount = Prediction::where(function($query) {
            $query->where('prediction_result', 'normal')
                  ->orWhere(function($q) {
                      $q->whereNull('prediction_result')
                        ->where('predicted_label', 0);
                  });
        })->count();

        // Get recent predictions with patient details
        $recentPredictions = Prediction::with(['patient', 'model'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'stats' => [
                'total_patients' => $totalPatients,
                'total_examinations' => $totalExaminations,
                'total_models' => $totalModels,
                'total_predictions' => $totalPredictions,
                'diabetes_count' => $diabetesCount,
                'normal_count' => $normalCount
            ],
            'recent_predictions' => $recentPredictions,
            'prediction_distribution' => [
                'diabetes' => $diabetesCount,
                'normal' => $normalCount
            ]
        ]);
    }

    /**
     * Get patient statistics
     */
    public function patientStats($patientId)
    {
        $patient = Patient::with(['examinations', 'predictions.model'])
            ->findOrFail($patientId);

        $totalExaminations = $patient->examinations->count();
        $totalPredictions = $patient->predictions->count();
        
        // Use predicted_label as fallback if prediction_result is null
        $diabetesPredictions = $patient->predictions->filter(function($pred) {
            return $pred->prediction_result === 'diabetes' || 
                   ($pred->prediction_result === null && $pred->predicted_label == 1);
        })->count();
        
        $normalPredictions = $patient->predictions->filter(function($pred) {
            return $pred->prediction_result === 'normal' || 
                   ($pred->prediction_result === null && $pred->predicted_label == 0);
        })->count();

        $latestPrediction = $patient->predictions->sortByDesc('created_at')->first();

        return response()->json([
            'patient' => $patient,
            'stats' => [
                'total_examinations' => $totalExaminations,
                'total_predictions' => $totalPredictions,
                'diabetes_predictions' => $diabetesPredictions,
                'normal_predictions' => $normalPredictions,
                'latest_prediction' => $latestPrediction
            ],
            'prediction_history' => $patient->predictions->sortByDesc('created_at')->take(10)
        ]);
    }

    /**
     * Get prediction statistics by model
     */
    public function modelStats($modelId)
    {
        $model = MlModel::with(['predictions.patient'])->findOrFail($modelId);

        $totalPredictions = $model->predictions->count();
        $diabetesCount = $model->predictions->where('prediction_result', 'diabetes')->count();
        $normalCount = $model->predictions->where('prediction_result', 'normal')->count();

        $accuracy = $totalPredictions > 0 ? ($diabetesCount + $normalCount) / $totalPredictions : 0;

        return response()->json([
            'model' => $model,
            'stats' => [
                'total_predictions' => $totalPredictions,
                'diabetes_count' => $diabetesCount,
                'normal_count' => $normalCount,
                'accuracy' => round($accuracy * 100, 2)
            ],
            'recent_predictions' => $model->predictions->sortByDesc('created_at')->take(10)
        ]);
    }

    /**
     * Get overall system statistics
     */
    public function systemStats()
    {
        $totalPatients = Patient::count();
        $totalExaminations = Examination::count();
        $totalModels = MlModel::count();
        $totalPredictions = Prediction::count();

        // Age distribution
        $ageGroups = [
            'under_30' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 30')->count(),
            '30_45' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 30 AND 45')->count(),
            '45_60' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN 45 AND 60')->count(),
            'over_60' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, dob, CURDATE()) > 60')->count()
        ];

        // Gender distribution
        $genderDistribution = [
            'male' => Patient::where('sex', 'L')->count(),
            'female' => Patient::where('sex', 'P')->count()
        ];

        // Model performance
        $models = MlModel::withCount('predictions')->get();

        return response()->json([
            'overview' => [
                'total_patients' => $totalPatients,
                'total_examinations' => $totalExaminations,
                'total_models' => $totalModels,
                'total_predictions' => $totalPredictions
            ],
            'demographics' => [
                'age_groups' => $ageGroups,
                'gender_distribution' => $genderDistribution
            ],
            'models' => $models
        ]);
    }
}
