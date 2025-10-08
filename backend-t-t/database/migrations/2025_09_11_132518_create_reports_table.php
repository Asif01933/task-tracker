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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary(); // varchar(36) UUID primary key
            $table->foreignId('team_id')->constrained('teams'); // reference to teams.id
<<<<<<< Updated upstream
            $table->foreignId('member_id')->constrained('team_members'); 
            $table->string('sent_to'); // email or recipient
=======
            $table->uuid('member_id');
            $table->dateTime('due_date'); 
            $table->boolean('is_sent')->default(false);
            $table->dateTime('sent_at')->nullable();
            $table->integer('report_frequency')->nullable();
>>>>>>> Stashed changes
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
