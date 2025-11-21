<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::all();
        return response()->json([
            'data' => $patients,
            'message' => 'Patients retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|max:16|unique:patients',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'sex' => 'required|in:L,P',
            'address' => 'required|string',
            'bmi' => 'nullable|numeric|min:10|max:50',
            'blood_glucose' => 'nullable|numeric|min:50|max:300'
        ]);

        $patient = Patient::create($validated);
        return response()->json([
            'data' => $patient,
            'message' => 'Patient created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return response()->json([
            'data' => $patient,
            'message' => 'Patient retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nik' => 'sometimes|required|string|max:16|unique:patients,nik,' . $patient->patient_id . ',patient_id',
            'name' => 'sometimes|required|string|max:255',
            'dob' => 'sometimes|required|date',
            'sex' => 'sometimes|required|in:L,P',
            'address' => 'sometimes|required|string',
            'bmi' => 'sometimes|nullable|numeric|min:10|max:50',
            'blood_glucose' => 'sometimes|nullable|numeric|min:50|max:300'
        ]);

        $patient->update($validated);
        return response()->json([
            'data' => $patient,
            'message' => 'Patient updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}
