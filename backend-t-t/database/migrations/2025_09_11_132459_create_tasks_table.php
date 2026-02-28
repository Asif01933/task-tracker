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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary(); // varchar(36) UUID primary key
            $table->foreignId('team_id')->constrained('teams'); // reference to teams.id
            $table->foreignUuid('team_member_id')
            ->constrained('team_members')
            ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('priority')->nullable();
            $table->string('status')->nullable();

            $table->timestamps(); // created_at & updated_at
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
