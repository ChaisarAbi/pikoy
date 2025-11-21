<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Examination;
use App\Models\DatasetVersion;
use App\Models\MlModel;
use App\Models\TrainingRun;
use App\Models\Prediction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DiabetesPredictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create dataset versions
        $dataset1 = DatasetVersion::create([
            'name' => 'Pima Indians Diabetes Dataset v1.0',
            'description' => 'Dataset awal untuk training model baseline'
        ]);

        $dataset2 = DatasetVersion::create([
            'name' => 'Enhanced Diabetes Dataset v2.0',
            'description' => 'Dataset dengan preprocessing dan feature engineering'
        ]);

        // Create ML models
        $model1 = MlModel::create([
            'name' => 'Random Forest Baseline',
            'algorithm' => 'random_forest',
            'version' => '1.0.0',
            'hyperparameters' => [
                'n_estimators' => 100,
                'max_depth' => 10,
                'random_state' => 42
            ]
        ]);

        $model2 = MlModel::create([
            'name' => 'Random Forest Optimized',
            'algorithm' => 'random_forest',
            'version' => '2.0.0',
            'hyperparameters' => [
                'n_estimators' => 200,
                'max_depth' => 15,
                'min_samples_split' => 5,
                'random_state' => 42
            ]
        ]);

        // Create training runs
        $trainingRun1 = TrainingRun::create([
            'model_id' => $model1->model_id,
            'dataset_id' => $dataset1->dataset_id,
            'accuracy' => 0.7821,
            'precision_score' => 0.7543,
            'recall' => 0.6984,
            'f1_score' => 0.7253,
            'confusion_matrix' => [
                'true_negative' => 120,
                'false_positive' => 15,
                'false_negative' => 19,
                'true_positive' => 44
            ],
            'started_at' => Carbon::now()->subDays(7),
            'finished_at' => Carbon::now()->subDays(7)->addHours(2)
        ]);

        $trainingRun2 = TrainingRun::create([
            'model_id' => $model2->model_id,
            'dataset_id' => $dataset2->dataset_id,
            'accuracy' => 0.8125,
            'precision_score' => 0.7892,
            'recall' => 0.7456,
            'f1_score' => 0.7668,
            'confusion_matrix' => [
                'true_negative' => 125,
                'false_positive' => 10,
                'false_negative' => 15,
                'true_positive' => 48
            ],
            'started_at' => Carbon::now()->subDays(3),
            'finished_at' => Carbon::now()->subDays(3)->addHours(3)
        ]);

        // Create patients
        $patients = [
            [
                'nik' => '1234567890123456',
                'name' => 'Ahmad Santoso',
                'dob' => '1980-05-15',
                'sex' => 'L',
                'address' => 'Jl. Merdeka No. 123, Jakarta'
            ],
            [
                'nik' => '2345678901234567',
                'name' => 'Siti Rahayu',
                'dob' => '1975-08-22',
                'sex' => 'P',
                'address' => 'Jl. Sudirman No. 45, Bandung'
            ],
            [
                'nik' => '3456789012345678',
                'name' => 'Budi Prasetyo',
                'dob' => '1988-12-10',
                'sex' => 'L',
                'address' => 'Jl. Gatot Subroto No. 78, Surabaya'
            ],
            [
                'nik' => '4567890123456789',
                'name' => 'Maya Sari',
                'dob' => '1990-03-25',
                'sex' => 'P',
                'address' => 'Jl. Thamrin No. 56, Medan'
            ]
        ];

        foreach ($patients as $patientData) {
            $patient = Patient::create($patientData);

            // Create examinations for each patient
            $examinations = [
                [
                    'patient_id' => $patient->patient_id,
                    'glucose' => 148.0,
                    'blood_pressure' => 72.0,
                    'skin_thickness' => 35.0,
                    'insulin' => 0.0,
                    'bmi' => 33.6,
                    'dpf' => 0.627,
                    'age' => 50,
                    'exam_date' => Carbon::now()->subDays(10)
                ],
                [
                    'patient_id' => $patient->patient_id,
                    'glucose' => 85.0,
                    'blood_pressure' => 66.0,
                    'skin_thickness' => 29.0,
                    'insulin' => 0.0,
                    'bmi' => 26.6,
                    'dpf' => 0.351,
                    'age' => 31,
                    'exam_date' => Carbon::now()->subDays(5)
                ]
            ];

            foreach ($examinations as $examData) {
                $examination = Examination::create($examData);

                // Create predictions for some examinations
                if (rand(0, 1)) {
                    $prediction = Prediction::create([
                        'patient_id' => $patient->patient_id,
                        'exam_id' => $examination->exam_id,
                        'model_id' => $model2->model_id,
                        'run_id' => $trainingRun2->run_id,
                        'predicted_label' => rand(0, 1),
                        'probability' => rand(60, 95) / 100,
                        'explanation' => [
                            'glucose_contribution' => rand(15, 30),
                            'bmi_contribution' => rand(10, 25),
                            'age_contribution' => rand(5, 20),
                            'blood_pressure_contribution' => rand(5, 15),
                            'other_factors' => rand(10, 30)
                        ]
                    ]);
                }
            }
        }

        $this->command->info('Diabetes Prediction System data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . Patient::count() . ' patients');
        $this->command->info('- ' . Examination::count() . ' examinations');
        $this->command->info('- ' . DatasetVersion::count() . ' dataset versions');
        $this->command->info('- ' . MlModel::count() . ' ML models');
        $this->command->info('- ' . TrainingRun::count() . ' training runs');
        $this->command->info('- ' . Prediction::count() . ' predictions');
    }
}
