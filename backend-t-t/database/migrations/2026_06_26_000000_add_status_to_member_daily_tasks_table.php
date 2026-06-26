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
        Schema::table('member_daily_tasks', function (Blueprint $table) {
            $table->string('status')->default('planned')->after('task_note');
            $table->timestamp('completed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_daily_tasks', function (Blueprint $table) {
            $table->dropColumn(['status', 'completed_at']);
        });
    }
};
