<?php

namespace App\Http\Controllers;

use App\Models\MlModel;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = MlModel::all();
        return response()->json($models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'algorithm' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'hyperparameters' => 'sometimes|array'
        ]);

        $model = MlModel::create($validated);
        return response()->json($model, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MlModel $model)
    {
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MlModel $model)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'algorithm' => 'sometimes|required|string|max:255',
            'version' => 'sometimes|required|string|max:50',
            'hyperparameters' => 'sometimes|array'
        ]);

        $model->update($validated);
        return response()->json($model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MlModel $model)
    {
        $model->delete();
        return response()->json(null, 204);
    }

    /**
     * Get training runs for a specific model
     */
    public function trainingRuns(MlModel $model)
    {
        $trainingRuns = $model->trainingRuns()->with('datasetVersion')->get();
        return response()->json($trainingRuns);
    }

    /**
     * Get predictions for a specific model
     */
    public function predictions(MlModel $model)
    {
        $predictions = $model->predictions()->with(['patient', 'examination'])->get();
        return response()->json($predictions);
    }
}
