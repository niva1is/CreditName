<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'registration_request_id')) {
                $table->foreignId('registration_request_id')->nullable()->after('id')
                    ->constrained('registration_requests')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropForeign(['registration_request_id']);
            $table->dropColumn('registration_request_id');
        });
    }
};