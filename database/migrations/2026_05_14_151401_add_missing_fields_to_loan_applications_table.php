<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'term_months')) {
                $table->integer('term_months')->default(12)->after('amount');
            }
            if (!Schema::hasColumn('loan_applications', 'purpose')) {
                $table->text('purpose')->nullable()->after('issue_date');
            }
            if (!Schema::hasColumn('loan_applications', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('loan_applications', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_person');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn(['term_months', 'purpose', 'contact_person', 'contact_phone']);
        });
    }
};