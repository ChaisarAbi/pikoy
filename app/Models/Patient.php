<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'patient_id';
    protected $fillable = [
        'nik',
        'name',
        'dob',
        'sex',
        'address',
        'bmi',
        'blood_glucose',
    ];

    /**
     * Get the examinations for the patient.
     */
    public function examinations()
    {
        return $this->hasMany(Examination::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the predictions for the patient.
     */
    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->dob)->age;
    }

    /**
     * Get the patient's gender (alias for sex).
     */
    public function getGenderAttribute()
    {
        return $this->sex;
    }

    /**
     * Get the patient's id (alias for patient_id).
     */
    public function getIdAttribute()
    {
        return $this->patient_id;
    }
}
