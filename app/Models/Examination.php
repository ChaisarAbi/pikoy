<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;

    protected $primaryKey = 'exam_id';
    protected $fillable = [
        'patient_id',
        'glucose',
        'blood_pressure',
        'skin_thickness',
        'insulin',
        'bmi',
        'dpf',
        'age',
        'exam_date'
    ];

    /**
     * Get the patient that owns the examination.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the prediction associated with the examination.
     */
    public function prediction()
    {
        return $this->hasOne(Prediction::class, 'exam_id', 'exam_id');
    }
}
