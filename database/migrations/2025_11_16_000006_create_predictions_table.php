<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id('prediction_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('exam_id')->constrained('examinations', 'exam_id');
            $table->foreignId('model_id')->constrained('models', 'model_id');
            $table->foreignId('run_id')->constrained('training_runs', 'run_id');
            $table->integer('predicted_label');
            $table->decimal('probability', 5, 4);
            $table->json('explanation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predictions');
    }
};
