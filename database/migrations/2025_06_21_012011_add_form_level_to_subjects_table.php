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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('form_level')->nullable()->after('description'); // 'Form 4', 'Form 5'
            $table->string('category')->nullable()->after('form_level'); // 'Core', 'Science', 'Arts', 'Technical'
            $table->string('subject_code')->nullable()->after('category'); // Malaysian subject codes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['form_level', 'category', 'subject_code']);
        });
    }
};
