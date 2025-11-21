<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $examinations = Examination::with('patient')->get();
        return response()->json([
            'data' => $examinations,
            'message' => 'Examinations retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'glucose' => 'required|numeric',
            'blood_pressure' => 'required|numeric',
            'skin_thickness' => 'required|numeric',
            'insulin' => 'required|numeric',
            'bmi' => 'required|numeric',
            'dpf' => 'required|numeric',
            'age' => 'required|integer',
            'exam_date' => 'required|date'
        ]);

        $examination = Examination::create($validated);
        return response()->json([
            'data' => $examination,
            'message' => 'Examination created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Examination $examination)
    {
        $examination->load('patient');
        return response()->json([
            'data' => $examination,
            'message' => 'Examination retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Examination $examination)
    {
        $validated = $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,patient_id',
            'glucose' => 'sometimes|required|numeric',
            'blood_pressure' => 'sometimes|required|numeric',
            'skin_thickness' => 'sometimes|required|numeric',
            'insulin' => 'sometimes|required|numeric',
            'bmi' => 'sometimes|required|numeric',
            'dpf' => 'sometimes|required|numeric',
            'age' => 'sometimes|required|integer',
            'exam_date' => 'sometimes|required|date'
        ]);

        $examination->update($validated);
        return response()->json([
            'data' => $examination,
            'message' => 'Examination updated successfully'
        ]);
    }

    /**
     * Get examinations by patient
     */
    public function byPatient(Patient $patient)
    {
        $examinations = $patient->examinations;
        return response()->json([
            'data' => $examinations,
            'message' => 'Patient examinations retrieved successfully'
        ]);
    }
}
