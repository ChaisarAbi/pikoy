<?php

namespace Database\Seeders;

use App\Models\MlModel;
use App\Models\DatasetVersion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a dataset version first if none exists
        $dataset = DatasetVersion::first();
        if (!$dataset) {
            $dataset = DatasetVersion::create([
                'name' => 'Diabetes Dataset v1.0',
                'description' => 'Initial diabetes prediction dataset',
            ]);
        }

        // Create only Random Forest model
        MlModel::create([
            'name' => 'Random Forest Diabetes Predictor',
            'algorithm' => 'random_forest',
            'version' => '1.0.0',
            'hyperparameters' => json_encode([
                'n_estimators' => 100,
                'max_depth' => 10,
                'min_samples_split' => 2,
                'min_samples_leaf' => 1,
                'random_state' => 42
            ]),
            'accuracy' => 0.85,
            'status' => 'active',
            'description' => 'Random Forest model trained on diabetes dataset with 85% accuracy'
        ]);

        $this->command->info('Dummy ML models created successfully!');
    }
}
