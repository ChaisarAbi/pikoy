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
        return response()->json($predictions);
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
        return response()->json($prediction, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prediction $prediction)
    {
        $prediction->load(['patient', 'examination', 'model', 'trainingRun']);
        return response()->json($prediction);
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
        return response()->json($prediction);
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
     * Make a prediction (dummy implementation)
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:examinations,exam_id',
            'model_id' => 'required|exists:models,model_id',
            'run_id' => 'required|exists:training_runs,run_id'
        ]);

        // Get the examination data
        $examination = Examination::with('patient')->find($validated['exam_id']);

        // Dummy prediction logic - in real implementation this would use the ML model
        $predictedLabel = rand(0, 1); // Random prediction (0 = no diabetes, 1 = diabetes)
        $probability = rand(50, 95) / 100; // Random probability between 0.5 and 0.95

        // Create prediction record
        $prediction = Prediction::create([
            'patient_id' => $examination->patient_id,
            'exam_id' => $validated['exam_id'],
            'model_id' => $validated['model_id'],
            'run_id' => $validated['run_id'],
            'predicted_label' => $predictedLabel,
            'probability' => $probability,
            'explanation' => [
                'glucose_contribution' => rand(10, 30),
                'bmi_contribution' => rand(5, 25),
                'age_contribution' => rand(5, 20),
                'other_factors' => rand(10, 40)
            ]
        ]);

        return response()->json([
            'message' => 'Prediction completed successfully',
            'prediction' => $prediction->load(['patient', 'examination', 'model', 'trainingRun'])
        ], 201);
    }

    /**
     * Get predictions by patient
     */
    public function byPatient($patientId)
    {
        $predictions = Prediction::where('patient_id', $patientId)
            ->with(['examination', 'model', 'trainingRun'])
            ->get();
        
        return response()->json($predictions);
    }

    /**
     * Get predictions by training run
     */
    public function byTrainingRun(TrainingRun $trainingRun)
    {
        $predictions = $trainingRun->predictions()->with(['patient', 'examination', 'model'])->get();
        return response()->json($predictions);
    }
}
