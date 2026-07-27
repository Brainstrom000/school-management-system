<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds indexes needed for the project to stay fast at 50k+ rows.
 *
 * Without these, every dashboard count/sum and every DataTable
 * search/sort on these columns forces MySQL to scan the whole
 * table (full table scan) instead of using an index — this is
 * the main reason things get slow as data grows.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('class');
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
        });

        Schema::table('attendances', function (Blueprint $table) {
            // Composite index: dashboard trend query filters by
            // date AND status together, so a composite index serves
            // that query far better than two separate single-column ones.
            $table->index(['date', 'status']);
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->index('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['class']);
        });

        Schema::table('fees', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['date', 'status']);
        });

        Schema::table('marks', function (Blueprint $table) {
            $table->dropIndex(['subject_id']);
        });
    }
};
