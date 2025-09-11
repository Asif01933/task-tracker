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
        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('id')->primary(); // varchar(36) UUID primary key
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade'); // reference to teams.id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // reference to users.id
            $table->string('role')->nullable();
            $table->boolean('want_report')->default(false);
            $table->string('status')->nullable();
            $table->string('invited_email')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
