<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->text('message');

            // Who this notice is meant for.
            $table->enum('audience', ['all', 'students', 'teachers'])->default('all');

            $table->foreignId('posted_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Dashboard/bell dropdown always ask for "latest notices for
            // this audience" — this composite index serves that query.
            $table->index(['audience', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
