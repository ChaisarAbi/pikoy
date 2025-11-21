<?php

namespace App\Http\Controllers;

use App\Models\TrainingRun;
use App\Models\MlModel;
use App\Models\DatasetVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingRuns = TrainingRun::with(['model', 'datasetVersion'])->get();
        return response()->json($trainingRuns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_id' => 'required|exists:models,model_id',
            'dataset_id' => 'required|exists:dataset_versions,dataset_id',
            'accuracy' => 'sometimes|numeric|between:0,1',
            'precision_score' => 'sometimes|numeric|between:0,1',
            'recall' => 'sometimes|numeric|between:0,1',
            'f1_score' => 'sometimes|numeric|between:0,1',
            'confusion_matrix' => 'sometimes|array',
            'started_at' => 'sometimes|date',
            'finished_at' => 'sometimes|date|after:started_at'
        ]);

        // Set default started_at if not provided
        if (!isset($validated['started_at'])) {
            $validated['started_at'] = Carbon::now();
        }

        $trainingRun = TrainingRun::create($validated);
        return response()->json($trainingRun, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingRun $trainingRun)
    {
        $trainingRun->load(['model', 'datasetVersion', 'predictions']);
        return response()->json($trainingRun);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingRun $trainingRun)
    {
        $validated = $request->validate([
            'model_id' => 'sometimes|required|exists:models,model_id',
            'dataset_id' => 'sometimes|required|exists:dataset_versions,dataset_id',
            'accuracy' => 'sometimes|numeric|between:0,1',
            'precision_score' => 'sometimes|numeric|between:0,1',
            'recall' => 'sometimes|numeric|between:0,1',
            'f1_score' => 'sometimes|numeric|between:0,1',
            'confusion_matrix' => 'sometimes|array',
            'started_at' => 'sometimes|date',
            'finished_at' => 'sometimes|date|after:started_at'
        ]);

        $trainingRun->update($validated);
        return response()->json($trainingRun);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingRun $trainingRun)
    {
        $trainingRun->delete();
        return response()->json(null, 204);
    }

    /**
     * Start a training run (dummy implementation)
     */
    public function startTraining(Request $request)
    {
        $validated = $request->validate([
            'model_id' => 'required|exists:models,model_id',
            'dataset_id' => 'required|exists:dataset_versions,dataset_id'
        ]);

        // Create a training run with started_at timestamp
        $trainingRun = TrainingRun::create([
            'model_id' => $validated['model_id'],
            'dataset_id' => $validated['dataset_id'],
            'started_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Training started successfully',
            'training_run' => $trainingRun
        ], 201);
    }

    /**
     * Finish a training run (dummy implementation)
     */
    public function finishTraining(TrainingRun $trainingRun, Request $request)
    {
        $validated = $request->validate([
            'accuracy' => 'required|numeric|between:0,1',
            'precision_score' => 'required|numeric|between:0,1',
            'recall' => 'required|numeric|between:0,1',
            'f1_score' => 'required|numeric|between:0,1',
            'confusion_matrix' => 'required|array'
        ]);

        $trainingRun->update([
            'accuracy' => $validated['accuracy'],
            'precision_score' => $validated['precision_score'],
            'recall' => $validated['recall'],
            'f1_score' => $validated['f1_score'],
            'confusion_matrix' => $validated['confusion_matrix'],
            'finished_at' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Training completed successfully',
            'training_run' => $trainingRun
        ]);
    }
}
