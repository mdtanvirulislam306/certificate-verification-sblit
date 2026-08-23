<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_code')->unique();
            $table->string('student_name');
            $table->string('course_name');
            $table->string('batch')->nullable();
            $table->string('duration')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('signature_name')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('logo')->nullable();
            $table->string('certificate_file')->nullable();
            $table->decimal('overall_score', 5, 1)->nullable();
            $table->string('grade', 10)->nullable();
            $table->unsignedTinyInteger('attendance')->nullable();
            $table->string('status')->default('Completed');
            $table->string('verify_url_display')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('certificate_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('obtained')->default(0);
            $table->unsignedSmallInteger('total')->default(100);
            $table->unsignedTinyInteger('percentage')->default(0);
            $table->string('grade', 10)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('certificate_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedSmallInteger('obtained')->default(0);
            $table->unsignedSmallInteger('total')->default(100);
            $table->unsignedTinyInteger('percentage')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('certificate_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('percentage')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('certificate_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('date_label')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_milestones');
        Schema::dropIfExists('certificate_skills');
        Schema::dropIfExists('certificate_assessments');
        Schema::dropIfExists('certificate_subjects');
        Schema::dropIfExists('certificates');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
