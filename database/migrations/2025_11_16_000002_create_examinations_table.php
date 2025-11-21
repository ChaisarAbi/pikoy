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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id('exam_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id')->onDelete('cascade');
            $table->decimal('glucose', 8, 2);
            $table->decimal('blood_pressure', 8, 2);
            $table->decimal('skin_thickness', 8, 2);
            $table->decimal('insulin', 8, 2);
            $table->decimal('bmi', 8, 2);
            $table->decimal('dpf', 8, 2);
            $table->integer('age');
            $table->date('exam_date');
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
        Schema::dropIfExists('examinations');
    }
};
