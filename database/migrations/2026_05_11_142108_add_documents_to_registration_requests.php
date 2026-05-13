<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_requests', function (Blueprint $table) {
            // Причина отказа (уже есть, но убедимся)
            if (!Schema::hasColumn('registration_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approved_at');
            }
            
            // Тип заявки: регистрация или изменение
            if (!Schema::hasColumn('registration_requests', 'request_type')) {
                $table->enum('request_type', ['registration', 'update'])->default('registration')->after('status');
            }
            
            // ID существующего клиента (для заявок на изменение)
            if (!Schema::hasColumn('registration_requests', 'existing_client_id')) {
                $table->foreignId('existing_client_id')->nullable()->constrained('clients')->nullOnDelete()->after('request_type');
            }
        });
        
        // Таблица для документов
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_request_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');           // Оригинальное имя файла
            $table->string('file_path');           // Путь в storage
            $table->string('document_type');       // Тип: charter, inn_certificate, ogrn_certificate, etc.
            $table->bigInteger('file_size');       // Размер в байтах
            $table->string('mime_type');           // MIME-тип файла
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
        Schema::table('registration_requests', function (Blueprint $table) {
            $table->dropColumn(['request_type', 'existing_client_id']);
        });
    }
};