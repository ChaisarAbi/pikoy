<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlModel extends Model
{
    use HasFactory;

    protected $table = 'models';
    protected $primaryKey = 'model_id';
    protected $fillable = [
        'name',
        'algorithm',
        'version',
        'hyperparameters'
    ];

    protected $casts = [
        'hyperparameters' => 'array',
    ];

    /**
     * Get the training runs for the model.
     */
    public function trainingRuns()
    {
        return $this->hasMany(TrainingRun::class, 'model_id', 'model_id');
    }

    /**
     * Get the predictions for the model.
     */
    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'model_id', 'model_id');
    }
}
