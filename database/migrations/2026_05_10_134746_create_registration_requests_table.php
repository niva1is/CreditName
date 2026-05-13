<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_requests', function (Blueprint $table) {
            $table->id();
            
            // Данные компании
            $table->string('company_full_name');
            $table->string('company_short_name');
            $table->string('inn', 12);
            $table->string('ogrn', 13);
            $table->string('ownership_form'); // ООО, АО, ПАО
            $table->string('legal_address');
            $table->string('phone');
            $table->string('contact_person');
            $table->string('contact_email');
            
            // Данные для входа (будущие)
            $table->string('email')->unique();
            $table->string('password_hash');
            
            // Статус заявки
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Кто обработал
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_requests');
    }
};