<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('label_task', function (Blueprint $table) {
            $table->foreignUuid('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();

            $table->foreignId('label_id')
                ->constrained('labels')
                ->cascadeOnDelete();

            $table->primary(['task_id', 'label_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_task');
    }
};

