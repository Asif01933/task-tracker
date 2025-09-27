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
        Schema::create('teams', function (Blueprint $table) {
            $table->id(); // id BIGINT PK AUTO_INCREMENT
            $table->string('name');
            $table->foreignId('owner_id')->constrained('users'); // foreign key to users.id
            $table->integer('report_frequency')->nullable();
            $table->timestamp('last_email_sent')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
