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

        // Create dummy Random Forest model
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
        ]);

        // Create additional dummy models
        MlModel::create([
            'name' => 'Logistic Regression Baseline',
            'algorithm' => 'logistic_regression',
            'version' => '1.0.0',
            'hyperparameters' => json_encode([
                'C' => 1.0,
                'penalty' => 'l2',
                'solver' => 'lbfgs',
                'max_iter' => 100
            ]),
        ]);

        MlModel::create([
            'name' => 'XGBoost Diabetes Classifier',
            'algorithm' => 'xgboost',
            'version' => '1.0.0',
            'hyperparameters' => json_encode([
                'n_estimators' => 150,
                'max_depth' => 8,
                'learning_rate' => 0.1,
                'subsample' => 0.8,
                'colsample_bytree' => 0.8
            ]),
        ]);

        $this->command->info('Dummy ML models created successfully!');
    }
}
