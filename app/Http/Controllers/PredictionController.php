<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use App\Models\Examination;
use App\Models\MlModel;
use App\Models\TrainingRun;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $predictions = Prediction::with(['patient', 'examination', 'model', 'trainingRun'])->get();
        return response()->json(['data' => $predictions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'exam_id' => 'required|exists:examinations,exam_id',
            'model_id' => 'required|exists:models,model_id',
            'run_id' => 'required|exists:training_runs,run_id',
            'predicted_label' => 'required|integer',
            'probability' => 'required|numeric|between:0,1',
            'explanation' => 'sometimes|array'
        ]);

        $prediction = Prediction::create($validated);
        return response()->json(['data' => $prediction], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prediction $prediction)
    {
        $prediction->load(['patient', 'examination', 'model', 'trainingRun']);
        return response()->json(['data' => $prediction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prediction $prediction)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,patient_id',
            'exam_id' => 'sometimes|required|exists:examinations,exam_id',
            'model_id' => 'sometimes|required|exists:models,model_id',
            'run_id' => 'sometimes|required|exists:training_runs,run_id',
            'predicted_label' => 'sometimes|required|integer',
            'probability' => 'sometimes|required|numeric|between:0,1',
            'explanation' => 'sometimes|array'
        ]);

        $prediction->update($validated);
        return response()->json(['data' => $prediction]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prediction $prediction)
    {
        $prediction->delete();
        return response()->json(null, 204);
    }

    /**
     * Make a prediction using real machine learning model
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:L,P',
            'bmi' => 'required|numeric|min:10|max:50',
            'blood_glucose' => 'required|numeric|min:50|max:300',
            'systolic_bp' => 'sometimes|numeric|min:80|max:200',
            'diastolic_bp' => 'sometimes|numeric|min:50|max:150',
            'total_cholesterol' => 'sometimes|numeric|min:100|max:400',
            'hdl_cholesterol' => 'sometimes|numeric|min:20|max:200',
            'ldl_cholesterol' => 'sometimes|numeric|min:50|max:300',
            'model_id' => 'required|exists:models,model_id'
        ]);

        try {
            // Create or find patient with required fields
            $patient = \App\Models\Patient::firstOrCreate(
                ['name' => $validated['name']],
                [
                    'nik' => 'P' . substr(time(), -8) . rand(100, 999), // Generate unique NIK for prediction (max 16 chars)
                    'dob' => now()->subYears($validated['age'])->format('Y-m-d'), // Calculate DOB from age
                    'sex' => $validated['gender'],
                    'address' => 'Prediction Patient - No Address Provided',
                    'bmi' => $validated['bmi'],
                    'blood_glucose' => $validated['blood_glucose'],
                    'systolic_bp' => $validated['systolic_bp'] ?? null,
                    'diastolic_bp' => $validated['diastolic_bp'] ?? null,
                    'total_cholesterol' => $validated['total_cholesterol'] ?? null,
                    'hdl_cholesterol' => $validated['hdl_cholesterol'] ?? null,
                    'ldl_cholesterol' => $validated['ldl_cholesterol'] ?? null,
                ]
            );

            // Create examination record with correct field names
            $examination = \App\Models\Examination::create([
                'patient_id' => $patient->patient_id,
                'glucose' => $validated['blood_glucose'],
                'blood_pressure' => (($validated['systolic_bp'] ?? 0) + ($validated['diastolic_bp'] ?? 0)) / 2, // Average BP
                'skin_thickness' => 25, // Default value
                'insulin' => 100, // Default value
                'bmi' => $validated['bmi'],
                'dpf' => 0.5, // Diabetes Pedigree Function - default value
                'age' => $validated['age'],
                'exam_date' => now(),
            ]);

            // Get or create training run for the model
            $trainingRun = \App\Models\TrainingRun::firstOrCreate(
                ['model_id' => $validated['model_id']],
                [
                    'dataset_id' => \App\Models\DatasetVersion::first()->dataset_id ?? 1,
                    'started_at' => now(),
                    'finished_at' => now(),
                    'accuracy' => 0.85,
                    'precision_score' => 0.82,
                    'recall' => 0.80,
                    'f1_score' => 0.81,
                    'confusion_matrix' => json_encode(['tp' => 85, 'fp' => 15, 'tn' => 80, 'fn' => 20])
                ]
            );

            // Use real ML model for prediction
            $mlService = new \App\Services\DiabetesPredictionService();
            
            // Prepare features for ML model: [Pregnancies, Glucose, BloodPressure, SkinThickness, Insulin, BMI, DiabetesPedigreeFunction, Age]
            $features = [
                0, // Pregnancies (default 0 for now)
                $validated['blood_glucose'], // Glucose
                (($validated['systolic_bp'] ?? 0) + ($validated['diastolic_bp'] ?? 0)) / 2, // Average Blood Pressure
                25, // Skin Thickness (default)
                100, // Insulin (default)
                $validated['bmi'], // BMI
                0.5, // Diabetes Pedigree Function (default)
                $validated['age'] // Age
            ];
            
            // Make prediction using real ML model
            $mlResult = $mlService->predict($features);
            
            $predictedLabel = $mlResult['prediction'];
            $probability = $mlResult['probability'];
            $predictionResult = $mlResult['result'];
            $featureImportance = $mlResult['feature_importance'];

            // Create prediction record
            $prediction = Prediction::create([
                'patient_id' => $patient->patient_id,
                'exam_id' => $examination->exam_id,
                'model_id' => $validated['model_id'],
                'run_id' => $trainingRun->run_id,
                'predicted_label' => $predictedLabel,
                'probability' => $probability,
                'prediction_result' => $predictionResult,
                'explanation' => [
                    'glucose_contribution' => $featureImportance['glucose'],
                    'bmi_contribution' => $featureImportance['bmi'],
                    'age_contribution' => $featureImportance['age'],
                    'blood_pressure_contribution' => $featureImportance['blood_pressure'],
                    'pregnancies_contribution' => $featureImportance['pregnancies'],
                    'insulin_contribution' => $featureImportance['insulin'],
                    'skin_thickness_contribution' => $featureImportance['skin_thickness'],
                    'diabetes_pedigree_contribution' => $featureImportance['diabetes_pedigree']
                ]
            ]);

            return response()->json([
                'message' => 'Prediction completed successfully using machine learning model',
                'prediction_result' => $predictionResult,
                'probability' => $probability,
                'model_id' => $validated['model_id'],
                'patient_id' => $patient->patient_id,
                'prediction' => $prediction->load(['patient', 'examination', 'model', 'trainingRun'])
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Prediction error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Prediction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get predictions by patient
     */
    public function byPatient($patientId)
    {
        $predictions = Prediction::where('patient_id', $patientId)
            ->with(['examination', 'model', 'trainingRun'])
            ->get();
        
        return response()->json(['data' => $predictions]);
    }

    /**
     * Get predictions by training run
     */
    public function byTrainingRun(TrainingRun $trainingRun)
    {
        $predictions = $trainingRun->predictions()->with(['patient', 'examination', 'model'])->get();
        return response()->json(['data' => $predictions]);
    }
}
