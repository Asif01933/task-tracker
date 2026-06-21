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
        Schema::table('tasks', function (Blueprint $table) {
            // drop existing foreign to allow rename
            $table->dropForeign(['team_member_id']);

            // rename columns
            $table->renameColumn('team_member_id', 'assigned_to');
            $table->renameColumn('description', 'problem_description');

            // re-add foreign key for assigned_to
            $table->foreign('assigned_to')
                ->references('id')
                ->on('team_members')
                ->cascadeOnDelete();

            // new nullable columns
            $table->text('solution_description')->nullable()->after('problem_description');
            $table->string('reporter_name')->nullable()->after('solution_description');
            $table->string('reporter_type')->nullable()->after('reporter_name');
            $table->dateTime('raised_at')->nullable()->after('reporter_type');
            $table->foreignUuid('entry_maker')->nullable()->after('raised_at')
                ->constrained('team_members')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // drop newly added columns and foreigns
            $table->dropConstrainedForeignId('entry_maker');
            $table->dropColumn(['solution_description', 'reporter_name', 'reporter_type', 'raised_at', 'entry_maker']);

            // drop foreign for assigned_to then rename back
            $table->dropForeign(['assigned_to']);
            $table->renameColumn('assigned_to', 'team_member_id');
            $table->renameColumn('problem_description', 'description');

            // re-add original foreign
            $table->foreign('team_member_id')
                ->references('id')
                ->on('team_members')
                ->cascadeOnDelete();
        });
    }
};
