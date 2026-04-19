<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A team member can schedule the same team task on a given day; another member
     * can schedule the same task on another (or the same) day — separate rows, separate reports.
     */
    public function up(): void
    {
        Schema::create('member_daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('team_member_id')
                ->constrained('team_members')
                ->cascadeOnDelete();
            $table->foreignUuid('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();
            $table->date('plan_date');
            $table->timestamps();

            $table->unique(['team_member_id', 'task_id', 'plan_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_daily_tasks');
    }
};
