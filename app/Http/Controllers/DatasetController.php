<?php

namespace App\Http\Controllers;

use App\Models\DatasetVersion;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datasets = DatasetVersion::all();
        return response()->json($datasets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string'
        ]);

        $dataset = DatasetVersion::create($validated);
        return response()->json($dataset, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DatasetVersion $dataset)
    {
        return response()->json($dataset);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatasetVersion $dataset)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|string'
        ]);

        $dataset->update($validated);
        return response()->json($dataset);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatasetVersion $dataset)
    {
        $dataset->delete();
        return response()->json(null, 204);
    }

    /**
     * Get training runs for a specific dataset
     */
    public function trainingRuns(DatasetVersion $dataset)
    {
        $trainingRuns = $dataset->trainingRuns()->with('model')->get();
        return response()->json($trainingRuns);
    }
}
