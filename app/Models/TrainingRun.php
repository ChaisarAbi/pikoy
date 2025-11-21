<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRun extends Model
{
    use HasFactory;

    protected $primaryKey = 'run_id';
    protected $fillable = [
        'model_id',
        'dataset_id',
        'accuracy',
        'precision_score',
        'recall',
        'f1_score',
        'confusion_matrix',
        'started_at',
        'finished_at'
    ];

    protected $casts = [
        'confusion_matrix' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Get the model that owns the training run.
     */
    public function model()
    {
        return $this->belongsTo(MlModel::class, 'model_id', 'model_id');
    }

    /**
     * Get the dataset version that owns the training run.
     */
    public function datasetVersion()
    {
        return $this->belongsTo(DatasetVersion::class, 'dataset_id', 'dataset_id');
    }

    /**
     * Get the predictions for the training run.
     */
    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'run_id', 'run_id');
    }
}
