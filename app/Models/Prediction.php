<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $primaryKey = 'prediction_id';
    protected $fillable = [
        'patient_id',
        'exam_id',
        'model_id',
        'run_id',
        'predicted_label',
        'probability',
        'explanation'
    ];

    protected $casts = [
        'explanation' => 'array',
    ];

    /**
     * Get the patient that owns the prediction.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * Get the examination that owns the prediction.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class, 'exam_id', 'exam_id');
    }

    /**
     * Get the model that owns the prediction.
     */
    public function model()
    {
        return $this->belongsTo(MlModel::class, 'model_id', 'model_id');
    }

    /**
     * Get the training run that owns the prediction.
     */
    public function trainingRun()
    {
        return $this->belongsTo(TrainingRun::class, 'run_id', 'run_id');
    }
}
