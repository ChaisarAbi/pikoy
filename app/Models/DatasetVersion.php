<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetVersion extends Model
{
    use HasFactory;

    protected $primaryKey = 'dataset_id';
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get the training runs for the dataset version.
     */
    public function trainingRuns()
    {
        return $this->hasMany(TrainingRun::class, 'dataset_id', 'dataset_id');
    }
}
